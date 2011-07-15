<?php
/** Controller for displaying the photos section of the flickr module.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Flickr_PhotosController extends Pas_Controller_ActionAdmin {
	
	protected $_oauth, $_flickrkey, $_secret, $_auth, $_config, $_userID, $_cache;
	/** Setup the contexts by action and the ACL.
	*/			
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$this->api = new Phlickr_Api($flickrkey, '9658f77b99f4eb54', '72157622564414951-412d4afcd026fd7f');
	$this->_config = Zend_Registry::get('config');
	$this->_flickrkey = $this->_config->webservice->flickr->apikey;
	$this->_secret = $this->_config->webservice->flickr->secret;
	$this->_auth = $this->_config->webservice->flickr->auth;
	$this->_cache = Zend_Registry::get('cache');
	$this->_oauth = new Pas_YqlOauth();
	}
	/** No direct access to photos, goes to the index controller
	*/			
	public function indexAction() {
	$this->_redirect('/flickr/');
    }
    
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
	url='http://api.flickr.com/services/rest/?method=flickr.photosets.getList&api_key=dbb87ca6390925131a4fedb34d9d8d80&user_id=10257668@N04'";
	$flickr = $this->_oauth->execute($q,$access['access_token'], $access['access_token_secret'],$access['access_token_expiry'],$access['handle'] );
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
	$data[] = array('title' => $title,'id' => $id,'description' => $description, 'primary' => $primary,'secret' => $secret,'farm' => $farm,'photos' => $photos,'videos' => $videos,'server' => $server, 
	//'comments' => $amount
	);
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	//Zend_Paginator::setCache($this->_cache);	
	if(isset($page) && ($page != "")) 
	{
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
	$this->view->page = $page;
	$this->view->place = $woeid;
	if (!($this->_cache->test('woeid' . $woeid))) {
	$q = 'SELECT * FROM flickr.photos.search(0,200) where api_key="' . $this->_flickrkey . '"
	 and radius="500" and radius_units="m" and tags="archaeology" and extras="description, license, 
	 date_upload, date_taken, owner_name, icon_server, original_format, last_update, 
	 geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, url_s, url_m, url_o"
	 and license="1,2,3,4,5,6,7" and woe_id=" '. $woeid. '";';
	$flickr = $this->_oauth->execute(
	$q, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load('woeid' . $woeid);
	}
	if(!is_null($flickr->query->results)){
	$photos = array();
	foreach($flickr->query->results->photo as $a)
	{
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
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($photos));
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
          	  ->setPageRange(10); 	
	$this->view->pictures = $paginator;
	}
	}
	/** Find images in a set
	*/		
	public function inasetAction() {
	if($this->_getParam('id',false)){
	$access = $this->tokens();
	$id = $this->_getParam('id');
	$page = $this->_getParam('page');
	if (!($this->_cache->test('set' . $id))) {
	$q = 'SELECT * from flickr.photosets.photos(0,200) where photoset_id ="' . $id . '"
	 and extras="license, date_upload, date_taken, owner_name, icon_server, original_format, 
	 last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_t, 
	 url_s, url_m, url_o" AND api_key="' . $this->_flickrkey .'"';
	$flickr = $this->_oauth->execute( 
	$q, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load('set' . $id);
	}
	$photos = array();
	foreach($flickr->query->results->photo as $a) {
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
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($photos));
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
          ->setPageRange(10); 
	$this->view->pictures = $paginator;
	$this->view->id = $id;
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** get photos's details
	*/		
	public function detailsAction() {
	if($this->_getParam('id',false)){
	$access = $this->tokens();
	$id = $this->_getParam('id');
	if (!($this->_cache->test('exif'.$id)))  {
	$exif = 'select * from xml where url="http://api.flickr.com/services/rest/?method=flickr.photos.getExif&api_key=dbb87ca6390925131a4fedb34d9d8d80&photo_id=' . $id . '"';
	$flickr = $this->_oauth->execute($exif,$access['access_token'], $access['access_token_secret'],$access['access_token_expiry'],$access['handle'] );
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
	$geo = 'select * from xml where url ="http://api.flickr.com/services/rest/?method=flickr.photos.geo.getLocation&api_key=dbb87ca6390925131a4fedb34d9d8d80&photo_id='.$id.'"';
	$geodata = $this->_oauth->execute($geo,$access['access_token'], $access['access_token_secret'],$access['access_token_expiry'],$access['handle'] );
	if($geodata->query->results->rsp == 'ok') {
	$geodataview = array();
	foreach($geodata->query->results->rsp->photo->location as $key => $value)
	{
	$geodataview[$key] = $value;
	}
	}
	$p = new Phlickr_Photo($this->api, $id);
	$image = array();
	$image['sizes'] = $p->getSizes();
	$image['rawtags'] = $p->getRawTags();
	$image['tags'] = $p->getTags();
	$image['description'] = $p->getDescription();
	$image['created'] = $p->getPostedDate();
	$image['url'] = $p->buildUrl();
	$image['mediumimage'] = $p->buildImgUrl('-');
	$image['image'] = $p->buildImgUrl('s');
	$image['title'] = $p->getTitle();
	$image['geo'] = $p->getGeo();
	$image['coords'] = $p->getCoords();
	$this->view->image = $image;
	
	$owner = 'select * from flickr.photos.info where  photo_id="' . $id . '" and api_key="dbb87ca6390925131a4fedb34d9d8d80" ';
	$data = $this->_oauth->execute($owner,$access['access_token'], $access['access_token_secret'],$access['access_token_expiry'],$access['handle'] );
	
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
	'author' => $c->getAuthorName(),
	'comment' => $c->getComment(),
	'submitted' => $c->getCreationDate(),
	'authorid' => $c->getAuthorId(),
	'url' => $c->buildUrl(),
	'permalink' => $c->getUrl(),
	'commentID' => $c->getId()
	);
	}
	$this->view->comments = $comms;
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Find images tagged in a certain way.
	*/		
	public function taggedAction() {
	$access = $this->tokens();
	$tags = $this->_getParam('as');
	$page = $this->_getParam('page');
	$tagmode = 'all';
	$userid = $this->api->getUserId();
	if (!($this->_cache->test('tagged'.$tags))) {
	$q = 'SELECT * FROM  flickr.photos.search(0,50) where user_id = "10257668@N04" and tags="' . $tags . '" and tag_mode="all" and 
	sort="interestingness-desc" and extras="license, date_upload, date_taken, owner_name, icon_server, original_format,last_update, geo, tags, machine_tags, o_dims, 
	views, media, path_alias, url_sq, url_t,url_s, url_m, url_o" and license="1,2,3,4,5,6,7" and api_key="' . $this->flickrkey . '"';
	$flickr = $this->_oauth->execute($q,$access['access_token'], $access['access_token_secret'],$access['access_token_expiry'],$access['handle'] );
	$this->_cache->save($flickr);
	} else {
	$flickr = $this->_cache->load('tagged'.$tags);
	}
	$photos = array();
	if(isset($flickr->query->results)){
	foreach($flickr->query->results->photo as $a) {
	if(isset($a->woeid)){
	$woeid = $a->woeid;
	} else {
	$woeid = NULL;
	}
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
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($photos));
	if(isset($page) && ($page != ""))  {
    $paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
		->setPageRange(10); 	
	$this->view->pictures = $paginator;
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
	$api_sig = md5($this->secret.'api_key'.$this->flickrkey.'auth_token'.$this->auth.'method'.$method);
	$request = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20xml%20where%20url%3D'http%3A%2F%2Fapi.flickr.com%2Fservices%2Frest%2F%3Fmethod%3Dflickr.groups.pools.getGroups%26api_key%3Ddbb87ca6390925131a4fedb34d9d8d80%26auth_token%3D72157622564414951-412d4afcd026fd7f%26api_sig%3D156cb34b6efb71aed0eecaaf7c770d94'&format=json&diagnostics=false&env=http%3A%2F%2Fdatatables.org%2Falltables.env";
	$q = "select * from xml where url='http://api.flickr.com/services/rest/?method=flickr.groups.pools.getGroups&api_key=dbb87ca6390925131a4fedb34d9d8d80&auth_token=72157622564414951-412d4afcd026fd7f&api_sig=156cb34b6efb71aed0eecaaf7c770d94'";
	$ph = $this->_oauth->execute($q,$access['access_token'], $access['access_token_secret'],$access['access_token_expiry'],$access['handle'] );
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
	$g = $this->_cache->load('groupinfo'.str_replace('@','',$id));
	}
	
	if (!($this->_cache->test('groupphotos'.str_replace('@','',$id)))) {
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
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Get a list of our favourite images
	*/		
	public function favouritesAction() {
	$access = $this->tokens();
	$page = $this->_getParam('page');
	if (!($this->_cache->test('faves'))) {
	$q = "select * from xml where url='http://api.flickr.com/services/rest/?method=flickr.favorites.getPublicList&api_key=dbb87ca6390925131a4fedb34d9d8d80&user_id=10257668@N04&license=1%2C2%2c3&extras=license%2C+date_upload%2C+date_taken%2C+owner_name%2C+icon_server%2C+original_format%2C+last_update%2C+geo%2C+tags%2C+machine_tags%2C+o_dims%2C+views%2C+media%2C+path_alias%2C+url_sq%2C+url_t%2C+url_s%2C+url_m%2C+url_o'";
	$ph = $this->_oauth->execute(
	$q, $access['access_token'], $access['access_token_secret'],
	$access['access_token_expiry'], $access['handle'] );
	$this->_cache->save($ph);
	} else {
	$ph = $this->_cache->load('faves');
	}
	$photos = array();
	foreach($ph->query->results->rsp->photos->photo as $g){
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
	/** Get a list of interesting flickr images attributed to archaeology
	*/			
	public function interestingAction() {
	$access = $this->tokens();
	$page = $this->_getParam('page');
	if (!($this->_cache->test('archaeologyflickrnew'))) {
	$q = "select * from flickr.photos.search(0,100) where api_key='dbb87ca6390925131a4fedb34d9d8d80' 
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
	foreach($ph->query->results->photo as $g)
	{
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