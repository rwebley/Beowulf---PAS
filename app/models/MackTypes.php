<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add, edit and delete functions to be created and moved from controllers
*/
class MackTypes extends Zend_Db_Table_Abstract {

	protected $_name = 'macktypes';

	protected $_primary = 'id';

	protected $_cache = NULL;

	/** Construct the cache object
	* @return object
	*/
	public function init() {
		$this->_cache = Zend_Registry::get('rulercache');
	}

	/** Retrieve key value paired dropdown list array
	* @return array $paginator
	*/
	public function getMackTypesDD(){
    if (!$options = $this->_cache->load('macktypedd')) {
	    $select = $this->select()
                       ->from($this->_name, array('type', 'type'))
                       ->order('type');
        $options = $this->getAdapter()->fetchPairs($select);
		$this->_cache->save($options, 'macktypedd');
		}
        return $options;
    }

    /** Retrieve data for an autocomplete ajax query
    * @param string $q
	* @return array $paginator
	* @todo reckon this can be made more efficient in the controller action
	*/
    public function getTypes($q) {
		$types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name, array('id','term' => 'type'))
			->where('type LIKE ? ', $q.'%')
			->order('type')
			->limit(10);
	   return $types->fetchAll($select);
	}

	/** Retrieve paginated mack types
    * @param integer $page
	* @return array $paginator
	*/
	public function getMackTypes($params) {
		$types = $this->getAdapter();
		$select = $types->select()
            ->from($this->_name)
			->joinLeft('coins','coins.mack_type = macktypes.type',array())
			->joinLeft('finds','finds.secuid = coins.findID', array('totals' => 'SUM(quantity)'))
			->order($this->_name.'.type')
			->group($this->_name.'.type');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30) 
	          ->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}
}