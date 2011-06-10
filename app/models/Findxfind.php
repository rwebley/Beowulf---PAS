<?php
/** Linked finds lookup table
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching
*/
class Findxfind extends Zend_Db_Table_Abstract {

	protected $_primary = 'id';

	protected $_name = 'findxfind';

	protected $_restricted = array('public','member','research');

	public function getRole() {
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()) {
	$user = $auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}
	/** Get linked finds
	* @param string $secuid
	* @return array
	* @todo add caching
	*/
	public function getRelatedFinds($secuid) {
	$relatedfinds = $this->getAdapter();
	$select = $relatedfinds->select()
		->from($this->_name, array('i' => 'id')) 
		->joinLeft(array('finds1' => 'finds'),'finds1.secuid = findxfind.find1ID',array())
		->joinLeft(array('finds2' => 'finds'),'finds2.secuid = findxfind.find2ID',array('id' ,'broadperiod', 'objecttype', 'old_findID','secuid'))
		->where('findxfind.find1ID = ? ', (string)$secuid) ;
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds1.secwfstage NOT IN ( 1, 2 )');
	}
	return $relatedfinds->fetchAll($select);
	}
}