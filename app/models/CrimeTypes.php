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

class CrimeTypes extends Zend_Db_Table_Abstract
{
	protected $_primary = 'id';
	
	protected $_name = 'crimeTypes';
	
	protected $_cache = NULL;
	
	/** Construct the cache object
	* @return object
	*/
	
	public function init(){
		$this->_cache = Zend_Registry::get('rulercache');
	}

	/** Get a list of all crime types as key pair values
	* @return array
	*/
	public function getTypes(){
       if (!$options = $this->_cache->load('crimetypes')) {
	    $select = $this->select()
                       ->from($this->_name, array('id', 'term'))
                       ->order('id');
        $options = $this->getAdapter()->fetchPairs($select);
		$this->_cache->save($options, 'crimetypes');
		}
        return $options;
    }	
}