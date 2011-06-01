<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching
*/

class FindSpotsAudit extends Zend_Db_Table_Abstract {

	protected $_name = 'findspotsAudit';

	protected $_primary = 'id';

	
	/** Get all changes to a findspot
	* @param integer $id
	* @return array
	* @todo add caching functions
	*/
	public function getChanges($id) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name . '.created', 'findID', 'editID'))
			->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
			array('id', 'fullname', 'username'))
			->where($this->_name . '.findID= ?',(int)$id)
			->order($this->_name . '.id DESC')
			->group($this->_name . '.created');
	return  $finds->fetchAll($select);
	}

	/** Get changes to a findspot
	* @param integer $id
	* @return array
	* @todo add caching
	*/
	
	public function getChange($id) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array($this->_name . '.created', 'afterValue', 'fieldName', 'beforeValue'))
			->joinLeft('users','users.id = '.$this->_name . '.createdBy',array('id', 'fullname', 'username'))
			->where($this->_name . '.editID = ?', (int)$id)
			->order($this->_name . '.' . $this->_primaryKey);
	return  $finds->fetchAll($select);
	}

}