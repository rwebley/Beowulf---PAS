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

class Cultures extends Zend_Db_Table {

	protected $_name = 'cultures';

	protected $_primary = 'id';

	/** Get a list of all ascribed cultures as key pair values
	* @return array
	*/
	public function getCultures() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term'))
                       ->order('id');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }
    
    /** Get a list of all crime types
	* @return array
	*/
	public function getCulturesList() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term','termdesc'))
                       ->where('valid = ?',(int)1)
					   ->order('id');
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
    }
	
    /** Get a list of all crime types for administration engine
    * @todo merge with the getCulturesList and add valid parameter to above.
	* @return array
	*/
	public function getCulturesListAdmin() {
        $options = $this->getAdapter();
		$select = $options->select()
                       	  ->from($this->_name)
						  ->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
   					      ->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
					      ->order('id');
        return $options->fetchAll($select);
    }


public function getCulture($id)
    {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term','termdesc'))
                       ->where('valid = ?',(int)1)
					   ->where('id = ?',(int)$id)
					   ->order('id');
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
    }
public function getCultureCountFinds($id)
{
		$reasons = $this->getAdapter();
		$select = $reasons->select()
            ->from('finds',array('c' => 'COUNT(finds.id)'))
			->where('finds.culture =?',(int)$id);
     return  $reasons->fetchAll($select);
}
}
