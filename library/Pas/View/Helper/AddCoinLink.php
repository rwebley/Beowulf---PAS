<?php
class Pas_View_Helper_AddCoinLink extends Zend_View_Helper_Abstract
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
public function getInst()
	{
	if($this->_auth->hasIdentity())	{
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	if(is_null($inst)){
	throw new Exception($this->_missingGroup);	
	}
	return $inst;
	} else {
	return FALSE;
	}	
	}
	public function checkAccessbyInstitution($oldfindID)
	{
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return TRUE;
	} else if($id == $inst) {
	return TRUE;
	}
	}

	public function checkAccessbyUserID($userID,$createdBy)
	{
	if($userID == $createdBy) {
	return TRUE;
	}
	}

	public function getUserGroups()
	{
	if($this->_auth->hasIdentity())
	{
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	} else {
	throw new Exception($this->_missingGroup);
	}	
	return $inst;
	}
	
	public function AddCoinLink($oldfindID,$findID,$secuid,$createdBy,$broadperiod)
	{
	$byID = $this->checkAccessbyUserID($this->getIdentityForForms(),$createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	
	if(in_array($this->getRole(),$this->restricted)) {
	if(($byID == TRUE && $instID == false) || ($byID == TRUE && $instID == TRUE)) {
	return $this->buildHtml($findID,$secuid,$broadperiod);
	} 
	} else if(in_array($this->getRole(),$this->higherLevel)) {
	return $this->buildHtml($findID,$secuid,$broadperiod);
	} else if(in_array($this->getRole(),$this->recorders)){
	if(($byID == TRUE && $instID == false) || ($byID == TRUE && $instID == TRUE) ||
	($byID == FALSE && $instID == TRUE)) {
	return $this->buildHtml($findID,$secuid,$broadperiod);
	} 	
	} else {
	return false;
	}
	}
	
	public function buildHtml($findID,$secuid,$broadperiod)
	{
	$url = $this->view->url(array('module' => 'database','controller' => 'coins','action' => 'add', 
	'broadperiod' => $broadperiod,'findID' => $secuid,'returnID' => $findID),NULL,TRUE);
	$string = '<a href="'.$url.'" title="Add '.$broadperiod.' coin data" accesskey="m">Add '.$broadperiod.' coin data</a>';
	return $string;
	}

}
