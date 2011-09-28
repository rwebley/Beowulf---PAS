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
	
	protected $_cache;
	/** Initialise the ACL and contexts
	*/ 	
	public function init() {
	$this->_helper->_acl->allow('fa',null);
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_cache = Zend_Registry::get('rulercache');
    }
	/** Display index page
	*/ 
	public function indexAction() {
		$contents = new Content();
		$this->view->contents = $contents->getContentAdmin($this->_getParam('page'));
	}
	/** Add contents
	*/ 	
	public function addAction()	{
		$form = new ContentForm();
		$form->submit->setLabel('Add new content to system');
		$form->author->setValue($this->getIdentityForForms());
		$this->view->form = $form;
		if ($this->_request->isPost()) {
		$formData = $this->_request->getPost();
		if ($form->isValid($formData)) {
		$insertData = array(
		'title' => $form->getValue('title'), 
		'menuTitle' => $form->getValue('menuTitle'),
		'slug' => $form->getValue('slug'), 
		'excerpt' => $form->getValue('excerpt'),
		'body' => $form->getValue('body'),
		'publishState' => $form->getValue('publishState'), 
		'section' => $form->getValue('section'), 
		'metaKeywords' => $form->getValue('metaKeywords'), 
		'metaDescription' => $form->getValue('metaDescription'),
		'frontPage' => $form->getValue('frontPage'),
		'author' => $form->getValue('author'),
		'created' => $this->getTimeForForms(),
		'createdBy' => $this->getIdentityForForms());
		$content = new Content();
		$update = $content->insert($insertData);
		$this->_flashMessenger->addMessage('Static content has been created!');
		$this->_redirect('/admin/content');
		} else 
		{
		$form->populate($formData);
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
		if ($this->_request->isPost()) {
		$id = $this->_getParam('id'); 
		$formData = $this->_request->getPost();
		if ($form->isValid($formData)) {
		$updateData = array(
		'title' => $form->getValue('title'), 
		'menuTitle' => $form->getValue('menuTitle'),
		'slug' => $form->getValue('slug'), 
		'excerpt' => $form->getValue('excerpt'),
		'body' => $form->getValue('body'),
		'publishState' => $form->getValue('publishState'), 
		'section' => $form->getValue('section'), 
		'metaKeywords' => $form->getValue('metaKeywords'), 
		'metaDescription' => $form->getValue('metaDescription'),
		'frontPage' => $form->getValue('frontPage'),
		'author' => $form->getValue('author'),
		'updated' => $this->getTimeForForms(),
		'updatedBy' => $this->getIdentityForForms()
		);
		$content = new Content();
		$where = array();
		$where[] = $content->getAdapter()->quoteInto('id = ?', $id);
		$content->update($updateData,$where);
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
