<?php
/** Controller for displaying os opendata gazetteer
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_OsdataController extends Pas_Controller_Action_Admin {
	
	protected $_contexts;

	/** Set up the ACL and contexts
	*/		
	public function init(){
	$this->_helper->_acl->allow('public',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_contexts = array('xml','json');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('oneto50k',$this->_contexts)
		->addActionContext('index',$this->_contexts)
		->initContext();
	}
	
	const REDIRECT = 'database/osdata/';

	/** Display a paginated list of OS data points
	*/		
	public function indexAction() {
	$monumentName = $this->_getParam('monumentName');
	$county =$this->_getParam('county');
	$district = $this->_getParam('district');
	$parish = $this->_getParam('parish');
	$page = $this->_getParam('page');
	$smrs = new Osdata();
	$this->view->osdata = $smrs->getSmrs($page,$county,$district,$parish,$monumentName);
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
        foreach($params as $key => $value)
        {
			if($value != NULL){
            $where[] = $key . '/' . urlencode(strip_tags($value));
			}
        }
	$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . 'index/' . $query.'/');
	} else {
	$form->populate($formData);
	}
	}
	}

	/** Set up the one to 50k entry page
	*/		
	public function oneto50kAction(){
	if($this->_getParam('id',false)){
	$gazetteers = new Osdata();
	$this->view->gazetteer = $gazetteers->getGazetteer($this->_getParam('id'));	
	} else {
		throw new Pas_ParamException($this->_missingParameter);	
	}
	}
	
	public function gazetteerAction() {
	}
}

