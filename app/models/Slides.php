<?php

/**
* Data model for accessing slides data
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @version 1
* @since 22 October 2010, 17:12:34
* @todo rewrite this terrible piece of cruddy programming. Man, I've learnt since I 
* wrote this crap. SOLR it up baby!
*/
class Slides extends Zend_Db_Table_Abstract {

	protected $_name = 'slides';
	
	protected $_primary = 'imageID';
	
	protected $_cache, $_auth;
	
	protected $_higherlevel = array('admin','flos','fa'); 
	
	protected $_restricted = array('public','member');

	/** Construct the auth, config, treasureID and other objects
	* @return object
	*/
	public function init(){
	$this->_auth = Zend_Registry::get('auth');
	$this->_cache = Zend_Registry::get('rulercache');
	}

	/** get user's role
	* @return string
	*/
	protected function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}

	/** Get thumbnails for a particular find number
	* @param integer $id 
	* @return array
	* @todo add caching
	*/
	public function getThumbnails($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','i' => 'imageID','label','createdBy'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('old_findID','objecttype','id','secuid'))
		->joinLeft('users','users.id = slides.createdBy', array('username','imagedir'))
		->where('finds.id = ?', (int)$id)
		->order('slides.' . $this->_primary . ' ASC');
	return  $thumbs->fetchAll($select);
	}

	/** Get last 10 thumbnails
	* @param integer $limit 
	* @return array
	*/
	public function getLast10Thumbnails($limit=NULL) {
	if (!$data = $this->_cache->load('frontimagesdb'.$this->getRole())){
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name,array('thumbnail'  => 'slides.imageID','created','label','f' => 'filename'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('objecttype','id','old_findID','broadperiod'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir','username'))
		->order('slides.created DESC')
		->limit($limit);
	$data =   $thumbs->fetchAll($select);
	$this->_cache->save($data, 'frontimagesdb'.$this->getRole());
	} 
	return $data;
	}

	/** Get last 12 thumbnails for a staff member
	* @param integer $limit 
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getLast12ThumbnailsFlo($limit=NULL,$id = NULL) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->distinct()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('objecttype','id','old_findID'))
		->joinLeft('staff','staff.dbaseID = finds.createdBy',array())
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->order($this->_name.'.imageID DESC')
		->where('staff.id = ?', (int)$id)
		->where('finds.id IS NOT NULL')
		->limit($limit);
	return  $thumbs->fetchAll($select);
	}


	/** Get last 12 thumbnails for a rally
	* @param integer $limit 
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getLast12ThumbnailsRally($limit=NULL,$id = NULL) {
		$thumbs = $this->getAdapter();
		$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('objecttype','id','old_findID'))
		->joinLeft('staff','staff.dbaseID = finds.createdBy',array())
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->order($this->_name.'.imageID DESC')
		->where('finds.rallyID = ?', (int)$id)
		->where('finds.id IS NOT NULL')
		->limit($limit);
	return  $thumbs->fetchAll($select);
	}

	/** Get specific thumbnails
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getThumb($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('old_findID'))
		->where('finds.id = ?', (int)$id)
		->limit(1);
	return  $thumbs->fetchAll($select);
	}

	/** Get coin examples
	* @param integer $limit 
	* @param integer $rulerID
	* @return array
	* @todo add caching
	*/
	public function getExamplesCoins($rulerID,$limit) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('id','old_findID','objecttype','broadperiod'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->where('coins.ruler_id = ?', (int)$rulerID)
		->order('finds.id DESC')
		->group('finds.id')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return  $thumbs->fetchAll($select);
	}

	/** Get a user's images paginated
	* @param integer $id 
	* @param array $params
	* @return array
	* @todo add caching
	*/
	public function getMyImagesUser($id,$params) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id')
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir'))
		->where($this->_name . '.createdBy = ?', (int)$id)
	->order($this->_name . '.created DESC');
	$rowCount = $thumbs->select()->from($this->_name);
	$rowCount->reset( Zend_Db_Select::COLUMNS )
		->columns( new Zend_Db_Expr( 'COUNT(*) AS ' . 
	Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN ));
	if(isset($params['old_findID']) && ($params['old_findID'] != "")) {
	$findID = strip_tags($params['old_findID']);
	$select->where('finds.old_findID = ?', (string)$findID);
	$rowCount->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array())
		->where('finds.old_findID = ?', (string)$findID);
	}
	if(isset($params['broadperiod']) && ($params['broadperiod'] != "")) {
	$broadperiod = strip_tags($params['broadperiod']);
	$select->where('slides.period = ?', (string)$broadperiod);
	$rowCount->where('slides.period = ?', (string)$broadperiod);
	}
	if(isset($params['label']) && ($params['label'] != "")) {
	$label = strip_tags($params['label']);
	$select->where('slides.label = ?', (string)$label);
	$rowCount->where('slides.label = ?', (string)$label);
	}
	if(isset($params['county']) && ($params['county'] != "")) {
	$county = strip_tags($params['county']);
	$select->where('slides.county = ?', (string)$county);
	$rowCount->where('slides.county = ?', (string)$county);
	}
	$paginator = Zend_Paginator::factory($select);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']); 
	}
	$paginator->setItemCountPerPage(40) 
		->setPageRange(20); 
	return $paginator;
	}
	
	/** Get all images
	* @param array $params 
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getAllImages($params) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','secuid'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('id','old_findID','broadperiod'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county'));
	$rowCount = $thumbs->select()->from($this->_name);
	$rowCount->reset( Zend_Db_Select::COLUMNS )
		->columns( new Zend_Db_Expr( 'COUNT(*) AS '. Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN ));
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > 2');
	}
	if(isset($params['old_findID']) && ($params['old_findID'] != ""))  {
	$findID = strip_tags($params['old_findID']);
	$select->where('finds.old_findID = ?', $findID);
	$rowCount->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array())
		->where('finds.old_findID = ?', $findID);
	}
	if(isset($params['broadperiod']) && ($params['broadperiod'] != "")) {
	$broadperiod = strip_tags($params['broadperiod']);
	$select->where('slides.period = ?', $broadperiod);
	$rowCount->where('slides.period = ?', $broadperiod);
	}
	if(isset($params['label']) && ($params['label'] != "")) {
	$label = strip_tags($params['label']);
	$select->where('slides.label LIKE ?', (string)'%'.$label . '%');
	$rowCount->where('slides.label LIKE ?',(string)'%'.$label . '%');
	}
	if(isset($params['county']) && ($params['county'] != "")) {
	$county = strip_tags($params['county']);
	$select->where('slides.county = ?', (string)$county);
	$rowCount->where('slides.county = ?', (string)$county);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->getAdapter()->setRowCount($rowCount);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']); 
	}
	$paginator->setItemCountPerPage(40) 
		->setPageRange(20); 
	return $paginator;
	}

	/** Get a specific image
	* @param integer $id 
	* @return array
	* @todo add caching
	*/
	public function getImage($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name,array('id' => 'imageID','filename','label',
		'filesize','county','period','imagerights',
		'secuid','created','createdBy'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('old_findID','broadperiod'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir','fullname'))
		->where('slides.imageID = ?', (int)$id);
	return  $thumbs->fetchAll($select);
	}
	
	/** Get linked finds to an image
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getLinkedFinds($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name)
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array('linkid' => 'id')) 
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('old_findID','broadperiod','objecttype',
		'findID' => 'id'))
		->joinLeft('users','users.id = slides.createdBy', array('fullname','userid' => 'id'))
		->where('slides.imageID = ?', (int)$id);
	return  $thumbs->fetchAll($select);
	}
	
	/** Get linked finds to an image
	* @param string $secuid
	* @return array
	* @todo add caching
	*/
	public function getImageForLinks($secuid) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name)
		->where('slides.secuid = ?', (string)$secuid);
	return  $thumbs->fetchAll($select);
	}

	/** Get last 10 finds for a specific object type
	* @param string $term
	* @param integer $limit
	* @return array
	* @todo add caching
	*/
	public function getLast10ThumbnailsToObjectType($term,$limit=NULL){
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','created','f' => 'filename','i' => 'imageID','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('objecttype','id','old_findID'))
		->joinLeft('users','users.id = slides.createdBy', array('username'))
		->order($this->_name.'.imageID DESC')
		->where('finds.objecttype = ?',(string)$term)
		->where('finds.id IS NOT NULL')
		->group('finds.id')
		->limit($limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage NOT IN ( 1, 2 )');
	}
	return  $thumbs->fetchAll($select);
	}

	/** Get the filename for an image number
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function getFileName($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('f' => 'filename','label','imageID','secuid'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array()) 
		->joinLeft('finds','finds_images.find_id = finds.secuid', array('id'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir'))
		->where($this->_name.'.imageID = ?', (int)$id);
	return  $thumbs->fetchAll($select);
	}
	
	/** Fetch deletion data
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	public function fetchDelete($id) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('f' => 'filename','imageID','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('users','users.id = slides.createdBy',array('imagedir'))
		->where($this->_name.'.imageID = ?', (int)$id);
	return  $thumbs->fetchAll($select);
	}

	/** Get example images for a coin period
	* @param integer $limit
	* @param string $period
	* @return array
	*/
	public function getExamplesCoinsPeriod($period,$limit) {
	if (!$data = $this->_cache->load('coinsperiod'.str_replace(' ','',$period) . $this->getRole())) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name,array('thumbnail'  => 'slides.imageID','created','label','f' => 'filename'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('objecttype','id','old_findID','broadperiod'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir','username'))
		->where('finds.broadperiod = ?', (string)$period)
		->where('finds.objecttype = ?', (string)'coin')
		->group('finds.id')
		->order('slides.created DESC')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	$data =  $thumbs->fetchAll($select);
	$this->_cache->save($data, 'coinsperiod' . str_replace(' ','',$period) . $this->getRole());
	} 
	return $data;
	}

	/** Get example images for a tribe
	* @param integer $limit
	* @param integer $tribeID
	* @return array
	*/
	public function getExamplesCoinsTribes($tribeID,$limit) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('id','old_findID','broadperiod','objecttype'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->where('coins.tribe = ?', (int)$tribeID)
		->order('finds.id DESC')
		->group('finds.id')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return  $thumbs->fetchAll($select);
	}


	/** Get example images for a denomination
	* @param integer $limit
	* @param integer $denomID
	* @return array
	*/
	public function getExamplesCoinsDenominations($denomID,$limit) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id', array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id', array('id','old_findID','objecttype','broadperiod'))
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->where('coins.denomination = ?', (int)$denomID)
		->order('finds.id DESC')
		->group('finds.id')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return  $thumbs->fetchAll($select);
	}

	/** Get example images for a mint
	* @param integer $limit
	* @param integer $mintID
	* @return array
	*/
	public function getExamplesCoinsMints($mintID,$limit) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('id','old_findID','objecttype','broadperiod'))
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->where('coins.mint_id = ?', (int)$mintID)
		->order('finds.id DESC')
		->group('finds.id')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return  $thumbs->fetchAll($select);
	}

	/** Get example images for an emperor
	* @param integer $limit
	* @param integer $emperorID
	* @return array
	*/
	public function getExamplesCoinsEmperors($emperorID,$limit) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('id','old_findID','objecttype','broadperiod'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->joinLeft('emperors','emperors.pasID = coins.ruler_id',array())
		->where('emperors.id = ?', (int)$emperorID)
		->order('finds.id DESC')
		->group('finds.id')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return  $thumbs->fetchAll($select);
	}

	/** Get example images for a reece period
	* @param integer $limit
	* @param integer $reeceID
	* @return array
	*/
	public function getExamplesCoinsReeces($reeceID,$limit) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('id','old_findID','objecttype','broadperiod'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->where('coins.reeceID = ?', (int)$reeceID)
		->order('finds.id DESC')
		->group('finds.id')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return  $thumbs->fetchAll($select);
	}

	/** Get example images for a medieval type
	* @param integer $limit
	* @param integer $typeID
	* @return array
	*/
	public function getExamplesCoinsMedTypes($typeID,$limit) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('id','old_findID','objecttype','broadperiod'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->where('coins.typeID = ?', (int)$typeID)
		->order('finds.id DESC')
		->group('finds.id')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return  $thumbs->fetchAll($select);
	}

	/** Get example images for a reverse type
	* @param integer $limit
	* @param integer $typeID
	* @return array
	*/
	public function getExamplesCoinsReverseTypes($typeID,$limit) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('id','old_findID','objecttype','broadperiod'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->where('coins.revtypeID = ?', (int)$typeID)
		->order('finds.id DESC')
		->group('finds.id')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return  $thumbs->fetchAll($select);
	}

	/** Get example images for a moneyer
	* @param integer $limit
	* @param integer $moneyerID
	* @return array
	*/
	public function getExamplesCoinsMoneyers($moneyerID,$limit) {
	$thumbs = $this->getAdapter();
	$select = $thumbs->select()
		->from($this->_name, array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('id','old_findID','objecttype','broadperiod'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->joinLeft('users','users.id = slides.createdBy',array('username'))
		->where('coins.moneyer = ?', (int)$moneyerID)
		->order('finds.id DESC')
		->group('finds.id')
		->limit((int)$limit);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return  $thumbs->fetchAll($select);
	}

	/** Get most recent finds
	* @param integer $limit
	* @param integer $username
	* @return array
	*/
	public function recentFinds($username,$limit = 4) {
	if (!$data = $this->_cache->load(md5($username))) {
	$users = $this->getAdapter();
	$select = $users->select()
		->from($this->_name,array('thumbnail'  => 'slides.imageID','f' => 'filename','label'))
		->joinLeft('finds_images','slides.secuid = finds_images.image_id',array()) 
		->joinLeft('finds','finds.secuid = finds_images.find_id',array('id','old_findID','objecttype','broadperiod'))
		->joinLeft('users','users.id = finds.createdBy',array('username'))
		->where('users.username = ?', $username)
		->where('finds.secwfstage > 2')
		->order($this->_primary . ' DESC')
		->group('finds.id')
		->limit((int)$limit);
    $data =  $users->fetchAll($select);
	$this->_cache->save($data, md5($username));
	}
	return $data;
}

}
