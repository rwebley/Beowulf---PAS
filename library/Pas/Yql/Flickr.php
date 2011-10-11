<?php
class Pas_Yql_Flickr {
	
	protected $_cache, $_oauth, $_accessToken, $_accessSecret;
    
 	protected $_accessExpiry, $_handle, $_flickr;
	
	public function __construct($flickr){
	$this->_cache = Zend_Registry::get('cache');
	$this->_oauth = new Pas_Yql_Oauth();
	$this->_flickr = $flickr;	
	}

	const API_URI = 'http://where.yahooapis.com/v1/';
	
	const FLICKRURI = 'http://api.flickr.com/services/rest/?';
	
	public function buildQuery($args){
	return http_build_query($args);
	}
	
	public function getTokens(){
	$tokens = new OauthTokens();
	$where = array();
	$where[] = $tokens->getAdapter()->quoteInto('service = ?','yahooAccess'); 
	$validToken = $tokens->fetchRow($where);
	$this->_accessToken= unserialize($validToken->accessToken);
	$this->_accessSecret = unserialize($validToken->tokenSecret);
	$this->_accessExpiry = $validToken->expires;
	$this->_handle = unserialize($validToken->sessionHandle);
	}
	
	public function getData($yql){
	return $this->_oauth->execute($yql, $this->_accessToken, $this->_accessSecret, $this->_accessExpiry, $this->_handle);
	}
	
	public function getContactTotal($as){
	$yql = '';	
	}
	
	public function getContacts($page = 1, $limit = 60){
	$args = array(
	'method' => 'flickr.contacts.getPublicList',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $this->_flickr->userid,
	'per_page' => $limit,
	'page' => $page
	);
	$yql = 'select * from xml where url="' . self::FLICKRURI . $this->buildQuery($args) . '"';	
	return $this->getData($yql)->query->results->rsp->contacts;
	}
	
	public function findByUsername($name){
	$yql = 'SELECT * FROM flickr.people.findbyusername WHERE username="' . $name . '" and api_key ="' . $this->_flickr->apikey  . '"';
	return $this->getData($yql)->query->results->user->nsid;
	}
	
	public function getContactDetails($as){
	$id = $this->findByUsername($as);
	$yql = 'SELECT * FROM flickr.people.info2 WHERE user_id="' . $id .'" AND api_key="' . $this->_flickr->apikey .'"';
	return $this->getData($yql)->query->results->person;
	}
	
	public function getContactPhotos($as, $start = 0, $limit = 18){
	$id = $this->findByUsername($as);
	$yql = 'SELECT * FROM flickr.people.publicphotos(0,' . $limit . ') WHERE user_id="' . $id .'" AND 
	extras="description,license,date_upload,date_taken,owner_name,icon_server,original_format,last_update,
	geo,tags,machine_tag,o_dims,views,media,path_alias,url_sq,url_t,url_s,url_m,url_o" AND api_key="' 
	. $this->_flickr->apikey .'"';
	return $this->getData($yql)->query->results->photo;
	}
	
	public function getPhotosGeoData( $start = 0, $limit = 200, $user_id){
	$yql = 'select * from flickr.photos.search(' . $start . ',' . $limit .') where has_geo="true" and user_id="' . 
	$user_id .'" and api_key="' . $this->_flickr->apikey .'" and extras="description, license, date_upload, date_taken, 
	owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, 
	url_sq, url_t, url_s, url_m, url_z, url_l, url_o" and sort="date-posted-desc"';
	return $this->getData($yql)->query->results->photo;	
	}

	public function getPhotoCommentList( $photoID ){
	$args = array(
	'method' => 'flickr.photos.comments.getList',
	'api_key' => $this->_flickr->apikey,
	'photo_id' => $photoID,
	'min_comment_date' => $mindate,
	'max_comment_date' => $maxdate);
	$yql = 'Select from xml where url ="' . self::FLICKRURI . $this->buildQuery($args);
	return $this->getData($yql)->query->results;					
	}
	
	public function getPhotoInfo( $id ){
		
	}
	
	public function getSets( $userid ){
		
	}
	
	public function getWoeidRadius( $woeid, $radius, $units, $limit, $start, $tags, $license){
	$yql = 'select * from xml where url ="http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=' 
	. $this->_flickr->apikey . '&tags=' . $tags . '&license=' . $license . '&woe_id=' . $woeid . '&radius=' . $radius 
	. '&radius_units=' . $units . '&extras=description,license,date_upload,date_taken,owner_name,icon_server,
	original_format,last_update,geo,tags,machine_tags,o_dims,views,media,path_alias,url_sq,url_t,url_s,url_m,
	url_o&per_page=' . $limit .'&page=' . $start . '&format=rest"';
	return $this->getData($yql);
	}
	
	public function getPhotosetInfo( $photosetID ){
	$yql = 'SELECT * FROM flickr.photosets.info WHERE photoset_id="' . $photosetID . '" and api_key="' 
	. $this->_flickr->apikey . '"';
	return $this->getData($yql);
	}
	
	public function getPhotosInASet( $setID, $per_page = 20, $page = 1 ){
	$args = array(
	'method' => 'flickr.photosets.getList',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $this->_flickr->userid,
	'page' => $page,
	'per_page' => $per_page);
	
	$yql = 'select * from xml where url="'. self::FLICKRURI . $this->buildQuery($args) . "'";
	return $this->getData($yql);	
	}
	
	public function getPhotoExifDetails( $photoID ){
	$yql = 'select * from flickr.photos.exif where photo_id="' . $photoID . '" and api_key="' . $this->_flickr->apiKey 
	. '"';
	return $this->getData($yql);
	}
	
	public function getPhotosTaggedAs( $tag, $per_page, $page){
	$args = array(
	'method' => 'flickr.photos.search',
	'api_key' => $this->_flickr->apikey,
	'user_id' => $this->_flickr->userid,
	'extras' => 'description,license,date_upload,date_taken,owner_name,
				icon_server,original_format,last_update,geo,tags,machine_tag,
				o_dims,views,media,path_alias,url_sq,url_t,url_s,url_m, url_o',
	'tags' => $tag,
	'tag_mode' => 'all',
	'safe_search' => 1,
	'page' => $page,
	'per_page' => $per_page);
	
	$yql = 'SELECT * FROM  xml where url="'. self::FLICKRURI . $this->buildQuery($args) . '";';
	return $this->getData($yql)->query->results->rsp->photos;	
	}
}
