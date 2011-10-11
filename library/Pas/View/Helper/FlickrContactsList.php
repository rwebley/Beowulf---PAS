<?php
/**
 *
 * @author dpett
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * FlickrContactsList helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_FlickrContactsList extends Zend_View_Helper_Abstract {
	

	
	protected $_cache = NULL;
	
	protected $_flickrKey;
	
	protected $_userID;
	
	protected $_oauth;
	
	public function __construct(  )  { 
	$this->_cache = Zend_Registry::get('cache');
	$this->_oauth = new Pas_Yql_Oauth();
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
	
	public function getFlickr($access){
	if (!($this->_cache->test('friends'))) {
	$contacts = 'select * from xml where url="http://api.flickr.com/services/rest/?method=flickr.contacts.getPublicList&per_page=60&api_key=' 
	. $this->_flickrKey  . '&user_id=' . $this->_userID . '"';
	$friends = $this->_oauth->execute(
	$contacts, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($friends);
	} else {
	$friends = $this->_cache->load('friends');
	}
	$contactslist = array();
	foreach($friends->query->results->rsp->contacts->contact as $contact => $value)	{
	$contactslist[] = 
	$contact = $value;
	}
	$total = $friends->query->results->rsp->contacts->total;
	return $this->buildHtml($contactslist, $total);
	} 
	
	public function buildHtml($contactslist, $total){
	if (!($this->_cache->test('contactsHtml'))) {
	$html = '<h3>Our flickr contacts</h3>';
	foreach($contactslist as $c){
	$type = '.jpg';
	$url = 'http://farm'. $c->iconfarm . '.static.flickr.com/' . $c->iconserver . '/buddyicons/' . $c->nsid . $type;
	$alturl = 'http://www.flickr.com/images/buddyicon.jpg';
	$link = 'http://www.flickr.com/photos/'. $c->nsid;
	
	if($c->iconfarm != 0) {
	$html .= '<a href="' . $link .'" title="Go to ' . $c->username . '\'s profile on flickr" rel="friend nofollow"><img src="' 
	. $url . '" height="48" width="48" alt="View ' 
	. $c->username . '\'s images" /></a>';
	} else {
	$html .= '<a href="' . $link . '" title="Go to this ' . $c->username . '\'s profile on flickr" rel="friend nofollow"><img src="' 
	. $alturl . '" height="48" width="48" alt="View ' 
	. $c->username . '\'s images" /></a>';
	}
	}
	$contactsurl = $this->view->url(array('module' => 'flickr','controller' => 'contacts'),'default',true);
	$html .= '<p>View our <a href="' . $contactsurl . '" title="View our contacts">' . $total . '</a> friends and their images &raquo;</p>';
	$this->_cache->save($html);
	} else {
	$html = $this->_cache->load('contactsHtml');
	}
	return $html;
	}
	
	public function flickrContactsList($flickr) {
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

