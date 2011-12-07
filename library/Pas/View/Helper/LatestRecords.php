<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * LatestRecords helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_LatestRecords extends Zend_View_Helper_Abstract{
	
	protected $_solr;
	
	protected $_solrConfig;
	
	protected $_config;
	
	protected $_cache;
	
	protected $_allowed =  array('fa','flos','admin','treasure');
	
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
	/**
	 * 
	 */
	public function latestRecords( $q = '*:*', $fields = 'id,old_findID,objecttype,imagedir,filename,thumbnail,broadperiod', $start = 0, $limit = 4,  
		$sort = 'created', $direction = 'desc') {
	$select = array(
    'query'         => $q,
    'start'         => $start,
    'rows'          => $limit,
    'fields'        => array($fields),
    'sort'          => array($sort => $direction),
	'filterquery' => array(),
    );
	if(!in_array($this->getRole(),$this->_allowed)) {
	$select['filterquery']['workflow'] = array(
            'query' => 'workflow:[3 TO 4]'
        );
	$select['filterquery']['knownas'] = array(
            'query' => 'knownas:["" TO *]'
        );
	}
	$select['filterquery']['images'] = array(
            'query' => 'thumbnail:[1 TO *]'
        );
	
	$query = $this->_solr->createSelect($select);
	$resultset = $this->_solr->select($query);
	$data = array();
	foreach($resultset as $doc){
		$fields = array();
	    foreach($doc as $key => $value){
	    	$fields[$key] = $value;
	    	
	    }
	    $data[] = $fields;
	}
	return $this->buildHtml($data);
	}
	
	public function buildHtml($data){
	if($data) {
	$html = '<h3>Latest examples recorded</h3>';
	$html .= '<div id="latest">';
	$html .= $this->view->partialLoop('partials/database/imagesPaged.phtml', $data);
	$html .= '</div>';
	return $html;
	} else {
		return false;
	}
	}
	
}

