<?php

/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
*/

class AllenTypes extends Zend_Db_Table_Abstract {
	protected $_name = 'allentypes';
	protected $_primary = 'id';
	protected $_cache;
	
	/** Construct the cache object
	* @return object
	*/
	public function init(){
		$this->_cache = Zend_Registry::get('rulercache');
	}

	/** Get all valid Allen Types for a dropdown listing
	* @return array
	*/
	public function getATypes(){
    	if (!$options = $this->_cache->load('atypedd')) {
		    $select = $this->select()
	                       ->from($this->_name, array('type', 'type'))
	                       ->order('type');
	        $options = $this->getAdapter()->fetchPairs($select);
			$this->_cache->save($options, 'atypedd');
		}
        return $options;
    }

    /** Get all valid Allen Types for a dropdown listing from a query string
    * @param string $q
	* @return array
	*/
	public function getTypes($q){
		$types = $this->getAdapter();
		$select = $types->select()
           				->from($this->_name, array('id','term' => 'type'))
						->where('type LIKE ? ', $q.'%')
						->order('type')
						->limit(10);
	   return $types->fetchAll($select);
	}

	 /** Get all valid Allen Types for a dropdown listing from a query string
    * @param array $params
	* @return array
	*/
	public function getAllenTypes($params){
		$types = $this->getAdapter();
		$select = $types->select()
           			    ->from($this->_name)
						->order($this->_name.'.type')
						->group($this->_name.'.type');
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30) 
		          ->setPageRange(20);
		if(isset($params['page']) && ($params['page'] != "")) {
	    $paginator->setCurrentPageNumber($params['page']); 
		}
		return $paginator;
	}

}