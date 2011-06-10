<?php
/** Retrieve and manipulate data from the quotes table
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching
*/
class Quotes extends Zend_Db_Table_Abstract {
	
	protected $_name = 'quotes';
	
	protected $_primary = 'id';
	
	protected $_cache;

	/** Set up the cache
	* @return object $_cache
	*/
	public function init(){
	$this->_cache = Zend_Registry::get('rulercache');	
	}
	/** Set up the time field
	* @return date $dateTime
	*/
	public function getTimeForForms() {
	$dateTime = Zend_Date::now()->toString('yyyy-MM-dd');
	return $dateTime;
	}

	/** Get all quotes from the admin interface
	* @param integer $page
	* @return array
	*/
	public function getQuotesAdmin($page) {
	$quotes = $this->getAdapter();
	$select = $quotes->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name .'.updatedBy',array('fn' => 'fullname'))
		->order('id DESC');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30) 
		->setPageRange(20);
	if(isset($page) && ($page != ""))  {
	$paginator->setCurrentPageNumber($page); 
	}
	return $paginator;
	}
	/** Get all valid quotes
	* @return array
	*/
	public function getValidQuotes(){
	if (!$data = $this->_cache->load('frontquotes')) {
	$quotes = $this->getAdapter();
	$select = $quotes->select()
		->from($this->_name,array('quote','quotedBy'))
		->where('expire >= ?', $this->getTimeForForms())
		->where('status = ?',(int)1)
		->order('RAND()')
		->limit(1);
	$data =  $quotes->fetchAll($select);
	$this->_cache->save($data, 'frontquotes');
    } 
	return $data;
	}
}
