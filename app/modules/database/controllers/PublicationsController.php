<?php
/** Controller for displaying publications information
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_PublicationsController extends Pas_Controller_Action_Admin {

	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow('public',array('index','publication'));
	$this->_helper->_acl->deny('public',array('add','edit','delete'));
	$this->_helper->_acl->allow('flos',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('publication', array('xml','json'))
		->addActionContext('index', array('xml','json'))
		->initContext();
    }
    
	const REDIRECT = 'database/publications/';
	
	/** Display of publications with filtration
	*/
	public function indexAction() {
//	$sort = $this->_getParam('sort') ? $this->_getParam('sort') : 'title ASC'; 
//	$publications = new Publications();
//	$paginator = $publications->getPublications($sort,$this->_getAllParams());
//	if(!in_array($this->_helper->contextSwitch()->getCurrentContext(),array('xml','json','rss','atom'))){ 
//	$form = new PublicationFilterForm();
//	$form->title->setValue($this->_getParam('title'));
//	$form->pubYear->setValue($this->_getParam('pubYear'));
//	$form->place->setValue($this->_getParam('place'));
//	$form->authorEditor->setValue($this->_getParam('authorEditor'));
//	$this->view->form = $form;
//	if ($this->_request->isGet() && ($this->_getParam('submit') != NULL)) {
//	$formData = $this->_request->getParams();
//	if ($form->isValid($formData)) {
//	$title = $form->getValue('title');
//	$pubDate = $form->getValue('pubDate');
//	$params = array_filter($formData);
//	unset($params['submit']);
//	unset($params['action']);
//	unset($params['controller']);
//	unset($params['module']);
//	unset($params['page']);
//	unset($params['csrf']);
//	$where = array();
//		foreach($params as $key => $value) {
//				if($value != NULL){
//				$where[] = $key . '/' . urlencode(strip_tags($value));
//				}
//			}
//				$whereString = implode('/', $where);
//	$query = $whereString;
//	$this->_redirect(self::REDIRECT.'index/'.$query.'/');		
//	} else {
//	$form->populate($formData);
//	}
//	}
//	$this->view->paginator = $paginator;
//	} else {
//		$data = array('pageNumber' => $paginator->getCurrentPageNumber(),
//				  'total' => number_format($paginator->getTotalItemCount(),0),
//				  'itemsReturned' => $paginator->getCurrentItemCount(),
//				  'totalPages' => number_format($paginator->getTotalItemCount()/$paginator->getItemCountPerPage(),0));
//		$this->view->data = $data;
//		$paginated = array();
//		foreach ($paginator as $k => $v){
//			$paginated[$k] = $v;
//		}
//		$this->view->paginator = $paginated;
//	}
	
	$limit = 20;
	$page = $this->_getParam('page');
	if(!isset($page)){
		$start = 0;
		
	} else {
		unset($params['page']);
		$start = ($page - 1) * 20;
	}	
	
	$config = array(
    'adapteroptions' => array(
    'host' => '127.0.0.1',
    'port' => 8983,
    'path' => '/solr/',
	'core' => 'beopublications'
    ));
	
	$select = array(
    'query'         => '*:*',
    'start'         => $start,
    'rows'          => $limit,
    'fields'        => array('*'),
    'sort'          => array('title' => 'asc'),
	'filterquery' => array(),
    );
   
	$client = new Solarium_Client($config);
	// get a select query instance based on the config
	$query = $client->createSelect($select);
    $resultset = $client->select($query);
	$data = NULL;
	foreach($resultset as $doc){
	    foreach($doc as $key => $value){
	    	$fields[$key] = $value;
	    }
	    $data[] = $fields;
	}
	$paginator = Zend_Paginator::factory($resultset->getNumFound());
    $paginator->setCurrentPageNumber($page)
              ->setItemCountPerPage($limit)
              ->setPageRange(20);
    $this->view->paginator = $paginator;
	$this->view->results = $data;
	}
	/** Display details of publication
	*/
	public function publicationAction() {
	if($this->_getParam('id',false)) {
	$publications = new Publications();
	$this->view->publications = $publications->getPublicationDetails($this->_getParam('id'));
	$finds = new Finds();
	$this->view->finds = $finds->getFindtoPublication($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Add a publication
	*/
	public function addAction() {
	$form = new PublicationForm();
	$form->submit->setLabel('Add new');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$insertData = array();
	$insertData['secuid'] = $this->secuid();
	$insertData['title'] = $form->getValue('title');
	$insertData['authors'] = $form->getValue('authors');
	$insertData['vol_no'] = $form->getValue('vol_no');
	$insertData['edition'] = $form->getValue('edition');
	$insertData['publisher'] = $form->getValue('publisher');
	$insertData['publication_place'] = $form->getValue('publication_place');
	$insertData['publication_year'] = $form->getValue('publication_year');
	$insertData['publication_type'] = $form->getValue('publication_type');
	$insertData['in_publication'] = $form->getValue('in_publication');
	$insertData['editors'] = $form->getValue('editors');
	$insertData['edition'] = $form->getValue('edition');
	$insertData['ISBN'] = $form->getValue('ISBN');
	$insertData['created'] = $this->getTimeForForms();
	$insertData['createdBy'] = $this->getIdentityForForms();
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
	 }
	$publications = new Publications();
	$insert = $publications->insert($insertData);

	$this->_redirect(self::REDIRECT . 'publication/id/' . $insert);
	$this->_flashMessenger->addMessage('A new reference work has been created on the system!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit publication details
	*/
	public function editAction() {
	$form = new PublicationForm();
	$form->submit->setLabel('Update details');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$updateData = array();	
	$updateData['title'] = $form->getValue('title');
	$updateData['authors'] = $form->getValue('authors');
	$updateData['vol_no'] = $form->getValue('vol_no');
	$updateData['edition'] = $form->getValue('edition');
	$updateData['publisher'] = $form->getValue('publisher');
	$updateData['publication_place'] = $form->getValue('publication_place');
	$updateData['publication_year'] = $form->getValue('publication_year');
	$updateData['publication_type'] = $form->getValue('publication_type');
	$updateData['in_publication'] = $form->getValue('in_publication');
	$updateData['editors'] = $form->getValue('editors');
	$updateData['edition'] = $form->getValue('edition');
	$updateData['ISBN'] = $form->getValue('ISBN');
	$updateData['updated'] = $this->getTimeForForms();
	$updateData['updatedBy'] = $this->getIdentityForForms();
	foreach ($updateData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($updateData[$key]);
      }
	 }
	$where = array();
	$publications = new Publications();
	$where =  $publications->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $publications->update($updateData,$where);
	$this->_flashMessenger->addMessage('Details for "' . $form->getValue('title') . '" updated!');
	$this->_redirect(self::REDIRECT . 'publication/id/' . $this->_getParam('id'));
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$publications = new Publications();
	$publication = $publications->fetchRow('id='.$id);
	$form->populate($publication->toArray());
	}
	}
	}
	/** Delete publication details
	*/	
	public function deleteAction() {
	if($this->_getParam('id',false)) {
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$publications = new Publications();
	$where = array();
	$where =  $publications->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$this->_flashMessenger->addMessage('Record deleted!');
	$publications->delete($where);
	}
	$this->_redirect(self::REDIRECT);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$publications = new Publications();
	$this->view->publication = $publications->fetchRow('id= ' . (int) $this->_getParam('id'));
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}
