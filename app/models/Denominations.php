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
class Denominations extends Zend_Db_Table_Abstract {
	
	protected $_name = 'denominations';
	
	protected $_primary = 'id';

	protected $_cache = NULL;
	
	/** Construct the cache object
	* @return object
	*/
	
	public function init(){
	$this->_cache = Zend_Registry::get('rulercache');
	}

	/** Get denomination by period as a list
	* @param $period
	* @return array
	*/
	
	public function getDenByPeriod($period) {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name)
		->where($this->_name.'.valid = ?',(int)1)
		->where($this->_name.'.period = ?',(int)$period)
		->order('denomination');
	return $denoms->fetchAll($select);
	}

	public function getOptionsRoman() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'denomination'))
					   ->where('period = ?',(int)'21')
                       ->order('denomination');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

	public function getOptionsIronAge() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'denomination'))
					   ->where('period = ?','16')
                       ->order('denomination');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }
    
	public function getOptionsEarlyMedieval() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'denomination'))
					   ->where('period = ?','47')
                       ->order('denomination');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

	public function getOptionsMedieval() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'denomination'))
					   ->where('period = ?','29')
                       ->order('denomination');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

	public function getOptionsPostMedieval() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'denomination'))
					   ->where('period = ?','36')
                       ->order('denomination');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

	public function getDenomsGreek() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'denomination'))
					   ->where('period = ?',(int)66)
					   ->where('valid = ?',(int)1)
                       ->order('denomination');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

	public function getDenomsByzantine() {
        $select = $this->select()
                       ->from($this->_name, array('id', 'denomination'))
					   ->where('period = 67')
                       ->order('denomination');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

	public function getRomanRulerDenom($ruler) {
		$select = $this->select()
                       ->from($this->_name, array('id', 'term' => 'denomination'))
    				   ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',array())
					   ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
					   ->where('denominations_rulers.ruler_id= ?',$ruler);
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
	}

	public function getRomanRulerDenomAdmin($ruler) {				
		$options = $this->getAdapter();
		$select = $options->select()
                       ->from($this->_name, array('id', 'term' => 'denomination'))
    				   ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',array('created','linkid' => 'id'))
					   ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
			   	 	   ->joinLeft('users','users.id = denominations_rulers.createdBy',array('fullname'))
			   	 	   ->joinLeft('periods','periods.id = denominations.period',array('period' => 'term'))
					   ->where('denominations_rulers.ruler_id= ?',$ruler)
					   ->order('denomination');
        return $options->fetchAll($select);
	}

	public function getEarlyMedRulerDenom($ruler) {
		$select = $this->select()
                       ->from($this->_name, array('id', 'term' => 'denomination'))
    				   ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',array())
					   ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
					   ->where('denominations_rulers.ruler_id= ?',$ruler)
					   ->group('denomination');
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
	}

	public function getEarlyMedRulerToDenomination($id) {
		$select = $this->select()
                       ->from($this->_name, array('id',  'denomination'))
    				   ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',array())
					   ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
					   ->where('denominations_rulers.ruler_id= ?',(int)$id)
					   ->group('denomination');
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
	}


	public function getEarlyMedRulerToDenominationPairs($id) {
		$select = $this->select()
                       ->from($this->_name, array('id',  'denomination'))
    				   ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',array())
					   ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
					   ->where('denominations_rulers.ruler_id= ?',(int)$id)
					   ->group('denomination');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
	}

	public function getPostMedRulerDenom($ruler) {
		$select = $this->select()
                       ->from($this->_name, array('id', 'term' => 'denomination'))
    				   ->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',array())
					   ->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
					   ->where('denominations_rulers.ruler_id= ?',$ruler)
					   ->group('denomination');
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
	}

	public function getIronAgeDenoms() {
        $denoms = $this->getAdapter();
		$select = $denoms->select()
                       ->from($this->_name, array('id','denomination'))
					   ->where('period = 16')
                       ->order('id');
        return $denoms->fetchAll($select);
    }

	public function getIronAgeDenom() {
        $denoms = $this->getAdapter();
		$select = $denoms->select()
                       ->from($this->_name, array('id','denomination'))
					   ->where('period = 16')
                       ->order('id');
        return $denoms->fetchAll($select);
    }

	public function getDenomName($denomname) {
        $denoms = $this->getAdapter();
		$select = $denoms->select()
                       ->from($this->_name, array('id','denomination'))
					   ->where('id= ?', $denomname)
					   ->group('id')
                       ->order('id');
        return $denoms->fetchAll($select);
    }

	public function getEmperorDenom($id) {
		$denoms = $this->getAdapter();
		$select = $denoms->select()
                       ->from($this->_name)
					  	 ->joinLeft('coins_denomxruler','coins_denomxruler.denomID = denominations.id',array())
						->joinLeft('rulers','coins_denomxruler.rulerID = rulers.id',array('rulerID' => 'id','issuer'))
						->joinLeft('emperors','rulers.id = emperors.pasID',array())
						->where('rulers.id = emperors.pasID AND emperors.id ='.$id)
						->order('emperors.date_from');
        return $denoms->fetchAll($select);
	}

	public function getDenom($id,$period){
        $denoms = $this->getAdapter();
		$select = $denoms->select()
                       ->from($this->_name)
					   ->joinLeft('materials',$this->_name.'.material = materials.id',array('term'))
					    ->joinLeft('coins',$this->_name.'.id = coins.denomination',array())
					   ->joinLeft('finds','finds.secuid = coins.findID',array('total' => 'SUM(quantity)'))
					   ->where($this->_name.'.id= ?', $id)
					   ->where('period = ?',$period)
					   ->group($this->_primary);
        return $denoms->fetchAll($select);
    }

	public function getDenominations($period,$page) {
        $denoms = $this->getAdapter();
		$select = $denoms->select()
                       ->from($this->_name)
					   ->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
   					   ->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
					   ->where('period = ?',(int)$period)
					   ->order('denomination');
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30) 
	          ->setPageRange(20);
		if(isset($page) && ($page != "")) {
    	      $paginator->setCurrentPageNumber($page); 
			  }
        return $paginator;   
	 }

	public function getDenominationsJson($period) {
        $denoms = $this->getAdapter();
		$select = $denoms->select()
                       ->from($this->_name)
					   ->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
   					   ->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
					   ->where('period = ?',(int)$period)
					   ->order('denomination');
		        return $denoms->fetchAll($select);
	 }

	public function getDenomsAdd($period){
        $denoms = $this->getAdapter();
		$select = $denoms->select()
                       ->from($this->_name,array('id','denomination'))
					   ->where('period = ?',(int)$period)
					   ->order('denomination');
        return $denoms->fetchPairs($select);
    }

	public function getDenomsValid($params) {
        $denoms = $this->getAdapter();
		$select = $denoms->select()
		->from($this->_name)
		->joinLeft('materials','denominations.material = materials.id',array('mat' => 'term'))
		->joinLeft('periods','periods.id = denominations.period',array('temporal' => 'term'))
		->where($this->_name.'.valid = ?',(int)1)
		->order('denomination');
		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30) 
	          ->setPageRange(20);
		if(isset($params['page']) && ($params['page'] != "")) {
    	      $paginator->setCurrentPageNumber($params['page']); 
		}
        return $paginator;
    }

	public function getRulerDenomination($id) {
		$options = $this->getAdapter();
		$select = $options->select()
                  ->from($this->_name, array('id','denomination'))
    		->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',array())
				->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array('i'=>'rulers.id','issuer'))
			->where('denominations_rulers.denomination_id= ?',(int)$id)
		->group('issuer');
        return $options->fetchAll($select);
	}

	public function getDenomination($id) {
		$options = $this->getAdapter();
		$select = $options->select()
               	->from('denominations',array('denomination','id'))
				->joinLeft('materials','denominations.material = materials.id',array('mat' => 'term'))
				->joinLeft('periods','periods.id = denominations.period',array('temporal' => 'term'))
				->where('denominations.id ='.$id);
        return $options->fetchAll($select);
	}

	public function getDenominationsSitemap($period) {
		if (!$data = $this->_cache->load('denomsSiteMap'.$period)) {
		$denoms = $this->getAdapter();
		$select = $denoms->select()
		->from($this->_name,array('id','denomination','updated'))
		->where($this->_name.'.valid = ?',(int)1)
		->where($this->_name.'.period = ?',(int)$period)
		->order('denomination');
		$data = $denoms->fetchAll($select);
		$this->_cache->save($data, 'denomsSiteMap'.$period);
		}
		return $data;
	}
}
