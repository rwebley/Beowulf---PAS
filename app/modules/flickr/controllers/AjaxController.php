<?php
/** Controller for pulling ajax data from flickr.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Flickr_AjaxController extends Pas_Controller_Action_Admin {

	protected $_cache, $_config, $_oauth, $_flickrkey, $_secret, $_auth;
	/** Setup the contexts by action and the ACL.
	*/			
	public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow('public',null);
		$this->_config = Zend_Registry::get('config');
		$this->_flickrkey = $this->_config->webservice->flickr->apikey;
		$this->_secret = $this->_config->webservice->flickr->secret;
		$this->_auth = $this->_config->webservice->flickr->auth;
		$this->_sig = $this->_config->webservice->flickr->sig;
		$this->_helper->layout->disableLayout();  
		$this->_cache = Zend_Registry::get('rulercache');
		$this->_oauth = new Pas_Yql_Oauth();
	}
	/** retrieve the access token array for accessing yql oauth
	 * 
	 * @return array $access
	*/			
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
		
	/** Display the index action for mapping flickr images
	*/		
	public function indexAction() {
	if (!($this->_cache->test('mappingflickr'))) { 
	$access = $this->tokens();
	$method = 'flickr.photos.getWithGeoDataper_page250';
	$api_sig = md5($this->_secret . 'api_key' . $this->_flickrkey . 'auth_token' 
	. $this->_auth . 'extrasgeo, url_sqmethod' . $method);
	$recentphotos = 'http://api.flickr.com/services/rest/?method=flickr.photos.getWithGeoData&api_key=' 
	. $this->_flickrkey . '&extras=geo%2C+url_sq&per_page=250&auth_token=' 
	. $this->_auth . '&api_sig=' . $api_sig;
	$q = 'select photos.photo.id,photos.photo.url_sq,photos.photo.title,photos.photo.latitude,photos.photo.longitude, photos.photo.woeid from xml where url ="http://api.flickr.com/services/rest/?method=flickr.photos.getWithGeoData&api_key=' 
	. $this->_flickrkey .'&extras=geo%2C+url_sq&per_page=250&auth_token=' . $this->_auth .'&api_sig=' . $this->_sig . '"';
	$ph = $this->_oauth->execute($q, $access['access_token'], $access['access_token_secret'], $access['access_token_expiry'],$access['handle'] );
	$this->_cache->save($ph);
	} else {
	$ph = $this->_cache->load('mappingflickr');
	}
	$recent = array();
	foreach($ph->query->results->rsp as $phot) {
	foreach($phot->photos as $a )
	$recent[] = array( 
		'id' => $a->id, 'square' => $a->url_sq, 'title' => $a->title,
		'lat' => $a->latitude, 'lon' => $a->longitude, 'woeid' => $a->woeid,
		'title' => $a->title);
	}
	$this->view->recent = $recent;
	}
	
}