<?php
/** Controller for manipulating comments
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_CommentsController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
		$this->_helper->_acl->allow('fa',null);
 		$this->_helper->_acl->allow('admin',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

	/** Display all the comments
	*/ 
	public function indexAction() {
	$this->view->params = $this->_getAllParams();
	$comments = new Comments;
	$this->view->comments = $comments->getComments($this->_getAllParams());
	}
	/** Publish a comment
	*/ 
	public function publishAction()	{
	if($this->_getParam('id',false)) {
	$form = new PublishCommentFindForm();
	$form->submit->setLabel('Submit changes');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$comments = new Comments();
	$data = array();
	$data['comment_type'] = 'recordcomment';
	$data['comment_author'] = $form->getValue('comment_author');
	$data['comment_author_email'] = $form->getValue('comment_author_email');
	$data['comment_content'] = $form->getValue('comment_content');
	$data['updated'] = $this->getTimeForForms();
	$data['updatedBy'] = $this->getIdentityForForms();
	$data['comment_approved'] = $form->getValue('approval');
	$where =  $comments->getAdapter()->quoteInto('comment_ID = ?', $this->_getParam('id'));
	$update = $comments->update($data,$where);
	$approvalstatus = $form->getValue('approval');
	switch($approvalstatus) {
	case $approvalstatus == 'approved' :
	$finds = new Finds();
	$find = $finds->getCreator($form->getValue('comment_findID'));
	$mail = new Zend_Mail();
		$mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme - Beowulf');
		$mail->setBodyText('Dear '.$form->getValue('comment_author').'
		Your comment has now been published at http://www.finds.org.uk/database/artefacts/record/id/'.$form->getValue('comment_findID').' and relates to find '.$find['old_findID']
		.'Many thanks for taking the time to comment!
		
		Yours,
		
		The Scheme.');
		$mail->setBodyHtml('<p>Dear '.$form->getValue('comment_author').'</p><p>
		Your comment has now been published at http://www.finds.org.uk/database/artefacts/record/id/'.$form->getValue('comment_findID').' - '.$find['0']['old_findID']
		.'</p><p>Many thanks for taking the time to comment!</p><p>Yours,</p><p>The Scheme.</p>');
		$mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
		$mail->addTo($form->getValue('comment_author_email'), $form->getValue('comment_author'));
		$mail->addBcc($find['0']['email']);
		$mail->setSubject('A comment you submitted has been approved');
		$mail->send();
	break;	
	}
	$this->_flashMessenger->addMessage('Comment data updated.');
	$this->_redirect('/admin/comments/');
	} else {
	$this->_flashMessenger->addMessage('There is a problem with the form, please check and resubmit');
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$comments = new Comments();
	$comment = $comments->fetchRow('comment_ID ='.$id);
	if(count($comment) != NULL) {
	$form->populate($comment->toArray());
	} else {
		throw new Exception('No comment found with that ID');
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	}