<?php 
/** Controller for displaying information about people
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_PeopleController extends Pas_Controller_Action_Admin {

    protected $_peoples, $_geocoder;
    /** Setup the contexts by action and the ACL.
    */	
    public function init() {
    $this->_helper->_acl->allow('flos',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addContext('csv',array('suffix' => 'csv'))
            ->addContext('vcf',array('suffix' => 'vcf'))
            ->addContext('rss',array('suffix' => 'rss'))
            ->addContext('atom',array('suffix' => 'atom'))
            ->addActionContext('person', array('xml','json','vcf'))
            ->addActionContext('index', array('xml','json'))
            ->initContext();
   
    $this->_peoples = new Peoples();
    $this->_gmapskey = $this->_helper->config->webservice->googlemaps->apikey;
    $this->_geocoder = new Pas_Service_Geocoder($this->_gmapskey);
}

    const REDIRECT = 'database/people/';
    /** Index page of all people on the database
    */
    public function indexAction(){
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
    'core' => 'beopeople'
    ));

    $select = array(
    'query'         => '*:*',
    'start'         => $start,
    'rows'          => $limit,
    'fields'        => array('*'),
    'sort'          => array('forename' => 'asc'),
    'filterquery' => array(),
    );

    $client = new Solarium_Client($config);
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
    /** Display details of a person
    */
    public function personAction(){
    if($this->_getParam('id',false)) {
    $this->view->peoples = $this->_peoples->getPersonDetails($this->_getParam('id'));
    $finds = new Finds();
    $this->view->finds = $finds->getFindsToPerson($this->_getAllParams());
    } else {
            throw new Exception($this->_missingParameter);
    }
    }

    /** Add personal data
    */
    public function addAction() {
    $secuid = $this->secuid();
    $form = new PeopleForm();
    $form->submit->setLabel('Add a new person');
    $this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues(); 
    $address = $form->getValue('address') . ',' . $form->getValue('city') . ','
    . $form->getValue('county') . ',' . $form->getValue('postcode');
    
    $coords = $this->_geocoder->getCoordinates($address);
    
    if($coords){
        $lat = $coords['lat'];
        $lon = $coords['lon']; 
    } else { 
        $lat = NULL;
        $lon = NULL;
    }


    $updateData['lat'] = $lat;
    $updateData['lon'] = $lon;
    
    if(array_key_exists('dbaseID',$updateData)){
    $users = new Users();
    $user = array('peopleID' => $audit['secuid']);
    $where =  $users->getAdapter()->quoteInto('id = ?', 
            $updateData['dbaseID']);
    $updateUsers = $users->update($user,$where);	
    }
    $insert = $this->_peoples->add($updateData);	
    
	$this->_helper->solrUpdater->update('beopeople', $insert);	
    $this->_redirect(self::REDIRECT . 'person/id/' . $insert);
    $this->_flashMessenger->addMessage('Record created!');
    } else {
    $form->populate($form->getValues());
    }
    }
    }
    /** Edit person's data
    */
    public function editAction() {
    if($this->_getParam('id', false)) {
    $form = new PeopleForm();
    $form->submit->setLabel('Update details');
    $this->view->form = $form;
    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues(); 
    $address = $form->getValue('address') . ',' . $form->getValue('city') . ','
    . $form->getValue('county') . ',' . $form->getValue('postcode');
    
    $coords = $this->_geocoder->getCoordinates($address);
    
    if($coords){
        $lat = $coords['lat'];
        $lon = $coords['lon']; 
    } else { 
        $lat = NULL;
        $lon = NULL;
    }

    $updateData['lat'] = $lat;
    $updateData['lon'] = $lon;

    $oldData = $this->_peoples->fetchRow('id=' . $this->_getParam('id'))->toArray();
   
    if(array_key_exists('dbaseID',$updateData)){
    $users = new Users();
    $userdetails = array('peopleID' => $oldData['secuid']);
    $whereUsers =  $users->getAdapter()->quoteInto('id = ?', $updateData['dbaseID']);
    $updateUsers = $users->update($userdetails, $whereUsers);	
    } 
    $where =  $this->_peoples->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
    //Updated the people db table
    $update = $this->_peoples->update($updateData, $where);
    //Update the solr instance
	$this->_helper->solrUpdater->update('beopeople', $this->_getParam('id'));
	//Update the audit log
    $this->_helper->audit($updateData, $oldData, 'PeopleAudit', 
            $this->_getParam('id'), $this->_getParam('id'));
    $this->_flashMessenger->addMessage('Person information updated!');
    $this->_redirect(self::REDIRECT . 'person/id/' . $this->_getParam('id'));
    } else {
    $form->populate($form->getValues());
    }
    } else {
    $id = (int)$this->_request->getParam('id', 0);
    if ($id > 0) {
    $form->populate($this->_peoples->fetchRow('id=' . $id)->toArray());
    }
    }
    } else {
    throw new Exception($this->_missingParameter);
    }
    }
    /** Delete a person's data
    */	
    public function deleteAction() {
    if ($this->_request->isPost()) {
    $id = (int)$this->_request->getPost('id');
    $del = $this->_request->getPost('del');
    if ($del == 'Yes' && $id > 0) {
    $where = 'id = ' . $id;
    $this->_peoples->delete($where);
	$this->_helper->solrUpdater->deleteById('beopeople', $id);
    $this->_flashMessenger->addMessage('Record deleted!');
    }
    $this->_redirect(self::REDIRECT);
    }  else  {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $this->view->people = $this->_peoples->fetchRow('id=' . $id);
    }
    }
    }
	
}
