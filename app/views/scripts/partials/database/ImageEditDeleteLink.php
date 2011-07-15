<?php


class Pas_View_Helper_ImageEditDeleteLink extends Zend_View_Helper_Abstract {

	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_missingGroup = 'User is not assigned to a group';
	
	protected $_auth = NULL;
	
	public function __construct()
    { 
    $auth = Zend_Auth::getInstance();
    $this->_auth = $auth; 
    }
	
		
	public function getRole()
	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	public function getUserID()
	{
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}	
	
	public function checkAccessbyUserID($createdBy)
	{
	if(!in_array($this->getRole(),$this->restricted)) {
	return TRUE;
	} else if(in_array($this->getRole(),$this->restricted)) {
	if($createdBy == $this->getUserID()) {
	return TRUE;
	}
	} else {
	return FALSE;
	}
	}
	
	public function buildHtml($id,$returnID,$secuid)
	{
	$unlink = $this->url(array('module' => 'database','controller' => 'images','action' => 'unlink',
	'id' => $id,'returnID' => $returnID, 'secuid' => $secuid),null,true);
	$string = '| <a href="' . $unlink . '" title="Unlink this image">Unlink</a>';
	return $string;
	}
	
	public function ImageEditDeleteLink($id,$returnID,$secuid,$createdBy)
	{
	$byID = $this->checkAccessbyUserID($this->getUserID(),$createdBy);		
	if(in_array($this->getRole(),$this->noaccess)){
	return FALSE;	
	} else if(in_array($this->getRole(),$this->restricted) && $byID == TRUE){
	$this->buildHtml($id,$returnID,$secuid);	
	} else if(in_array($this->getRole(),$this->recorders)){
	$this->buildHtml($id,$returnID,$secuid);	
	} elseif(in_array($this->getRole(),$this->higherLevel)) {
	$this->buildHtml($id,$returnID,$secuid);
	} else {
	return FALSE;
	}
	}
}
