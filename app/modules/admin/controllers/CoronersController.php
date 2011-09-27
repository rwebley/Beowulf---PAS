<?php
/** Controller for administering coroner details
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_CoronersController extends Pas_Controller_Action_Admin {
	
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$flosActions = array('index',);
	$this->_helper->_acl->allow('flos',$flosActions);
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	
	protected $_redirectUrl = 'admin/coroners/';
	/** Display index page of coroners
	*/	
	public function indexAction() {
	$coroners = new Coroners();
	$this->view->coroners = $coroners->getAll($this->_getParam('page'));
	}
	/** Add a new coroner
	*/		
	public function addAction() {
	$form = new CoronerForm();
	$form->submit->setLabel('Add a new coroner');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('address_1') . ',' . $form->getValue('address_2') . ','
	. $form->getValue('town') . ',' . $form->getValue('county') . ',' 
	. $form->getValue('postcode') . ',' . $form->getValue('country');
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$long = $coords['lon']; 
		$pm = new Pas_Service_Geoplanet();
		$place = $pm->reverseGeoCode($lat,$lon);
		$woeid = $place['woeid'];
	} else {
		$lat = NULL;
		$lon = NULL;
		$woeid = NULL;
	}
	$coroners = new Coroners();
	$updateData = array();
	$updateData['firstname'] = $form->getValue('firstname');
	$updateData['lastname'] = $form->getValue('lastname');
	$updateData['email'] = $form->getValue('email');
	$updateData['address_1'] = $form->getValue('address_1');
	$updateData['address_2'] = $form->getValue('address_2');
	$updateData['town'] = $form->getValue('town');
	$updateData['county'] = $form->getValue('county');
	$updateData['country'] = $form->getValue('country');
	$updateData['postcode'] = $form->getValue('postcode');
	$updateData['longitude'] = $lon;
	$updateData['latitude'] = $lat;
	$updateData['region_name'] = $form->getValue('region_name');
	$updateData['telephone'] = $form->getValue('telephone');
	$updateData['fax'] = $form->getValue('fax');
	$updateData['created'] = $this->getTimeForForms();
	$updateData['createdBy'] = $this->getIdentityForForms();
	$updateData['woeid'] = $woeid;
	$insert = $coroners->insert($updateData);
	$this->_flashMessenger->addMessage('Coroner details created!');
	$this->_redirect($this->_redirectUrl);
	} else {
	$form->populate($formData);
	}
	}
	}
	
	/** Edit a coroner
	*/	
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new CoronerForm();
	$form->submit->setLabel('Save');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('address_1') . ',' . $form->getValue('address_2') . ','
	. $form->getValue('town') . ',' . $form->getValue('county') . ',' 
	. $form->getValue('postcode') . ',' . $form->getValue('country');
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$long = $coords['lon']; 
		$pm = new Pas_Service_Geoplanet();
		$place = $pm->reverseGeoCode($lat,$lon);
		$woeid = $place['woeid'];
	} else {
		$lat = NULL;
		$lon = NULL;
		$woeid = NULL;
	}
	$coroners = new Coroners();
	$updateData = array();
	$updateData['firstname'] = $form->getValue('firstname');
	$updateData['lastname'] = $form->getValue('lastname');
	$updateData['email'] = $form->getValue('email');
	$updateData['address_1'] = $form->getValue('address_1');
	$updateData['address_2'] = $form->getValue('address_2');
	$updateData['town'] = $form->getValue('town');
	$updateData['county'] = $form->getValue('county');
	$updateData['country'] = $form->getValue('country');
	$updateData['postcode'] = $form->getValue('postcode');
	$updateData['longitude'] = $lon;
	$updateData['latitude'] = $lat;
	$updateData['region_name'] = $form->getValue('region_name');
	$updateData['telephone'] = $form->getValue('telephone');
	$updateData['fax'] = $form->getValue('fax');
	$updateData['updated'] = $this->getTimeForForms();
	$updateData['updatedBy'] = $this->getIdentityForForms();
	$updateData['woeid'] = $woeid;
	$where = array();
	$where[] = $coroners->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$insert = $coroners->update($updateData,$where);
	$this->_flashMessenger->addMessage($form->getValue('firstname') . ' ' 
	. $form->getValue('lastname') . '\'s information updated!');
	$this->_redirect($this->_redirectUrl);
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$coroners = new Coroners();
	$coroner = $coroners->fetchRow('id ='.$id);
	$form->populate($coroner->toArray());
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Delete a coroner
	*/		
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$coroners = new Coroners();
	$where = 'id = ' . $id;
	$coroners->delete($where);
	}	
	$this->_flashMessenger->addMessage('Coroner\'s information deleted! This cannot be undone.');
	$this->_redirect($this->_redirectUrl);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$coroners = new Coroners();
	$this->view->coroner = $coroners->fetchRow('id =' . $id);
	}
	}
	}
}