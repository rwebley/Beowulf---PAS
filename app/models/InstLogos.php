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
class InstLogos extends Zend_Db_Table_Abstract {

	protected $_name = 'instLogos';

	protected $_primary = 'id';

	/** get paginated hoard list 
	* @param string $inst 
	* @return array $data
	* @todo add caching
	*/
	public function getLogosInst($inst) {
		$logos = $this->getAdapter();
		$select = $logos->select()
            ->from($this->_name, array('image'))
			->joinLeft('institutions','institutions.institution = ' . $this->_name.'.instID',array())
			->where('institutions.institution = ?', (string)$inst);
     return  $logos->fetchAll($select);
	}
}