<?php
/** Controller for displaying index page of the flickr module
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Flickr_IndexController extends Pas_Controller_Action_Admin {
	
	protected $_oauth, $_flickrkey, $_secret, $_auth, $_config, $_userID, $_cache;
	/** Setup the contexts by action and the ACL.
	*/			
	public function init(){
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$this->_config = Zend_Registry::get('config');
	$this->_flickrkey = $this->_config->webservice->flickr->apikey;
	$this->_secret = $this->_config->webservice->flickr->secret;
	$this->_auth = $this->_config->webservice->flickr->auth;
	$this->_cache = Zend_Registry::get('cache');
	$this->_oauth = new Pas_YqlOauth();
	}
	
	public function tokens() {
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
	} 
	}	
	/** Display the index page
	*/			
	public function indexAction() {
   	$access = $this->tokens();
	$flickrkey = $_config->webservice->flickr->apikey;
	if (!($this->_cache->test('flickrintro'))) {
	$q= 'SELECT * FROM flickr.photos.search WHERE tag_mode = "all" AND user_id ="' 
	. $this->_userID . '" AND extras="geo,license,url_sq,url_m" AND api_key="' 
	. $this->_flickrkey . '" LIMIT 10';
	$flickr = $this->_oauth->execute(
	$q, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle']);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load('flickrintro');
	}
	$recent = array();
	foreach($flickr->query->results->photo as $a) {
	$recent[] = array( 
	'square' => $a->url_sq, 'medium' => $a->url_m, 'license' => $a->license, 
	'id' => $a->id, 'title' => $a->title);
	}
	$this->view->photos = $recent;
	if (!($this->_cache->test('cloud'))) {
	$usertags = 'select * from xml where url="http://api.flickr.com/services/rest/?method=flickr.tags.getListUserPopular&api_key=' 
	. $this->_flickrkey . '&user_id=' . $this->_userID . '&count=20"';
	$data = $this->_oauth->execute(
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
	$this->view->tags = $tags;
	if (!($this->_cache->test('friends'))) {
	$contacts = 'select * from xml where url="http://api.flickr.com/services/rest/?method=flickr.contacts.getPublicList&api_key=' 
	. $this->_flickrkey  . '&user_id=' . $this->_userID . '"';
	$friends = $this->_oauth->execute(
	$contacts, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($friends);
	} else {
	$friends = $this->_cache->load('friends');
	}
	$contactslist = array();
	foreach($friends->query->results->rsp->contacts->contact as $contact => $value)	{
	$contactslist[] = array(
	$contact => $value;
	);
	}
	$this->view->contactslist = $contactslist;
	}
	
}