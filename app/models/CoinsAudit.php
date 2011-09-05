<?php
/**
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
*/
class CoinsAudit extends Pas_Db_Table_Abstract {

	protected $_name = 'coinsAudit';
	protected $_primary = 'id';

	/** Get all changes to a coin record since creation
	* @param integer $id 
	* @return array
	*/
	public function getChanges($id) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array($this->_name . '.created','findID','editID'))
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('id','fullname','username'))
		->where($this->_name . '.findID= ?',(int)$id)
		->order($this->_name . '.id DESC')
		->group($this->_name . '.created');
	return  $finds->fetchAll($select);
	}

	/** Get change by id
	* @param integer $id
	* @return array
	*/
	public function getChange($id) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array($this->_name . '.created','afterValue','fieldName','beforeValue'))
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
		array('id','fullname','username'))
		->where($this->_name . '.editID= ?',$id)
		->order($this->_name . '.' . $this->_primaryKey);
	return $finds->fetchAll($select);
	}

}