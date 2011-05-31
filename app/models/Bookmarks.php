<?php
/**
* Model for pulling bookmark system data.
*
*
* @category   Zend
* @package    Zend_Db_Table
* @subpackage Abstract
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Bookmarks extends Zend_Db_Table_Abstract {

	protected $_name = 'bookmarks';
	protected $_primary = 'id';

	/** Construct the cache object
	* @return object
	*/
	public function init(){
		$this->_cache = Zend_Registry::get('rulercache');
	}
	
	/** Get all valid bookmarks
	* @return array
	*/
	public function getValidBookmarks() {
		if (!$data = $this->_cache->load('bookmarksSite')) {
		$bookmarks = $this->getAdapter();
		$select = $bookmarks->select()
            				->from($this->_name, array('image','url','service'))
							->where('valid = ?',(int)1);
	    $data =  $bookmarks->fetchAll($select);
	 	$this->_cache->save($data, 'bookmarksSite');
		}
		return $data;
	}

}