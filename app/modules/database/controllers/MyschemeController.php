<?php
/** Controller for displaying individual's finds on the database.
 * @todo finish module's functions and replace with solr functionality. Scripts suck the big one.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_MyschemeController extends Pas_Controller_Action_Admin {
	
    /**
    * 
    * @var object $_auth
    */
    protected $_auth;
	
    public function init() {	
    $this->_helper->_acl->allow('member',null);
    $this->_auth = Zend_Registry::get('auth');
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->contextSwitch()
         ->setAutoDisableLayout(true)
         ->addContext('csv',array('suffix' => 'csv'))
         ->addContext('kml',array('suffix' => 'kml'))
         ->addContext('rss',array('suffix' => 'rss'))
         ->addContext('atom',array('suffix' => 'atom'))
         ->addActionContext('record', array('xml','json','rss','atom'))
         ->addActionContext('index', array('xml','json','rss','atom'))
         ->initContext();
    }
    
    const REDIRECT = '/database/myscheme/';
	
    /** Protected function for finding institution
     *  
     * @todo needs abstracting out to extended controller's getAccount()
     *  @throws Pas_Exception_Param if no institution is attached
     * 
     */
    protected function getInstitution() {
    if($this->_auth->hasIdentity()) {
    $user = $this->_auth->getIdentity();
    $inst = $user->institution;
    return $inst;
    } else {
	throw new Pas_Exception_Param('No institution attached');
    }
    }
	
    /** Protected function for finding user's image directory
     * @todo needs abstracting out to extended controller's getAccount()
     * @throws Pas_Exception_Param if no institution is attached
     * 
     */
    protected function getImageDir() {
    if($this->_auth->hasIdentity()) {
    $user = $this->_auth->getIdentity();
    $imagedir = $user->imagedir;
    return $imagedir;
    } else {
            throw new Pas_Exception_Param('No image directory set up');
    }
    }

    /** Redirect as no root access allowed
     * 
     */	
    public function indexAction() {
    $this->_flashMessenger->addMessage('No access to index page');
    $this->_redirect('/database/');
    }

    /** List of user's finds that they have entered. Can be solr'd
     * 
     */		
    public function myfindsAction() {
    $limit = 20;
    $page = $this->_getParam('page');
    if(!isset($page)){
            $start = 0;

    } else {
            unset($params['page']);
            $start = ($page - 1) * 20;
    }	
	
    $config = array(
    'adapteroptions' => array(
    'host' => '127.0.0.1',
    'port' => 8983,
    'path' => '/solr/',
    'core' => 'beowulf'
    ));
	
    $select = array(
    'query'         => '*:*',
    'start'         => $start,
    'rows'          => $limit,
    'fields'        => array('*'),
    'sort'          => array('created' => 'desc'),
    'filterquery' => array(),
    );
   
    $client = new Solarium_Client($config);
    // get a select query instance based on the config
    $query = $client->createSelect($select);
    if(!is_null($d) && !is_null($lon) && !is_null($lat)){
    $helper = $query->getHelper();
    $query->createFilterQuery('geo')->setQuery($helper->geofilt($lat,$lon, 'coordinates', $d));
    }
    $query->createFilterQuery('myfinds')->setQuery('createdBy:' . $this->getIdentityForForms());
    $resultset = $client->select($query);
    $paginator = Zend_Paginator::factory($resultset->getNumFound());
    $paginator->setCurrentPageNumber($page)
            ->setItemCountPerPage($limit)
            ->setPageRange(20);
    $this->view->paginator = $paginator;
    $this->view->results = $this->processResults($resultset);
    }
	
    /** Finds recorded by an institution assigned to the user 
     * 
    */	
    public function myinstitutionAction() {
    $limit = 20;
    $page = $this->_getParam('page');
    if(!isset($page)){
            $start = 0;

    } else {
            unset($params['page']);
            $start = ($page - 1) * 20;
    }	

    $config = array(
    'adapteroptions' => array(
    'host' => '127.0.0.1',
    'port' => 8983,
    'path' => '/solr/',
    'core' => 'beowulf'
    ));
	
    $select = array(
    'query'         => '*:*',
    'start'         => $start,
    'rows'          => $limit,
    'fields'        => array('*'),
    'sort'          => array('created' => 'desc'),
    'filterquery' => array(),
    );
   
    $client = new Solarium_Client($config);
    // get a select query instance based on the config
    $query = $client->createSelect($select);
    if(!is_null($d) && !is_null($lon) && !is_null($lat)){
    $helper = $query->getHelper();
    $query->createFilterQuery('geo')->setQuery($helper->geofilt($lat,$lon, 'coordinates', $d));
    }
    $query->createFilterQuery('myinst')->setQuery('institution:' . $this->getInstitution());
    $resultset = $client->select($query);
    $paginator = Zend_Paginator::factory($resultset->getNumFound());
    $paginator->setCurrentPageNumber($page)
          ->setItemCountPerPage($limit)
          ->setPageRange(20);
    $this->view->paginator = $paginator;
    $this->view->results = $this->processResults($resultset);
    }
    /** Display all images that a user has added.
     * 
     */		
    public function myimagesAction() {
    	
    $search = new Pas_Solr_Handler('beowulf');
    $search->setFields(array('id','identifier','objecttype'));
    $search->setFacets(array('period' => 'broadperiod',
    'object' => 'objectType'));
    Zend_Debug::dump($search->execute(array('createdBy' => 56)));
    exit;	 
    $config = array(
    'adapteroptions' => array(
    'host' => '127.0.0.1',
    'port' => 8983,
    'path' => '/solr/',
    'core' => 'beoimages'
    ));
	
    $select = array(
    'query'         => '*:*',
    'start'         => $this->getStart(),
    'rows'          => $this->getLimit(),
    'fields'        => array('*'),
    'sort'          => array('created' => 'desc'),
    'filterquery' => array(),
    );
   
    $client = new Solarium_Client($config);
    // get a select query instance based on the config
    $query = $client->createSelect($select);
    if(!is_null($d) && !is_null($lon) && !is_null($lat)){
    $helper = $query->getHelper();
    $query->createFilterQuery('geo')->setQuery($helper->geofilt($lat,$lon, 'coordinates', $d));
    }
    $query->createFilterQuery('myimages')->setQuery('createdBy:' . $this->getIdentityForForms());
    $resultset = $client->select($query);
    $this->view->paginator = $this->createPagination($resultset);
    $this->view->results = $this->processResults($resultset);
    }
    
    
    
    public function getPage(){
	return $this->_getParam('page');
    }
    
    public function getStart(){
	$page = $this->getPage();
    if(!isset($page)){
            $start = 0;

    } else {
            unset($params['page']);
            $start = ($page - 1) * 20;
    }	
    return $start;
    }
    
    public function getLimit(){
	$limited = $this->_getParam('limit');
    if(!isset($limited)){
            $limit = 20;

    } else {
            unset($params['page']);
            $limit = $limited;
    }	
    return $limit;
    }
    
    public function createPagination($resultset){
    $paginator = Zend_Paginator::factory($resultset->getNumFound());
    $paginator->setCurrentPageNumber($this->getPage())
            ->setItemCountPerPage($this->getLimit())
            ->setPageRange(20);
    return $paginator;	
    }
    
    public function processResults($resultset){
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
    
    public function mytreasureAction(){
        
    }

}