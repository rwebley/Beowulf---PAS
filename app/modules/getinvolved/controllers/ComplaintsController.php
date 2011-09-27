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
	$spam = '{SPAM: Akismet checked}';
	$notspam = 'Akismet checked  - clean';
	$form = new ComplaintsForm();
	$this->view->form = $form;
		if ($this->_request->isPost()) {
		$formData = $this->_request->getPost();
		if ($form->isValid($formData)) {
		$data = array();
		$data['user_ip'] = $form->getValue('comment_author_IP');
		$data['user_agent'] = $form->getValue('comment_agent');
		$data['comment_type'] = 'contactmessage';
		$data['comment_author'] = $form->getValue('comment_author');
		$data['comment_author_email'] = $form->getValue('comment_author_email');
		$data['comment_content'] = $form->getValue('comment_content');
		$data['comment_date'] = $this->getTimeForForms();
		$data['user_id'] = $this->getIdentityForForms();
		$config = Zend_Registry::get('config');
		$akismetkey = $config->webservice->akismetkey;
		$akismet = new Zend_Service_Akismet($akismetkey, 'http://www.finds.org.uk');

		if ($akismet->isSpam($data)) { 
		$data['comment_approved'] = $spam;
		} else {
		$data['comment_approved'] =  $notspam;
		}
		
		$messages = new Messages();
		$insert = $messages->insert($data);
		$mail = new Zend_Mail();
		$mail->setBodyText('You submitted this complaint: '.strip_tags($data['comment_content']));
		$mail->setFrom($data['comment_author_email'], $data['comment_author']);
		$mail->addTo('dpett@british-museum.ac.uk', 'The Portable Antiquities Scheme');
		$mail->addCC($data['comment_author_email'], $data['comment_author']);
		$mail->setSubject('Complaint about the Scheme');
		$mail->send();
		$this->_flashMessenger->addMessage('Your complaint has been submitted to the Scheme, we will respond shortly');
		$this->_redirect('getinvolved/complaints/');
		
		} else {
		$this->_flashMessenger->addMessage('There are problems with your submission');
		$form->populate($formData);
		}
		}
		}



}