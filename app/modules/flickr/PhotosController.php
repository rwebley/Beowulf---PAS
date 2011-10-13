<?php
/** Controller for displaying the photos section of the flickr module.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Flickr_PhotosController 
	extends Pas_Controller_Action_Admin {
	
	protected 	$_oauth, $_config, $_userid, $_cache, $_api;
	/** Setup the contexts by action and the ACL.
	*/			
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$this->_config = Zend_Registry::get('config');
	$this->_flickr = $this->_config->webservice->flickr;
	$this->_cache = Zend_Registry::get('cache');
	$this->_oauth = new Pas_Yql_Oauth();
	$this->_api	= new Pas_Yql_Flickr($this->_flickr);
	}
	
	/** No direct access to photos, goes to the index controller
	*/			
	public function indexAction() {
	$this->_redirect('/flickr/');
    }
    
	/** Retrieve the sets of photos we have
	*/		
	public function setsAction() {
	$page = $this->_getParam('page');
		if(!isset($page)){
		$start = 1;
	} else {
		$start = $page ;
	}
	$key = md5('sets' . $start);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getSetsList($this->_flickr->userid, $start);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$pagination = array(    
	'page'          => $page, 
	'perpage'      => (int)$flickr->photosets->perpage, 
    'total_results' => (int)$flickr->photosets->total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
    $paginator->setCurrentPageNumber($pagination['page'])
		->setItemCountPerPage($pagination['perpage'])
		->setCache($this->_cache);
	$this->view->paginator = $paginator;
	$this->view->photos = $flickr->photosets;
	}
	
	/** Find photos with a set radius of the where on earthID
	*/		
	public function whereonearthAction() {
	$woeid = (int)$this->_getParam('id');
	$page = (int)$this->_getParam('page');
	if(!isset($page)){
		$start = 1;
	} else {
		$start = $page ;
	}
	$this->view->place = $woeid;
	$key = md5('woeid' . $woeid . $page);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getWoeidRadius( $woeid, $radius = 500, $units = 'm', 
	$per_page = 20, $start, 'archaeology', '1,2,3,4,5,6,7');
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$total = $flickr->photos->total;
	$perpage = 	$flickr->photos->perpage;
	$pagination = array(    
	'page'          => $page, 
	'per_page'      => $perpage, 
    'total_results' => (int)$total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
    $paginator->setCurrentPageNumber($pagination['page'])
		->setItemCountPerPage(20)
		->setPageRange(20);
	$this->view->paginator = $paginator;
	$this->view->pictures = $flickr;
	
	}
	/** Find images in a set
	*/		
	public function inasetAction() {
	if($this->_getParam('id',false)){
	$id = $this->_getParam('id');
	$page = $this->_getParam('page');
	$number = 5;
	if(!isset($page)){
		$start = 1;
	} else {
		$start = $page ;
	}
	$key = md5 ('set' . $id . $page);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getPhotosInAset($id, 10, $page);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$pagination = array(    
	'page'          => $page, 
	'per_page'      => $flickr->photoset->perpage, 
    'total_results' => (int)$flickr->photoset->total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
    $paginator->setCurrentPageNumber($pagination['page'])
		->setItemCountPerPage(10)
		->setPageRange(20);
	$this->view->paginator = $paginator;
	$this->view->pictures = $flickr;
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** get photos's details
	*/		
	public function detailsAction() {
	$id = $this->_getParam('id');
	$exif = $this->_api->getPhotoExifDetails( $id );
	Zend_Debug::dump($exif);
	$geo = $this->_api->getGeoLocation($id);
	Zend_Debug::dump($geo);
	$comments = $this->_api->getPhotoCommentList($id);
	Zend_Debug::dump($comments);
	$image = $this->_api->getPhotoInfo($id);
	$this->view->image = $image;
	Zend_Debug::dump($image);
	$sizes = $this->_api->getSizes($id);
	$this->view->sizes = $sizes;
	Zend_Debug::dump($sizes);
	}
	
	/** Find images tagged in a certain way.
	*/		
	public function taggedAction() {
	$tags = $this->_getParam('as');
	$page = $this->_getParam('page');
	$per_page = 10;
	if(!isset($page)){
		$start = 1;
	} else {
		$start = $page ;
	}
	$key = md5('tagged' . $tags . $page);
	if (!($this->_cache->test($key))) {
	$flickr = $this->_api->getPhotosTaggedAs( $tags, $per_page, $page);
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$photos = array();
	if(!is_null($flickr)){
	$total = $flickr->total;
	$photos = array();
	foreach($flickr->photo as $k => $v) {
	$photos[$k] = $v;
	}
	if(!array_key_exists('woeid',$photos)){
	$photos['woeid'] = NULL;
	}	
	$this->view->tagtitle = $tags;
	$pagination = array(    
	'page'          => $page, 
	'results' 		=> $photos,
	'per_page'      => 10, 
    'total_results' => (int) $total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
    $paginator->setCurrentPageNumber($pagination['page'])
    	->setCache($this->_cache);
	$paginator->setPageRange(20);
	$this->view->paginator = $paginator;
	$this->view->pictures = $photos;
	}
	}
	
	
	/** Find groups of images we contribute to
	*/		
	public function groupsAction() {
	$access = $this->tokens();	
	$page = $this->_getParam('page');
	if (!($this->_cache->test('groupsnew'))) {
	$method = 'flickr.groups.pools.getGroups';
	$perm = 'read';
	$api_sig = md5($this->secret . 'api_key' . $this->flickrkey . 'auth_token' . $this->auth . 'method' . $method);
	$q = "select * from xml where url='http://api.flickr.com/services/rest/?method=flickr.groups.pools.getGroups&api_key=" 
	. $this->_flickrkey. "&auth_token=" . $this->_flickrauth . "&api_sig=" . $this->_sig . "'";
	$ph = $this->_oauth->execute($q,$access['access_token'], $access['access_token_secret'], 
		$access['access_token_expiry'],$access['handle'] );
	$this->_cache->save($ph,'groupsnew');
	} else {
	$ph = $this->_cache->load('groupsnew');
	}
	$photos = array();
	$ids = array();
	foreach($ph->query->results->rsp->groups->group as $g)	{
	$ids[] = array('id' => $g->id);
	$photos[] = array(
	'id' => $g->id,
	'name' => $g->name,
	'iconfarm' =>  $g->iconfarm,
    'iconserver' => $g->iconserver,
	'id' => $g->id,
	'name' => $g->name,
    'nsid' => $g->nsid,
    'photos' => $g->photos
	);
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($photos));
	if(isset($page) && ($page != ""))  {
    $paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
          ->setPageRange(10); 	
	$this->view->photos = $paginator;
	}
	/** Retrieve a specific image
	*/			
	public function groupAction() {
	if($this->_getParam('id',false)){
	$access = $this->tokens();
	$id = $this->_getParam('id');
	$page = $this->_getParam('page');
	if (!($this->_cache->test('groupinfo'.str_replace('@','',$id)))) {
	$groupinfo = 'select * from xml WHERE url ="http://api.flickr.com/services/rest/?method=flickr.groups.getInfo&api_key=dbb87ca6390925131a4fedb34d9d8d80&group_id='.$id.'"';
	$g = $this->_oauth->execute($groupinfo,$access['access_token'], $access['access_token_secret'],$access['access_token_expiry'],$access['handle'] );
	$this->_cache->save($g);
	} else {
	$g = $this->_cache->load('groupinfo' . str_replace('@','',$id));
	}
	
	if (!($this->_cache->test('groupphotos' . str_replace('@', '' ,$id)))) {
	$groupphotos = 'SELECT * FROM flickr.groups.pools.photos(0,100) WHERE group_id="' 
	. $id . '" AND extras="license, date_upload, date_taken, owner_name, icon_server, original_format, 
	 last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, 
	 url_s, url_m, url_o" AND api_key="' . $this->flickrkey .'"';
	$g2 = $this->_oauth->execute($groupphotos,$access['access_token'], $access['access_token_secret'],$access['access_token_expiry'],$access['handle'] );
	$this->_cache->save($g2);
	} else {
	$g2 = $this->_cache->load('groupphotos'.str_replace('@','',$id));
	}

	$this->view->name = $g->query->results->rsp->group->name;
	$this->view->desc = $g->query->results->rsp->group->description;
	$this->view->id = $g->query->results->rsp->group->id;
	$this->view->members = $g->query->results->rsp->group->members;
	$this->view->iconfarm = $g->query->results->rsp->group->iconfarm;
	$this->view->iconserver = $g->query->results->rsp->group->iconserver;
	
	$data = array();
	foreach($g2->query->results->photo as $p)	{
	$ps = array();
	foreach($p as $key => $value) {
	$ps[$key] = $value;
	}
	$data[] = $ps;
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	if(isset($page) && ($page != "")) {
   	$paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
    	      ->setPageRange(10); 	
	$this->view->photos = $paginator;
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Get a list of our favourite images
	*/		
	public function favouritesAction() {
	$access = $this->tokens();
	$page = $this->_getParam('page');
	if(!isset($page)){
		$start = 1;
	} else {
		$start = $page ;
	}
	$key = md5('faves' . $start);
	if (!($this->_cache->test($key))) {
	$q = "select * from xml where url='http://api.flickr.com/services/rest/?method=flickr.favorites.getPublicList&api_key=" . $this->_flickrkey . "&user_id=" . $this->_userid . "&license=1,2,3&extras=license,date_upload,date_taken,owner_name,icon_server,original_format,last_update,geo,tags,machine_tags,o_dims,views,media,path_alias,url_sq,url_t,url_s,url_m,url_o&per_page=5&page=" . $start ."'";
	$ph = $this->_oauth->execute( $q, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($ph);
	} else {
	$ph = $this->_cache->load($key);
	}
	$total = (int)$ph->query->results->rsp->photos->total;
	$photos = array();
	foreach($ph->query->results->rsp->photos->photo as $g){
	$data = array();
	foreach($g as $key => $value) {
	$data[$key] = $value;
	}
	$photos[] = $data;
	}
	
	$pagination = array(    
	'page'          => $page, 
	'results' 		=> $photos,
	'per_page'      => 5, 
    'total_results' => $total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
    $paginator->setCurrentPageNumber($pagination['page'])
              ->setItemCountPerPage(5)
              ->setPageRange(20);
	$this->view->paginator = $paginator;
	$this->view->photos = $photos;
	}
	
	/** Get a list of interesting flickr images attributed to archaeology
	 * The woeid 23424975 = United Kingdom
	 * 
	*/			
	public function interestingAction() {
	$access = $this->tokens();
	$page = $this->_getParam('page');
	if (!($this->_cache->test('archaeologyflickrnew'))) {
	$q = "select * from flickr.photos.search(0,100) where api_key='" . $this->_flickrkey . "' 
	and tags='archaeology' and tag_mode='all' and license='1,2,3,4,5,6,7' and woe_id='23424975' 
	and extras='license, date_upload, date_taken, owner_name, icon_server, original_format,last_update, 
	geo, tags, machine_tags, o_dims,views, media, path_alias, url_sq, url_t,url_s, url_m, url_o'";
	$ph = $this->_oauth->execute(
	$q, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($ph);
	} else {
	$ph = $this->_cache->load('archaeologyflickrnew');
	}
	
	$photos = array();
	foreach($ph->query->results->photo as $g) {
	$data = array();
	foreach($g as $key => $value) {
	$data[$key] = $value;
	}
	$photos[] = $data;
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($photos));
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
         	  ->setPageRange(10); 	
	$this->view->photos = $paginator;	
	}
}