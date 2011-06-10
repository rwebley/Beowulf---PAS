<?php

/** Retrieve and manipulate data for OAI-PMH module that no one uses
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo switch over to solr?
*/
class OaiFinds extends Zend_Db_Table_Abstract {
	
	protected $_name = 'finds';

	protected $_primary = 'id';

	protected $_cache;

	/** Set up the cache
	* @return object $_cache
	*/
	public function init() {
	$this->_cache = Zend_Registry::get('rulercache');
	}
	
	
	/** Get all Roman mints as a key value pair list for dropdown
	* @param integer $cursor last point of entry
	* @param string $set which group of PAS records to harvest
	* @param date $from date to choose from
	* @param date $until date to choose records to
	* @return array
	*/
	public function getRecords($cursor,$set,$from,$until) {
	ini_set("memory_limit","750M");
	$listLimit = 30;
	$records = $this->getAdapter();
	$select = $records->select()
		->from($this->_name)
		->joinLeft('people','finds.finderID = people.secuid',
		array('finder' => 'CONCAT(people.title," ",people.forename," ",people.surname)'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid', 
		array('identifier' => 'CONCAT(ident1.title," ",ident1.forename," ",ident1.surname)'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid', 
		array('secondaryIdentifier' => 'CONCAT(ident2.title," ",ident2.forename," ",ident2.surname)'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid', 
		array('recorder' => 'CONCAT(record.title," ",record.forename," ",record.surname)'))
		->joinLeft('findspots','finds.secuid = findspots.findID', 
		array('county','parish', 'district', 'easting', 'northing', 'gridref', 'fourFigure', 'map25k', 
		'map10k', 'address', 'postcode',
		'findspotdescription' => 'description', 'lat' => 'declat', 'lon' => 'declong', 'knownas'))
		->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id', array('primaryMaterial' =>'term'))
		->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id', array('secondaryMaterial' => 'term'))
		->where('finds.secwfstage > ?',(int)2);
 	if(!is_null($set)){
	$select->where('finds.institution = ?',$set);
	}
	if(!is_null($from)) {
	$select->where('DATE(finds.created) >= ?',$from);
	}
	if(!is_null($until)) {
	$select->where('DATE(finds.created) <= ?',$until);
	}
	if(!is_null($cursor)) {
	$select->limit($listLimit,$cursor);
	}        
	return $records->fetchAll($select);
	}
	
	/** Get individual record for OAI
	* @param integer $itemId Item number
	* @return array
	*/
	public function getRecord($itemId) {
	if (!$data = $this->_cache->load('oairecord'.$itemId)) {
	$records = $this->getAdapter();
	$select = $records->select()
		->from($this->_name)
		->joinLeft('people','finds.finderID = people.secuid', 
		array('finder' => 'CONCAT(people.title," ",people.forename," ",people.surname)'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid', 
		array('identifier' => 'CONCAT(ident1.title," ",ident1.forename," ",ident1.surname)'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid', 
		array('secondaryIdentifier' => 'CONCAT(ident2.title," ",ident2.forename," ",ident2.surname)'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid', 
		array('recorder' => 'CONCAT(record.title," ",record.forename," ",record.surname)'))
		->joinLeft('findspots','finds.secuid = findspots.findID', 
		array('county', 'parish', 'district', 'easting', 'northing', 'gridref', 
		'fourFigure', 'map25k', 'map10k', 'address', 'postcode',
		'findspotdescription' => 'description', 'lat' => 'declat', 'lon' => 'declong', 'knownas'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id',array())
		->joinLeft('slides','slides.secuid = finds_images.image_id',array('i' => 'imageID','f' => 'filename')) 
		->joinLeft(array('u' => 'users'),'slides.createdBy = u.id',array('imagedir'))
		->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id',array('primaryMaterial' =>'term'))
		->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id',array('secondaryMaterial' => 'term'))
		->where('finds.secwfstage > ?',(int)2)
		->where('finds.id = ?',(int)$itemId)
		->limit(1);
	$data =  $records->fetchAll($select);
	$this->_cache->save($data, 'oairecord'.$itemId);
	}
	return $data;
	}

	/** Get individual record's images for OAI
	* @param integer $itemId Item number
	* @return array
	*/
	public function getImages($itemId) {
	if (!$data = $this->_cache->load('oaiimages'.$itemId)) {
	$records = $this->getAdapter();
	$select = $records->select()->from($this->_name,array())
		->joinLeft('finds_images','finds.secuid = finds_images.find_id',array())
		->joinLeft('slides','slides.secuid = finds_images.image_id',array('i' => 'imageID','f' => 'filename')) 
		->joinLeft(array('u' => 'users'),'slides.createdBy = u.id',array('imagedir'))
		->where($this->_name . '.' . $this->_primary . ' = ?',(int)$itemId);
	$data =  $records->fetchAll($select);
	$this->_cache->save($data, 'oaiimages'.$itemId);
	}
	return $data;
	}
	
	/** Get record counts for a set
	* @param string $set which group of PAS records to harvest
	* @param date $from date to choose from
	* @param date $until date to choose records to
	* @return array
	*/
	public function getCountAllFinds($set,$from,$until) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('q' => 'SUM(quantity)','c' => 'COUNT(*)'))
		->where('finds.secwfstage > ?',(int)2);
	if(!is_null($set)){
	$select->where('finds.institution = ?',$set);
	}
	if(!is_null($from)) {
	$select->where('DATE(finds.created) >= ?',$from);
	}
	if(!is_null($until)) {
	$select->where('DATE(finds.created) <= ?',$until);
	}
	return $finds->fetchAll($select);
	}

}