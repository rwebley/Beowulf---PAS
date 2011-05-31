<?php 
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
*/

class EventTypes extends Zend_Db_Table_Abstract
{
	protected $_name = 'eventtypes';
	protected $_primaryKey = 'id';
	protected $_cache;
	
	
	public function init(){
	$this->_cache = Zend_Registry::get('rulercache');
	}
	
	/**
     * Retrieves all event types that we list
     * @param integer $type
     * @return array
	*/
	
	public function getType($type){
		if(!$data = $this->_cache->load(md5('eventtypes' . $type ))) {
		$events = $this->getAdapter();
		$select = $events->select()
						 ->from($this->_name, array('id','type'));
		$data =  $events->fetchRow($select); 
		$this->_cache->save($data, md5('eventtypes' . $type ));
	   }
	   return $data;	
	}
	
	/**
     * Retrieves all event types that we list
     * 
     * @return array
	*/
	
	public function getTypes(){
	if(!$data = $this->_cache->load('eventtypes')) {
		$events = $this->getAdapter();
		$select = $events->select()
						 ->from($this->_name, array('id','type'));
		$data =  $events->fetchPairs($select); 
		$this->_cache->save($data, 'eventtypes');
	   }
	   return $data;
	}

}