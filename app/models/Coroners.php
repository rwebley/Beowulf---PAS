<?php
/** Model for displaying coroner details
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class Coroners extends Pas_Db_Table_Abstract {
	protected $_name = 'coroners';
	protected $_primary = 'id';
	
	/** retrieve all coroners on the system
	* @param integer $params['page'] 
	* @return object
	*/
	public function getAll($params) {
	$coroners = $this->getAdapter();
	$select = $coroners->select()
		->from($this->_name);
	$data = $coroners->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	$paginator->setCache($this->_cache);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']);
	}
	$paginator->setItemCountPerPage(20)
		->setPageRange(10);
	return $paginator;
	}

	/** Retrieve individual coroner details
	* @param integer $id] 
	* @return object
	*/
	
	public function getCoronerDetails($id) {
	if (!$data = $this->_cache->load('coroner' . $id)) {
	$coroners = $this->getAdapter();
	$select = $coroners->select()
		->from($this->_name)
		->where('id = ?',(int)$id);
	$data = $coroners->fetchAll($select);
	$this->_cache->save($data, 'coroner' . $id);
	}
	return $data;
	}
}
