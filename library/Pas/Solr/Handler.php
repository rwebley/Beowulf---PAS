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
define('SCHEMA_PATH', '/home/beowulf2/solr/solr/');
    
define('SCHEMA_FILE', '/conf/schema.xml' );

class Pas_Solr_Handler {

    protected $_solr;
    
    protected $_index;
    
    protected $_limit;
    
    protected $_cache;
    
    protected $_config;
    
    protected $_solrConfig;
    
    protected $_facets;
    
    protected $_allowed =  array('fa','flos','admin','treasure');
    
    protected $_formats = array('json', 'csv', 'xml', 'midas', 'rdf', 'n3', 'rss', 'atom');
    
    public function __construct($core){
    $this->_cache = Zend_Registry::get('rulercache');
    $this->_config = Zend_Registry::get('config');
    $this->_core = $core;
    $this->_solrConfig = $this->_setSolrConfig($this->_core);
    $this->_solr = new Solarium_Client($this->_solrConfig);
    $this->_checkFieldList($this->_core, $this->setFields());
    $this->_checkCoreExists($this->_core);
    }

	private function _getCores() {
	if (!($this->_cache->test('solrCores'))) {
	$dir = new DirectoryIterator(SCHEMA_PATH);
        $cores = array();
        foreach ($dir as $dirEntry) {
		if($dirEntry->isDir() && !$dirEntry->isDot()){
			$cores[] = $dirEntry->getFilename();
		}
        }
	$this->_cache->save($cores);
	} else {
	$cores = $this->_cache->load('solrCores');
	}
        return $cores;
	}
    
    protected function _checkCoreExists($core){
    	if(!in_array($core,$this->_getCores())){
    		throw new Exception('That is not a valid core',500);
    	} else {
    		return true;
    	}
    }
	
	protected function _setSolrConfig($core){
    $config = $this->_config->solr->toArray();
    if(isset($core)){
    	$config['core'] = $core;
    }
    return $this->_solrConfig = array('adapteroptions' => $config);
    }
    
	protected function _getRole(){
	$user = new Pas_UserDetails();
	return $user->getPerson()->role;
	}
    
	
    public function setFields($fields = NULL){
    if(is_array($fields)){
        $this->_fields = implode($fields,',');
    } else {
       $this->_fields = '*';
    }
    }

    public function setParams($params){
    	if(is_array($params)){
    	return $this->_filterParams($params);	
    	}
    }

    public function setFacets($facets){
    	if(is_array($facets)){
    		$this->_facets = $facets;
    		return $this->_facets;
    	}
    }
    protected function _createPagination($resultset){
    $paginator = Zend_Paginator::factory($resultset->getNumFound());
    $paginator->setCurrentPageNumber(1)
            ->setItemCountPerPage(10)
            ->setPageRange(20);
    return $paginator;	
    }
    
    protected function _processResults($resultset){
    $data = array();
    foreach($resultset as $doc){
		$fields = array();
	 		 foreach($doc as $key => $value){
              $fields[$key] = $value;
		}
    	$data[] = $fields;
    }
    return $data;	
    }
    
 
    public function _checkFieldList($core = 'beowulf', $fields){
    if(!is_null($fields)){
    $file = SCHEMA_PATH . $core . SCHEMA_FILE;
	$key = md5($file);
	if (!($this->_cache->test($key))) {
    if(file_exists($file)){
    	$xml = simplexml_load_file($file);
    	$schemaFields = array();
    	foreach($xml->fields->field as $field){
			$string = get_object_vars($field->attributes());
			//This bit looks honky, couldn't get it to work with object notation
			$schemaFields[] = $string["@attributes"]['name'];
    	}
    }
	$this->_cache->save($schemaFields);
	} else {
	$schemaFields = $this->_cache->load($key);
	}
	foreach($fields as $f){
		if(!in_array($f,$schemaFields)){
			throw new Pas_Solr_Exception('The field ' . $f . ' is not in the schema');
		}
	}
    }
    }
	
    protected function _getSort($core, $params){
    	if(array_key_exists('sort',$params)){
    		$this->_checkFieldList($core, array($params['sort']));
    		$field = $params['sort'];
    	} else {
    		$field = 'created';
    	}
    	$allowed = array('desc','asc');
    	if(array_key_exists('direction', $params)) {
    		if(in_array($params['direction'],$allowed)){
    		$direction = $params['direction'];
    		} else {
    			throw new Pas_Solr_Exception('That directional sort does not exist');
    		}
    	} else {
    		$direction = 'desc';
    	}
    	
    	return array($field => $direction);
    }
    
    public function execute(array $params ){
  	$select = array(
    'query'         => '*:*',
    'start'         => 1,
    'rows'          => 20,
    'fields'        => array('*'),
    'filterquery' => array(),
    );
   	$select[]['sort'] = $this->_getSort($this->_core, $params);
    // get a select query instance based on the config
    $this->_query = $this->_solr->createSelect($select);
    $this->_query->setFields($this->_fields);
    if(!is_null($params['d']) && !is_null($params['lon']) && !is_null($params['lat'])){
    $helper = $this->_query->getHelper();
    $this->_query->createFilterQuery('geo')->setQuery($helper->geofilt($params['lat'], $params['lon'],
     'coordinates', $params['d']));
    }
    if(!in_array($this->_getRole(),$this->_allowed)) {
    $this->_query->createFilterQuery('workflow')->setQuery('workflow:[3 TO 4]');
    }
    if(!is_null($this->_facets)){
    	$this->_createFacets($this->_facets);
    }
    if(is_null($params)){
    	$this->_createFilters($params);
    }
    Zend_Debug::dump($this->_query);
    
    $resultset = $this->_solr->select($this->_query);
    Zend_Debug::dump($resultset);
   	Zend_Debug::dump($this->_createPagination($resultset));
    Zend_Debug::dump($this->_processResults($resultset));
    }
   
    protected function _createFilters($params){
    	
    }

    protected function _createFacets($facets){
    	$facetSet = $this->_query->getFacetSet();
    	foreach($facets as $key => $value){
    		$facetSet->createFacetField($key)->setField($value);
    	}
    }
    
    
}

