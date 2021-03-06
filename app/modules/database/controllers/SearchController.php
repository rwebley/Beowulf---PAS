<?php
/** Controller for searching for finds on database
 * @todo finish module's functions and replace with solr functionality. Scripts suck the big one.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_SearchController extends Pas_Controller_Action_Admin {
	
	protected $_contexts = array(
	'xml', 'rss', 'json',
	'atom', 'kml', 'georss',
	'ics', 'rdf', 'xcs');

	protected $_config, $_akismetkey, $_googleapikey, $_solr;
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('public',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_config         = Zend_Registry::get('config');
	$this->_akismetkey     = $config->webservice->akismetkey;
	$this->_solr           = new Apache_Solr_Service( 'localhost', '8983', '/solr/beowulf' );
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addContext('kml',array('suffix' => 'kml'))
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('results', array('xml','kml','json','rss','atom'));
	$her = array('her');
	$herroles = array('hero','flos','admin','fa');
	$role = $this->getAccount();
	if($role){
	$user = $role->role;
	if(in_array($user,array('hero','flos','admin','fa','treasure','member','research'))) {
	$this->_helper->contextSwitch()
		->addContext('csv',array('suffix' => 'csv'))
		->addActionContext('results', array('csv'));
	}
	}
		
	if($role){
	$user = $role->role;
	if(in_array($user,$herroles)) {
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()
		->addContext('hero',array('suffix' => 'hero'))
		->addActionContext('results', array('hero'));
	}
	}
	$this->_helper->contextSwitch()->initContext();
      
	if(!in_array($this->_helper->contextSwitch()->getCurrentContext(),$this->_contexts )) {
	$this->view->googleapikey = $this->_config->webservicegooglemaps->apikey; 
	}
	}
      
	
	/** Display the basic what/where/when page.
	*/	
	public function indexAction() {
	$form = new WhatWhereWhenForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($this->_getAllParams());
	}
	}
	}
	
	
	function array_cleanup( $array ) {
    $todelete = array('submit','action','controller','module','page','csrf');
		foreach( $array as $key => $value ) {
    foreach($todelete as $match){
    	if($key == $match){
    		unset($array[$key]);
    	}
    } 
    }
    return $array;
}
		
	/** Generate the advanced search page
	*/	
	public function advancedAction(){
	$form = new AdvancedSearchForm(array('disableLoadDefaultDecorators' => true));
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($this->_getAllParams());
	}
	}
	}
	/** Display the byzantine search form
	*/	
	public function byzantinenumismaticsAction() {
	$form = new ByzantineNumismaticSearchForm();
	$this->view->byzantineform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($this->_getAllParams());
	}
	}
	}
	/** Display the early medieval numismatics form
	*/	
	public function earlymednumismaticsAction() {
	$form = new EarlyMedNumismaticSearchForm();
	$this->view->earlymedform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($this->_getAllParams());
	}
	}
	}
	/** Display the medieval numismatics page
	*/		
	public function mednumismaticsAction() {
	$form = new MedNumismaticSearchForm();
	$this->view->earlymedform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($this->_getAllParams());
	}
	}
	}
	/** Display the post medieval numismatics pages
	*/		
	public function postmednumismaticsAction() {
	$form = new PostMedNumismaticSearchForm();
	$this->view->earlymedform = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($this->_getAllParams());
	}
	}
	}
	
	/** Display the roman numismatics pages
	*/		
	public function romannumismaticsAction() {
	$form = new RomanNumismaticSearchForm();
	$this->view->formRoman = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($this->_getAllParams());
	}
	}
	}
	/** Display the iron age numismatics pages
	*/	
	public function ironagenumismaticsAction() {
	$form = new IronAgeNumismaticSearchForm();
	$this->view->formIronAge = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($this->_getAllParams());
	}
	}
	}
	/** Display the greek and roman provincial pages
	*/	
	public function greekromanAction() {
	$form = new GreekRomanSearchForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($_POST)) 	 {
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
	$params = $this->array_cleanup($params);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('results','search','database',$params);
	} else {
	$form->populate($this->_getAllParams());
	}
	}
	}

	
	/** Remove multiple values
	 * 
	 * @param array $array
	 * @param string $sub_key
	*/		
	private function unique_multi_array($array, $sub_key) {
	$target = array();
	$existing_sub_key_values = array();
	foreach ($array as $key=>$sub_array) {
       if (!in_array($sub_array[$sub_key], $existing_sub_key_values)) {
           $existing_sub_key_values[] = $sub_array[$sub_key];
           $target[$key] = $sub_array;
       }
	}
	return $target;
	}
	/** Display the map of results
	*/	
	public function mapAction() {
	$data = $this->_getAllParams();
	$params = array_filter($data);
	$this->view->params = $params;
		unset($params['controller']);
		unset($params['module']);
		unset($params['action']);
		unset($params['submit']);
		unset($params['csrf']);

	$where = array();
        foreach($params as $key => $value) {
            if(!is_null($value)) {
            $where[] = $key . '/' . urlencode($value);
            }
        }
   	$whereString = implode('/', $where);
	$query = $whereString;
	$this->view->query = $query;
	}

	public function saveAction() {
	$form = new SaveSearchForm();
	$form->submit->setLabel('Save search');
	$this->view->form = $form;
	$searches = new Searches();
	$lastsearch = $searches->fetchRow($searches->select()->where('userid = ?',
	$this->getIdentityForForms())->order('id DESC'));
	$querystring = unserialize($lastsearch->searchString);
	$params = array();
	$query = '';
	foreach($querystring as $key => $value) {
	$query .= $key.'/'.$value.'/';
	$params[$key] = $value;
	}
	$this->view->params = $params;
	if ($this->_request->isPost()) {
	$data = $this->_getAllParams();
	if ($form->isValid($data)) {

	$insertData = array();
	$insertData['created'] = $this->getTimeForForms();
	$insertData['createdBy'] = $this->getIdentityForForms();
	$insertData['title'] = $form->getValue('title');
	$insertData['description'] = $form->getValue('description');
	$insertData['searchString'] = $lastsearch->searchString;
	$insertData['public'] = $form->getValue('public');
	
	
	$saved = new SavedSearches();
	$insert = $saved->insert($insertData);
	$this->_redirect(self::REDIRECT.$query);
	} else  {
	$this->_flashMessenger->addMessage('There are problems with your submission.');
	$form->populate($data);
	}
	}
	}
	/** Email a search result
	*/		
	public function emailAction() {
	$user = $this->getAccount();
	$this->view->headTitle('Email this search to another person');
	$searches = new Searches();
	$lastsearch = $searches->fetchRow($searches->select()->where('userid = ?',$this->getIdentityForForms())->order('id DESC'));
	if(count($lastsearch)) {
	$querystring = unserialize($lastsearch->searchString);
	$params = array();
	$query = '';
	foreach($querystring as $key => $value) {
	$query .= $key.'/'.$value.'/';
	$params[$key] = $value;
	}
	$this->view->params = $params;
	
	$form = new EmailSearchForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$data = $this->_getAllParams();
	if ($form->isValid($data)) {
	$sender = $user->fullname;
	$senderemail = $user->email;
	$recipient = $form->getValue('fullname');
	$recipientemail = $form->getValue('email');
	$message = $form->getValue('messageToUser');
	$strippedmessage = strip_tags($message);
	$url = 'http://'.$_SERVER['SERVER_NAME'].'/database/search/results/'.$query;
	$mail = new Zend_Mail();
	$mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme\'s awesome database');
	$mail->setBodyHtml('<p>Dear '.$recipient.'</p>'.$message.'<p>Located at this url:  '.$url.'</p><p>From '.$sender.'</p>');
	$mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
	$mail->setBodyText('Dear '.$recipient.','.
	$message 
	.' '.
	$url
	.'From,'.
	
	$sender);
	$mail->addTo($recipientemail, $recipient);
	$mail->addCC($senderemail,$sender);
	$mail->setSubject('I thought you might be interested in this search on the PAS Database.');
	$mail->send();	
	$this->_flashMessenger->addMessage('Your email has been sent to '.$recipient
	.'. Thank you for sending them some of our records.');
	$this->_redirect('/database/search/results/'.$query);
	}  else {
	$form->populate($data);
	}
	}
	} else {
	$this->_flashMessenger->addMessage('You haven\'t ever searched, so you have nothing to email!');
	$this->_redirect('/database/search/');
	
	}
	}
	/** Display saved searches
	*/		
	public function savedsearchesAction() {
	$allowed = array('fa','flos','admin');
	if(in_array($this->getRole(),$allowed)) {
	$private = 1;
	} else {
	$private = NULL;
	}
	$searches = new SavedSearches(); 

	if($this->_getParam('by') == 'me'){
	$data = $searches->getSavedSearches($this->getIdentityForForms(),$this->_getParam('page'),$private);
	} else {
	$data = $searches->getSavedSearches(NULL,$this->_getParam('page'), $private);
	}
	$this->view->data = $data;
	}
	/** Display the solr form
	*/		
	public function solrAction(){
 	if (  !$this->_solr->ping() ) {
 	echo '<h2>Search engine system error</h2>';
	echo '<p>Solr service not responding.</p>';
	} else {
	$form = new SolrForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$data = $this->_getAllParams();
	if ($form->isValid($data)) {
	$this->_redirect($this->view->url(array('module' => 'database',
	'controller' => 'search','action' => 'results','q' => $data['q'])));
	} else {
	$form->populate($q);
	}
	}
	}	
	}
	/** Display the index page.
	*/		
	public function resultsAction(){
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
	
	if(array_key_exists('radius',$params)){
	$d = (int)$params['radius'];
	unset($params['radius']);
	}
	if(array_key_exists('lat',$params)){
	$lat = (float)$params['lat'];
	unset($params['lat']);
	}
	if(array_key_exists('lon',$params)){
	$lon = (float)$params['lon'];
	unset($params['lon']);
	}
	$params = array_filter($params);
	
	foreach($params as $k => $v){
	$q .= $k . ':"' . $v . '" ';
	}
	$config = array(
    'adapteroptions' => array(
    'host' => '127.0.0.1',
    'port' => 8983,
    'path' => '/solr/',
	'core' => 'beowulf'
    )
	);
	
	$select = array(
    'query'         => $q,
    'start'         => $start,
    'rows'          => $limit,
    'fields'        => array('*'),
    'sort'          => array('created' => 'desc'),
	'filterquery' => array(),
    );
	$allowed = array('fa','flos','admin','treasure');
	if(!in_array($this->getRole(),$allowed)) {
	$select['filterquery']['workflow'] = array(
            'query' => 'workflow:[3 TO *]'
        );
	if(array_key_exists('parish',$params)){
	$select['filterquery']['knownas'] = array(
            'query' => 'knownas:["" TO *]'
        );
	}
	}
	
	if(!is_null($images)){
	$select['filterquery']['images'] = array(
            'query' => 'thumbnail:[1 TO *]'
        );
	}
	
	// create a client instance
	$client = new Solarium_Client($config);
	// get a select query instance based on the config
	$query = $client->createSelect($select);
	if(!is_null($d) && !is_null($lon) && !is_null($lat)){
	$helper = $query->getHelper();
	$query->createFilterQuery('geo')->setQuery($helper->geofilt($lat,$lon, 'coordinates', $d));
	}
	$facetSet = $query->getFacetSet();

	$facetSet->createFacetField('period')->setField('broadperiod');
	$facetSet->createFacetField('county')->setField('county');
	$facetSet->createFacetField('objectType')->setField('objectType');
	$resultset = $client->select($query);
	$pagination = array(    
	'page'          => $page, 
	'per_page'      => $limit, 
    'total_results' => $resultset->getNumFound()
	);
	
	$data = NULL;
	foreach($resultset as $doc){
		$fields = array();
	    foreach($doc as $key => $value){
	    	$fields[$key] = $value;
	    }
	    $data[] = $fields;
	}
//	Zend_Debug::dump($data);
//	$periodFacet = $resultset->getFacetSet()->getFacet('period');
//	foreach($periodFacet as $value => $count) {
//    echo $value . ' [' . $count . ']<br/>';
//	}
//	$objectFacet = $resultset->getFacetSet()->getFacet('objectType');
//	foreach($objectFacet as $value => $count) {
//    echo $value . ' [' . $count . ']<br/>';
//	}
//	$countyFacet = $resultset->getFacetSet()->getFacet('county');
//	foreach($countyFacet as $value => $count) {
//    echo $value . ' [' . $count . ']<br/>';
//	}
	$paginator = Zend_Paginator::factory($resultset->getNumFound());
    $paginator->setCurrentPageNumber($page)
              ->setItemCountPerPage($limit)
              ->setPageRange(20);
    $this->view->paginator = $paginator;
	$this->view->results = $data;
	} else {
		throw new Pas_Exception_Param('Your search has no parameters!',500);	
	}
	}
//EOS
}