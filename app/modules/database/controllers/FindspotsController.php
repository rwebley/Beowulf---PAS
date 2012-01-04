<?php
/** The find spots controller for CRUD to database
 * 
 *  This class allows for the creation, editing, updating and deletion of findspot 
 *  data. It makes use of a couple of webservices.
 * 
 * @author Daniel Pett
 * @category Pas
 * @package  Pas_Controller_Action_Admin
 * @subpackage Admin
 * @version 1
 * @license GNU 
 * @since September 2009
 * @todo move audit to own class
 * @todo DRY the class
 */
class Database_FindspotsController
	extends Pas_Controller_Action_Admin {

		
    protected $_findspots;
    /** The Yahoo! appid variable for placemaker
     * 
     * @var string $_appid;
     */
    protected $_appid;

    /** Base Url redirect
     * 
     */
    const REDIRECT = '/database/artefacts/';

    /** Set up the ACL access and appid from config
     * 
     */
    public function init() {
    $this->_helper->_acl->deny('public',null);
    $this->_helper->_acl->allow('member',array('index','add','delete','edit'));
    $this->_helper->_acl->allow('flos',null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_appid = $this->_config->webservice->ydnkeys->placemakerkey;
    $this->_findspots = new Findspots();
}


/** The index page with no root access
 * 
 */
    public function indexAction() {
    $this->_flashMessenger->addMessage('You cannot access the findspots index.');
    $this->_redirect(self::REDIRECT);
    }

    /** Add a new findspot action
     * @todo The audit function needs abstracting to make thin controller happen.
     */
    public function addAction() {
    $finds = $this->_findspots->getFindtoFindspotsAdmin($this->_getParam('id'), 
            $this->_getParam('secuid'));
    if(!is_null($finds)){
    throw new Exception('A findspot already exists for this record.', 500);
    }
    if($this->_getParam('id',false)){
    $secuid = $this->secuid();
    $form = new FindSpotForm();
    $form->returnID->setValue($this->_getParam('id'));
    $form->old_findspotid->setValue($this->FindUid());
    $form->findsecuid->setValue($this->_getParam('secuid')); 
    $form->submit->setLabel('Add a findspot');

    if($this->_getParam('copy') === 'last') {
    $this->_flashMessenger->addMessage('Your last record data has been cloned');
    $findspotdata = $this->_findspots->getLastRecord($this->getIdentityForForms());
    foreach($findspotdata as $findspotdataflat){	
    if(!is_null($findspotdataflat['county'])) {
    $districts = new Places();
    $districtList = $districts->getDistrictList($findspotdataflat['county']);
    if($districtList) {
    $form->district->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
    }
    if(!is_null($findspotdataflat['district'])) {
    $parish_list = $districts->getParishList($findspotdataflat['district']);
    $form->parish->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
    }
    $cnts = new Counties();
    $region_list = $cnts->getRegionsList($findspotdataflat['county']);
    $form->regionID->addMultiOptions(array(NULL => NULL,'Choose region' => $region_list));
    }
    $landcodes = new Landuses();
    $landusecode_options = $landcodes->getLandusesChildList($findspotdataflat['landusevalue']);
    $form->landusecode->addMultiOptions(array(NULL => NULL,'Choose code' => $landusecode_options));
    $form->populate($findspotdataflat);
    }
    }
    $this->view->form = $form;

    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $updateData = $form->getValues();
    $this->_findspots->addAndProcess($updateData);
    $this->_helper->solrUpdater->update('beowulf', $returnID);
    $this->_redirect(self::REDIRECT . 'record/id/' . $returnID);
    $this->_flashMessenger->addMessage('A new findspot for has been created.');
    } else {
    $form->populate($form->getValues());
    }
    }
    } else {
    throw new Pas_Exception_Param($this->_missingParameter);
    }
    }

    /** Action for editing findspots
     * 
     */
    public function editAction() {
    if($this->_getParam('id',false)) {
    $form = new FindSpotForm();
    $form->submit->setLabel('Update findspot');
    $this->view->form = $form;

    if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    
    $updateData = $form->getValues();

    $oldData = $this->_findspots->fetchRow('id=' . $this->_getParam('id'))->toArray();
    $where = array();
    $where[] = $this->_findspots->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
    $this->_findspots->update($updateData, $where);
    $returnID = $form->getValue('returnID');
    $this->_helper->audit($updateData, $oldData, 'FindSpotsAudit',
     $this->_getParam('id'), $returnID);
    $this->_helper->solrUpdater->update('beowulf', $returnID);
    $this->_flashMessenger->addMessage('Details for the findspot updated!');
    $this->_redirect(self::REDIRECT.'record/id/'.$returnID);
    } else {
    $id = (int)$this->_request->getParam('id', 0);
    if ($id > 0) {
   
    $where = array();
    $where[] = $this->_findspots->getAdapter()->quoteInto('id = ?', 
             $this->_getParam('id'));
    $findspot = $this->_findspots->fetchRow($where);
    if(!is_null($findspot['county'])) {
    $districts = new Places();
    $district_list = $districts->getDistrictList($findspot['county']);
    $form->district->addMultiOptions(array(NULL => 'Choose district',
        'Available districts' => $district_list));
    if(!is_null($findspot['district'])) {
    $parish_list = $districts->getParishList($findspot['district']);
    $form->parish->addMultiOptions(array(NULL => 'Choose parish',
        'Available parishes' => $parish_list));
    }
    }
    $cnts = new Counties();
    $region_list = $cnts->getRegionsList($findspot['county']);
    $form->regionID->addMultiOptions(array(NULL => 'Choose region',
        'Available regions' => $region_list));
    $landcodes = new Landuses();
    $landusecode_options = $landcodes->getLandusesChildList($findspot['landusevalue']);
    $form->landusecode->addMultiOptions(array(NULL => 'Choose code',
        'Available landuses' => $landusecode_options));
    $finds = new Finds();
    $finds = $finds->getFindtoFindspots($this->_getParam('id'));

    foreach($finds as $find) {
    $form->returnID->setValue($find['id']);
    $form->findsecuid->setValue($find['secuid']);
    }
    if(!is_null($findspot['landowner'])) {
    $finders = new Peoples();
    $finders = $finders->getName($findspot['landowner']);
    foreach($finders as $finder) {
    $form->landownername->setValue($finder['term']);
    }
    }
    $this->view->finds = $finds;
    $this->view->findspot = $findspot->toArray();
    $form->populate($formData);
    }
    }
    } else {
    $id = (int)$this->_getParam('id', 0);
    if ($id > 0) {
    $where = array();
    $where[] = $this->_findspots->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
    $findspot = $this->_findspots->fetchRow($where);
    if(!is_null($findspot['county'])) {
    $districts = new Places();
    $district_list = $districts->getDistrictList($findspot['county']);
    $form->district->addMultiOptions(array(NULL => 'Choose district', 
        'Available districts' => $district_list));
    if($findspot['district'] != NULL) {
    $parish_list = $districts->getParishList($findspot['district']);
    $form->parish->addMultiOptions(array(NULL => 'Choose parish', 
        'Available parishes' => $parish_list));
    }
    $cnts = new Counties();
    $region_list = $cnts->getRegionsList($findspot['county']);
    $form->regionID->addMultiOptions(array(NULL => 'Choose region', 
        'Available regions' => $region_list));
    }
    $landcodes = new Landuses();
    $landusecode_options = $landcodes->getLandusesChildList($findspot['landusevalue']);
    $form->landusecode->addMultiOptions(array(NULL => 'Choose code',
        'Available landuses' => $landusecode_options));
    $finds = new Finds();
    $finds = $finds->getFindtoFindspots($this->_getParam('id'));

    foreach($finds as $find) {
    $form->returnID->setValue($find['id']);
    $form->findsecuid->setValue($find['secuid']);
    }
    if($findspot['landowner'] != NULL)  {
    $finders = new Peoples();
    $finders = $finders->getName($findspot['landowner']);
    foreach($finders as $finder){
    $form->landownername->setValue($finder['term']);
    }
    }
    $this->view->finds = $finds;
    $this->view->findspot = $findspot->toArray();
    $form->populate($findspot->toArray());
    }
    }
    } else {
        throw new Exception($this->_missingParameter,500);
    }
    }

    /** Action for deleting findspot
     * 
     */
    public function deleteAction() {
    if($this->_getParam('id',false)){
    if ($this->_request->isPost()) {
    $id = (int)$this->_request->getPost('id');
    $findID = (int)$this->_request->getPost('findID');
    $del = $this->_request->getPost('del');
    if ($del == 'Yes' && $id > 0) {
    $this->_findspots = new Findspots();
    $where = 'id = ' . $id;
    $this->_flashMessenger->addMessage('Findspot deleted.');
    }
    $this->_redirect(self::REDIRECT . 'record/id/' . $findID);
    } else {
    $id = (int)$this->_request->getParam('id');
    if ($id > 0) {
    $findspots = new Findspots();
    $this->view->findspot = $findspots->getFindtoFindspotDelete($this->_getParam('id'));
    }
    }
    } else {
        throw new Pas_Exception_Param($this->_missingParameter,500);
    }
    }

}
