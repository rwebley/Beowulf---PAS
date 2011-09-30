<?php

/**
 * Yahoo
 * 
 * @author dpett
 * @version 
 */


class Yahoo extends Pas_Db_Table_Abstract {
	/**
	 * The default table name 
	 */
	protected $_name = 'yahoo';
	protected $_primary = 'id';
	
	const OAUTHYAHOO = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
	
	const OAUTHYAHOOREQ = 'https://api.login.yahoo.com/oauth/v2/request_auth?';
	
	const SITEYAHOOCALLBACK = 'http://beta.finds.org.uk/admin/oauth/yahooaccess/';
	
	protected $_consumerKey;
	
	protected $_consumerSecret;
	
	protected $_tokens;
	
	
	public function init(){
	$this->_tokens = new OauthTokens();
	$this->_consumerKey = $this->_config->webservice->ydnkeys->consumerKey;
	$this->_consumerSecret = $this->_config->webservice->ydnkeys->consumerSecret;
	}
	
	public function request(){
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
	  Zend_Debug::dump($token);
    exit;
	$session = new Zend_Session_Namespace('yahoo_oauth');
	$session->token  = $token->getToken();
	$session->secret = $token->getTokenSecret();
	$urlParams = $token->getResponse()->getBody();
	$url = self::OAUTHYAHOOREQ . $urlParams;
	$this->_redirect($url);	
	}

	public function access(){
	$config = array(
	'siteUrl' => 'https://api.login.yahoo.com/oauth/v2/get_token',
	'callbackUrl' => 'http://beta.finds.org.uk/admin/oauth/',
	'consumerKey' => $this->_consumerKey,
 	'consumerSecret' => $this->_consumerSecret,
	);
	$session = new Zend_Session_Namespace('yahoo_oauth');
	// build the token request based on the original token and secret
	$request = new Zend_Oauth_Token_Request();
	$request->setToken($session->token)->setTokenSecret($session->secret);
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
	$tokenRow->created = $date;
	$tokenRow->expires = $this->expires();
	$tokenRow->save();
	$this->_redirect('/admin/oauth/');
	
	}
}

