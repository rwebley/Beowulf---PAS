<?php

/**
* Model for pulling common acronyms used for the website from the database.
*
*
* @category   Zend
* @package    Zend_Db_Table
* @subpackage Abstract
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/

class Acronyms extends Pas_Db_Table_Abstract {

	protected $_primary = 'id';
	protected $_name = 'abbreviations';
	
	/** Get all valid acronyms
	* @return array
	*/
	public function getValid() {
		if (!$data = $this->_cache->load('acronymsSite')) {
		$acros = $this->getAdapter();
		$select = $acros->select()
		->from($this->_name, array('abbreviation','expanded'))
		->where('valid = 1');
	    $data =  $acros->fetchPairs($select); 
		$this->_cache->save($data, 'acronymsSite');
		}
	return $data;
	}
	
	/** Get list of all acronyms and paginator them
	* @return array
	* @param array $params sent via controller
	*/
	public function getAllAcronyms($params)	{
		$acros = $this->getAdapter();
		$select = $acros->select()
		->from($this->_name, array('id','abbreviation','expanded','updated'))
		->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
		->order('abbreviation');
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(20) 
		->setPageRange(20);
		if(isset($params['page']) && ($params['page'] != "")) {
	    $paginator->setCurrentPageNumber($params['page']); 
		}
	return $paginator;
	}

}