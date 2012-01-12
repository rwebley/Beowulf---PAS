<?php
/** Controller for getting complaints based form and submitting it
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GetInvolved_ComplaintsController extends Pas_Controller_Action_Admin {

	/** Initialise the ACL and contexts
	*/ 
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->messages = $this->_flashMessenger->getMessages();
		$this->_helper->acl->allow('public',null);
		}
		
	/** Submit complaints action
	*/ 
	public function indexAction() {

	$form = new ComplaintsForm();
	$this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) 	 {
    if ($form->isValid($form->getValues())) {
    $insertData = $form->getValues();
    $cc = array($form->getvalue('comment_author_email'), $form->getValue('comment_author'));
	$this->_helper->mailer($insertData, 'complaint', $cc );
	$messages = new Messages();
	$insert = $messages->insert($data);
	$this->_flashMessenger->addMessage('Your complaint has been submitted');
	$this->_redirect('getinvolved/complaints/');
	} else {
	$this->_flashMessenger->addMessage('There are problems with your submission');
	$form->populate($form->getValues());
	}
	}
	}
}