<?php
/** Controller for replying  to contact us messages
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_MessagesController extends Pas_Controller_Action_Admin {
	
	protected $_messages;
	
	protected $_replies;
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_messages = new Messages();
	$this->_replies = new Replies();
    }
	/** Display list of messages sent
	*/
	public function indexAction() 	{
	$this->view->params = $this->_getAllParams();
	$this->view->messages = $this->_messages->getMessages($this->_getAllParams());
	}
	/** Reply to a stored message
	*/	
	public function replyAction() {
	if($this->_getParam('id',false)) {
	$form = new MessageReplyForm();
	$form->submit->setLabel('Send reply');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$messagetext = $form->getValue('messagetext');
	$data = array();
	$data['updated'] = $this->getTimeForForms();
	$data['updatedBy'] = $this->getIdentityForForms();
	$data['replied'] = 1;
	$where =  $this->_messages->getAdapter()->quoteInto('id= ?', $this->_getParam('id'));
	$update = $this->_messages->update($data,$where);
	$replydata = array();
	$replydata['created'] = $this->getTimeForForms();
	$replydata['createdBy'] = $this->getIdentityForForms();
	$replydata['messagetext'] = $messagetext;
	$replydata['messageID'] = $this->_getParam('id');
	$insert = $this->_replies->insert($replydata);
	$mail = new Zend_Mail();
	$mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme - Beowulf');
	$mail->setBodyText('Dear '.$form->getValue('comment_author')
		.$messagetext);
	$mail->setBodyHtml('<p>Dear '.$form->getValue('comment_author').'</p>'.
	$messagetext);
	$mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
	$mail->addTo($form->getValue('comment_author_email'), $form->getValue('comment_author'));
	$mail->setSubject('Response from the Portable Antiquities Scheme to your message');
	$mail->send();
	$this->_flashMessenger->addMessage('Message replied to.');
	$this->_redirect('/admin/messages/');
	} else {
	$this->_flashMessenger->addMessage('There is a problem with the form, please check and resubmit');
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$message = $this->_messages->fetchRow('id ='.$id);
	if($message) {
	$form->populate($message->toArray());
	} else {
		throw new Pas_Exception_Param($this->_nothingFound);
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	}