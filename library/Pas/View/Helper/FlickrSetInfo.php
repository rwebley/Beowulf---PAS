<?php
class Pas_View_Helper_FlickrSetInfo 
	extends Zend_View_Helper_Abstract {
	
    protected $_cache = NULL;
    protected $_oauth = NULL;
	protected $_accessToken;
	protected $_accessSecret;
	protected $_accessExpiry;
	protected $_handle;
	protected $_flickrKey;
	protected $_config;
	protected $_flickrSecret;
	protected $_flickrAuth;
	
    public function __construct() {
    $this->_cache = Zend_Registry::get('rulercache');
	$this->_oauth = new Pas_Yql_Oauth();
	$tokens = new OauthTokens();
    $where = array();
	$where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess'); 
	$validToken = $tokens->fetchRow($where);
	$this->_accessToken= unserialize($validToken->accessToken);
	$this->_accessSecret = unserialize($validToken->tokenSecret);
	$this->_accessExpiry = $validToken->expires;
	$this->_handle = unserialize($validToken->sessionHandle);
	$this->_config = Zend_Registry::get('config');
	$this->_flickrKey = $this->_config->webservice->flickr->apikey;
	$this->_flickrSecret = $this->_config->webservice->flickr->secret ;
	$this->_flickrAuth = $this->_config->webservice->flickr->auth;
    }	

	public function FlickrSetInfo($id) {
	if (!($this->_cache->test('flickrSet' . $id))) {
	$query = 'SELECT * FROM flickr.photosets.info WHERE photoset_id="' . $id .'" and api_key="' . $this->_flickrKey . '";';
	$flickr = $this->_oauth->execute($query,$this->_accessToken, 
    $this->_accessSecret,$this->_accessExpiry,$this->_handle);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load('flickrSet' . $id);
	}
	$this->view->headTitle('All photos in the set titled: ' . $flickr->query->results->photoset->title);
	$this->view->MetaBase($flickr->query->results->photoset->description,'photos','archaeology,photos,portable antiquities');
	echo '<h2>' . $flickr->query->results->photoset->title . '</h2>';
	
	}


}