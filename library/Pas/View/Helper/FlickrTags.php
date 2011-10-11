<?php
class Pas_View_Helper_FlickrTags extends Zend_View_Helper_Abstract {
	
	protected $_userID;
	
	protected $_flickrKey;
	
	protected $_count;
	
	public function __construct(){
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
	
	protected function getFlickr($access){
	if (!($this->_cache->test('cloud'))) {
	$oauth = new Pas_Yql_Oauth();
	$usertags = 'select * from xml where url="http://api.flickr.com/services/rest/?method=flickr.tags.getListUserPopular&api_key=' 
	. $this->_flickrKey . '&user_id=' . $this->_userID . '&count=' . $this->_count .'"';
	$data = $oauth->execute(
	$usertags, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'],$access['handle'] );
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('cloud');
	}
	$tags = array();
	foreach($data->query->results->rsp->who->tags->tag as $s){
	$tags[] = array('tag' => $s->content,'count' => (int)$s->count);
	}
	return $this->createTagCloud($tags);
	}
	



	public function createTagCloud($tags){
	if (!($this->_cache->test('cloudHtml'))) {
	$tag = array();
	foreach($tags as $tagged){
	$tag[] = array(
	'title' => strtolower($tagged['tag']), 
	'weight' => $tagged['count'], 
	'params' => array(
	'url' => $this->view->url(array(
		'module' => 'flickr',
		'controller' => 'photos',
		'action' => 'tagged',
		'as' => strtolower($tagged['tag'])),
		'default',
		true)));
	}
	$tags = array(
	'tags' => $tag,
	'cloudDecorator' => array(
	'decorator' => 'HtmlCloud',
	'options' => array('htmlTags' => array(
	'ul' => array('id' => 'period-object-cloud')))),
	'tagDecorator' => array(
	'decorator' => 'HtmlTag',
	'options' => array(
	'htmlTags' => array( 'li'),
	'minFontSize' => 100,
	'maxFontSize' => 200,
	'fontSizeUnit' => '%')));
	$cloud = new Zend_Tag_Cloud($tags);
	$this->_cache->save($cloud);
	} else {
	$cloud = $this->_cache->load('cloudHtml');
	}
	return $cloud;
	}
	
	/**
	 * 
	 */
	public function flickrTags($flickr) {
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

