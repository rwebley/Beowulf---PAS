<?php
/** Controller for managing latest news on the website
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_NewsController extends Pas_Controller_Action_Admin {	
	
	protected $_news;
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_news = new News();
	}
    
	const REDIRECT = '/admin/news/';
	
	/** Display an index of news stories
	*/		
	public function indexAction(){
	$this->view->news = $this->_news->getAllNewsArticlesAdmin($this->_getAllParams());
	}
	/** Add and geocode a news story
	*/		
	public function addAction(){
	$form = new NewsStoryForm();
	$form->submit->setLabel('Add story');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('primaryNewsLocation');
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
	$row = $this->_news->createRow();
	$row->title = $form->getValue('title');
	$row->summary = $form->getValue('summary');
	$row->contents = $form->getValue('contents');
	$row->author = $form->getValue('author');
	$row->contactTel = $form->getValue('contactTel');
	$row->contactEmail = $form->getValue('contactEmail');
	$row->contactName = $form->getValue('contactName');
	$row->keywords = $form->getValue('keywords');
	$row->golive = $form->getValue('golive');
	$row->publish_state = $form->getValue('publish_state');
	$row->datePublished = $this->getTimeForForms();
	$row->primaryNewsLocation = $address;
	$row->latitude = $lat;
	$row->longitude = $long;
	$row->createdBy = $this->getIdentityForForms();
	$row->created = $this->getTimeForForms();
	$row->save();
	$this->_flashMessenger->addMessage('News story created!');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a news story
	*/			
	public function editAction(){
	$form = new NewsStoryForm();
	$form->submit->setLabel('Update story');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$address = $form->getValue('primaryNewsLocation');
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
	
	$row = $this->_news->fetchRow('id ='.$this->_getParam('id'));
	//Database rows created here ->
	$row->title = $form->getValue('title');
	$row->summary = $form->getValue('summary');
	$row->contents = $form->getValue('contents');
	$row->author = $form->getValue('author');
	$row->contactTel = $form->getValue('contactTel');
	$row->contactEmail = $form->getValue('contactEmail');
	$row->contactName = $form->getValue('contactName');
	$row->keywords = $form->getValue('keywords');
	$row->primaryNewsLocation = $address;
	$row->latitude = $lat;
	$row->longitude = $long;
	$row->updatedBy = $this->getIdentityForForms();
	$row->updated = $this->getTimeForForms();
	$row->golive = $form->getValue('golive');
	$row->publish_state = $form->getValue('publish_state');
	$row->datePublished = $this->getTimeForForms();
	
	//Save and redirect
	$row->save();
	$this->_flashMessenger->addMessage('News story information updated!');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$new = $this->_news->fetchRow('id='.$id);
	$form->populate($new->toArray());
	}
	}
	}
	/** Delete a news story
	*/		
	public function deleteAction(){
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = 'id = ' . $id;
	$this->_news->delete($where);
	$this->_flashMessenger->addMessage('Record deleted!');
	}
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->new = $this->_news->fetchRow('id='.$id);
	}
	}
	}

}