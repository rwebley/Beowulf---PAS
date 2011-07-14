<?php
/** Controller for index page for database module
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_IndexController extends Pas_Controller_Action {
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('public',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	}
	/** Setup index page
	*/	
	public function indexAction() {
	$content = new Content();
	$this->view->contents = $content->getFrontContent('database');
	
	$thumbs = new Slides();
	$this->view->thumbs = $thumbs->getLast10Thumbnails(4);
	
	$finds = new Finds();
	$this->view->counts = $finds->getCountAllFinds();

	$recent = new Logins();
	$this->view->logins = $recent->todayVisitors();
	
	$form = new WhatWhereWhenForm();
	$form->setMethod('get');
	$this->view->form = $form;
	$values = $form->getValues();
	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
	$data = $this->_getAllParams();
	if ($form->isValid($data)) {
	$params = array_filter($data);
	unset($params['submit']);
	unset($params['action']);
	unset($params['controller']);
	unset($params['module']);
	unset($params['page']);
	unset($params['csrf']);
	$where = array();
	foreach($params as $key => $value) {
	if($value != NULL){
	$where[] = $key . '/' . urlencode($value);
	}
	}
	$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect('/database/search/results/'.$query.'/');
	} else {
	$form->populate($data);
	}
	}	
	}
}