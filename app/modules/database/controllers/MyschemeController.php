<?php
/** Controller for displaying individual's finds on the database.
 * @todo finish module's functions and replace with solr functionality. Scripts suck the big one.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_MyschemeController extends Pas_Controller_Action_Admin {
	/**
	 * 
	 * @var object $_auth
	 */
	protected $_auth;
	
	public function init() {	
	$this->_helper->_acl->allow('member',null);
	$this->_auth = Zend_Registry::get('auth');
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
			 ->addContext('csv',array('suffix' => 'csv'))
 			 ->addContext('kml',array('suffix' => 'kml'))
  			 ->addContext('rss',array('suffix' => 'rss'))
			 ->addContext('atom',array('suffix' => 'atom'))
			 ->addActionContext('record', array('xml','json','rss','atom'))
 			 ->addActionContext('index', array('xml','json','rss','atom'))
             ->initContext();

    }
	const REDIRECT = '/database/myscheme/';
	/** Protected function for finding institution
	 * @todo needs abstracting out to extended controller's getAccount()
	 * @throws Pas_ParamException if no institution is attached
	 * 
	 */
	protected function getInstitution() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	} else {
		throw new Pas_ParamException('No institution attached');
	}
	}
	
	/** Protected function for finding user's image directory
	 * @todo needs abstracting out to extended controller's getAccount()
	 * @throws Pas_ParamException if no institution is attached
	 * 
	 */
	protected function getImageDir() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$imagedir = $user->imagedir;
	return $imagedir;
	} else {
		throw new Pas_ParamException('No image directory set up');
	}
	}

	/** Redirect as no root access allowed
	 * 
	 */	
	public function indexAction() {
	$this->_flashMessenger->addMessage('No access to index page');
	$this->_redirect('/database/');
	}
	
	/** List of user's finds that they have entered. Can be solr'd
	 * 
	 */		
	public function myfindsAction() {
	$form = new FindFilterForm();
	$this->view->form = $form;
	$page = $this->_getParam('page');
	$id = $this->getIdentityForForms();
	$finds = new Finds();
	$this->view->paginator = $finds->getMyFindsUser($id,$this->_getAllParams(),$page);
	$sort = $this->_getParam('sort') ? $this->_getParam('sort') : 'finds.id DESC'; 
	$this->view->params = $this->_getAllParams();
	$form = new FindFilterForm();
	$this->view->form = $form;
	$form->old_findID->setValue($this->_getParam('old_findID'));
	$form->objecttype->setValue($this->_getParam('objecttype'));
	$form->broadperiod->setValue($this->_getParam('broadperiod'));
	$form->county->setValue($this->_getParam('county'));
	
	if ($this->_request->isPost() && ($this->_getParam('submit') != NULL)) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
			$params = array_filter($formData);
			unset($params['submit']);
			unset($params['action']);
			unset($params['controller']);
			unset($params['module']);
			unset($params['page']);
			unset($params['csrf']);
			$where = array();
			foreach($params as $key => $value)
			{
				if($value != NULL){
				$where[] = $key . '/' . urlencode(strip_tags($value));
				}
			}
				$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect('database/myscheme/myfinds/' . $query.'/');
		
	} else  {
	$form->populate($formData);
	}
	}
	}
	/** Finds recorded by an institution assigned to the user
	 * 
	 */	
	public function myinstitutionAction() {
	$inst = $this->getInstitution();
	$this->view->inst = $inst;
	$page = $this->_getParam('page');
	$finds = new Finds();
	$this->view->paginator = $finds->getMyFindsInstitution($inst,$this->_getAllParams(),$page);
	$this->view->params = $this->_getAllParams();
	$form = new FindFilterForm();
	$this->view->form = $form;
	$form->old_findID->setValue($this->_getParam('old_findID'));
	$form->objecttype->setValue($this->_getParam('objecttype'));
	$form->broadperiod->setValue($this->_getParam('broadperiod'));
	$form->county->setValue($this->_getParam('county'));
	if ($this->_request->isPost() && ($this->_getParam('submit') != NULL)) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
			$params = array_filter($formData);
			unset($params['submit']);
			unset($params['action']);
			unset($params['controller']);
			unset($params['module']);
			unset($params['page']);
			unset($params['csrf']);
			$where = array();
			foreach($params as $key => $value)
			{
				if($value != NULL){
				$where[] = $key . '/' . urlencode(strip_tags($value));
				}
			}
				$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect('database/myscheme/myinstitution/' . $query.'/');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Display all images that a user has added.
	 * 
	 */		
	public function myimagesAction() {
	$images = new Slides();
	$this->view->paginator = $images->getMyImagesUser($this->getIdentityForForms(),$this->_getAllParams());
	$form = new ImageFilterForm();
	$this->view->form = $form;
	$form->old_findID->setValue($this->_getParam('old_findID'));
	$form->label->setValue($this->_getParam('label'));
	$form->broadperiod->setValue($this->_getParam('broadperiod'));
	$form->county->setValue($this->_getParam('county'));
	if ($this->_request->isPost() && ($this->_getParam('submit') != NULL)) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
			$params = array_filter($formData);
			unset($params['submit']);
			unset($params['action']);
			unset($params['controller']);
			unset($params['module']);
			unset($params['page']);
			unset($params['csrf']);
			$where = array();
			foreach($params as $key => $value)
			{
				if($value != NULL){
				$where[] = $key . '/' . urlencode(strip_tags($value));
				}
			}
				$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect('database/myscheme/myimages/' . $query.'/');
	} else {
	$form->populate($formData);
	}
	}
	}

}