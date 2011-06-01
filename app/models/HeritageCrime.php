<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching and rewrite this crappy function set! 
*/
class HeritageCrime extends Zend_Db_Table_Abstract {

	protected $_primary = 'id';
	
	protected $_name = 'heritagecrime';
	
	/** Get all crimes back
	* @param array $params 
	* @return array
	*/
	public function getCrimesAgainstHeritage($params) {
	if(array_key_exists('page',$params)){
	$page = $params['page'];
	}
	if(array_key_exists('district',$params)){
	$district = $params['district'];
	}
	if(array_key_exists('county',$params)){
	$county = $params['county'];
	}
	if(array_key_exists('parish',$params)){
	$parish = $params['parish'];
	}
	$crimes = $this->getAdapter();
	$select = $crimes->select()
			->from($this->_name)
			->joinLeft('crimeTypes','crimeTypes.id = ' . $this->_name . '.crimeType',array('term'))
			->joinLeft('people','people.secuid = ' . $this->_name . '.reporterID',array('fullname'))
			->order('county');
	if(isset($district) && ($district != "")){
	$select->where('district = ?',$district);	
	}
	if(isset($county) && ($county != "")){
	$select->where('county = ?',$county);	
	}
	if(isset($parish) && ($parish != "")){
	$select->where('parish = ?',$parish);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(20) 
	          ->setPageRange(20);
	if(isset($page) && ($page != ""))  {
    $paginator->setCurrentPageNumber($page); 
	}
	return $paginator;
	}
	
	/** Get crime data by id
	* @param integer $id 
	* @return array
	*/
	public function getCrime($id) {
		$crime = $this->getAdapter();
		$select = $crime->select()
			->from($this->_name)
			->joinLeft('people',$this->_name.'.reporterID = people.secuid', array('reporter' => 'fullname'))
			->joinLeft('scheduledMonuments',$this->_name . '.samID = scheduledMonuments.id',array('sam' => 'monumentName'))
			->where($this->_name . '.id = ?', (int)$id)
			->group($this->_name . '.id')
			->limit('1');
       return $crime->fetchAll($select);
	}
}