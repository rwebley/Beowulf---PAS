<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add edit and delete functions and caching
*/

class Moneyers extends Zend_Db_Table_Abstract {
	
	protected $_name = 'moneyers';
	
	protected $_primary = 'id';
	
	/** Retrieve moneyer list with concatenated names and dates
	* @return array 
	* @todo add caching
	*/
	public function getMoneyers() {
	$moneyers = $this->getAdapter();
	$select = $moneyers->select()
		->from($this->_name, array('id','term' => 'CONCAT(name,"(",date_1," ",date_2,")")'));
	return $moneyers->fetchAll($select);
    }
    	
    /** Retrieve moneyer list with concatenated names and dates in key value pair array
	* @return array 
	* @todo add caching
	*/
	public function getRepublicMoneyers() {
	$moneyers = $this->getAdapter();
	$select = $moneyers->select()
		->from($this->_name, array('id','term' => 'CONCAT(name,"(",date_1," ",date_2,")")'));
	return $moneyers->fetchPairs($select);
    }	
	
    /** Retrieve paginated moneyer list 
    * @param integer $params['page'] 
	* @return array 
	*/
	public function getValidMoneyers($params) {
	$moneyers = $this->getAdapter();
	$select = $moneyers->select()
		->from($this->_name)
		->where('valid = ?',(int)1);
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30) 
		->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
    }		
	
    /** Retrieve moneyer by ID number 
    * @param integer $id
	* @return array 
	* @todo change to fetchrow?
	* @todo add caching?
	*/
    public function getMoneyer($id) {
	$moneyers = $this->getAdapter();
	$select = $moneyers->select()
		->from($this->_name)
		->where($this->_primary.' = ?',(int)$id);
	return $moneyers->fetchAll($select);
    }			
}