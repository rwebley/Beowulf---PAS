<?php
/** Controller for displaying the SMRs provided by NMR EH
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_SmrController extends Pas_Controller_ActionAdmin {
	/** Initialise the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('flos',null);
	$this->_helper->_acl->allow('hero',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	const REDIRECT = 'database/smr/';
	/** Index page for smrs
	*/
	public function indexAction() {
	
	$monumentName = $this->_getParam('monumentName');
	$county =$this->_getParam('county');
	$district = $this->_getParam('district');
	$parish = $this->_getParam('parish');
	$page = $this->_getParam('page');
	
	$smrs = new ScheduledMonuments();
	$this->view->smrs = $smrs->getSmrs($page,$county,$district,$parish,$monumentName);
	
	$form = new SAMFilterForm();
	$this->view->form = $form;
	
	if(!is_null($county)) {
	$districts = new Places();
	$district_list = $districts->getDistrictList($county);
	$form->district->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
	
	if(!is_null($district)) {
	$parish_list = $districts->getParishList($district);
	$form->parish->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
	}
	}
	
	$form->monumentName->setValue($monumentName);
	$form->district->setValue($district);
	$form->parish->setValue($parish);
	$form->county->setValue($county);
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
        foreach($params as $key => $value) {
			if($value != NULL){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
	$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . 'index/' . $query.'/');
	} else{
	$form->populate($formData);
	}
	}
	}
	/** Individual SMR record
	*/	
	public function recordAction() {
	if($this->_getParam('id',false)) {
	$smrs = new ScheduledMonuments();
	$this->view->smrs = $smrs->getSmrDetails($this->_getParam('id'));
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** SMR by WOEID
	*/	
	public function bywoeidAction() {
	if($this->_getParam('number',false)) {
	$this->view->woeid = $this->_getParam('number');
	$smrs = new ScheduledMonuments();
	$this->view->smrs = $smrs->getSmrsByWoeid($this->_getParam('number'),$this->_getParam('page'));
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
}