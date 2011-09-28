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
	$this->_solr           = new Apache_Solr_Service( 'localhost', '8983', '/solr' );
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
      
	const REDIRECT = 'database/search/results/';
	
	/** Display the basic what/where/when page.
	*/	
	public function indexAction() {
	$form = new WhatWhereWhenForm();
	$form->setMethod('get');
	$this->view->form = $form;
	$values = $form->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($form->isValid($data)) {
	$params = array_filter($data);
	unset($params['submit']);
	unset($params['action']);
	unset($params['controller']);
	unset($params['module']);
	unset($params['page']);
	unset($params['csrf']);
		
	$where = array();
	foreach($params as $key => $value){
	if($value != NULL){
	$where[] = $key . '/' . urlencode(strip_tags($value));
	}
	}
	$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT.$query.'/');
	}  else  {
	$form->populate($data);
	}
	}
	}
	
	/** Generate the advanced search page
	*/	
	public function advancedAction(){
	$params = $this->_getAllParams();
	$form = new AdvancedSearchForm(array('disableLoadDefaultDecorators' => true));
	$form->setMethod('get');
	$this->view->form = $form;
	$values = $form->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($form->isValid($data)) {
	$params = array_filter($data);
		unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
	    unset($params['csrf']);

	$where = array();
    foreach($params as $key => $value) {
	if($value != NULL){
    $where[] = $key . '/' . urlencode(strip_tags($value));
		}
     }
	$whereString = implode('/', $where);
	$query = $whereString;
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_redirect(self::REDIRECT . $query . '/');
	} else {
	$form->populate($data);
	}
	}
	}
	/** Display the byzantine search form
	*/	
	public function byzantinenumismaticsAction() {
	$byzantineform = new ByzantineNumismaticSearchForm();
	$this->view->byzantineform = $byzantineform;
	$values = $byzantineform->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($byzantineform->isValid($data)) {
		$params = array_filter($data);
		unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
		unset($params['csrf']);

		$where = array();
        foreach($params as $key => $value)
        {
			if($value != NULL){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
			$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . $query . '/');
	} 
	else 
	{
	$byzantineform->populate($data);
	}
	}
	}
	/** Display the early medieval numismatics form
	*/	
	public function earlymednumismaticsAction() {
	$params = $this->_getAllParams();
	$earlymedform = new EarlyMedNumismaticSearchForm();
	$this->view->earlymedform = $earlymedform;
	$values = $earlymedform->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($earlymedform->isValid($data)) {
	
		$params = array_filter($data);
		unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
		unset($params['csrf']);

		$where = array();
        foreach($params as $key => $value)
        {
			if($value != NULL){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
			$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . $query.'/');
	} else {
	$earlymedform->populate($data);
	}
	}
	}
	/** Display the medieval numismatics page
	*/		
	public function mednumismaticsAction() {
	$earlymedform = new MedNumismaticSearchForm();
	$this->view->earlymedform = $earlymedform;
	$values = $earlymedform->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($earlymedform->isValid($data)) {
	
		$params = array_filter($data);
		unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
		unset($params['csrf']);

		$where = array();
        foreach($params as $key => $value)
        {
			if($value != NULL){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
			$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . $query.'/');
	} else {
	$earlymedform->populate($data);
	}
	}
	}
	/** Display the post medieval numismatics pages
	*/		
	public function postmednumismaticsAction() {
	$earlymedform = new PostMedNumismaticSearchForm();
	$this->view->earlymedform = $earlymedform;
	$values = $earlymedform->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($earlymedform->isValid($data)) {
	
		$params = array_filter($data);
		unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
		unset($params['csrf']);

		$where = array();
        foreach($params as $key => $value)
        {
			if($value != NULL){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
			$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . $query.'/');
	} else {
	$earlymedform->populate($data);
	}
	}
	}
	
	/** Display the roman numismatics pages
	*/		
	public function romannumismaticsAction() {
	$formRoman = new RomanNumismaticSearchForm();
	$this->view->formRoman = $formRoman;
	$values = $formRoman->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($formRoman->isValid($data)) {
		$params = array_filter($data);
		unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
		unset($params['csrf']);

		$where = array();
        foreach($params as $key => $value)
        {
			if($value != NULL){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
			$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . $query.'/');
	} else {
	$formRoman->populate($data);
	}
	}
	}
	/** Display the iron age numismatics pages
	*/	
	public function ironagenumismaticsAction() {
	$formIronAge = new IronAgeNumismaticSearchForm();
	$this->view->formIronAge = $formIronAge;
	$values = $formIronAge->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($formIronAge->isValid($data)) {
		$params = array_filter($data);
		unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
		unset($params['csrf']);

		$where = array();
        foreach($params as $key => $value)
        {
			if($value != NULL){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
			$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . $query.'/');
	} else {
	$formIronAge->populate($data);
	}
	}
	}
	/** Display the greek and roman provincial pages
	*/	
	public function greekromanAction() {
	$form = new GreekRomanSearchForm();
	$this->view->form = $form;
	$values = $form->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($form->isValid($data)) {
		$params = array_filter($data);
		unset($params['submit']);
		unset($params['action']);
		unset($params['controller']);
		unset($params['module']);
		unset($params['page']);
		unset($params['csrf']);

		$where = array();
        foreach($params as $key => $value)
        {
			if($value != NULL){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
			$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . $query . '/');
	}  else {
	$form->populate($data);
	}
	}
	}

	/** Display the results after filtering
	*/	
	public function resultsAction() {
	ini_set("memory_limit","256M");
	$date = Zend_Date::now()->toString('yyyy-MM-ddHHmm');
	$data = $this->_getAllParams();
	$params = array_filter($data);
	$contexts = array('csv','hero','kml');
	$kml = array('kml');
	if(!in_array($this->_helper->contextSwitch->getCurrentContext(),$contexts))	{	
	$this->view->headTitle('Search results: ');
	unset($params['submit']);
	unset($params['action']);
	unset($params['controller']);
	unset($params['module']);
	unset($params['csrf']);
	unset($params['database']);
	unset($params['record']);
    if(isset($params['recordby']) && !isset($params['recorderID'])){
    	$params['recorderID'] = $params['recordby'];
    }	
	if(count($params) == 0 || (count($params) == 1 && array_key_exists('page',$params))) {
	$this->_flashMessenger->addMessage('You didn\'t choose anything to search on, 
	so you might as well see all the data!');
	$this->_redirect('/database/artefacts/');
	}
	$finds = new Finds();
	$results = $finds->getSearchResultsAdvanced($params,$this->getRole());
	
		//unset($params['page']);
	$stringsearch = serialize($params);
	
	$queries = new Searches();
	$inserts = $queries->insertResults($stringsearch);
	$where = array();
    foreach($params as $key => $value) {
	$where[] = array( $key  => urlencode($value));
    }
    $_json = array('json');
    if(!in_array($this->_helper->contextSwitch->getCurrentContext(),$_json)) {
	$this->view->results = $results;
    } else  {
    $data = array('pageNumber' => $results->getCurrentPageNumber(),'total' => number_format($results->getTotalItemCount(),0),
    'itemsReturned' => $results->getCurrentItemCount(),'totalPages' => number_format($results->getTotalItemCount()/$results->getCurrentItemCount(),0));
	$this->view->data = $data;
	$findsjson = array();
	foreach($results as $k => $v) {
	$findsjson[$k] = $v;
	}
	$this->view->objects = array('object' => $findsjson);	
    }
	$this->view->params = $params;
	
	} else if(in_array($this->_helper->contextSwitch->getCurrentContext(),$kml)) {
	
	$records = new Search();
	
	$data = $records->getSearchResultsAdvanced($data,$this->getRole());
	$data = $this->unique_multi_array($data,'old_findID');
	$this->view->results = $data;
	$this->view->documentname = "resultsKMLexport_" . $this->getIdentityForForms() . $date . ".kml";
	} else {
	ini_set("memory_limit","256M");
	$records = new Search();
	$context = $this->_helper->contextSwitch->getCurrentContext();
	
	switch($context) {
	case($context == 'hero') :
		$results = $records->goGetTheHero($data,$this->getRole());
		$csvname = 'hero';
	break;
	default:
		$results = $records->getSearchResultsAdvanced($data,$this->getRole());
		$csvname = 'search';
	break;
	}
	
	$csv_terminated = "\n";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";
    $fields_cnt = count($results['0']);
	$row_cnt = count($results);
    $schema_insert = '';
    for ($i = 0; $i < 1; $i++)
    {
	foreach($results['0'] as $key => $value)
        {
        $l = $csv_enclosed . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed,
            stripslashes($key)) . $csv_enclosed;
        $schema_insert .= $l;
        $schema_insert .= $csv_separator;
		}
    } // end for
 
    $out = trim(substr($schema_insert, 0, -1));
    $out .= $csv_terminated;
	
	foreach($results as $object) {
	
	foreach($object as $key => $value){
	 
	$schema_insert = '';
	$schema_insert .= $csv_enclosed . 
					stripslashes(strip_tags(str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, str_replace('"','',$value)))) . $csv_enclosed;	$schema_insert .= $csv_separator;
	
	$out .= $schema_insert;
	
	}
	$out .= $csv_terminated;
	}
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: " . strlen($out));
	header("Content-type:application/vnd.ms-excel");
    header("Content-type: text/x-csv");
    //header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename="  .$csvname . "resultsCSVexport_" 
    . $this->getIdentityForForms() . $date . ".csv");
    echo $out;
    exit;
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
        foreach($params as $key => $value)
        {
			if($value != NULL){
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
	$this->_flashMessenger->addMessage('Your email has been sent to '.$recipient.'. Thank you for sending them some of our records.');
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
 	echo '<h2>Solr Service borked</h2>';
	echo '<p>Solr service not responding.</p>';
	} else {
	$form = new SolrForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$data = $this->_getAllParams();
	if ($form->isValid($data)) {
	$this->_redirect($this->view->url(array('module' => 'database',
	'controller' => 'search','action' => 'solrresults','q' => $data['q'])));
	} else {
	$form->populate($q);
	}
	}
	}	
	}
	/** Display the index page.
	*/		
	public function solrresultsAction(){
	if($this->_getParam('q',false)){
	$q = $this->_getParam('q');
	$limit = 20;
	$page = $this->_getParam('page');
	if(!isset($page)){
		$start = 0;
	} else {
		$start = ($page - 1) * 20;
	}
	$additionalParameters = array( 
	'sort' => 'created desc', 
	'facet' => 'true', 
	'hl' => 'true',
	'facet.field' => array(
	'objectType',
	'broadperiod',
	'material',
	'county'
	), 
	'facet.mincount' => '1',
	'facet.sort' => 'true',
	'facet.missing' => 'false'
	);
	
	$allowed = array('fa','flos','admin');
	if(!in_array($this->getRole(),$allowed)) {
	$additionalParameters['fq'] = 'secwfstage:[3 TO *]';
	}

	$results = $this->_solr->search($q, $start, $limit,$additionalParameters);

	$numFound = $results->response->numFound;
	$facetPeriod = $results->facet_counts->facet_fields->broadperiod;
	$facetObjects = $results->facet_counts->facet_fields->objectType;
	$facetMaterials = $results->facet_counts->facet_fields->material;
	$facetCounties = $results->facet_counts->facet_fields->county;
	
//	Zend_Debug::dump($facets,'FACET');
	$facetlistPeriod = NULL;
	foreach($facetPeriod as $k=> $v){
	$facetlistPeriod[$k] = $v;
	}
	
	$facetlistObject = NULL;
	foreach($facetObjects as $k=> $v){
	$facetlistObject[$k] = $v;
	}
	
	$facetMaterial = NULL;
	foreach($facetMaterials as $k=> $v){
	$facetMaterial[$k] = $v;
	}
	
	$facetCounty = NULL;
	foreach($facetCounties as $k=> $v){
	$facetCounty[$k] = $v;
	}
	if(!is_null($facetlistObject)){
	$this->view->facetlistObject = array_slice($facetlistObject,0,15);
	}
	$this->view->facetlistPeriod = $facetlistPeriod;
		if(!is_null($facetMaterial)){
	$this->view->facetlistMaterial = array_slice($facetMaterial,0,15);
		}
		if(!is_null($facetCounty)){
	$this->view->facetlistCounties = array_slice($facetCounty,0,15);
		}
	$data = NULL;
	foreach($results->response->docs as $doc){
		$fields = NULL;
	    foreach($doc as $key => $value){
	    	
	    	$fields[$key] = $value;
	    	
	    }
	    $data[] = $fields;
	}
	$pagination = array(    
	'page'          => $page, 
	'results' => $data,
	'per_page'      => $limit, 
    'total_results' => $numFound
	);
	
	$paginator = Zend_Paginator::factory($pagination['total_results']);
    $paginator->setCurrentPageNumber($pagination['page'])
              ->setItemCountPerPage($limit)
              ->setPageRange(20);
    $this->view->paginator = $paginator;
	$this->view->results = $data;
	} else {
		throw new Pas_Exception_Param('No query has been entered',500);
	}
	}
//EOS
}