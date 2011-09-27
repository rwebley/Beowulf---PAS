<?php
/** Controller for administering vacancies 
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_VacanciesController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$flosActions = array();
	$this->_helper->_acl->allow('flos',$flosActions);
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}
	/** 
	 * 
	 * @staticvar string Redirect url
	 */
	const REDIRECT = '/admin/vacancies';
	/** Setup the contexts by action and the ACL.
	*/	
	public function indexAction() {
	$currentvacs = new Vacancies();
	$this->view->currentvacs = $currentvacs->getJobsAdmin($this->_getParam('page'));
	}
	/** Add a new vacancy
	*/	
	public function addAction() {
	$form = new VacancyForm();
	$form->submit->setLabel('Add a new job...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$vacancies = new Vacancies();
	$insertdata = array(
	'title' => $form->getValue('title'),
	'salary' => $form->getValue('salary'),
	'specification' => $form->getValue('specification'),
	'regionID' => $form->getValue('regionID'),
	'status' => $form->getValue('status'),
	'live' => $form->getValue('live'),
	'expire' => $form->getValue('expire'),
	'created' => $this->getTimeForForms(), 
	'createdBy' => $this->getIdentityForForms());
	$vacancies->insert($insertdata);
	$this->_flashMessenger->addMessage('Vacancy details created: ' . $form->getValue('title'));
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit a vacancy
	*/		
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new VacancyForm();
	$form->submit->setLabel('Submit changes to vacancy details...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$vacs = new Vacancies();
	$where = array();
	$where[] = $vacs->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$insertdata = array(
	'title' => $form->getValue('title'),
	'salary' => $form->getValue('salary'),
	'specification' => $form->getValue('specification'),
	'regionID' => $form->getValue('regionID'),
	'status' => $form->getValue('status'),
	'live' => $form->getValue('live'),
	'expire' => $form->getValue('expire'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms());
	$vacs->update($insertdata,$where);
	$this->_flashMessenger->addMessage('Vacancy details updated!');
	$this->_redirect(self::REDIRECT);
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_getParam('id', 0);
	if ($id > 0) {
	$vacs = new Vacancies();
	$vac = $vacs->fetchRow('id = ' . $id);
	if(count($vac)) {
	$form->populate($vac->toArray());
	} else {
		throw new Pas_ParamException($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParamter);
	}
	}
	/** Delete a vacancy
	*/	
	public function deleteAction() {
	if ($this->_request->isPost()) {
	$id = (int)$this->_getParam('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$vacs = new Vacancies();
	$where = 'id = ' . (int)$id;
	$vacs->delete($where);
	$this->_flashMessenger->addMessage('Vacancy\'s information deleted! This cannot be undone.');
	}
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$vacs = new Vacancies();
	$this->view->vac = $vacs->fetchRow('id = ' . $id);
	}
	}
	}


}