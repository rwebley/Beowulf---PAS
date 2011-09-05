<?php 

/**
* Model for pulling coin classifications
* I have no idea why this is different to the Coins Classifications model! 
* @category   Zend
* @package    Zend_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
*/

class CoinClass extends Zend_Db_Table_Abstract {
	
	protected $_name = 'coinclassifications';
	protected $_primary = 'id';
	
	/** Get all valid references for coin classifications
	* @return array
	*/
	public function getRefs() {
	$references = $this->getAdapter();
	$select = $references->select()
		->from($this->_name, array('id','referenceName','valid'))
		->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
		->joinLeft('periods',$this->_name.'.period = periods.id',array('term'));
	return $references->fetchAll($select); 
	}
	
	/** Get all valid references for coin classifications as a dropdown
	* @return array
	*/
	public function getRefsByPeriod() {
	$references = $this->getAdapter();
	$select = $references->select()
		->from($this->_name, array('id','referenceName'))
		->where($this->_name.'.valid = ?',(int)1);
	return $references->fetchPairs($select); 
	}
}
