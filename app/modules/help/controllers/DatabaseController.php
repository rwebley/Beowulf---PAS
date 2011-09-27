<?php
/** Controller for displaying information topics
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Help_DatabaseController extends Pas_Controller_Action_Admin {
	/** Setup the ACL.
	*/
	public function init()  {
	$this->_helper->acl->allow('public',null);
	}
	/** Display the help topics
	*/		
	public function indexAction() {
	$help = new Help();
	$this->view->help = $help->getTopics($this->_getParam('page'), $section = 'databasehelp');
	}
	/** Display an individual topic
	*/	
	public function topicAction() {
	$help = new Help();
	$this->view->help = $help->getTopic($section = 'databasehelp', $this->_getParam('id'));
	}
	/** Submit a request for help
	*/
	public function requesthelpAction() {
	$form = new HelpRequestForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$data = $form->getValues();
	$help = new Messages();
	$insert = $help->addrequest($data);
	$this->_flashMessenger->addMessage('Your enquiry has been submitted to the Scheme, 
	we will respond shortly');
	$this->_redirect('help/');
	} else  {
	$this->view->messages = $form->getMessages();
	$this->_flashMessenger->addMessage('There are problems with your submission');
	$form->populate($formData);
	}
	}
	}
}