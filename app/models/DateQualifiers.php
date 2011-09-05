<?php
/**
* Get a list of date qualifiers for numeric dating
* @category Pas
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add caching
*/
class DateQualifiers extends Pas_Db_Table_Abstract {

	protected $_name = 'datequalifiers';
	
	protected $_primaryKey = 'id';

	/** Get qualifier terms for form listing as key value pairs
	* @return array
	*/
	public function getTerms(){
	if (!$options = $this->_cache->load('circa')) {
	$select = $this->select()
		->from($this->_name, array('id', 'term'))
		->order($this->_primaryKey)
		->where('valid = ?',(int)1);
	$options = $this->getAdapter()->fetchPairs($select);
	$this->_cache->save($options, 'circa');
	}
	return $options;
    }
}