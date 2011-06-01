<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching throughout model as the cached version won't be changing!
*/
class FindsAudit extends Zend_Db_Table_Abstract {
	
	protected $_name = 'finds_audit';
	
	protected $_primary = 'id';

	/** get all audited changes on a record
	* @param integer $id 
	* @return array
	* @todo add cache
	*/
	public function getChanges($id) {
		$finds = $this->getAdapter();
		$select = $finds->select()
						->from($this->_name,array('finds_audit.created','findID','editID'))
						->joinLeft('users','users.id = finds_audit.createdBy',
						array('id','fullname','username'))
						->where('finds_audit.findID= ?',(int)$id)
						->order('finds_audit.id DESC')
						->group('finds_audit.created');
	return  $finds->fetchAll($select);
	}

	/** get an audited change set on a record
	* @param integer $id 
	* @return array
	* @todo add cache
	*/
	public function getChange($id){
		$finds = $this->getAdapter();
		$select = $finds->select()
						->from($this->_name,array('finds_audit.created','afterValue','fieldName','beforeValue'))
						->joinLeft('users','users.id = finds_audit.createdBy',array('id','fullname','username'))
						->where('finds_audit.editID= ?',$id)
						->order($this->_name.'.id');
	return  $finds->fetchAll($select);
	}
}