<?php
/** Controller for adding and manipulating research and topics
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_ResearchController extends Pas_Controller_Action_Admin {
	
	protected $_research;
	
	protected $_suggested;
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_research = new ResearchProjects();
	$this->_suggested = new Suggested();
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}
	/** Set up the redirect baseurl
	 * 
	 * @var string REDIRECT
	*/		
	const REDIRECT = '/admin/research/';

	public function indexAction(){
	$this->view->research = $this->_research->getAllProjects($this->_getAllParams());
	}
	/** Add a new research topic
	*/		
	public function addAction(){
	$form = new ResearchForm();
	$form->submit->setLabel('Add a project');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insertData = array(
	'title' => $form->getValue('title'),
	'description' => $form->getValue('description'),
	'investigator' => $form->getValue('investigator'),
	'startDate' => $form->getValue('startDate'),
	'endDate' => $form->getValue('endDate'),
	'level' => $form->getValue('level'),
	'valid' => $form->getValue('valid'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms());
	foreach ($insertData as $key => $value) {
		if (is_null($value) || $value=="") {
			unset($insertData[$key]);
		}
	}
	$this->_research->insert($insertData);
	$this->_flashMessenger->addMessage('A new research project has been entered.');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a research project
	*/	
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new ResearchForm();
	$form->submit->setLabel('Submit changes to project');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$updateData = array(
	'title' => $form->getValue('title'),
	'description' => $form->getValue('description'),
	'investigator' => $form->getValue('investigator'),
	'startDate' => $form->getValue('startDate'),
	'endDate' => $form->getValue('endDate'),
	'level' => $form->getValue('level'),
	'valid' => $form->getValue('valid'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms()
	);
	foreach ($updateData as $key => $value) {
		if (is_null($value) || $value=="") {
		unset($updateData[$key]);
		}
	}
	$where =  $this->_research->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_research->update($updateData,$where);
	$this->_flashMessenger->addMessage('Research project details updated.');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$res = $this->_research->fetchRow('id='.$id);
	$form->populate($res->toArray());
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}	
	}
	/** Add a suggested research topic
	*/		
	public function addsuggestedAction() {
	$form = new SuggestedForm();
	$form->submit->setLabel('Add a project');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insertData = array(
	'title' => $form->getValue('title'),
	'description' => $form->getValue('description'),
	'period' => $form->getValue('period'),
	'level' => $form->getValue('level'),
	'taken' => $form->getValue('taken'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms());
	foreach ($insertData as $key => $value) {
	if (is_null($value) || $value=="") {
		unset($insertData[$key]);
		}
	}
	$this->_suggested->insert($insertData);
	$this->_flashMessenger->addMessage('A new suggested research project has been entered.');
	$this->_redirect(self::REDIRECT . 'suggested/');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** List all suggested topics
	*/	
	public function suggestedAction(){
	$this->view->suggested = $this->_suggested->getAll($this->_getAllParams());
	}
	
	/** Edit a suggested topic
	*/		
	public function editsuggestedAction() {
	if($this->_getParam('id',false)) {
	$form = new SuggestedForm();
	$form->submit->setLabel('Submit changes to project');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$updateData = array(
	'title' => $form->getValue('title'),
	'description' => $form->getValue('description'),
	'period' => $form->getValue('period'),
	'startDate' => $form->getValue('startDate'),
	'endDate' => $form->getValue('endDate'),
	'level' => $form->getValue('level'),
	'taken' => $form->getValue('taken'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms()
	);
	foreach ($insertData as $key => $value) {
	if (is_null($value) || $value=="") {
		unset($insertData[$key]);
		}
	}
	$where =  $this->_suggested->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_suggested->update($updateData,$where);
	$this->_flashMessenger->addMessage('Suggested research project details updated.');
	$this->_redirect(self::REDIRECT . 'suggested/');
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$res = $this->_suggested->fetchRow('id=' . $id);
	$form->populate($res->toArray());
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}	
	}
	/** Delete a suggested topic
	*/		
	public function deletesuggestedAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$suggested = new Suggested();
	$where = $suggested->getAdapter()->quoteInto('id = ?', $id);
	$suggested->delete($where);
	$this->_flashMessenger->addMessage('Record deleted!');
	$this->_redirect(self::REDIRECT . 'suggested/');
	}
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$suggested = new Suggested();
	$this->view->suggest = $suggested->fetchRow('id=' . $id);
	}
	}
	}
	
}
