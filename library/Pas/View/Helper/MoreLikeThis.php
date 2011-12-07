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
    
    protected $_cache;
    
    protected $_config;
    
    protected $_solrConfig;
    
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
    if($solrResponse){
    return $this->buildHtml($solrResponse);
    } else {
    	return false;
    }
    }
    
    private function buildHtml($solrResponse){
    $html ='<div id="similar"><h4>Similar objects</h4>';
    foreach($solrResponse['results'] as $document){
       if(($document->thumbnail)){ 
                $html .= '<div class="thumbnail">';
                $html .= '<a href="http://beta.finds.org.uk/database/artefacts/record/id/' . $document->id . '">';
                $html .= '<img src="/images/thumbnails/'. $document->thumbnail .'.jpg"/>';
                $html .= '</a><br />Find number: ';
                $html .= $document->old_findID;
                $html .= '<br />Object type: ' . $document->objecttype;
                $html .= '<br />Broadperiod: ' . $document->broadperiod;
                $html .= '</div>';
       }         
    }
    $html .= '</div>';
    return $html;
    }
    
}


