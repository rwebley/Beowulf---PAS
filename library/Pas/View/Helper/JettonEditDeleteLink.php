<?php
/** A view helper for creating links for jettons and tokens
 * @todo This could perhaps be DRYed out and merged with coins
 * @category Pas
 * @package Pas_View
 * @subpackage  Helper
 * @author Daniel Pett
 * @since September 30 2011
 * @version 1
 * @license GNU
 * @copyright Daniel Pett
 */
class Pas_View_Helper_JettonEditDeleteLink 
	extends Zend_View_Helper_Abstract {
	
	protected $_noaccess = array('public');
	protected $_restricted = array('member','research','hero');
	protected $_recorders = array('flos');
	protected $_higherLevel = array('admin','fa','treasure');
	protected $_missingGroup = 'User is not assigned to a group';
	protected $_message = 'You are not allowed edit rights to this record';
	
	protected $_auth = NULL;
	
	/** Construct the auth object
	 * 
	 */
	public function __construct()  { 
    $auth = Zend_Auth::getInstance();
    $this->_auth = $auth; 
    }

    /** GET THE USER'S ROLE
     * 
     */
	public function getRole(){
	if($this->_auth->hasIdentity())	{
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	/** Check access by institution to a find
	 * 
	 * @param string $oldfindID The record's find ID
	 */
	public function checkAccessbyInstitution($oldfindID) {
	$find = explode('-', $oldfindID);
	$id = $find['0'];
	$inst = $this->getInst();
	if($id == $inst) {
	return true;
	} else if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return true;
	}
	}

	/** Get the user's institution
	 * 
	 */
	public function getInst() {
	if($this->_auth->hasIdentity())	{
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	if(is_null($inst)){
	throw new Pas_Exception_Group($this->_missingGroup);	
	}
	return $inst;
	} else {
	return FALSE;
	}	
	}
	
	/** get the user's id number
	 */
	public function getUserID() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}
	
	/** Check access by the user's ID
	 * 
	 * @param int $createdBy The id number for the creator
	 */
	public function checkAccessbyUserID($createdBy) {
	if($createdBy == $this->getUserID()) {
	return TRUE;
	} else {
	return FALSE;
	}
	}

	
	
	/** Create the links for jetton
	 * 
	 * @param string $oldfindID The find unique id
	 * @param int $id The coin table primary key
	 * @param string $broadperiod The record's period - determines data to asign
	 * @param string $secuid The find table secuid string 
	 * @param int $returnID The id of find to return to
	 * @param int $createdBy Record creator id number
	 */
	public function JettonEditDeleteLink($oldfindID, $id, $broadperiod, $secuid, $returnID, $createdBy) {
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($oldfindID);
	if(in_array($this->getRole(),$this->_restricted)) {
	if(($byID == true && $instID == true) || ($byID == TRUE && $instID == FALSE)) {
	return $this->buildHtml($id,$broadperiod,$secuid,$returnID);
	} 
	} else if(in_array($this->getRole(),$this->_higherLevel)) {
	return $this->buildHtml($id,$broadperiod,$secuid,$returnID);
	} else if (in_array($this->getRole(),$this->_recorders)){
	if(($byID == true && $instID == true) || ($byID == false && $instID == true)
	|| ($byID == TRUE && $instID == FALSE)	) {
	return $this->buildHtml($id,$broadperiod,$secuid,$returnID);
	}
	} else {
	return false;
	}
	}
	
	/** Build and return the HTML
	 * @param id $id The coin table primary key
 	 * @param string $broadperiod The find broadperiod
	 * @param string $secuid The find table secuid assigned
	 * @param int $returnID The find number to return to
	 * @return string $string 
	 */
	public function buildHtml($id,$broadperiod,$secuid,$returnID) {
	$editurl = $this->view->url(array('module' => 'database','controller' => 'jettons','action' => 'edit',
	'broadperiod' => $broadperiod,'findID' => $secuid,'id' => $id,'returnID' => $returnID),null,TRUE);
	$deleteurl = $this->view->url(array('module' => 'database','controller' => 'jettons','action' => 'delete',
	'id' => $id),null,TRUE);
	$string = '<span class="noprint"><p><a href="' . $editurl
	. '" title="Edit numismatic data for this record">Edit numismatic data</a> | <a href="' . 
	$deleteurl . '" title="Delete numismatic data">Delete</a></p></span>';
	return $string;
	}

}
