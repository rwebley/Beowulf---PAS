<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MoreLikeThis
 *
 * @author Katiebear
 */
class Pas_View_Helper_MoreLikeThis extends Zend_View_Helper_Abstract {
    
    
    protected $_solr;
    
    protected $_index;
    
    protected $_limit;
    
    protected $_cache;
    
    protected $_config;
    
    protected $_solrConfig;
    
    protected $_results;
    
    public function __construct(){
    $this->_cache = Zend_Registry::get('rulercache');
    $this->_config = Zend_Registry::get('config');
    $this->_solrConfig = $this->_config->solr->toArray();
    $this->_solr = new Pas_Solr_MoreLikeThis();
    
    }

    public function moreLikeThis($id){
     $mlt = $this->_solr;
     $mlt->setFields(array('objecttype','broadperiod','description'));
     $mlt->setQuery('id:' . $id . ' thumbnail:[1 TO *]');
     $solrResponse =  $mlt->executeQuery();
     
    return $this->buildHtml($solrResponse);
    }
    
    private function buildHtml($solrResponse){
        
    $html ='<h4>' . $this->_results . ' similar objects</h4><ul>';
    foreach($solrResponse as $document){
        foreach($document as $doc ){
            
                $html .= '<li>';
                $html .= '<img src="/images/thumbnails/'. $doc->_fields['thumbnail'] .'"/>';
                $html .= '<li>';
                
        } 
       
    }
    $html .= '</ul>';
    return $html;
    }
    
}


