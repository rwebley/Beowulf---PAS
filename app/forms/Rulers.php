<?php

class Rulers extends Zend_Db_Table
{
protected $_name = 'rulers';

public function getOptions()
    {
        $select = $this->select()
                       ->from($this->_name, array('id', 'issuer'))
					   ->where('period = 21')
                       ->order('issuer');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

public function getAllRulers($periodID)
    {
        $rulers = $this->getAdapter();
			$select = $rulers->select()
            ->from($this->_name, array('id','term' => 'issuer'))
					   ->where('period = ?',(int)$periodID)
                       ->order('id');
        return $rulers->fetchAll($select);
      
    }

public function getRulersGreek()
    {
        $select = $this->select()
                       ->from($this->_name, array('id','term' => 'issuer'))
					   ->where('period = 66')
                       ->order('date1')
					   ->order('date2')
					   ->where('valid = 1');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }


public function getRulersByzantine()
    {
        $select = $this->select()
                       ->from($this->_name, array('id','term' => 'issuer'))
					   ->where('period = 67')
                       ->order('date1')
					   ->order('date2')
					   ->where('valid = 1');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }
	
public function getRulersByzantineList()
    {
        $select = $this->select()
                       ->from($this->_name, array('id', 'issuer','date1','date2'))
					   ->where('period = ?',(int)67)
                       ->order('date1')
					   ->order('date2')
					   ->where('valid = ?',(int)1);
        $options = $this->getAdapter()->fetchAll($select);
        return $options;
    }

public function getRulersGreekList($params)
    {
        $select = $this->select()
                       ->from($this->_name, array('id', 'issuer','date1','date2'))
					   ->where('period = ?',(int)66)
                       ->order('date1')
					   ->order('date2')
					   ->where('valid = ?',(int)1);
       $paginator = Zend_Paginator::factory($select);

	$paginator->setItemCountPerPage(30) 
	          ->setPageRange(20);
	$cache = Zend_registry::get('cache');
	Zend_Paginator::setCache($cache);
	if(isset($params['page']) && ($params['page'] != "")) 
	{
    $paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}


public function getEarlyMedRulers()
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
					   ->where('period = ?',(int)'47')
					   ->where('valid = ?',(int)'1')
                       ->order('issuer');
        return $rulers->fetchPairs($select);
    }
	
public function getRomanRulers()
    {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term' => 'issuer'))
					   ->where('period = ?',(int)21)
					   ->where('valid =?',(int)1)
                       ->order('date1')
					   ->order('date2');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

public function getAllMedRulers()
    {
        $select = $this->select()
                       ->from($this->_name, array('id', 'term' => 'issuer'))
					   ->where('period IN (29,36,47)')
                       ->order('date1')
					   ->order('date2');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }


public function getMedievalRulers()
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
					   ->where('period = 29')
					   ->where('valid =?',(int)1)
                       ->order('id');
        return $rulers->fetchPairs($select);
    }
	
	public function getMedievalRulersList()
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','issuer' ,'date1','date2'))
					   ->where('period = ?',(int)'29')
                       ->order('id');
        return $rulers->fetchAll($select);
    }

	
public function getPostMedievalRulers()
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
					   ->where('period = ?','36')
					   ->where('valid = ?',(int)1)
                       ->order('date1');
        return $rulers->fetchPairs($select);
    }

	
	
public function getEarlyMedievalRulers($catID = NULL)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','issuer','date1','date2'))
   					   ->joinLeft('medievaltypes','medievaltypes.rulerID = '.$this->_name.'.id',array())
					   ->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array('category'))
					   ->where('period = ?',(int)'47')
					   ->where($this->_name.'.valid',(int)'1')
  					   ->where('medievaltypes.categoryID = ?',(int)$catID)
					   ->group($this->_name.'.id')
					   ->order('date1');
        return $rulers->fetchAll($select);
    }

public function getEarlyMedievalRulersAjax($catID = NULL)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
   					   ->joinLeft('medievaltypes','medievaltypes.rulerID = '.$this->_name.'.id',array())
					   ->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array())
					   ->where('period = ?',(int)'47')
					   ->where($this->_name.'.valid',(int)'1')
  					   ->where('medievaltypes.categoryID = ?',(int)$catID)
					   ->group($this->_name.'.id')
					   ->order('date1');
        return $rulers->fetchAll($select);
    }

public function getMedievalRulersAjax($catID = NULL)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
   					   ->joinLeft('medievaltypes','medievaltypes.rulerID = '.$this->_name.'.id',array())
					   ->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array())
					   ->where('period = ?',(int)'29')
					   ->where($this->_name.'.valid',(int)'1')
  					   ->where('medievaltypes.categoryID = ?',(int)$catID)
					   ->group($this->_name.'.id')
					   ->order('date1');
        return $rulers->fetchAll($select);
    }
