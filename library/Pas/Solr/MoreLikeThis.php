<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MoreLikeThis
 *
 * @author Daniel Pett
 */
class Pas_Solr_MoreLikeThis {

    protected $_solr;
    
    protected $_index;
    
    protected $_limit;
    
    protected $_cache;
    
    protected $_config;
    
    protected $_solrConfig;
    
    public function __construct(){
    $this->_cache = Zend_Registry::get('rulercache');
    $this->_config = Zend_Registry::get('config');
    $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
    $this->_solr = new Solarium_Client($this->_solrConfig);
  
    }

	public function getRole(){
	$user = new Pas_UserDetails();
	return $user->role;
	}
    
	protected $_allowed =  array('fa','flos','admin','treasure');
    
    public function setFields($fields){
    if(is_array($fields)){
        $this->_fields = implode($fields,',');
    } else {
        throw new Pas_Solr_Exception('The field list is not an array');
    }
    }
    
    public function setQuery($query){
    if(is_string($query)){
        $this->_query = (string)$query;
    } else {
        throw new Pas_Solr_Exception('query must be a string');
    }
    }
    
    public function executeQuery( $minDocFreq = 1, $minTermFreq = 1, $count = 4){
    $client = $this->_solr;
    $query = $client->createSelect();
    $query->setQuery($this->_query)
            ->getMoreLikeThis()
            ->setFields($this->_fields)
            ->setMinimumDocumentFrequency($minDocFreq)
            ->setMinimumTermFrequency($minTermFreq)
            ->setCount($count);
    if(!in_array($this->getRole(),$this->_allowed)) {
    $query->createFilterQuery('workflow')->setQuery('workflow:[3 TO 4]');
    }
    $resultset = $client->select($query);
    $mlt = $resultset->getMoreLikeThis();
    foreach($resultset as $result){
       
    $mltResult = $mlt->getResult($result->findIdentifier);
    $mltArray = array();
    if($mltResult){
    $mltArray['maxScore'] = $mltResult->getMaximumScore();
    $mltArray['numFound'] = $mltResult->getNumFound();
    $mltArray['numFetched'] = count($mltResult);
    foreach($mltResult AS $k => $v) {
       $mltArray['results'][$k] = $v;
    }
    }
    }
    
    return $mltArray;
    }
      
}

