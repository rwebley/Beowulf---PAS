<?php

class Pas_View_Helper_FlickrNsid extends Zend_View_Helper_Abstract{
	
	protected $_cache = NULL;
	protected $_config;
	protected $_api;
	
	public function __construct(){
	$this->_config = Zend_Registry::get('config');
	$this->_cache = Zend_Registry::get('cache');
	$this->_api = new Pas_Yql_Flickr($this->_config->webservice->flickr);
	}
	
	public function flickrNsid( $username ) {
	if (!($this->_cache->test($username))) {
	$flickr = $this->_api->findByUsername($username);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($username);
	}
	return $flickr;
	}
}

