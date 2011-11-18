<?php
/** Controller for the Staffordshire symposium
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class Search_ResultsController extends Pas_Controller_Action_Admin {
	
	protected $_solr;
	/**
	 * Set up the ACL
	 */
	public function init() {
	$this->_helper->_acl->allow('public',null);	
	}
	
	/** List of the papers available
	 */
	public function indexAction() {
	$params = array_slice($this->_getAllParams(),3);
	if(sizeof($params) > 0){
	$limit = 20;
	$page = $this->_getParam('page');
	if(!isset($page)){
		$start = 0;
		
	} else {
		unset($params['page']);
		$start = ($page - 1) * 20;
	}	
	$q = '';
	if(array_key_exists('q',$params)){
	$q .= $params['q'] . ' ';
	unset($params['q']); 
	}
	if(array_key_exists('images',$params)){
	$images = (int)1;
	unset($params['images']);
	}
	$params = array_filter($params);
	
	foreach($params as $k => $v){
	$q .= $k . ':"' . $v . '" ';
	}
	$config = array(
    'adapteroptions' => array(
    'host' => '127.0.0.1',
    'port' => 8983,
    'path' => '/solr/beocontent/',
    )
	);
	
	$select = array(
    'query'         => $q,
    'start'         => $start,
    'rows'          => $limit,
    'fields'        => array('*','score'),
	'filterquery' => array(),

    );
	$client = new Solarium_Client($config);
	$query = $client->createSelect($select);
	$query->addSort('score', Solarium_Query_Select::SORT_DESC);
// add distributed search settings
// see http://wiki.apache.org/solr/DistributedSearch#Distributed_Search_Example for setting up two solr instances
$distributedSearch = $query->getDistributedSearch($select);
$distributedSearch->addShard('shard1', 'localhost:8983/solr/beocontent');
//$distributedSearch->addShard('shard2', 'localhost:8983/solr/beopeople');
// this executes the query and returns the result
$resultset = $client->select($query);
//	$client = new Solarium_Client($config);
//	$query = $client->createSelect($select);

	$resultset = $client->select($query);
	$pagination = array(    
	'page'          => $page, 
	'per_page'      => $limit, 
    'total_results' => $resultset->getNumFound()
	);
	$data = NULL;
	foreach($resultset as $doc){
	    foreach($doc as $key => $value){
	    	$fields[$key] = $value;
	    }
	    $data[] = $fields;
	}
	$paginator = Zend_Paginator::factory($resultset->getNumFound());
    $paginator->setCurrentPageNumber($page)
              ->setItemCountPerPage($limit)
              ->setPageRange(20);
    $this->view->paginator = $paginator;
	$this->view->results = $data;
	$this->view->query = $q;
	} else {
		throw new Pas_Exception_Param('Your search has no parameters!',500);	
	}
	}

}

