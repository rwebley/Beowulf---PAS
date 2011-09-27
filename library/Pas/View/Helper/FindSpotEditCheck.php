<?php
class Pas_View_Helper_FindSpotEditCheck extends Zend_View_Helper_Abstract
{
	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_auth = NULL;
	
	protected $_missingGroup = 'User is not assigned to a group';
	protected $_message = 'You are not allowed edit rights to this record';
	
	public function __construct()
    { 
    	$auth = Zend_Auth::getInstance();
        $this->_auth = $auth; 
    }
	
	public function getRole()
	{
	if($this->_auth->hasIdentity())
	{
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	public function getIdentityForForms()
	{
	if($this->_auth->hasIdentity())
	{
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else {
	$id = '3';
	return $id;
	}
	}
	
	public function checkAccessbyInstitution($findspotID)
	{
	$find = explode('-', $findspotID);
	$id = $find['0'];
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return true;
	} else if($id == $inst) {
	return true;
	}
	}

	public function checkAccessbyUserID($userID,$createdBy)
	{
	if($userID == $createdBy) {
	return true;
	}
	}

	public function getInst()
	{
	if($this->_auth->hasIdentity())
	{
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	} else {
	return FALSE;
	//throw new Exception($this->_missingGroup);
	}	
	
	}
	
	public function FindSpotEditCheck($findspotID,$createdBy)
	{
	$byID = $this->checkAccessbyUserID($this->getIdentityForForms(),$createdBy);
	$instID = $this->checkAccessbyInstitution($findspotID);
	
	if(in_array($this->getRole(),$this->restricted)) {
	if(($byID == TRUE && $instID == TRUE) || ($byID == TRUE && $instID == FALSE)) {
	return TRUE;
	}
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return TRUE;
	} elseif (in_array($this->getRole(),$this->recorders)){
	if(($byID == TRUE && $instID == TRUE) || ($instID == TRUE && $byID == FALSE)
	 || ($byID == FALSE && $instID == TRUE)) {
	return TRUE;
	}
	} else {
	throw new Pas_NotAuthorisedException($this->_message);
	}
	}


}
