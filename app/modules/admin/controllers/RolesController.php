<?php
/** Controller for adding and manipulating user roles
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_RolesController extends Pas_Controller_ActionAdmin {

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
	$roles = new StaffRoles();
	$this->view->roles = $roles->getValidRoles();	
	}
	/** View a role's details
	*/			
	public function roleAction(){
	$roles = new StaffRoles();
	$this->view->roles = $roles->getRole($this->_getParam('id'));
	$this->view->members = $roles->getMembers($this->_getParam('id'));
	}
	/** Add a role
	*/			
	public function addAction(){
	$form = new StaffRoleForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$staffroles = new Staffroles();
	$insertData = array(
	'role' => $form->getValue('role'),
	'description' => $form->getValue('description'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms()
	);
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$staffroles->insert($insertData);
	$this->_flashMessenger->addMessage('A new staff role has been created.');
	$this->_redirect($this->_redirectUrl . 'roles/');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a role
	*/		
	public function editAction() {
	$form = new StaffRoleForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$staffroles = new StaffRoles();
	$updateData = array('role' => $form->getValue('role'),'description' => $form->getValue('description'),'updated' => $this->getTimeForForms(), 'updatedBy' => $this->getIdentityForForms());
	$where = array();
	$where[] =  $staffroles->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $staffroles->update($updateData,$where);
	$this->_flashMessenger->addMessage($form->getValue('role') . '\'s details updated.');
	$this->_redirect($this->_redirectUrl . 'roles/');
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$staffroles = new StaffRoles();
	$roles = $staffroles->fetchRow('id=' . $id);
	$form->populate($roles->toArray());
	}
	}
	}	
	/** Delete a role
	*/		
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$roles = new StaffRoles();
	$where = 'id = ' . $id;
	$roles->delete($where);
	}
	$this->_flashMessenger->addMessage('Role information deleted! This cannot be undone.');
	$this->_redirect($this->_redirectUrl);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$roles = new StaffRoles();
	$this->view->role = $roles->fetchRow('id =' . $id);
	}
	}
	}
}
	