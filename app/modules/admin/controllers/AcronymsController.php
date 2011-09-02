<?php
/** Controller for manipulating acronyms on the system
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_AcronymsController extends Pas_Controller_ActionAdmin {
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    const REDIRECT = '/admin/acronyms/';
	/** Display all the acronyms
	*/ 
	public function indexAction(){
	$acronyms = new Acronyms();
	$this->view->acronyms = $acronyms->getAllAcronyms($this->_getAllParams());
	}
	/** Add a new acronym
	*/ 	
	public function addAction()	{
	$form = new AcronymForm();
	$form->details->setLegend('Add an acronym: ');
	$form->submit->setLabel('Add new acronym');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$acros = new Acronyms();
	$data = $form->getValues();
	$acros->add($data);
	$this->_flashMessenger->addMessage('A new acronym has been created.');
	$this->_redirect(self::REDIRECT);
	} else 	{
	$form->populate($formData);
	}
	}
	}
	
	/** Edit an acronym
	*/ 	
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new AcronymForm();
	$form->details->setLegend('Edit an acronym: ');
	$form->submit->setLabel('Save new acronym details');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$acros = new Acronyms();
	$updateData = array(
	'abbreviation' => $form->getValue('abbreviation'),
	'expanded' => $form->getValue('expanded'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms()
	);
	$where = array();
	$where[] =  $acros->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $acros->update($updateData,$where);
	$this->_flashMessenger->addMessage('Acronym details updated.');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$acros = new Acronyms();
	$acro = $acros->fetchRow('id='.$id);
	$this->view->acro = $acro->toArray();
	$form->populate($acro->toArray());
	}
	}
	} else {
		throw new Exception($this->_missingParameter);
	}
	}
	/** Delete an acronym
	*/ 	
	public function deleteAction(){
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$acros = new Acronyms();
	$where = 'id = ' . $id;
	$acros->delete($where);
	}
	$this->_redirect(self::REDIRECT);
	$this->_flashMessenger->addMessage('Record deleted!');
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$acros = new Acronyms();
	$this->view->acro = $acros->fetchRow('id='.$id);
	}
	}
	}	
	

}