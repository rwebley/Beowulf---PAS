<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add edit and delete functions
*/

class WebServices extends Zend_Db_Table_Abstract {
	
	protected $_name = 'webServices';
	protected $_primary = 'id';

	
	/** Retrieve all web services
	* @return array 
	*/
	public function getValidServices() {
	$cache = Zend_Registry::get('cache');
	if (!$data = $cache->load('webservices')) {
	$webservices = $this->getAdapter();
	$select = $webservices->select()
			->from($this->_name,array('service','service'))
			->where('valid = ?',(int)1);
    $data =  $webservices->fetchPairs($select);
	$cache->save($data, 'webservices');
	}
	return $data;
	}

}