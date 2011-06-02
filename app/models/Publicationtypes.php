<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add edit and delete functions and caching
*/
class Publicationtypes extends Zend_Db_Table_Abstract {

	protected $_name = 'publicationtypes';

	protected $_primary = 'id';

	/** Get dropdown list of publication types
	* @return array
	*/
	public function getTypes() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term'))
                       ->order('term ASC');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }
}
