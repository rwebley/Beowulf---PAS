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
	$finds = new Findspots();
	$finds = $finds->getFindtoFindspotsAdmin($this->_getParam('id'),$this->_getParam('secuid'));
	if(count($finds) != 0){
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
	$place = new Pas_Service_Geo_Geoplanet($this->_appid);
	
//	$findelevation = $place->getElevation(NULL,$results['Latitude'],$results['Longitude']);
	
	$findwoeid = $place->reverseGeoCode($results['decimalLatLon']['decimalLatitude'],
		$results['decimalLatLon']['decimalLongitude']);
//	$elevation = $findelevation['elevation'];
	$woeid = $findwoeid['woeid'];
	} else {
	$woeid = NULL;
	$elevation = NULL;		
	}
	$findspots = new Findspots();
	$insertData = array(
		'secuid' => $secuid,
		'findID' => $form->getValue('findsecuid'),
		'old_findspotid' =>  $this->FindUid(),
		'county' => $form->getValue('county'),
		'district' => $form->getValue('district'),
		'parish' => $form->getValue('parish'),
		'knownas' => $form->getValue('knownas'),
		'regionID' => $form->getValue('regionID'),
		'knownas' => $form->getValue('knownas'),
		'gridref' => $results['gridref'],
		'gridrefsrc' => $form->getValue('gridrefsrc'),
		'declat' => $results['decimalLatLon']['decimalLatitude'],
		'declong' => $results['decimalLatLon']['decimalLongitude'],
		'easting' => $results['easting'],
		'northing' => $results['northing'],	  
		'map10k' => $results['10kmap'],
		'map25k' => $results['25kmap'],
		'fourFigure' => $results['fourFigureGridRef'],
		'gridrefcert' => $form->getValue('gridrefcert'),
		'description' => $form->getValue('description'),
		'comments' => $form->getValue('comments'),
		'landusecode' => $form->getValue('landusecode'),
		'landusevalue' => $form->getValue('landusevalue'),
		'created' => $this->getTimeForForms(), 
		'createdBy' => $this->getIdentityForForms(),
		'depthdiscovery' => $form->getValue('depthdiscovery'),	
		'landowner' => $form->getValue('landowner'),
		'address' => $form->getValue('address'),
		'postcode' => $form->getValue('postcode'),
		'accuracy' => $acc,
		'woeid' => $woeid,
		'elevation' => $elevation

		);
		
		$returnID = $form->getValue('returnID');
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
	 }
	$findspots->insert($insertData);
	$solr = new Pas_Solr_Updater();
	$solr->add($returnID);
	$this->_redirect(self::REDIRECT.'record/id/'.$returnID);
	$this->_flashMessenger->addMessage('A new findspot for has been created.');
	} else {
	$form->populate($formData);
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
	$findwoeid = $place->reverseGeoCode($results['decimalLatLon']['decimalLatitude'],$results['decimalLatLon']['decimalLongitude']);
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
	foreach ($updateData as $key => $value) {
      if (is_null($value) || $value=="") {
       $updateData[$key] = NULL;
      }
    }
	$auditData = $updateData;
	$audit = $findspots->fetchRow('id='.$this->_getParam('id'));
	$oldarray = $audit->toArray();

	$where = array();
	$where[] = $findspots->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$findspots->update($updateData,$where);
	$returnID = $form->getValue('returnID');

	if (!empty($auditData)) {
        // look for new fields with empty/null values
        foreach ($auditData as $item => $value) {
            if (empty($value)) {
                if (!array_key_exists($item, $oldarray)) {
                    // value does not exist in $oldarray, so remove from $newarray
                    unset ($updateData[$item]);
                } // if
            } else {
                // remove slashes (escape characters) from $newarray
                $auditData[$item] = stripslashes($auditData[$item]);
            } // if
        } // foreach 
        // remove entry from $oldarray which does not exist in $newarray
        foreach ($oldarray as $item => $value) {
            if (!array_key_exists($item, $auditData)) {
                unset ($oldarray[$item]);
            } // if
        } // foreach
    } //

	$fieldarray   = array();
    $ix           = 0;
	$editID = md5($this->getTimeForForms());
    foreach ($oldarray as $field_id => $old_value) {
        $ix++;
        $fieldarray[$ix]['findspotID']     = $this->_getParam('id');
		$fieldarray[$ix]['findID']     = $returnID;
		$fieldarray[$ix]['editID']     = $editID;
        $fieldarray[$ix]['created']     = $this->getTimeForForms();
		$fieldarray[$ix]['createdBy']     = $this->getIdentityForForms();
        $fieldarray[$ix]['fieldName']     = $field_id;
        $fieldarray[$ix]['beforeValue']    = $old_value;
        if (isset($auditData[$field_id])) {
            $fieldarray[$ix]['afterValue'] = $auditData[$field_id];
            // remove matched entry from $newarray
            unset($auditData[$field_id]);
        } else {
            $fieldarray[$ix]['afterValue'] = '';
        } // if
    } // foreach
    
    // process any unmatched details remaining in $newarray
    foreach ($auditData as $field_id => $new_value) {
        $ix++;
        $fieldarray[$ix]['findspotID']     = $this->_getParam('id');
		$fieldarray[$ix]['findID']     = $returnID;
		$fieldarray[$ix]['editID']     = $editID;
        $fieldarray[$ix]['created']     = $this->getTimeForForms();
		$fieldarray[$ix]['createdBy']     = $this->getIdentityForForms();
        $fieldarray[$ix]['fieldName']     = $field_id;
        $fieldarray[$ix]['afterValue']    = $new_value;
		
    } 
	function filteraudit($fieldarray)
	{
	if ($fieldarray['afterValue'] != $fieldarray['beforeValue'])
	  {
	return true;
	  }
	}
	
	$fieldarray = array_filter($fieldarray,'filteraudit');
	
	foreach($fieldarray as $f){
	foreach ($f as $key => $value) {
      if (is_null($value) || $value=="") {
       $f[$key] = NULL;
      }
    }

	$audit = new FindSpotsAudit();
	$auditBaby = $audit->insert($f);
	}
	/* Zend_Debug::dump($updateData);
	exit; */
	$solr = new Pas_Solr_Updater();
			$solr->add($returnID,'beowulf');
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
