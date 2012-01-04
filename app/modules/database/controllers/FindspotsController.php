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
	$this->_flashMessenger->addMessage('You cannot access root file for findspots');
	$this->_redirect(self::REDIRECT);
	}

	/** Add a new findspot action
	 * @todo The audit function needs abstracting to make thin controller happen.
	 */
	public function addAction() {
	$finds = $this->_findspots->getFindtoFindspotsAdmin($this->_getParam('id'),$this->_getParam('secuid'));
	if(!is_null($finds)){
	throw new Exception('A findspot already exists for this record, or that find does not exist.');
	}
	if($this->_getParam('id',false)){
	$secuid = $this->secuid();
	$form = new FindSpotForm();
	$form->returnID->setValue($this->_getParam('id'));
	$form->old_findspotid->setValue($this->FindUid());
	$form->findsecuid->setValue($this->_getParam('secuid')); 
	$form->submit->setLabel('Add a findspot');
	$last = $this->_getParam('copy');
	if($last == 'last') {
	$this->_flashMessenger->addMessage('Your last record data has been cloned');
	$findspots = new Findspots();
	$findspotdata = $findspots->getLastRecord($this->getIdentityForForms());
	foreach($findspotdata as $findspotdataflat){	
	if(!is_null($findspotdataflat['county'])) {
	$districts = new Places();
	$district_list = $districts->getDistrictList($findspotdataflat['county']);
	if(count($district_list)) {
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
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$ngr = $form->getValue('gridref');
	
	if($ngr != ""){
	$conversion = new Pas_Geo_Gridcalc($form->getValue('gridref'));
	$results = $conversion->convert();
	$place = new Pas_Service_Geo_Geoplanet($this->_helper->config->webservice->ydnkeys->placemakerkey);
	
	$findwoeid = $place->reverseGeoCode($results['decimalLatLon']['decimalLatitude'],
		$results['decimalLatLon']['decimalLongitude']);
		
	$woeid = $findwoeid['woeid'];
	} else {
	$woeid = NULL;
	}
	$updateData = $form->getValues();
	$updateData['secuid'] = $this->secuid();
	$updateData['old_findspotid'] = $this->FindUid();
	$updateData['gridref'] = $results['gridref'];
	$updateData['declat'] = $results['decimalLatLon']['decimalLatitude'];
	$updateData['declong'] = $results['decimalLatLon']['decimalLongitude'];
	$updateData['easting'] = $results['easting'];
	$updateData['northing'] = $results['northing'];	  
	$updateData['map10k'] = $results['10kmap'];
	$updateData['map25k'] = $results['25kmap'];
	$updateData['fourFigure'] = $results['fourFigureGridRef'];
	$updateData['woeid'] = $woeid;
	$updateData['accuracy'] = $results['accuracy'];

	$findspots->add($updateData);
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
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$findspots = new Findspots();
	$ngr = $form->getValue('gridref');
	if($ngr != ""){
	$geo = new Pas_Geo_Gridcalc($ngr);
	$results = $geo->convert();
	$place = new Pas_Service_Geo_Geoplanet($this->_appid);
//	$findelevation = $place->getElevation(NULL,$results['Latitude'],$results['Longitude']);
	$findwoeid = $place->reverseGeoCode($results['decimalLatLon']['decimalLatitude'],
	$results['decimalLatLon']['decimalLongitude']);
//	$elevation = $findelevation['elevation'];
	$woeid = $findwoeid['woeid'];
	} else {
	$elevation = NULL;
	$woeid = NULL;	
	}
	$updateData = array(
	'county' => $form->getValue('county'),
	'district' => $form->getValue('district'),
	'parish' => $form->getValue('parish'),
	'knownas' => $form->getValue('knownas'),
	'regionID' => $form->getValue('regionID'),
	'knownas' => $form->getValue('knownas'),
	'gridref' => strtoupper(str_replace(' ','',$ngr)),
	'gridrefsrc' => $form->getValue('gridrefsrc'),
	'declat' => $results['decimalLatLon']['decimalLatitude'],
	'declong' => $results['decimalLatLon']['decimalLongitude'],
	'easting' => $results['easting'],
	'northing' => $results['northing'],	  
	'map10k' => $results['10kmap'],
	'map25k' => $results['25kmap'],
	'landusecode' => $form->getValue('landusecode'),
	'landusevalue' => $form->getValue('landusevalue'),
	'landowner' => $form->getValue('landowner'),
	'fourFigure' => $results['fourFigureGridRef'],
	'gridrefcert' => $form->getValue('gridrefcert'),
	'description' => $form->getValue('description'),
	'comments' => $form->getValue('comments'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms(),
	'highsensitivity' => $form->getValue('highsensitivity'),
	'depthdiscovery' => $form->getValue('depthdiscovery'),
	'accuracy' => $results['accuracy']['precision'],
	'address' => $form->getValue('address'),
	'postcode' => $form->getValue('postcode'),
	'woeid' => $woeid,
	'elevation' => $elevation
	);

	$oldData = $findspots->fetchRow('id=' . $this->_getParam('id'))->toArray();

	$where = array();
	$where[] = $findspots->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$findspots->update($updateData,$where);
	$returnID = $form->getValue('returnID');
	$this->_helper->audit($updateData, $oldData, 'FindSpotsAudit',
	 $this->_getParam('id'), $returnID);
	$this->_helper->solrUpdater->update('beowulf', $returnID);
	$this->_flashMessenger->addMessage('Details for the findspot updated!');
	$this->_redirect(self::REDIRECT.'record/id/'.$returnID);
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$findspots = new Findspots();
	
	$where = array();
	$where[] = $findspots->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$findspot = $findspots->fetchRow($where);
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
	
	foreach($finds as $find)
	{
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
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$findspots = new Findspots();
	
	$where = array();
	$where[] = $findspots->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$findspot = $findspots->fetchRow($where);
	if(!is_null($findspot['county'])) {
	$districts = new Places();
	$district_list = $districts->getDistrictList($findspot['county']);
	$form->district->addMultiOptions(array(NULL => NULL,'Choose district' => $district_list));
	if($findspot['district'] != NULL) {
	$parish_list = $districts->getParishList($findspot['district']);
	$form->parish->addMultiOptions(array(NULL => NULL,'Choose parish' => $parish_list));
	}
	$cnts = new Counties();
	$region_list = $cnts->getRegionsList($findspot['county']);
	$form->regionID->addMultiOptions(array(NULL => NULL,'Choose region' => $region_list));
	}
	$landcodes = new Landuses();
	$landusecode_options = $landcodes->getLandusesChildList($findspot['landusevalue']);
	$form->landusecode->addMultiOptions(array(NULL => NULL,'Choose code' => $landusecode_options));
	$finds = new Finds();
	$finds = $finds->getFindtoFindspots($this->_getParam('id'));
	
	foreach($finds as $find) {
	$form->returnID->setValue($find['id']);
	$form->findsecuid->setValue($find['secuid']);
	}
	if($findspot['landowner'] != NULL)
	{
	$finders = new Peoples();
	$finders = $finders->getName($findspot['landowner']);
	foreach($finders as $finder)
	{
	$form->landownername->setValue($finder['term']);
	}
	}
	$this->view->finds = $finds;
	$this->view->findspot = $findspot->toArray();
	$form->populate($findspot->toArray());
	}
	}
	} else {
	throw new Exception($this->_missingParameter);
	}
	}

	/** Action for deleting findspot
	 * 
	 */
	public function deleteAction() {
	if($this->_getParam('id',false)){
	$this->view->headTitle("Delete Findspot");
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$findID = (int)$this->_request->getPost('findID');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$findspots = new Findspots();
	$where = 'id = ' . $id;
	$findspots->delete($where);
	$this->_flashMessenger->addMessage('Findspot deleted.');
	}
	$this->_redirect(self::REDIRECT.'record/id/'.$findID);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$findspots = new Findspots();
	$this->view->findspot = $findspots->getFindtoFindspotDelete($this->_request->getParam('id'));
	}
	}
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

}
