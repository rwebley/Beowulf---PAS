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

    public function moreLikeThis($query){
     $mlt = $this->_solr;
     $mlt->setFields(array('objecttype','broadperiod','description','notes'));
     $mlt->setQuery($query);
     $solrResponse =  $mlt->executeQuery();
     
    return $this->buildHtml($solrResponse);
    }
    
    private function buildHtml($solrResponse){
    $html ='<h4>We found some similar objects</h4><ul>';
    foreach($solrResponse['results'] as $document){
       if(($document->thumbnail)){ 
                $html .= '<li style="list-style:none;display:inline;">';
                $html .= '<a href="http://beta.finds.org.uk/database/artefacts/record/id/' . $document->id . '">';
                $html .= '<img src="/images/thumbnails/'. $document->thumbnail .'.jpg"/>';
                $html .= '</a>';
                $html .= $document->old_findID . ' - ' . $document->objecttype;
                $html .= '</li>';
       }         
      
       
    }
    $html .= '</ul>';
    return $html;
    }
    
}


