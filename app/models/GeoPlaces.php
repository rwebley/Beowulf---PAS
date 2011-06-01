<?php 
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching
*/
class GeoPlaces extends Zend_Db_Table_Abstract {

	protected $_name = 'geoplanetplaces';

	protected $_primary = 'WOE_ID';
	
	/** Retrieval of adjacent places in the Yahoo geoplanet dataset
	* @param integer $woeid 
	* @return array $data
	* @todo add caching
	*/

	public function getAdjacent($woeid) {
		$adj = $this->getAdapter();
		$select = $adj->select()
			->from($this->_name, array())
			->joinLeft('geoplanetadjacent',$this->_name . '.WOE_ID = geoplanetadjacent.PLACE_WOE_ID', array())
			->joinLeft(array('geos' => $this->_name),'geos.WOE_ID = geoplanetadjacent.NEIGHBOUR_WOE_ID', array('Name','WOE_ID'))
			->where($this->_name.'.WOE_ID = ?', (int)$woeid);
       return $adj->fetchAll($select);
	}

}