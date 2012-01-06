<?php
/** Controller for adding static contents to the Scheme website
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_ContentController extends Pas_Controller_Action_Admin {
	
	protected $_content;
	
	protected $_cache;
	/** Initialise the ACL and contexts
	*/ 	
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_content = new Content();
    }
	/** Display index page
	*/ 
	public function indexAction() {
		$this->view->contents = $this->_content->getContentAdmin($this->_getParam('page'));
	}
	/** Add contents
	*/ 	
	public function addAction()	{
		$form = new ContentForm();
		$form->submit->setLabel('Add new content to system');
		$form->author->setValue($this->getIdentityForForms());
		$this->view->form = $form;
		if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    	if ($form->isValid($form->getValues())) {
		$insertData = $form->getValues();
		$content = new Content();
		$insert = $content->add($insertData);
		$this->_helper->solrUpdater->update('beocontent', $insert);
		$this->_flashMessenger->addMessage('Static content has been created!');
		$this->_redirect('/admin/content');
		} else 
		{
		$form->populate($form->getValues());
		}
		}
		}
	/** Edit a content article
	*/ 		
	public function editAction() {
		if($this->_getParam('id',false)) {
		$form = new ContentForm();
		$form->submit->setLabel('Submit changes');
		$form->author->setValue($this->getIdentityForForms());
		$this->view->form = $form;
		if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    	if ($form->isValid($form->getValues())) {
		$updateData = $form->getValues();
		$where = array();
		$where[] = $this->_content->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
		$this->_content->update($updateData,$where);
		
		$this->_helper->solrUpdater->update('beocontent', $this->_getParam('id'));
		$cache = Zend_Registry::get('rulercache');
		$tag = 'content' . md5($updateData['slug']);
		$tag2 = 'frontcontent' . $form->getValue('section');
		$cache->remove($tag2);
		$cache->remove($tag);
		$this->_flashMessenger->addMessage('You updated: <em>' . $form->getValue('title') 
		. '</em> successfully. It is now available for use.');
		$this->_redirect('admin/content/');
		} else {
		$form->populate($formData);
		}
		} else {
		// find id is expected in $params['id']
		$id = (int)$this->_request->getParam('id', 0);
		if ($id > 0) {
		$contents = new Content();
		$content = $contents->fetchRow('id=' . (int)$id);
		if(count($content))
		{
		$form->populate($content->toArray());
		$this->view->headTitle('Edit content &raquo; ' . $content['title']);
		} else {
			throw new Pas_Exception_Param($this->_nothingFound);
		}
		}
		}
		} else {
			throw new Pas_Exception_Param($this->_missingParameter);
		}
		}
		/** Delete article
		*/ 		
		public function deleteAction() {
		if ($this->_request->isPost()) {
		$id = (int)$this->_request->getPost('id');
		$del = $this->_request->getPost('del');
		if ($del == 'Yes' && $id > 0) {
		$contents = new Content();
		$where = 'id = ' . $id;
		$contents->delete($where);
		$this->_flashMessenger->addMessage('Record deleted!');
		
		$this->_helper->solrUpdater->deleteById('beocontent', $id);
		}
		$this->_redirect('/admin/content/');
		}  else  {
		$id = (int)$this->_request->getParam('id');
		if ($id > 0) {
		$contents = new Content();
		$this->view->content = $contents->fetchRow('id=' . $id);
		}
		}
	}
	
}
