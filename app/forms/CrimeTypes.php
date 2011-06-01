<?php

class CrimeTypes extends Zend_Db_Table_Abstract {

	protected $_primary = 'id';
	protected $_name = 'crimeTypes';
	
	public function getTypes()
		{
		$mons = $this->getAdapter();
		$select = $mons->select()
            ->from($this->_name, array('term','term'))
			->order($this->_primary);
	   return $mons->fetchPairs($select);	
		}
}

?>