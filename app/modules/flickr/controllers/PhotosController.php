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
	
	protected 	$_oauth, $_flickrkey, $_secret, $_flickrauth, 
				$_config, $_userid, $_cache, $_api,
				$_sig;
	/** Setup the contexts by action and the ACL.
	*/			
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$this->_config = Zend_Registry::get('config');
	$this->_flickrkey = $this->_config->webservice->flickr->apikey;
	$this->_secret = $this->_config->webservice->flickr->secret;
	$this->_flickrauth = $this->_config->webservice->flickr->auth;
	$this->_sig = $this->_config->webservice->flickr->sig;
	$this->_cache = Zend_Registry::get('cache');
	$this->_oauth = new Pas_Yql_Oauth();
	$this->_api = new Phlickr_Api($this->_flickrkey, $this->_secret, $this->_flickrauth);
	$this->_userid = $this->_config->webservice->flickr->userid;
	}
	
	/** No direct access to photos, goes to the index controller
	*/			
	public function indexAction() {
	$this->_redirect('/flickr/');
    }
    
    /** Retrieve the oauth tokens for use with YQL
     */
	public function tokens(){
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
	
	/** Retrieve the sets of photos we have
	*/		
	public function setsAction() {
	$page = $this->_getParam('page');
	$this->view->page = $page;
   	$access = $this->tokens();
	if (!($this->_cache->test('sets'))) {
	$q = "select * from xml where 
	url='http://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=" . $this->_flickrkey 
	. "&user_id=" . $this->_userid . "'";
	$flickr = $this->_oauth->execute($q,$access['access_token'], $access['access_token_secret'], 
		$access['access_token_expiry'],$access['handle'] );
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load('sets');
	}
	$data= array();
	foreach($flickr->query->results->rsp->photosets->photoset as $fs){
	$title = $fs->title;
	$id = $fs->id;
	$description = $fs->description;
	$primary = $fs->primary;
	$secret = $fs->secret;
	$farm = $fs->farm;
	$photos = $fs->photos;
	$videos = $fs->videos;
	$server = $fs->server;
	$data[] = array('title'	=> $title,
					'id'	=> $id,
					'description'	=> $description, 
					'primary'	=> $primary,
					'secret'	=> $secret,
					'farm'	=> $farm,
					'photos'	=> $photos,
					'videos'	=> $videos,
					'server'	=> $server, 
					'comments'	=> $fs->count_comments
	);
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	//Zend_Paginator::setCache($this->_cache);	
	if(isset($page) && ($page != ""))  {
    $paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
		->setPageRange(10); 	
	$this->view->data = $paginator;
	}
	
	/** Find photos with a set radius of the where on earthID
	*/		
	public function whereonearthAction() {
	$access = $this->tokens();
	$woeid = (int)$this->_getParam('id');
	$page = (int)$this->_getParam('page');
	if(!isset($page)){
		$start = 1;
	} else {
		$start = $page ;
	}
	$this->view->page = $page;
	$this->view->place = $woeid;
		$key = md5('woeid' . $woeid . $page);
	
	if (!($this->_cache->test($key))) {
	$q = 'select * from xml where url ="http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=' 
	. $this->_flickrkey . '&tags=archaeology&license=1%2C2%2C3%2C4%2C5%2C6%2C7&woe_id=' . $woeid . '&radius=500&radius_units=m&extras=description%2Clicense%2Cdate_upload%2Cdate_taken%2C+owner_name%2C+icon_server%2C+original_format%2C+last_update%2C++%09+geo%2C+tags%2C+machine_tags%2C+o_dims%2C+views%2C+media%2C+path_alias%2C+url_sq%2C+url_t%2C+url_s%2C+url_m%2C+url_o&per_page=20&page=' . $start . '&format=rest"';
	
	$flickr = $this->_oauth->execute(
	$q, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	Zend_Debug::dump($flickr);
	if(!is_null($flickr->query->results)){
	$photos = array();
	$total = (int)$flickr->query->results->rsp->photos->total;
	$photos = array();
	foreach($flickr->query->results->rsp->photos->photo as $a){
	$photos[] = array(
	'id' => $a->id,
	'title' => $a->title,
	'views' => $a->views,
	'description' => $a->description,
	'farm' => $a->farm,
	'secret' => $a->secret,
	'server' => $a->server,
	'mediumimage' => $a->url_m,
	'image' => $a->url_sq,
	'tags' => $a->tags,
	'created' => $a->datetaken,
	'lat' => $a->latitude,
	'lon' => $a->longitude,
	'machines' => $a->machine_tags,
	'pathalias' => $a->pathalias,
	'owner' => $a->owner,
	'iconserver' => $a->iconserver,
	'iconfarm' => $a->iconfarm,
	'ownername' => $a->ownername,
	'woeid' => $a->woeid
	);
	}
	$pagination = array(    
	'page'          => $page, 
	'results' 		=> $photos,
	'per_page'      => 20, 
    'total_results' => $total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
    $paginator->setCurrentPageNumber($pagination['page'])
              ->setItemCountPerPage(20)
              ->setPageRange(20);
	$this->view->paginator = $paginator;
	$this->view->pictures = $photos;
	}
	}
	/** Find images in a set
	*/		
	public function inasetAction() {
	if($this->_getParam('id',false)){
	$access = $this->tokens();
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
	$q = 'SELECT * from xml where url="http://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=' . $this->_flickrkey . '&photoset_id=' . $id . '&extras=license%2Cdate_upload%2Cdate_taken%2Cowner_name%2C+icon_server%2C+original_format%2C+last_update%2Cgeo%2Ctags%2Cmachine_tags%2Co_dims%2Cviews%2Cmedia%2Cpath_alias%2Curl_sq%2C+url_t%2Curl_s%2Curl_m%2Curl_o&per_page='. $number . '&page=' . $start . '&format=rest"';
	$flickr = $this->_oauth->execute( 
	$q, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$photos = array();
	$total = (int) $flickr->query->results->rsp->photoset->total;
	foreach($flickr->query->results->rsp->photoset->photo as $a) {
	if(isset($a->woeid)){
		$woeid = $a->woeid;
	} else {
		$woeid = NULL;
	}
	$photos[] = array(
	'id' => $a->id,
	'title' => $a->title,
	'description' => NULL,
	'farm' => $a->farm,
	'secret' => $a->secret,
	'server' => $a->server,
	'mediumimage' => $a->url_m,
	'image' => $a->url_sq,
	'tags' => $a->tags,
	'created' => $a->datetaken,
	'lat' => $a->latitude,
	'lon' => $a->longitude,
	'machines' => $a->machine_tags,
	'woeid' => $woeid,
	'views' => $a->views,
	'licence' => $a->license,
	'media' => $a->media
//	'pathalias' => $a->pathalias,
//	'owner' => $a->ownername,
//	'iconserver' => $a->iconserver,
//	'iconfarm' => $a->iconfarm,
//	'ownername' => $a->ownername,
//	'woeid' => $a->woeid,
//	'username' => $a->ownername,
	);
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
	$this->view->pictures = $photos;
	$this->view->id = $id;
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** get photos's details
	*/		
	public function detailsAction() {
	if($this->_getParam('id',false)){
	$access = $this->tokens();
	$id = $this->_getParam('id');
	if (!($this->_cache->test('exif'.$id)))  {
	$exif = 'select * from xml where url="http://api.flickr.com/services/rest/?method=flickr.photos.getExif&api_key=' 
	. $this->_flickrkey. '&photo_id=' . $id . '"';
	$flickr = $this->_oauth->execute($exif,$access['access_token'], $access['access_token_secret'], 
		$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($exif);
	} else {
	$exifdata = $this->_cache->load('exif'.$id);
	}
	$noise = array();
	if(isset($flickr->query->results->rsp->photo->exif)){
	foreach($flickr->query->results->rsp->photo->exif as $e) {
	$noise[$e->label] = $e->raw;
	}
	$this->view->exif = $noise;
	}
	$geo = 'select * from xml where url ="http://api.flickr.com/services/rest/?method=flickr.photos.geo.getLocation&api_key=' 
	. $this->_flickrkey . '&photo_id='.$id.'"';
	$geodata = $this->_oauth->execute($geo,$access['access_token'], $access['access_token_secret'], 
		$access['access_token_expiry'], $access['handle'] );
	if($geodata->query->results->rsp == 'ok') {
	$geodataview = array();
	foreach($geodata->query->results->rsp->photo->location as $key => $value) {
	$geodataview[$key] = $value;
	}
	}
	$p = new Phlickr_Photo($this->api, $id);
	$image = array();
	$image['sizes'] =	$p->getSizes();
	$image['rawtags'] =	$p->getRawTags();
	$image['tags'] =	$p->getTags();
	$image['description'] =	$p->getDescription();
	$image['created'] =	$p->getPostedDate();
	$image['url'] =	$p->buildUrl();
	$image['mediumimage']	= $p->buildImgUrl('-');
	$image['image'] =	$p->buildImgUrl('s');
	$image['title'] =	$p->getTitle();
	$image['geo'] =	$p->getGeo();
	$image['coords'] =	$p->getCoords();
	$this->view->image = $image;
	
	$owner = 'select * from flickr.photos.info where  photo_id="' . $id . '" and api_key="'. $this->_flickrkey .'" ';
	$data = $this->_oauth->execute($owner,$access['access_token'], $access['access_token_secret'], 
		$access['access_token_expiry'],$access['handle'] );
	$ownerdata = array();
	foreach ($data->query->results->photo as $key => $value){
		$ownerdata[$key] = $value;
	}
	$this->view->ownerdata = $ownerdata;
	$request = $this->api->createRequest('flickr.photos.comments.getList',array('photo_id'=> $id));
	$comments = new Phlickr_CommentList($request);
	$yousay = $comments->getComments();
	$comms = array();
	foreach($yousay as $c) {
	$comms[] = array(
	'author'	=> $c->getAuthorName(),
	'comment'	=> $c->getComment(),
	'submitted'	=> $c->getCreationDate(),
	'authorid'	=> $c->getAuthorId(),
	'url'	=> $c->buildUrl(),
	'permalink'	=> $c->getUrl(),
	'commentID'	=> $c->getId()
	);
	}
	$this->view->comments = $comms;
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	/** Find images tagged in a certain way.
	*/		
	public function taggedAction() {
	$access = $this->tokens();
	$tags = $this->_getParam('as');
	$page = $this->_getParam('page');
	$number = 10;
	if(!isset($page)){
		$start = 1;
	} else {
		$start = $page ;
	}
	$key = md5('tagged' . $tags . $page);
	if (!($this->_cache->test($key))) {
	$q = 'SELECT * FROM  xml where url="http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=' . $this->_flickrkey . '&user_id=' . $this->_userid . '&tags=' . $tags 
	. '&extras=description,license,date_upload,date_taken,owner_name,icon_server,original_format,last_update,geo,tags,machine_tag,o_dims,views,media,path_alias,url_sq,url_t,url_s,url_m,url_o&per_page=' . $number .'&page=' . $start . '";';
	$flickr = $this->_oauth->execute($q,$access['access_token'], $access['access_token_secret'],$access['access_token_expiry'],$access['handle'] );
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load($key);
	}
	$photos = array();
	if(isset($flickr->query->results->rsp->photos)){
	$total = $flickr->query->results->rsp->photos->total;
	
	foreach($flickr->query->results->rsp->photos->photo as $a) {
	if(isset($a->woeid)){
	$woeid = $a->woeid;
	} else {
	$woeid = NULL;
	}
	//Should change this to a foreach key => value and save code.
	$photos[] = array(
	'id' => $a->id,
	'title' => $a->title,
	'views' => $a->views,
	'farm' => $a->farm,
	'secret' => $a->secret,
	'server' => $a->server,
	'mediumimage' => $a->url_m,
	'image' => $a->url_sq,
	'tags' => $a->tags,
	'created' => $a->datetaken,
	'lat' => $a->latitude,
	'lon' => $a->longitude,
	'machines' => $a->machine_tags,
	'woeid' => $woeid,
	'pathalias' => $a->pathalias,
	'owner' => $a->owner,
	'iconserver' => $a->iconserver,
	'iconfarm' => $a->iconfarm,
	'ownername' => $a->ownername,
	'username' => $a->ownername,
	);
	
	}
	$this->view->tagtitle = $tags;
	$pagination = array(    
	'page'          => $page, 
	'results' 		=> $photos,
	'per_page'      => 5, 
    'total_results' => (int) $total
	);
	$paginator = Zend_Paginator::factory($pagination['total_results']);
    $paginator->setCurrentPageNumber($pagination['page'])
              ->setItemCountPerPage(5)
              ->setPageRange(20);
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