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
    
    public function executeQuery( $minDocFreq = 1, $minTermFreq = 1){
    $client = $this->_solr;
    $query = $client->createSelect();
    $query->setQuery($this->_query)
            ->getMoreLikeThis()
            ->setFields($this->_fields)
            ->setMinimumDocumentFrequency($minDocFreq)
            ->setMinimumTermFrequency($minTermFreq);
    $resultset = $client->select($query);
    return $this->getLikes($resultset);
    }
      
    public function getLikes($resultSet){
    
    $mlt = $resultSet->getMoreLikeThis();
    foreach($resultSet as $document){
         $mltResult = $mlt->getResult($document->id);
          if($mltResult){
        $likeData['Maxscore'] = $mltResult->getMaximumScore();
        $likeData['NumFound'] = $mltResult->getNumFound();
        $likeData['Numfetched'] = count($mltResult);
        foreach($mltResult AS $mltDoc) {
        $likeData[$mltDoc->name] = $mltDoc->id;
        }
          }
    }
        return $likeData;
       
               
    }
    
}

