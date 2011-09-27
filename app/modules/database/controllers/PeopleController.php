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

	protected $_peoples, $pm, $_config, $_geocoder;
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
	$config = Zend_Registry::get('config');
	$this->view->googleapikey = $config->webservice->googlemaps->apikey;
	$this->_peoples = new Peoples();
	$this->_pm = new Placemaker();
	$this->_config = Zend_Registry::get('config');
	$this->_gmapskey = $this->_config->webservice->googlemaps->apikey;
	$this->_geocoder = new Pas_Service_Geocoder($this->_gmapskey);
    }
    
	const REDIRECT = 'database/people/';
	/** Index page of all people on the database
	*/
	public function indexAction(){
	$this->view->paginator = $this->_peoples->getPeopleList($this->_getAllParams());
	$form = new PersonFilterForm();
	$this->view->form = $form;
	$form->fullname->setValue($this->_getParam('fullname'));
	$form->primary_activity->setValue($this->_getParam('primary_activity'));
	$form->organisation->setValue($this->_getParam('organisation'));
	$form->organisationID->setValue($this->_getParam('organisationID'));
	$form->county->setValue($this->_getParam('county'));
	if ($this->_request->isPost() && ($this->_getParam('submit') != NULL)) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
			$params = array_filter($formData);
			unset($params['submit']);
			unset($params['action']);
			unset($params['controller']);
			unset($params['module']);
			unset($params['page']);
			unset($params['csrf']);
	$where = array();
			foreach($params as $key => $value)
			{
				if($value != NULL){
				$where[] = $key . '/' . urlencode(strip_tags($value));
				}
			}
				$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT . 'index/' . $query.'/');
	} else {
	$form->populate($formData);
	}
	}
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
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData))  {
	
	$address = $form->getValue('address') . ',' . $form->getValue('city') . ','
	. $form->getValue('county') . ',' . $form->getValue('postcode');
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$long = $coords['lon']; 
	} else {
		$lat = NULL;
		$lon = NULL;
	}
	$place = $this->_pm->get($address);
	$woeid = $place->woeid;
	$insertData = array(
		'forename' => $form->getValue('forename'),
		'surname' => $form->getValue('surname'),
		'fullname' => $form->getValue('fullname'),
		'title' => $form->getValue('title'),
		'county' => $form->getValue('county'),
		'email' => $form->getValue('email'),
		'address' => $form->getValue('address'),
		'town_city' => $form->getValue('town_city'),
		'county' => $form->getValue('county'),
		'postcode' => $form->getValue('postcode'),
		'country' => $form->getValue('country'),
		'hometel' => $form->getValue('hometel'),
		'worktel' => $form->getValue('worktel'),
		'faxno' => $form->getValue('fax'),
		'comments' => $form->getValue('comments'),
		'secuid' => $secuid,
		'primary_activity' => $form->getValue('primary_activity'),
		'dbaseID' => $form->getValue('dbaseID'),
		'created' => $this->getTimeForForms(), 
		'createdBy' => $this->getIdentityForForms(),
	 	'organisationID' => $form->getValue('organisationID'),
		'lat' => $lat,
		'lon' => $lon,
		'dbaseID' => $form->getValue('dbaseID'),
		'woeid' => $woeid
		);
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	if(array_key_exists('dbaseID',$updateData)){
	$users = new Users();
	$userdetails = array('peopleID' => $audit['secuid'],
						 'updated' => $updateData['updated'],
						 'updatedBy' => $updateData['updatedBy']
	);
	
	
	$whereUsers =  $users->getAdapter()->quoteInto('id = ?', $updateData['dbaseID']);
	
	$updateUsers = $users->update($userdetails,$whereUsers);	
	}
	$insert = $this->_peoples->insert($insertData);		
	$this->_redirect(self::REDIRECT . 'person/id/' . $insert);
	$this->_flashMessenger->addMessage('Record created!');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Edit person's data
	*/
	public function editAction() {
	if($this->_getParam('id',false)) {
	$form = new PeopleForm();
	$form->submit->setLabel('Update details on database...');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
		$address = $form->getValue('address') . ',' . $form->getValue('city') . ','
	. $form->getValue('county') . ',' . $form->getValue('postcode');
	$coords = $this->_geocoder->getCoordinates($address);
	if($coords){
		$lat = $coords['lat'];
		$long = $coords['lon']; 
	} else {
		$lat = NULL;
		$lon = NULL;
	}
	$place = $this->_pm->get($address);
	$woeid = $place->woeid;
	$updateData = array(
		'forename' => $form->getValue('forename'),
		'surname' => $form->getValue('surname'),
		'fullname' => $form->getValue('fullname'),
		'title' => $form->getValue('title'),
		'county' => $form->getValue('county'),
		'email' => $form->getValue('email'),
		'address' => $form->getValue('address'),
		'town_city' => $form->getValue('town_city'),
		'county' => $form->getValue('county'),
		'postcode' => $form->getValue('postcode'),
		'country' => $form->getValue('country'),
		'hometel' => $form->getValue('hometel'),
		'worktel' => $form->getValue('worktel'),
		'faxno' => $form->getValue('fax'),
		'dbaseID' => $form->getValue('dbaseID'),
		'comments' => $form->getValue('comments'),
		'primary_activity' => $form->getValue('primary_activity'),
		'updated' => $this->getTimeForForms(), 
		'updatedBy' => $this->getIdentityForForms(),
	 	'organisationID' => $form->getValue('organisationID'),
		'lat' => $lat,
		'lon' => $lon,
		'woeid' => $woeid 		
		);
	foreach ($updateData as $key => $value) {
      if (is_null($value) || $value=="") {
       $updateData[$key] = NULL;
      }
    }
	$auditData = $updateData;
	$audit = $this->_peoples->fetchRow('id=' . $this->_getParam('id'));
	$oldarray = $audit->toArray();
	if(array_key_exists('dbaseID',$updateData)){
	$users = new Users();
	$userdetails = array('peopleID' => $audit['secuid'],
						 'updated' => $updateData['updated'],
						 'updatedBy' => $updateData['updatedBy']
	);
	
	
	$whereUsers =  $users->getAdapter()->quoteInto('id = ?', $updateData['dbaseID']);
	
	$updateUsers = $users->update($userdetails,$whereUsers);	
	}
	
	$where =  $this->_peoples->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_peoples->update($updateData,$where);
	
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
		$fieldarray[$ix]['personID']     = $this->_getParam('id');
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
		$fieldarray[$ix]['personID']     = $this->_getParam('id');
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

	$audit = new PeopleAudit();
	$auditBaby = $audit->insert($f);
	}
	$this->_flashMessenger->addMessage('Person information updated!');
	$this->_redirect(self::REDIRECT . 'person/id/' . $this->_getParam('id'));
	} else {
	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$this->_flashMessenger->addMessage('No change to information');
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$people = $this->_peoples->fetchRow('id=' . $id);
	$form->populate($people->toArray());
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
