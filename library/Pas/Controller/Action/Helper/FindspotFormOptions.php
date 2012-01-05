<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoinFormLoader
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Pas_Controller_Action_Helper_FindspotFormOptions
    extends Zend_Controller_Action_Helper_Abstract {
    
  	protected $_view;

    public function preDispatch()
    {
	
	$this->_view = $this->_actionController->view;
    }
    
    public function __construct() {
    }
    
    public function direct(){
     
    return $this->optionsAddClone();
    }
    
    
    protected function _getIdentity(){
    	$user = new Pas_UserDetails();
    	return $user->getPerson()->id;
    }
    
    
    
    public function optionsAddClone(){
  	$findspots = new Findspots();
    $findspot = $findspots->getLastRecord($this->_getIdentity());
    $data = $findspot[0];
    $this->_view->form->populate($data);    	
    Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
    	->addMessage('Your last record data has been cloned');
    if(!is_null($data['county'])) {
    $districts = new Places();
    $district = $districts->getDistrictList($data['county']);
    if($district) {
    $this->_view->form->district->addMultiOptions(array(NULL => 'Choose district',
    	'Available districts' => $district));
    }
    if(!is_null($data['district'])) {
    $parishes = $districts->getParishList($data['district']);
    $this->_view->form->parish->addMultiOptions(array(NULL => 'Choose parish',
    	'Available parishes' => $parishes));
    }
     if(!is_null($data['county'])) {
    $cnts = new Counties();
    $region_list = $cnts->getRegionsList($data['county']);
    $this->_view->form->regionID->addMultiOptions(array(NULL => 'Choose region',
    	'Available regions' => $region_list));
    }
    }
     if(!is_null($data['landusevalue'])) {
    $landcodes = new Landuses();
    $landusecode_options = $landcodes->getLandusesChildList($data['landusevalue']);
    $this->_view->form->landusecode->addMultiOptions(array(NULL => 'Choose code',
    	'Available landuses' => $landusecode_options));
     }
    if(!is_null($findspot['landowner'])) {
    $finders = new Peoples();
    $finders = $finders->getName($findspot['landowner']);
    foreach($finders as $finder) {
    $form->landownername->setValue($finder['term']);
    }
    }
    } 
    
}


