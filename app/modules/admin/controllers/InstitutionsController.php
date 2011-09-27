<?php
/** Controller for adding and manipulating institutional data
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_InstitutionsController extends Pas_Controller_Action_Admin {

	protected $_redirectUrl = 'admin/contacts/';
	/** Set up the ACL and contexts
	*/		
	public function init() {
		$flosActions = array('index',);
 		$this->_helper->_acl->allow('flos',$flosActions);
		$this->_helper->_acl->allow('fa',null);
 		$this->_helper->_acl->allow('admin',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
    
  	/** Display the index page
	*/	  
	public function indexAction() {
	$institutions = new Institutions();
	$this->view->insts = $institutions->getValidInsts($this->_getAllParams());
	}
	/** Add an institution
	*/	
	public function addAction() {
	$form = new InstitutionForm();
	$form->details->setLegend('Add institution details: ');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insts = new Institutions();
	$insertData = array(
	'institution' => $form->getValue('institution'),
	'description' => $form->getValue('description'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms()
	);
	$insts->insert($insertData);
	$this->_flashMessenger->addMessage('A new recording institution has been created.');
	$this->_redirect($this->_redirectUrl . 'institutions/');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit an institution
	*/	
	public function editAction() {
	$form = new InstitutionForm();
	$form->details->setLegend('Edit institution details: ');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insts = new Institutions();
	$updateData = array(
	'institution' => $form->getValue('institution'),
	'description' => $form->getValue('description'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms()
	);
	$where = array();
	$where[] =  $insts->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $insts->update($updateData,$where);
	$this->_flashMessenger->addMessage($form->getValue('institution') . '\'s details updated.');
	$this->_redirect($this->_redirectUrl . 'institutions/');
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$insts = new Institutions();
	$insts = $insts->fetchRow('id='.$id);
	$this->view->inst = $insts->toArray();
	$form->populate($insts->toArray());
	}
	}
	}
	/** View institutional details
	*/	
	public function institutionAction() {
	$institutions = new Institutions();
	$this->view->inst = $institutions->getInst($this->_getParam('id'));
	$users = new Users();
	$this->view->members = $users->getMembersInstitution($this->_getParam('id'));
	}
}