public function getPostMedievalRulersAjax($catID = NULL)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
   					   ->joinLeft('medievaltypes','medievaltypes.rulerID = '.$this->_name.'.id',array())
					   ->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array())
					   ->where('period = ?',(int)'36')
					   ->where($this->_name.'.valid',(int)'1')
  					   ->where('medievaltypes.categoryID = ?',(int)$catID)
					   ->group($this->_name.'.id')
					   ->order('date1');
        return $rulers->fetchAll($select);
    }

public function getMedievalRulersListed($catID = NULL, $period = NULL)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','issuer','date1','date2'))
   					   ->joinLeft('medievaltypes','medievaltypes.rulerID = '.$this->_name.'.id',array())
					   ->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array('category'))
					   ->where('period = ?',(int)$period)
					   ->where('country IS NULL')
					   ->where($this->_name.'.valid = ?',(int)'1')
  					   ->where('medievaltypes.categoryID = ?',(int)$catID)
					   ->group($this->_name.'.id')
					   ->order('date1');
        return $rulers->fetchAll($select);
    }

public function getForeign($catID = NULL, $country = NULL)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','issuer','date1','date2'))
   					   ->joinLeft('medievaltypes','medievaltypes.rulerID = '.$this->_name.'.id',array())
					   ->joinLeft('categoriescoins','categoriescoins.id = medievaltypes.categoryID',array('category'))
					   ->where('period = ?',(int)'29')
					   ->where($this->_name.'.valid',(int)'1')
  					   ->where('medievaltypes.categoryID = ?',(int)$catID)
					   ->where('country = ?',(int)$country)
					   ->group($this->_name.'.id')
					   ->order('date1');
        return $rulers->fetchAll($select);
    }


public function getMedievalRulerProfile($id)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','issuer','date1','date2'))
					   ->where('valid = ?',(int)'1')
					   ->where('id = ?',(int)$id)
					   ->limit('1');
        return $rulers->fetchAll($select);
    }

public function getRulerProfile($id)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name)
					   ->where('valid = ?',(int)'1')
					   ->where('id = ?',(int)$id)
					   ->limit('1');
        return $rulers->fetchAll($select);
    }

public function getRulerProfileAdmin($id)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name)
					   ->where('id = ?',(int)$id)
					   ->limit('1');
        return $rulers->fetchAll($select);
    }

	
public function getEarlyMedievalRulersNorthumbrian()
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','issuer','date1','date2'))
					   ->where('period = ?',(int)'47')
					   ->where('valid',(int)'1')
                       ->order('date1');
        return $rulers->fetchAll($select);
    }
	
public function getIronAgeRulers()
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','issuer'))
					   ->where('period = 16')
                       ->order('issuer ASC');
        return $rulers->fetchPairs($select);
    }
	
public function getIronAgeRulersListed()
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
                       ->from($this->_name, array('id','issuer','region'))
					   ->where('period = 16')
					   ->where('valid = ?', 1)
                       ->order('issuer ASC');
        return $rulers->fetchAll($select);
    }

public function getIronAgeRuler($id)
    {
        $rulers = $this->getAdapter();
		$select = $rulers->select()
            ->from($this->_name, array('id','issuer','date1','date2','region'))
            ->where('id = ?',$id)
            ->where('period = ?','16')
            ->order('id ASC');
        return $rulers->fetchAll($select);
    }
public function getRomanDenomRuler($denomination)
{
		$rulers = $this->getAdapter();
		$select = $rulers->select()
            ->from($this->_name, array('id','term' => 'CONCAT(issuer," (",date1," - ",date2,")")'))
			->joinLeft('coins_denomxruler','rulers.id = coins_denomxruler.rulerID',array())
			->joinLeft('denominations','denominations.id = coins_denomxruler.denomID',array())
            ->where('denominations.id = ?',$denomination)
            ->order('issuer ASC');
        return $rulers->fetchAll($select);
}

public function getIronAgeRulerRegion($ruler)
{
		$rulers = $this->getAdapter();
		$select = $rulers->select()
            ->from($this->_name, array('id','term' => 'issuer'))
			->joinLeft('ironagerulerxregion','ironagerulerxregion.rulerID = rulers.id',array())  
			->joinLeft('geographyironage','ironagerulerxregion.regionID = geographyironage.id',array())
			->where('geographyironage.id = ?',$ruler)
            ->order('issuer ASC');
        return $rulers->fetchAll($select);
}


