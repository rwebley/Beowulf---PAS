<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * MoreLikeThis view helper for compiling an html render of 4 objects that are similar to 
 * the current one being viewed.
 * @category Pas
 * @package  Pas_View_
 * @subpackage Helper
 * @version  1
 * @copyright DEJ PETT
 * @author Daniel Pett
 */
class Pas_View_Helper_MoreLikeThis extends Zend_View_Helper_Abstract {
    
    /** The Solr instance
     * 
     * @var object
     */
    protected $_solr;
    
    /** The cache
     * 
     * @var object
     */
    protected $_cache;
    
    /** The config object
     * 
     * @var object
     */
    protected $_config;
    
    /** Solr config
     * 
     * @var array
     */
    protected $_solrConfig;
    
    /** Construct all the objects
     * 
     */
    public function __construct(){
    $this->_cache = Zend_Registry::get('rulercache');
    $this->_config = Zend_Registry::get('config');
    $this->_solrConfig = $this->_config->solr->toArray();
    $this->_solr = new Pas_Solr_MoreLikeThis();
    }

    /** Query the solr instance
     * 
     * @param string $query
     */
    public function moreLikeThis($query){
    $key = md5('mlt' . $query);
	if (!($this->_cache->test($key))) {
	$mlt = $this->_solr;
	$mlt->setFields(array('objecttype','broadperiod','description','notes'));
	$mlt->setQuery($query);
	$solrResponse =  $mlt->executeQuery();
	$this->_cache->save($solrResponse);
	} else {
	$solrResponse = $this->_cache->load($key);
	}
    if($solrResponse){
    return $this->buildHtml($solrResponse);
    } else {
    	return false;
    }
    }
    
    /** Get the baseurl for the site
     * 
     */
    private function getSiteUrl(){
    	return Zend_Registry::get('siteurl');
    }
    
    /** Build the HTML response
     * 
     * @param array $solrResponse
     */
    private function buildHtml($solrResponse){
    $html ='<div id="similar"><h4>Similar objects</h4>';
    foreach($solrResponse['results'] as $document){
       if(($document->thumbnail)){ 
			$html .= '<div class="thumbnail">';
			$html .= '<a href="' . $this->getSiteUrl() . '/database/artefacts/record/id/' 
                . $document->id . '">';
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


