<?php

/**Retrieve treasure valuations from the database
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
*/
class AgreedTreasureValuations extends Zend_Db_Table_Abstract {
	
	protected $_treasureID, $_auth, $_time, $_cache;
	protected $_primary = 'id';
	protected $_name = 'agreedTreasureValuations';
	
	public function init() {
		$this->_cache = Zend_Registry::get('rulercache');
		$this->_request = Zend_Controller_Front::getInstance()->getRequest();
		$this->_treasureID = $this->_request->getParam('treasureID');
		$this->_auth = Zend_Registry::get('auth');
		$this->_time = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	}
	
	/** Add  a valuation
	* @param array $data 
	* @return boolean
	*/
	public function add($data){
		if (!isset($data['created']) || ($data['created'] instanceof Zend_Db_Expr)) {
	    $data['created'] = $this->_time;
	  	}
	  	$data['createdBy'] = $this->_auth->getIdentity()->id;
		$data['treasureID'] = $this->_treasureID;
		return parent::insert($data);
	}
	
	/** Update a valuation
	* @param array $data 
	* @return boolean
	*/
	public function updateTreasure($data){
		if (!isset($data['updated']) || ($data['updated'] instanceof Zend_Db_Expr)) {
	    $data['updated'] = $this->_time;
	  	}
	  	$where = parent::getAdapter()->quoteInto('treasureID = ?', $this->_treasureID);
	  	$data['updatedBy'] = $this->_auth->getIdentity()->id;
	  	return parent::update($data,$where);	
	}
	
	/** Delete a valuation
	* @return boolean
	*/
	public function delete($data){
		
	}
	
	/** List valuations
	* @param integer $treasureID 
	* @return array
	*/
	public function listvaluations($treasureID){
	$values = $this->getAdapter();
	$select = $values->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('enteredBy' => 'fullname'))
		->where('treasureID = ?',(string)$treasureID)
		->order($this->_name . '.created');
	return $values->fetchAll($select);
	}
	
	/** Get individual valuation
	* @param integer $treasureID 
	* @return array
	*/
	public function getValuation($treasureID){
	$values = $this->getAdapter();
	$select = $values->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('enteredBy' => 'fullname'))
		->where('treasureID = ?',(string)$treasureID)
		->order($this->_name . '.created');
	return $values->fetchAll($select);
	}
}

