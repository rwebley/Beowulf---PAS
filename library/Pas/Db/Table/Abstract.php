<?php
class Pas_Db_Table_Abstract extends Zend_Db_Table_Abstract {

	public $_config;
	
	public $_cache;
	
	public function __construct(){
	$this->_config = Zend_Registry::get('config');	
	$this->_cache = Zend_Registry::get('cache');
	
	parent::__construct($options);
	}

	public function userNumber(){
	$user = new Pas_UserDetails();
	return $user->getIdentityForForms();
	}
	
	public function timeCreation(){
	$dateTime = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	return $dateTime;
	}
	
	public function add($data){
	if(empty($data['created'])){
		$data['created'] = $this->timeCreation();
	}
	if(empty($data['createdBy'])){
		$data['createdBy'] = $this->userNumber();
	}
	return parent::insert($data);	
	}
	
	public function update($data, $where){
	if(empty($data['updated'])){
		$data['updated'] = $this->timeCreation();
	}
	if(empty($data['updatedBy'])){
		$data['updatedBy'] = $this->userNumber();
	}
	return parent::update((array)$data, (array)$where);
	}
	
	public function _purgeCache(){
    $this->_cache->clean(Zend_Cache::CLEANING_MODE_ALL);
	}
	
	public function delete($where) {
    parent::delete($where);
    $this->_purgeCache();
	} 
}