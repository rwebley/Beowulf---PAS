<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add some caching to model
*/
class Completeness extends Zend_Db_Table_Abstract {

	protected $_name = 'completeness';
	protected $_primary = 'id';

	/** Get completeness details by id
	* @param integer $id
	* @return array
	* @todo change to fetchrow in future?
	* @todo add caching
	*/
	public function getDetails($id) {
	$comp = $this->getAdapter();
		$select = $comp->select()
						->from($this->_name)
						->where('id = ?',(int)$id)
						->order('id');
		 return $comp->fetchAll($select);
	}
	
}