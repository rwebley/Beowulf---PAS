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

    protected $_comments;
    /** Initialise the ACL and contexts
    */ 
    public function init() {
    $this->_helper->_acl->allow('fa',null);
    $this->_helper->_acl->allow('admin',null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_comments = new Comments();
    }

    /** Display all the comments
    */ 
    public function indexAction() {
    $this->view->params = $this->_getAllParams();
    $this->view->comments = $this->_comments->getComments($this->_getAllParams());
    }
	
    /** Publish a comment
     * 
     */ 
    public function publishAction()	{
    if($this->_getParam('id',false)) {
    $form = new PublishCommentFindForm();
    $form->submit->setLabel('Submit changes');
    $this->view->form = $form;
    if($this->getRequest()->isPost() 
        && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $data = $form->getValues();
    $data['comment_type'] = 'recordcomment';
    $where =  $this->_comments->getAdapter()->quoteInto('comment_ID = ?', $this->_getParam('id'));
    $update = $this->_comments->update($data,$where);
    $approvalstatus = $form->getValue('approval');
	switch($form->getValue('comment_approval')) {
	case 'approved' :
	$finds = new Finds();
	$find = $finds->getCreator($form->getValue('comment_findID'));
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
    $comment = $this->_comments->fetchRow('comment_ID =' . $id)->toArray();
    if($comment) {
    $form->populate($comment);
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