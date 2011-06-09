<?php 
/** Get submitted messages on the system
* 
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add edit and delete functions
*/
class Messages extends Zend_Db_Table_Abstract {

	protected $_name = 'messages';

	protected $_primary = 'id';

	/** get a count of messages
	* @return array 
	*/
	public function getCount(){
	$messages = $this->getAdapter();
	$select = $messages->select()
		->from($this->_name,array('total' => 'COUNT(id)'))
		->where('replied != ?',(int)1);
	return $messages->fetchAll($select);	
	}

	/** Get a paginated list of messages 
	* @return array $paginator
	*/
	public function getMessages($params){
	$messages = $this->getAdapter();
	$select = $messages->select()
		->from($this->_name)
		->order($this->_primary.' DESC');
    $paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30) 
	          ->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
}

}