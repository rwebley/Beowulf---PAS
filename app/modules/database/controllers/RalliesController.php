<?php
/** Controller for CRUD of rallies recorded by the Scheme. Note not attended!
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_RalliesController extends Pas_Controller_Action_Admin {
	
	protected $_cache, $_config, $_rallies;
	/** Initialise the ACL and contexts
	*/	
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');	
	$this->_helper->_acl->allow('public',array('index','rally','map'));
	$this->_helper->_acl->deny('public',array('addflo','delete','deleteflo'));
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addContext('csv',array('suffix' => 'csv'))
		->addContext('kml',array('suffix' => 'kml'))
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('rally', array('xml','json'))
		->addActionContext('index', array('xml','json','rss','atom'))
		->initContext();
	$this->_cache = Zend_Registry::get('rulercache');
	$this->_config = Zend_Registry::get('config');
	$this->_rallies = new Rallies();
    }
	/** Set up the url for redirect
	*/	
	const URL = '/database/rallies/';
	/** Create an array of years
	 * 
	 * @return array year list
	*/
	private function years(){
	$current_year = date('Y');
	//1998 is the first year recording for the Scheme was digital
	$years = range(1998, $current_year);
	$yearslist = array();
		foreach($years as $key => $value) {
		$yearslist[] = array('year' => $value);
	}
	return $yearslist;	
	}
	/** Index page for the list of rallies.
	*/	
	public function indexAction() {	
	$rallies = $this->_rallies->getRallyNames((array)$this->_getAllParams());	
	if(count($rallies)){
	$data = array(
	'pageNumber' => $rallies->getCurrentPageNumber(),
	'total' => number_format($rallies->getTotalItemCount(),0),
	'itemsReturned' => $rallies->getCurrentItemCount(),
	'totalPages' => number_format($rallies->getTotalItemCount()/$rallies->getCurrentItemCount(),0));
	$this->view->data = $data;
	}
	$contexts = array('json');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$ralliesa = array();
	foreach($rallies as $r => $v){
	$ralliesa['rally'][$r] = $v;
	}
	$this->view->rallies = $ralliesa;
	} else {
	$this->view->rallies = $rallies;
	$this->view->years = $this->years();
	}
	}	
	/** Individual rally details
	*/
	public function rallyAction() {
	if($this->_getParam('id',false)){
	$rallies = $this->_rallies->getRally($this->_getParam('id'));
	if(count($rallies)) {
	$this->view->rallies = $rallies;
	$attending = new RallyXFlo();
	$this->view->atts = $attending->getStaff($this->_getParam('id'));
	$slides = new Slides();
	$this->view->slides = $slides->getLast12ThumbnailsRally(4,$this->_getParam('id'));
	} else {
	throw new Exception('No rally exists with that id');
	}
	} else {
	throw new Exception($this->parameterMissing);
	}
	}

	/** Add a new rally
	 * @todo move functionality to model
	*/
	public function addAction() {
	$form = new RallyForm();
	$form->submit->setLabel('Add a new rally');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$results = $this->GridCalc($form->getValue('gridref'));
	$fourFigure = $this->FourFigure($form->getValue('gridref'));
	$insertData = array(
	'rally_name' => $form->getValue('rally_name'),
	'organiser' => $form->getValue('organiser'),
	'county' => $form->getValue('county'),
	'district' => $form->getValue('district'),
	'parish' => $form->getValue('parish'),
	'gridref' => $form->getValue('gridref'),
	'easting' => $results['Easting'],
	'northing' => $results['Northing'],
	'latitude' => $results['Latitude'],
	'longitude' => $results['Longitude'],
	'map10k' => $results['Tenk'],
	'map25k' => $results['2pt5K'],
	'fourFigure' => $fourFigure,
	'comments' => $form->getValue('comments'),
	'record_method' => $form->getValue('record_method'),
	'date_from' => $form->getValue('date_from'),
	'date_to' => $form->getValue('date_to'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
	);
	$insert = $this->_rallies->insert($insertData);
	$this->_cache->remove('rallydd');
	$this->_redirect(self::URL . 'rally/id/' . $insert);
	$this->_flashMessenger->addMessage('Details for ' . $form->getValue('rally_name') 
	. ' have been created!');
	} else  {
	$form->populate($formData);
	}
	}
	}
	/** Edit individual rally details
	*/	
	public function editAction() {
	if($this->_getParam('id',false)){
	$form = new RallyForm();
	$form->submit->setLabel('Update details');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$results = $this->GridCalc($form->getValue('gridref'));
	$fourFigure = $this->FourFigure($form->getValue('gridref'));
	$updateData = array(
	'rally_name' => $form->getValue('rally_name'),
	'organiser' => $form->getValue('organiser'),
	'county' => $form->getValue('county'),
	'district' => $form->getValue('district'),
	'parish' => $form->getValue('parish'),
	'gridref' => $form->getValue('gridref'),
	'easting' => $results['Easting'],
	'northing' => $results['Northing'],
	'latitude' => $results['Latitude'],
	'longitude' => $results['Longitude'],
	'map10k' => $results['Tenk'],
	'map25k' => $results['2pt5K'],
	'fourFigure' => $fourFigure,
	'comments' => $form->getValue('comments'),
	'record_method' => $form->getValue('record_method'),
	'date_from' => $form->getValue('date_from'),
	'date_to' => $form->getValue('date_to'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$where = array();
	$where[] = $this->_rallies->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_rallies->update($updateData,$where);
	$this->_cache->remove('rallydd');
	$this->_flashMessenger->addMessage('Rally information updated!');
	$this->_redirect(self::URL . 'rally/id/' . $this->_getParam('id'));
	} else {
	if($formData['district'] != NULL) {
	$districts = new Places();
	$district_list = $districts->getDistrictList($formData['county']);
	$form->district->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
	$parish_list = $districts->getParishList($formData['district']);
	$form->parish->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
	}
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$rallies = new Rallies();
	$rally = $rallies->fetchRow('id='.$id);
	if(count($rally)) {
	$form->populate($rally->toArray());
	} else {
	throw new Exception($this->_nothingFound);
	}

	if($rally['district'] != NULL) {
	$districts = new Places();
	$district_list = $districts->getDistrictList($rally['county']);
	$form->district->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
	$parish_list = $districts->getParishList($rally['district']);
	$form->parish->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
	}
	if($rally['organiser'] != NULL) {
	$organisers = new Peoples();
	$organisers = $organisers->getName($rally['organiser']);
	foreach($organisers as $organiser) {
	$form->organisername->setValue($organiser['term']);
	}
	}
	}
	}
	} else {
		throw new Exception($this->_missingParameter);
	}
	}
	/** Delete rally details
	*/
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$rallies = new Rallies();
	$where = 'id = ' . $id;
	$rallies->delete($where);
	$this->_cache->remove('rallydd');
	$this->_flashMessenger->addMessage('Record for rally deleted!');
	}
	$this->_redirect(self::URL);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$rallies = new Rallies();
	$this->view->rally = $rallies->fetchRow('id=' . $id);
	}
	}
	}
	/** Add a flo to a rally as attending
	*/	
	public function addfloAction() {
	if($this->_getParam('id',false)) {
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() . '/js/JQuery/ui.datepicker.js',
	$type='text/javascript');
	$this->view->headLink()->appendStylesheet($this->view->baseUrl() . '/css/ui.datepicker.css');
	$form = new AddFloRallyForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$rallies = new RallyXFlo();
	$rallyID = $this->_getParam('id');
	$insertData = array(
	'rallyID' => $rallyID,
	'staffID' => $form->getValue('staffID'),
	'dateFrom' => $form->getValue('dateFrom'),
	'dateTo' => $form->getValue('dateTo'),
	'created' => $this->getTimeForForms(),
	'createdBy' => $this->getIdentityForForms()
		);
	$insert = $rallies->insert($insertData);
	$this->_redirect(self::URL . 'rally/id/' . $rallyID);
	$this->_flashMessenger->addMessage('Finds Liaison Officer added to a rally');
	} else 
	{
	$form->populate($formData);
	}
	}
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}
	
	/** Delete an attending flo
	*/
	public function deletefloAction() {
	if ($this->_request->isPost()) {
	$staffID = (int)$this->_request->getPost('staffID');
	$rallyID = (int)$this->_request->getPost('rallyID');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes') {
	$rallies = new RallyXFlo();
	$where = array();
	$where[] = $this->rallies->getAdapter()->quoteInto('staffID = ?', (int)$staffID);
	$where[] = $rallies->getAdapter()->quoteInto('rallyID = ?', (int)$rallyID);
	$rallies->delete($where);
	$this->_flashMessenger->addMessage('Attending FLO for rally deleted!');
	}
	$this->_redirect(self::URL.'rally/id/'.$rallyID);
	} else {
	$rallyID = (int)$this->_request->getParam('rallyID');
	$staffID = (int)$this->_request->getParam('staffID');
	$rallies = new RallyXFlo();
	$where = array();
	$where[] = $rallies->getAdapter()->quoteInto('staffID = ?', (int)$staffID);
	$where[] = $rallies->getAdapter()->quoteInto('rallyID = ?', (int)$rallyID);

	$this->view->rally = $rallies->fetchRow($where);
	}
	}
	/** Display a map of attended rallies
	*/	
	public function mapAction() {
	$this->view->apikey = $this->_config->webservice->googlemaps->apikey;
	}

	}