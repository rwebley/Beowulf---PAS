<?php
/** Controller for administering oauth and setting up tokens
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_OauthController extends Pas_Controller_ActionAdmin {
	
	protected $_config;
	
	protected $_consumerKey;
	
	protected $_consumerSecret;
	
	const DATATABLES_URL  = 'store://datatables.org/alltableswithkeys';
	
	const OAUTHYAHOO = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
	
	const OAUTHYAHOOREQ = 'https://api.login.yahoo.com/oauth/v2/request_auth?';
	
	const SITEYAHOOCALLBACK = 'http://www.finds.org.uk/admin/oauth/yahooaccess/';
	
	protected $_tokens; 
	
	/** Set up the ACL and resources
	*/		
	public function init() {
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_config = Zend_Registry::get('config');
	$this->_consumerKey = $this->_config->webservice->ydnkeys->consumerKey;
	$this->_consumerSecret = $this->_config->webservice->ydnkeys->consumerSecret;
	$this->_tokens = new OauthTokens();
    }
    
	/** List available Oauth tokens
	*/	
    public function indexAction() {
    $this->view->tokens = $this->_tokens->fetchAll();
    }
    
	/** Initiate request to create a yahoo token. This can only be done when logged into Yahoo
	 * and also as an admin
	*/	
    public function yahoorequestAction() {
    $config = array(
    'version' => '1.0', 
    'requestScheme' => Zend_Oauth::REQUEST_SCHEME_HEADER,
    'signatureMethod' => 'HMAC-SHA1',
	'callbackUrl' => self::SITEYAHOOCALLBACK,
    'siteUrl' => self::OAUTHYAHOO,
    'consumerKey' => $this->_consumerKey,
 	'consumerSecret' => $this->_consumerSecret,
    );
	
	$consumer = new Zend_Oauth_Consumer($config);
	$token = $consumer->getRequestToken();
	$session = new Zend_Session_Namespace('yahoo_oauth');
	$session->token  = $token->getToken();
	$session->secret = $token->getTokenSecret();
	$urlParams = $token->getResponse()->getBody();
	$url = self::OAUTHYAHOOREQ . $urlParams;
	$this->_redirect($url);
    }
    
	/** Initiate request to create a yahoo token. This can only be done when logged into Yahoo
	 * and also as an admin
	*/	
    public function yahooaccessAction(){
	$config = array(
	'siteUrl' => 'https://api.login.yahoo.com/oauth/v2/get_token',
	'callbackUrl' => 'http://www.finds.org.uk/admin/oauth/',
	'consumerKey' => $this->_consumerKey,
 	'consumerSecret' => $this->_consumerSecret,
	);
		
	$session = new Zend_Session_Namespace('yahoo_oauth');
	// build the token request based on the original token and secret
	$request = new Zend_Oauth_Token_Request();
	$request->setToken($session->token)
		->setTokenSecret($session->secret);
	unset($session->token);
	unset($session->secret);
	$date = new Zend_Date();
	$expires = $date->add('1', Zend_Date::HOUR);
	$consumer = new Zend_Oauth_Consumer($config);
	$token = $consumer->getAccessToken($_GET, $request);
	$oauth_guid = $token->xoauth_yahoo_guid;
	$oauth_session = $token->oauth_session_handle;
	$oauth_token = $token->getToken();
	$oauth_token_secret = $token->getTokenSecret();
	$tokenRow = $this->_tokens->createRow();	
	$tokenRow->service = 'yahooAccess';
	$tokenRow->accessToken = serialize($oauth_token);
	$tokenRow->tokenSecret = serialize($oauth_token_secret);
	$tokenRow->guid = serialize($oauth_guid);
	$tokenRow->sessionHandle = serialize($oauth_session);
	$tokenRow->created = $this->getTimeForForms();
	$tokenRow->expires = $this->expires();
	$tokenRow->save();
	echo 'Token created';
	}
	
	/** To be created
	 * 
	 */
	public function twitteraccessAction(){
	}
	
	/** generate the nonce for the oauth call.
	*/		
	static function generate_nonce() {
    $mt = microtime();
    $rand = mt_rand();
    return md5($mt . $rand);
	}
	
	/** Set up the expiry time for token
	*/		
    private function expires() {
    $date = new Zend_Date();
    $expires = $date->add('1', Zend_Date::HOUR);
	$kickmeout = $expires->toString('yyyy-MM-dd HH:mm:ss');
    return $kickmeout;
    }
    
	/** Create a token for storing in the database for a yahoo request
	*/		
	public function createToken($data){
	$data = (object)$data;
	$tokenRow = $this->_tokens->createRow();	
	$tokenRow->service = 'yahooAccess';
	$tokenRow->accessToken = serialize(urldecode($data->oauth_token));
	$tokenRow->tokenSecret = serialize($data->oauth_token_secret);
	$tokenRow->guid = serialize($data->xoauth_yahoo_guid);
	$tokenRow->sessionHandle = serialize($data->oauth_session_handle);
	$tokenRow->created = $this->getTimeForForms();
	$tokenRow->expires = $this->expires();
	$tokenRow->save();
	$tokenData = array('accessToken' => $data->oauth_token, 'secret' => $data->oauth_token_secret);
	return $tokenData;
	}
	
	/** Build the oauth http query
	 * 
	 * @param $params
	 * @param $excludeOauthParams
	*/		
	private function oauth_http_build_query($params, $excludeOauthParams = false) {
 	 $query_string = '';
  	if (! empty($params)) {
    // rfc3986 encode both keys and values
    $keys = OAuthUtil::urlencode_rfc3986(array_keys($params));
    $values = OAuthUtil::urlencode_rfc3986(array_values($params));
    $params = array_combine($keys, $values);
    // Parameters are sorted by name, using lexicographical byte value ordering.
    // http://oauth.net/core/1.0/#rfc.section.9.1.1
    uksort($params, 'strcmp');
    // Turn params array into an array of "key=value" strings
    $kvpairs = array();
    foreach ($params as $k => $v) {
      if ($excludeOauthParams && substr($k, 0, 5) == 'oauth') {
        continue;
      }
	if (is_array($v)) {
	// If two or more parameters share the same name,
	// they are sorted by their value. OAuth Spec: 9.1.1 (1)
	natsort($v);
	foreach ($v as $value_for_same_key) {
		array_push($kvpairs, ($k . '=' . $value_for_same_key));
	}
	} else {
	// For each parameter, the name is separated from the corresponding
	// value by an '=' character (ASCII code 61). OAuth Spec: 9.1.1 (2)
    	array_push($kvpairs, ($k . '=' . $v));
		}
    }
    // Each name-value pair is separated by an '&' character, ASCII code 38.
    // OAuth Spec: 9.1.1 (2)
    $query_string = implode('&', $kvpairs);
  	}
  	return $query_string;
	}

	/**
	 * Parse a query string into an array.
	 * @param string $query_string an OAuth query parameter string
	 * @return array an array of query parameters
	 * @link http://oauth.net/core/1.0/#rfc.section.9.1.1
	 */
	private function oauth_parse_str($query_string) {
	$query_array = array();
	if (isset($query_string)) {
	// Separate single string into an array of "key=value" strings
	$kvpairs = explode('&', $query_string);
	// create an array of key value pairs
	 foreach ($kvpairs as $pair) {
	list($k, $v) = explode('=', $pair, 2);
	// Handle the case where multiple values map to the same key
	// by pulling those values into an array themselves
	if (isset($query_array[$k])) {
	// If the existing value is a scalar, turn it into an array
	if (is_scalar($query_array[$k])) {
		$query_array[$k] = array($query_array[$k]);
	}
	array_push($query_array[$k], $v);
	} else {
	$query_array[$k] = $v;
		}
	}
	}
	return $query_array;
	}
	
	/**
	 * Build an OAuth header for API calls
	 * @param array $params an array of query parameters
	 * @return string encoded for insertion into HTTP header of API call
	 */
	private function build_oauth_header($params, $realm='') {
	$header = 'Authorization: OAuth realm="' . $realm . '"';
	foreach ($params as $k => $v) {
	if (substr($k, 0, 5) == 'oauth') {
		$header .= ',' . OAuthUtil::urlencode_rfc3986($k) . '="' . OAuthUtil::urlencode_rfc3986($v) . '"';
	  	}
	  }
	return $header;
	}
	
	/**
	 * Compute an OAuth PLAINTEXT signature
	 * @param string $consumer_secret
	 * @param string $token_secret
	 */
	private function oauth_compute_plaintext_sig($token_secret) {
	  return ($this->_consumerSecret . '&' . $token_secret);
	}
	
	/**
	 * Compute an OAuth HMAC-SHA1 signature
	 * @param string $http_method GET, POST, etc.
	 * @param string $url
	 * @param array $params an array of query parameters for the request
	 * @param string $consumer_secret
	 * @param string $token_secret
	 * @return string a base64_encoded hmac-sha1 signature
	 * @see http://oauth.net/core/1.0/#rfc.section.A.5.1
	 */
	private function oauth_compute_hmac_sig($http_method, $url, $params, $token_secret) {
	  $base_string = $this->signature_base_string($http_method, $url, $params);
	  $signature_key = OAuthUtil::urlencode_rfc3986($this->_consumerSecret) . '&' . OAuthUtil::urlencode_rfc3986($token_secret);
	  $sig = base64_encode(hash_hmac('sha1', $base_string, $signature_key, true));
	  return $sig;
	}
	
	/**
	 * Make the URL conform to the format scheme://host/path
	 * @param string $url
	 * @return string the url in the form of scheme://host/path
	 */
	private function normalize_url($url) {
	$parts = parse_url($url);
	$scheme = $parts['scheme'];
	$host = $parts['host'];
	$path = $parts['path'];
	return "$scheme://$host$path";
	}
	
	/**
	 * Returns the normalized signature base string of this request
	 * @param string $http_method
	 * @param string $url
	 * @param array $params
	 * The base string is defined as the method, the url and the
	 * parameters (normalized), each urlencoded and the concated with &.
	 * @see http://oauth.net/core/1.0/#rfc.section.A.5.1
	 */
	private function signature_base_string($http_method, $url, $params){
	  // Decompose and pull query params out of the url
	  $query_str = parse_url($url, PHP_URL_QUERY);
	  if ($query_str) {
	    $parsed_query = $this->oauth_parse_str($query_str);
	    // merge params from the url with params array from caller
	    $params = array_merge($params, $parsed_query);
	  }
	
	  // Remove oauth_signature from params array if present
	  if (isset($params['oauth_signature'])) {
	    unset($params['oauth_signature']);
	  }
	
	  // Create the signature base string. Yes, the $params are double encoded.
	  $base_string = OAuthUtilMap::urlencode_rfc3986(strtoupper($http_method)) . '&' .
	                 OAuthUtilMap::urlencode_rfc3986($this->normalize_url($url)) . '&' .
	                 OAuthUtilMap::urlencode_rfc3986($this->oauth_http_build_query($params));
	
	  //logit("signature_base_string:INFO:normalized_base_string:$base_string");
	
	  return $base_string;
	}
	
	/**
	 * Encode input per RFC 3986
	 * @param string|array $raw_input
	 * @return string|array properly rfc3986 encoded raw_input
	 * If an array is passed in, rfc3896 encode all elements of the array.
	 * @link http://oauth.net/core/1.0/#encoding_parameters
	 */
	private function rfc3986_decode($raw_input){
	return rawurldecode($raw_input);
	}
	
	private function call_yql($access_token, $access_token_secret, 
	$usePost = false, $passOAuthInHeader = true, $method = 'GET') {
	
	$url = 'http://query.yahooapis.com/v1/yql';
	$params['q'] = 'select * from geo.places where text="London,bloomsbury"';
	$params['format'] = 'json';
	$params['env'] = self::DATATABLES_URL;
	$params['oauth_version'] = '1.0';
	$params['oauth_nonce'] = $this->generate_nonce();
	$params['oauth_timestamp'] = time();
	$params['oauth_consumer_key'] = $this->_consumerKey;
	$params['oauth_token'] = $access_token;
	$params['oauth_signature_method'] = 'HMAC-SHA1';
	$params['oauth_signature'] =
	$this->oauth_compute_hmac_sig($method, $url, $params,
	                             $access_token_secret);
	
	  // Pass OAuth credentials in a separate header or in the query string
	  if ($passOAuthInHeader) {
	    $query_parameter_string = $this->oauth_http_build_query($params, true);
	    $header = $this->build_oauth_header($params, "yahooapis.com");
	    $headers[] = $header;
	  } else {
	    $query_parameter_string = $this->oauth_http_build_query($params);
	  }
	  
	    $request_url = $url . ($query_parameter_string ?
	                           ('?' . $query_parameter_string) : '' );
	                           
	    $response = $this->curl($request_url, 80, $headers);
	  	
	
	  return $response;
	}
	
	
	private function refresh_access_token( $old_access_token, $old_token_secret, $oauth_session_handle, 
	$usePost=false, $useHmacSha1Sig=true, $passOAuthInHeader=true){
	  $url = 'https://api.login.yahoo.com/oauth/v2/get_token';
	  $params['oauth_version'] = '1.0';
	  $params['oauth_nonce'] = $this->generate_nonce();
	  $params['oauth_timestamp'] = time();
	  $params['oauth_consumer_key'] = $this->_consumerKey;
	  $params['oauth_token'] = $old_access_token;
	  $params['oauth_session_handle'] = $oauth_session_handle;
	 
	  if ($useHmacSha1Sig) {
	    $params['oauth_signature_method'] = 'HMAC-SHA1';
	    $params['oauth_signature'] =
	    $this->oauth_compute_hmac_sig('GET', $url, $params,
	                              $old_token_secret);
	  } else {
	    $params['oauth_signature_method'] = 'PLAINTEXT';
	    $params['oauth_signature'] =
	      $this->oauth_compute_plaintext_sig($this->_consumerSecret, $old_token_secret);
	  }
	
	  if ($passOAuthInHeader) {
	    $query_parameter_string = $this->oauth_http_build_query($params, true);
	    $header = $this->build_oauth_header($params, "yahooapis.com");
	    $headers[] = $header;
	  } else {
	    $query_parameter_string = $this->oauth_http_build_query($params);
	  }
	
	    $request_url = $url . ($query_parameter_string ?
	                           ('?' . $query_parameter_string) : '' );
	    $response = $this->curl($request_url, 443, $headers);
	    $response = $this->oauth_parse_str($response);
	  return $response;
	}
	
	public function curl($url,$port,$headers) {
	$config = array(
	'adapter'   => 'Zend_Http_Client_Adapter_Curl',
	'curloptions' => array(
	CURLOPT_POST =>  false,
	CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_PORT => $port,
	CURLOPT_HEADER => false,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_LOW_SPEED_TIME => 1,
	CURLOPT_SSL_VERIFYHOST => false,
	CURLOPT_SSL_VERIFYPEER => false,
	));
		
	$client = new Zend_Http_Client($url, $config);
	$client->setHeaders($headers);
	$response = $client->request();
	$code = $this->getStatus($response);
	$header = $response->getHeaders();
	if($code == true && $header != 'text/html;charset=UTF-8'){
	$data = $this->getDecode($response);
	return $data;	
	} else {
	return NULL;
	}
	}
	/** Decode the response returned from the curl request
	 * 
	 * @param object $response
	 */
	private function getDecode($response) {
    $data = $response->getBody();
	$json = json_decode($data);
	return $json;	
    }
	/** Determine the status of the response
	*/	
    private function getStatus($response)  {
    $code = $response->getStatus();
    switch($code) {
    	case ($code == 200):
    		return true;
    		break;
    	case ($code == 400):
    		throw new Exception('A valid appid parameter is required for this resource');
    		break;
    	case ($code == 404):
    		throw new Exception('The resource could not be found');
    		break;
    	case ($code == 406):
    		throw new Exception('You asked for an unknown representation');
    		break;
    	default;
    		return false;
    		break;	
    }
	}
	
	
}
	class OAuthUtilMap {
	  public static function urlencode_rfc3986($input) {
	  if (is_array($input)) {
	    return array_map(array('OAuthUtilMap', 'urlencode_rfc3986'), $input);
	  } else if (is_scalar($input)) {
	    return str_replace(
	      '+',
	      ' ',
	      str_replace('%7E', '~', rawurlencode($input))
	    );
	  } else {
	    return '';
	  }
	}
	}