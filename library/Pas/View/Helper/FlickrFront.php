<?php
/**
 * A view helper for displaying html list of flickr images
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_Title
 * @uses Pas_View_Helper_CurUrl
 * @uses Zend_View_Helper_Baseurl
 */
class Pas_View_Helper_FlickrFront extends Zend_View_Helper_Abstract {
	
	/**
	 * @var Zend_View_Interface 
	 */
	protected $_cache = NULL;
	
	public function __construct()  { 
	$this->_cache = Zend_Registry::get('rulercache');
	}
	
	private function getAccessKeys() {
	$tokens = new OauthTokens();
    $where = array();
	$where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess'); 
	//$where[] = $tokens->getAdapter()->quoteInto('expires >= ?',$this->getTimeForForms()); 
	$validToken = $tokens->fetchRow($where);
	if(!is_null($validToken)) {
	$access = array(
	'access_token' => unserialize($validToken->accessToken),
	'access_token_secret' => unserialize($validToken->tokenSecret),
	'access_token_expiry' => $validToken->expires,
	'handle' => unserialize($validToken->sessionHandle)
	);
	return $access;
	} else {
	 return false;	
	}
	}
	
	/** Get response from Flickr YQL
	 * @uses Pas_YqlOauth
	 * @param object $access
	 */
	private function getFlickr($access) {
	$access = (object)$access;
	if (!($this->_cache->test('flickrimagesfrontjson'))) {
	$oauth = new Pas_YqlOauth();
	$q = 'SELECT * FROM flickr.photos.search WHERE tag_mode ="all" AND user_id="10257668@N04" AND extras="geo,license,url_sq,url_m" and api_key="dbb87ca6390925131a4fedb34d9d8d80" LIMIT 10';
    $data = $oauth->execute($q,$access->access_token, $access->access_token_secret,$access->access_token_expiry,$access->handle);
   
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('flickrimagesfrontjson');
	}	
	if(is_array((array)$data)){
	return $this->parseFlickr($data);
	} else {
		return false;
	}
	}
	
	/** Parse the flickr response to an array and build html
	 * 
	 * @param unknown_type $data
	 
	 */
	private function parseFlickr($data) {
	if(!is_null($data)){	
	$recent = array();
	foreach($data->query->results->photo as $a) {
	$recent[] = array( 
	'square' => $a->url_sq,
	'medium' => $a->url_m,
	'license' => $a->license,
	'id' => $a->id,
	'title' => $a->title);
	}
	return $this->buildHtml($recent);
	} else {
		return false;
	}
	}

	public function buildHtml($recent) {
	$html = '';
	$html .= '<div id="flickrbox">';
	foreach($recent as $p){ 
	$html .= '<a href="';
	$html .= $p['medium'];
	$html .= '" rel="lightbox" ';
	$html .= 'title="';
	$html .=$p['title'];
	$html .= '"><img src="';
	$html .=$p['square'];
	$html .= '" alt="';
	$html .=$p['title'];
	$html .='" width="75" height="75"/></a>';
		}
	$html .= '<a href="';
	$html .= $this->view->url(array('module' => 'flickr'),null,true);
	$html .='" title="View our flickr images"><img src="';
	$html .= $this->view->baseUrl();
	$html .='/images/logos/flickr.png" alt="flickr\'s logo" height="39" width="140" id="badgeflickr"/></a><br /></div>';
	return $html;
	}
	
	public function flickrFront() {
	$openup = $this->getAccessKeys();
	if(!is_null($openup)){
		return $this->getFlickr($openup);
	} else {
	return false;	
	}
	}
	
}

