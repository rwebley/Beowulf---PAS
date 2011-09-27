<?php 
/** Controller for CRUD of references on database
 * @todo This is very slow due to number of queries. Maybe change to ajax calls?
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_ReferencesController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/
	public function init() {
	$publicActions = array('index');
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('member',array('add','edit','delete'));
	$this->_helper->_acl->allow('public',$publicActions);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}
	/** Constant for redirect url
	*/	
	const REDIRECT = 'database/artefacts/record/id/';
	/** No direct access to the references controller, redirect applied.
	*/
	public function indexAction(){
	$this->_redirect('/database/publications');
	}
	/** Adding a reference
	*/
	public function addAction(){
	$form = new ReferenceFindForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insertData = array();
	$insertData['pages_plates'] = $form->getValue('pages_plates');
	$insertData['reference'] = $form->getValue('reference');
	$insertData['pubID'] = $form->getValue('pubID');
	$insertData['findID'] = $this->_getParam('secID');
	$insertData['created'] = $this->getTimeForForms();
	$insertData['createdBy'] = $this->getIdentityForForms();
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
	 }
	$bibliography = new Bibliography();
	$insert = $bibliography->insert($insertData);
	$findID = $this->_getParam('findID');
	$this->_flashMessenger->addMessage('A new reference work has been added to this record');
	$this->_redirect(self::REDIRECT . $findID);
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a reference entity
	 * @todo move the update function to model
	*/
	public function editAction() {
	$form = new ReferenceFindForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$updateData = array();
	$updateData['pages_plates'] = $form->getValue('pages_plates');
	$updateData['reference'] = $form->getValue('reference');
	$updateData['updated'] = $this->getTimeForForms();
	$updateData['updatedBy'] = $this->getIdentityForForms();
	foreach ($updateData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($updateDataData[$key]);
      }
	 }
	$where = array();
	$bibs = new Bibliography();
	$where =  $bibs->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $bibs->update($updateData,$where);
	$findID = $this->_getParam('findID');
	$this->_flashMessenger->addMessage('Reference details updated!');
	$this->_redirect(self::REDIRECT . $findID);	
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$bibs = new Bibliography();
	$bib = $bibs->fetchFindBook($id);
	$form->populate($bib['0']);
	}
	}
	}
	/** Delete a reference
	*/
	public function deleteAction() {
	if($this->_getParam('id',false)) {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$findID = (int)$this->_request->getPost('findID');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$bibs = new Bibliography();
	$where = array();
	$where =  $bibs->getAdapter()->quoteInto('id = ?', $id);
	$bibs->delete($where);
	$this->_flashMessenger->addMessage('Reference deleted!');
	$this->_redirect(self::REDIRECT . $findID);	
	}
	} else {
	$id = (int)$this->_getParam('id');
	if ($id > 0) {
	$this->view->id = $id;
	$bibs = new Bibliography();
	$this->view->bib = $bibs->fetchFindBook($id);
	}
	}
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}
	
}