<?php
class Pas_View_Helper_AddRefLink extends Zend_View_Helper_Abstract
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
	
	public function getUserID()
	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}

	
	public function getIdentityForForms()
	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else {
	$id = '3';
	return $id;
	}
	}
	
	public function checkAccessbyInstitution($oldfindID)
	{
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getUserGroups();
	if(($id == $inst)) {
	return TRUE;
	} else if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return TRUE;
	} else {
	return FALSE;	
	}
	}
	
	public function getUserGroups()
	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	} else {
	return FALSE;
		//throw new Exception($this->_missingGroup);
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

	
	public function AddRefLink($oldfindID,$findID,$secuid,$createdBy)
	{
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(in_array($this->getRole(),$this->noaccess)) {
	return false;
	} else if(in_array($this->getRole(),$this->restricted)) {
	if(($byID == TRUE && $instID == TRUE) || ($byID == TRUE && $instID == FALSE)) {
	return $this->buildHtml($findID,$secuid);
	}
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return $this->buildHtml($findID,$secuid);
	} else if(in_array($this->getRole(),$this->recorders)) {
	if((($byID == TRUE) && ($instID == FALSE)) || (($byID == FALSE) && ($instID == TRUE))
	|| ($byID == TRUE && $instID == TRUE)) {
	return $this->buildHtml($findID,$secuid);
	}
	}
	}
	
	public function buildHtml($findID,$secuid)
	{
	$url = $this->view->url(array('module' => 'database','controller' => 'references','action' => 'add',
	'findID' => $findID,'secID' => $secuid),NULL,TRUE);
		
	$string = '<div id="addref" class="addpanel noprint"><a href="' . $url 
	. '" title="Add a reference" accesskey="r">Add a reference</a></div>';

	return $string;
	}

}
