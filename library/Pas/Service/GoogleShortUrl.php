<?php
class Pas_Service_GoogleShortUrl {
 
	const GOOGLE = 'https://www.googleapis.com/urlshortener/v1/url';
	
	const INVALIDURL = 'Your entry is not a valid URL.';
	
	const INVALIDSHORTURL = 'That is not a valid google shortened url';
	
	const GOOGLEURL = 'goo.gl';
	
	protected $_api;
	
	public function __construct( $key ) {
	$this->_api = self::GOOGLE . '?key=' . $key;
	}

	public function shorten( $url ) {
	$url = $this->checkUrl( $url );
	$response = $this->send($url,true);
	return $response;
    }     

    public function expand($url ) {
	$url = $this->checkShortUrl( $url );
	$response = $this->send($url,false);
	return $response;
    }
	
    public function analytics($shortUrl){
	$url = $this->checkShortUrl( $shortUrl );
	$client = new Zend_Http_Client();
	$client->setUri($this->_api);
	$client->setMethod(Zend_Http_Client::GET);
	$client->setParameterGet('shortUrl', $shortUrl);
	$client->setParameterGet('projection', 'FULL');
	$response = $client->request();
	if($response->isSuccessful()){
	return $this->getDecode($response);
	} else {
		return false;
	}
    }
    
    private function getDecode($response){
    $data = $response->getBody();
	$json = json_decode($data);
	return $json;	
    }
    
	private function getStatus($response) {
    $code = $response->getStatus();
    switch($code) {
    	case ($code == 200):
    		return true;
    		break;
    	case ($code == 400):
    		throw new Exception('Bad request made');
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
	
	private function checkUrl($url) {
	if (!Zend_Uri::check($url)) {
    	throw new Exception(self::INVALIDURL);
    }
	return $url;
	}
	
	private function checkShortUrl($url){
	$shorturl = parse_url($url);
		if($shorturl['host'] === self::GOOGLEURL){
			return $url;
		} else {
	throw new Exception(self::INVALIDSHORTURL);		
	}	
	}
	
	public function send($url, $short = true) {
	if($short){
	$options = array(
	CURLOPT_URL => $this->_api, 
	CURLOPT_POST => true,             
	CURLOPT_HEADER => false,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_RETURNTRANSFER =>  1, 
	);	
	$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => $options
	);
	$client = new Zend_Http_Client( $this->_api, $config );
	$client->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'application/json');
	$client->setMethod(Zend_Http_Client::POST);
	$client->setRawData(json_encode(array("longUrl"=>$url)));
	} else {
	$options = array(
	CURLOPT_URL => $this->_api . '&shortUrl=' . $url,
	CURLOPT_SSL_VERIFYPEER => 0,
	CURLOPT_RETURNTRANSFER =>  1, 
	);
	$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => $options
	);
	$client = new Zend_Http_Client( $this->_api, $config );
	}
	$response = $client->request();
	if($response->isSuccessful()) {
	$code = $this->getStatus($response);
	$header = $response->getHeaders();
	if($code == true && $header != 'text/html;charset=UTF-8'){
		return $this->getDecode($response);	
	} else {
		return NULL;
	}
	} else {
		return NULL;
	}
	}

}
