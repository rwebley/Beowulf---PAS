<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add edit and delete functions
*/

class SubsequentActions extends Zend_Db_Table_Abstract {

	protected $_name = 'subsequentActions';
	
	protected $_primary = 'id';
	
	protected $_cache = NULL;

	/** Construct the cache object
	* @return object
	*/
	public function init() {
		$this->_cache = Zend_Registry::get('rulercache');
	}
	
	/** Retrieve a key value pair list for subsequent actions
	* @return Array
	*/
	public function getSubActionsDD() {
	if (!$actions = $this->_cache->load('actions')) {
		 $select = $this->select()
                   ->from($this->_name, array('id', 'action'))
				   ->order(array('action'));
        $actions = $this->getAdapter()->fetchPairs($select);
		$this->_cache->save($actions, 'actions');
		} 
        return $actions;
	}
}