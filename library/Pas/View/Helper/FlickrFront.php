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
	
	protected $_cache = NULL;
	
	protected $_flickrKey;
	
	protected $_userID;
	
	
	public function __construct(  )  { 
	$this->_cache = Zend_Registry::get('cache');
	}
	
	private function getAccessKeys() {
	$tokens = new OauthTokens();
    $where = array();
	$where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess'); 
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
	$oauth = new Pas_Yql_Oauth();
	$q = 'SELECT * FROM flickr.photos.search WHERE tag_mode ="all" AND user_id="' . $this->_userID
	. '" AND extras="geo,license,url_sq,url_m" and api_key="' . $this->_flickrKey . '" LIMIT 8';
    $data = $oauth->execute($q, $access->access_token, $access->access_token_secret, $access->access_token_expiry, 
    $access->handle);
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
	foreach($data->query->results->photo as $k) {
	$recent[] = $k;
	}
	return $this->buildHtml($recent);
	} else {
		return false;
	}
	}

	public function buildHtml($recent) {
	if (!($this->_cache->test('frontHtml'))) {
	$html = '<h3>Our recent images</h3>';
	$html .= '<div id="flickrbox">';
	foreach($recent as $p){ 
	$html .= '<a href="';
	$html .= $p->url_m;
	$html .= '" rel="lightbox" ';
	$html .= 'title="';
	$html .=$p->title;
	$html .= '"><img src="';
	$html .=$p->url_sq;
	$html .= '" alt="';
	$html .=$p->title;
	$html .='" width="75" height="75"/></a>';
		}
	$html .= '<a href="';
	$html .= $this->view->url(array('module' => 'flickr'),null,true);
	$html .='" title="View our flickr images"><img src="';
	$html .= $this->view->baseUrl();
	$html .='/images/logos/flickr.png" alt="flickr\'s logo" height="39" width="140" id="badgeflickr"/></a><br /></div>';
	$this->_cache->save($html);
	} else {
	$html = $this->_cache->load('frontHtml');
	}
	
	return $html;
	}
	
	public function flickrFront(  $flickr ) {
	$this->_flickrKey = $flickr->apikey;
	$this->_userID = $flickr->userid;
	$openup = $this->getAccessKeys();
	if(!is_null($openup)){
		return $this->getFlickr($openup);
	} else {
	return false;	
	}
	}
	
}

