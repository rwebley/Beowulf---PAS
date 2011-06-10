<?php
/** Retrieve and manipulate data from the places listing
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching
*/
class Places extends Zend_Db_Table_Abstract {
	protected $_name = 'places';

	protected $_primary = 'id';

	protected $_cache;

	protected $_config;

	/** Set up the cache
	* return object $_cache
	*/
 	public function init(){
	$this->_auth = Zend_Registry::get('auth');
	$this->_config = Zend_Registry::get('config');
	$this->_cache = Zend_Registry::get('rulercache');	
	}

	/** Get the district by county
	* @param string $county
	* @return array
	* @todo add caching
	*/
 	public function getDistrict($county){
	$districts = $this->getAdapter();
	$select = $districts->select()
		->from($this->_name, array('id' => 'district','term' => 'district'))
		->where('county LIKE ?', (string)'%' . $county . '%')
		->where('district IS NOT NULL')
		->where('county IS NOT NULL')
		->order('district')
		->group('district');
	return $districts->fetchAll($select);
	}
	
	/** Get the district by county as dropdown list
	* @param string $county
	* @return array
	* @todo add caching
	*/
	public function getDistrictList($county) {
	$districts = $this->getAdapter();
	$select = $districts->select()
		->from($this->_name, array('id' => 'district','term' => 'district'))
		->where('county = ?', (string)$county)
		->where('district IS NOT NULL')
		->where('county IS NOT NULL')
		->order('district')
		->group('district');
	return $districts->fetchPairs($select);
	}

	/** Get the parish by district
	* @param string $district The district to choose from
	* @return array
	* @todo add caching
	*/
	public function getParish($district) {
	$parishes = $this->getAdapter();
	$select = $parishes->select()
		->from($this->_name, array('id' => 'parish','term' =>'parish'))
		->where('district = ?', (string)$district)
		->where('district IS NOT NULL')
		->where('county IS NOT NULL')
		->where('parish IS NOT NULL')
		->order('parish')
		->group('term');
	return $parishes->fetchAll($select);
	}

	/** Get the parish by district as a dropdown array
	* @param string $district The district to choose from
	* @return array
	* @todo add caching
	*/
	public function getParishList($district) {
	$parishes = $this->getAdapter();
	$select = $parishes->select()
		->from($this->_name, array('id' => 'parish','term' =>'parish'))
		->where('district = ?', (string)$district)
		->where('district IS NOT NULL')
		->where('county IS NOT NULL')
		->where('parish IS NOT NULL')
		->order('parish')
		->group('term');
	return $parishes->fetchPairs($select);
	}

	/** Get the parish by district as a dropdown array
	* @param string $district The district to choose from
	* @return array
	* @todo add caching
	*/
	public function getDistrictParish($district){
	$parishes = $this->getAdapter();
	$select = $parishes->select()
		->from($this->_name, array('id' => 'district','term' =>'district'))
		->where('district = ?', (string)$district)
		->where('district IS NOT NULL')
		->where('county IS NOT NULL')
		->where('parish IS NOT NULL')
		->order('parish')
		->group('parish');
	   return $parishes->fetchAll($select);
	}


	/** Get the parishes by county
	* @param string $county The county to choose from
	* @return array
	* @todo add caching
	*/
	public function getParishByCounty($county) {
		$parishes = $this->getAdapter();
		$select = $parishes->select()
            ->from($this->_name, array('id' => 'parish','term' =>'parish'))
			->where('county = ?', (string)$county)
			->where('district IS NOT NULL')
			->where('county IS NOT NULL')
			->where('parish IS NOT NULL')
			->order('parish')
			->group('parish');
	   return $parishes->fetchAll($select);
	}

	/** Get the district by county
	* @param string $county The county to choose from
	* @return array
	* @todo add caching and rename
	*/
	public function getDistrictByParish($county) {
		$districts = $this->getAdapter();
		$select = $districts->select()
            ->from($this->_name, array('id' => 'district','term' =>'district'))
			->where('parish = ?', (string)$county)
			->where('district IS NOT NULL')
			->where('county IS NOT NULL')
			->where('parish IS NOT NULL')
			->order('district')
			->group('district');
	   return $districts->fetchAll($select);
	}
	
	/** Get the stuff to update for districts
	* @param string $county The county to choose from
	* @param string $parish The parish to choose from
	* @return array
	* @todo add caching
	*/
	public function getDistrictUpdate($county,$parish){
		$districts = $this->getAdapter();
		$select = $districts->select()
            ->from($this->_name, array('district'))
			->where('parish = ?', (string)$parish)
			->where('county = ?', (string)$county);
	   return $districts->fetchAll($select);
	}

}
