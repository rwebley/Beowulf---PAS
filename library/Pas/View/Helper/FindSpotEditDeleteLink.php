<?php
	class Pas_View_Helper_FindSpotEditDeleteLink extends Zend_View_Helper_Abstract
	{
	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_missingGroup = 'User is not assigned to a group';
	protected $_message = 'You are not allowed edit rights to this record';
	
	protected $_auth = NULL;
	
	public function __construct()
    { 
    $auth = Zend_Auth::getInstance();
    $this->_auth = $auth; 
    }
    
	public function getRole()
	{
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	}
	}
	
	public function getUserID()
	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}
		
	public function checkAccessbyUserID($createdBy)
	{
	if(!in_array($this->getRole(),$this->restricted)) {
	return true;
	} else if(in_array($this->getRole(),$this->restricted)) {
	if($createdBy == $this->getUserID()) {
	return true;
	}
	} else {
	return false;
	}
	}
	
	public function checkAccessbyInstitution($findspotID)
	{
	$find = explode('-', $findspotID);
	$id = $find['0'];	
	$inst = $this->getInst();
	if($id == $inst) {
	return true;
	} else if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return true;
	}
	}
		
	public function getInst()
	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	} else {
	//throw new Exception($this->_missingGroup);
	return FALSE;
	}	
	
	}
	
	public function FindSpotEditDeleteLink($findspotID,$ID,$createdBy)
	{
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($findspotID);
	
	if(in_array($this->getRole(),$this->restricted)) {
	if(($byID == TRUE && $instID== TRUE) || ($byID == TRUE && $instID == FALSE)) {
	return $this->buildHtml($ID);
	}	
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return $this->buildHtml($ID);
	} else if(in_array($this->getRole(),$this->recorders)) {
	if(($instID == TRUE && $byID == FALSE) || ($byID == true && $instID == true) ||
	($byID == false && $instID == true)) {
	return $this->buildHtml($ID);
	} 
	}
	}
	
	public function buildHtml($ID)
	{
	$editurl = $this->view->url(array('module' => 'database','controller' => 'findspots','action' => 'edit',
	'id' => $ID),null,true);
	$deleteurl = $this->view->url(array('module' => 'database','controller' => 'findspots','action' => 'delete',
	'id' => $ID),null,true);
	$string = '<p><a href="' . $editurl
	. '" title="Edit spatial data for this record">Edit findspot</a> | <a href="' . $deleteurl 
	. '" title="Delete spatial data">Delete findspot</a></p>';
	return $string;
	}

}