public function getIronAgeRulerToRegion($region)
{
		$rulers = $this->getAdapter();
		$select = $rulers->select()
            ->from($this->_name, array('id', 'issuer','region'	))
		->joinLeft('ironagerulerxregion','ironagerulerxregion.rulerID = rulers.id',array())  
		->joinLeft('geographyironage','ironagerulerxregion.regionID = geographyironage.id',array())
		->where('geographyironage.id = ?',$region);
        return $rulers->fetchAll($select);
}

public function getRulersName($ruler)
{
			$rulers = $this->getAdapter();
			$select = $rulers->select()
            ->from($this->_name, array('id','issuer'))
			->where('rulers.id = ?',$ruler)
			->limit(1)
            ->order('issuer ASC');
        return $rulers->fetchAll($select);
}

public function getRulerImage($id)
{
		$images = $this->getAdapter();
		$select = $images->select()
                       ->from($this->_name, array('id'))
						->joinLeft('coin_ruler_images','rulers.id = coin_ruler_images.rulerID',array('filename'))
					   //->where('rulers.period = ?',(int)'29')
					   ->where('valid',(int)'1')
					   ->where('rulers.id = ?',(int)$id);
        return $images->fetchAll($select);
}

public function getRomanMintRulerList($id)
{		
		$actives = $this->getAdapter();
		$select = $actives->select()
			->from($this->_name)
			->joinLeft('emperors','rulers.id = emperors.pasID',array('df' => 'date_from', 'dt' => 'date_to','name','pasID','empID' => 'id'))
			->joinLeft('mints_rulers','rulers.id = mints_rulers.ruler_id',array())
			->joinLeft('mints','mints.id = mints_rulers.mint_id',array('mintid' => 'id','n' => 'mint_name' ))
			->joinLeft('romanmints','romanmints.pasID = mints.id',array('id' ))
			->where('emperors.id IS NOT NULL')
			->where('romanmints.id= ?',(int)$id)
			->order('date_from')
			->group('issuer');
        return $actives->fetchAll($select);

}

public function getMedievalMintRulerList($id)
{		
		$actives = $this->getAdapter();
		$select = $actives->select()
			->from($this->_name)
			->joinLeft('mints_rulers','rulers.id = mints_rulers.ruler_id',array())
			->joinLeft('mints','mints.id = mints_rulers.mint_id',array('mintid' => 'id' ))
			->where('mints.id= ?',(int)$id)
			->group('issuer');
        return $actives->fetchAll($select);

}

public function getRulerList($params)
{
		$actives = $this->getAdapter();
		$select = $actives->select()
			->from($this->_name)
			->joinLeft('periods','periods.id = rulers.period',array('term','i' => 'id'))
			->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
            ->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))	
			->where($this->_name.'.valid = ?',(int)1)
			->group('issuer');
	if(isset($params['period']) && ($params['period'] != ""))
	{
	$select->where('period = ?',(int)$params['period']);
	}		
			
	
	$paginator = Zend_Paginator::factory($select);

	$paginator->setItemCountPerPage(30) 
	          ->setPageRange(20);
	$cache = Zend_registry::get('cache');
	Zend_Paginator::setCache($cache);
	if(isset($params['page']) && ($params['page'] != "")) 
	{
    $paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}

public function getRulerListAdmin($params)
{
		$actives = $this->getAdapter();
		$select = $actives->select()
			->from($this->_name)
			->joinLeft('periods','periods.id = rulers.period',array('term','i' => 'id'))
			->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
            ->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))	
			->group('issuer');
	if(isset($params['period']) && ($params['period'] != ""))
	{
	$select->where('period = ?',(int)$params['period']);
	}		
			
	
	$paginator = Zend_Paginator::factory($select);

	$paginator->setItemCountPerPage(30) 
	          ->setPageRange(20);
	$cache = Zend_registry::get('cache');
	Zend_Paginator::setCache($cache);
	if(isset($params['page']) && ($params['page'] != "")) 
	{
    $paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}
	public function getRulerProfileMed($id)
	{
		$monarchs = $this->getAdapter();
		$select = $monarchs->select()
                       ->from($this->_name, array('id','issuer'))
					   ->joinLeft('monarchs','rulers.id = monarchs.dbaseID',array('name','biography','styled','alias','date1' => 'date_from','date2' => 'date_to','born','died','created','createdBy','updated','updatedBy'))
					   ->where('valid',(int)'1')
					   ->where('rulers.id = ?',(int)$id);
        return $monarchs->fetchAll($select);
	
	}		
	
}
