<?php
/** Controller for displaying information about heritage crime.
 * Not comfortable with keeping this data.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_HeritageController extends Pas_Controller_Action_Admin {
	
	const REDIRECT = '/database/heritage/';	
	
	protected $_crimes;
	
	/** Setup the  the ACL.
	*/	
	public function init() {
	$this->_helper->_acl->allow('flos',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_crimes = new HeritageCrime();
    }
	/** Display the index page
	*/
	public function indexAction() {
	$this->view->crimes = $this->_crimes->getCrimesAgainstHeritage($this->_getAllParams());
	}
	/** Display the individual crime
	*/	
	public function crimeAction() {
	if($this->_getParam('id',false)) {
	$this->view->crime = $this->_crimes->getCrime($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);	
	}
	}
	/** Add a heritage crime
	*/	
	public function addAction() {
	$secuid = $this->secuid();
	$form = new HeritageCrimeForm(); 
	$form->submit->setLabel('Submit crime');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$ngr = $form->getValue('gridref');
	if(!is_null($ngr) || ($ngr != '')) {
	$results = $this->GridCalc($form->getValue('gridref'));
	$fourFigure = $this->FourFigure($form->getValue('gridref'));
	$place = new Pas_Service_Geo_Geoplanet($this->_appid);
	$findelevation = $place->getElevation(NULL,$results['Latitude'],$results['Longitude']);
	$findwoeid = $place->reverseGeoCode($results['Latitude'],$results['Longitude']);
	$elevation = $findelevation['elevation'];
	$woeid = $findwoeid['woeid'];
	} else {
	$woeid = NULL;
	$elevation = NULL;		
	}
	$insertData = array(
		'subject' => $form->getValue('subject'),
		'reporterID' => $form->getValue('reporterID'),
		'incidentDate' => $form->getValue('incidentDate'),
		'crimeType' => $form->getValue('crimeType'),
		'gridref' => $form->getValue('gridref'),
		'latitude' => $results['Latitude'],
		'longitude' => $results['Longitude'],
		'easting' => $results['Easting'],
		'northing' => $results['Northing'],	  
		'map10k' => $results['Tenk'],
		'map25k' => $results['2pt5K'],
		'fourFigure' => $fourFigure,
		'description' => $form->getValue('description'),
		'created' => $this->getTimeForForms(), 
		'createdBy' => $this->getIdentityForForms(),
		'woeid' => $woeid,
		'elevation' => $elevation,
		'samID' => $form->getValue('samID'),
		'evaluation' => $form->getValue('evaluation'),
		'reliability' => $form->getValue('reliability'),
		'county' => $form->getValue('county'),
		'district' => $form->getValue('district'),
		'parish' => $form->getValue('parish'),
		'intellEvaluation' => $form->getValue('intellEvaluation'),
		'reportSubject' => $form->getValue('reportSubject'),
		'subjectDetails' => $form->getValue('subjectDetails'),
		'reportingPerson' => $form->getValue('reportingPerson')
		);
	foreach ($insertData as $key => $value) {
    if (is_null($value) || $value=="") {
    unset($insertData[$key]);
    }
	}
	$this->_crimes->insert($insertData);
	$this->_redirect(self::REDIRECT);
	$this->_flashMessenger->addMessage('A new crime has been entered.');
	} else {
	$form->populate($formData);
	if(!is_null($formData['county'])) {
	$districts = new Places();
	$district_list = $districts->getDistrictList($formData['county']);
	if(count($district_list)) {
	$form->district->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
	}
	if(!is_null($formData['district'])) {
	$parish_list = $districts->getParishList($formData['district']);
	$form->parish->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
	}
	}
	}
	}
	} 

	/** Edit crime details
	 * @todo messy rewrite
	*/
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new HeritageCrimeForm();
	$form->submit->setLabel('Update crime');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$ngr = $form->getValue('gridref');
	if(!is_null($ngr) || ($ngr != "")){
	$results = $this->GridCalc($ngr);
	$fourFigure = $this->FourFigure($ngr);
	$place = new Pas_Service_Geo_Geoplanet($this->_appid);
	$findelevation = $place->getElevation(NULL,$results['Latitude'],$results['Longitude']);
	$findwoeid = $place->reverseGeoCode($results['Latitude'],$results['Longitude']);
	$elevation = $findelevation['elevation'];
	$woeid = $findwoeid['woeid'];
	} else {
	$elevation = NULL;
	$woeid = NULL;	
	}
	$updateData = array(
	'subject' => $form->getValue('subject'),
		'reporterID' => $form->getValue('reporterID'),
		'incidentDate' => $form->getValue('incidentDate'),
		'crimeType' => $form->getValue('crimeType'),
		'gridref' => $form->getValue('gridref'),
		'latitude' => $results['Latitude'],
		'longitude' => $results['Longitude'],
		'easting' => $results['Easting'],
		'northing' => $results['Northing'],	  
		'map10k' => $results['Tenk'],
		'map25k' => $results['2pt5K'],
		'fourFigure' => $fourFigure,
		'description' => $form->getValue('description'),
		'updated' => $this->getTimeForForms(), 
		'updatedBy' => $this->getIdentityForForms(),
		'woeid' => $woeid,
		'elevation' => $elevation,
		'samID' => $form->getValue('samID'),
		'evaluation' => $form->getValue('evaluation'),
		'reliability' => $form->getValue('reliability'),
		'county' => $form->getValue('county'),
		'district' => $form->getValue('district'),
		'parish' => $form->getValue('parish'),
		'intellEvaluation' => $form->getValue('intellEvaluation'),
		'reportSubject' => $form->getValue('reportSubject'),
		'subjectDetails' => $form->getValue('subjectDetails'),
		'reportingPerson' => $form->getValue('reportingPerson')
	);
	foreach ($updateData as $key => $value) {
      if (is_null($value) || $value=="") {
       $updateData[$key] = NULL;
      }
    }
	$where = array();
	$where[] = $this->_crimes->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$this->_crimes->update($updateData,$where);
	$returnID = $this->_getParam('id');
	$this->_flashMessenger->addMessage('Crime details updated!');
	$this->_redirect(self::REDIRECT . 'crime/id/' . $returnID);
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$crime = $this->_crimes->getCrime($this->_getParam('id'));
	if($crime['0']['county'] != NULL) {
	$districts = new Places();
	$district_list = $districts->getDistrictList($crime['0']['county']);
	$form->district->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
	if($crime['0']['district'] != NULL) {
	$parish_list = $districts->getParishList($crime['0']['district']);
	$form->parish->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
	}
	}
	$form->populate($formData);
	}
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$crime = $this->_crimes->getCrime($this->_getParam('id'));
	if(!is_null($crime['0']['county'])) {
	$districts = new Places();
	$district_list = $districts->getDistrictList($crime['0']['county']);
	$form->district->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
	if($crime['0']['district'] != NULL) {
	$parish_list = $districts->getParishList($crime['0']['district']);
	$form->parish->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
	}
	}
	$form->populate($crime['0']);
	}
	}
	} else {
		throw new Exception($this->_missingParameter);
	}
	}
	/** Delete a crime
	*/
	public function deleteAction() {
	if($this->_getParam('id',false)){
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = 'id = ' . $id;
	$this->_crimes->delete($where);
	$this->_flashMessenger->addMessage('Crime deleted.');
	}
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$crimes = new HeritageCrime();
	$this->view->crime = $crimes->fetchRow($crimes->select()->where('id = ?',$id));
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
		
}

