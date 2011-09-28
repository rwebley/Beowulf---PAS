<?php
/** A view helper class to check for findspot edit rights.
* @todo Perhaps change to an asserts acl check
* @todo DRY the access groups to another class, this is used too often
* @category Pas
* @package  Pas_View_Helper
* @subpackage Abstract
* @since September 27 2011
* @author Daniel Pett
* @version 1
*/
class Pas_View_Helper_FindSpotEditCheck
	extends Zend_View_Helper_Abstract {
	
	/** Array where no access is granted
	 * @var array $_noaccess
	 */
	protected $_noaccess = array('public');
	
	/** Array of restricted access
	 * @var array $_restricted
	 */
	protected $_restricted = array('member','research','hero');
	
	/** Array of users roles with recording privileges
	 * @var array $_recorders
	 */
	protected $_recorders = array('flos');
	
	/** Array of higher level users
	 * @var array $_higherLevel
	 */
	protected $_higherLevel = array('admin','fa','treasure');
	
	/** The authority object
	 * @var object $_auth
	 */
	protected $_auth = NULL;
	
	/** Message for missing group exception
	 * @var string $_missingGroup
	 */
	protected $_missingGroup = 'User is not assigned to a group';
	
	/** Message for access rights exception
	 * @var string $_message
	 */
	protected $_message = 'You are not allowed edit rights to this record';
	
	/** Construct the auth object
	 */
	public function __construct() { 
	$auth = Zend_Auth::getInstance();
	$this->_auth = $auth; 
    }

    /** Get the user's role
     */
	public function getRole(){
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}

	/** Get the identity for forms
	 */
	public function getIdentityForForms(){
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else {
	$id = '3';
	return $id;
	}
	}
	
	/** Check for access via user institution
	 * @return boolean
	 * @param string $findspotID
	 */
	public function checkAccessbyInstitution( $findspotID ) {
	$find = explode('-', $findspotID);
	$id = $find['0'];
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return true;
	} else if($id === $inst) {
	return true;
	} else {
		return false;
	}
	}

	/** Check for access via user userid
	 * 
	 * @param int $userID
	 * @param int $createdBy
	 */
	public function checkAccessbyUserID($userID,$createdBy){
	if($userID == $createdBy) {
	return true;
	}
	}

	/** Check for user institution
	 */
	public function getInst(){
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	} else {
	return FALSE;
	}	
	
	}
	
	/** Check the findspot edit permissions
	 * 
	 * @param string $findspotID
	 * @param int $createdBy
	 */
	public function findSpotEditCheck($findspotID,$createdBy) {
	$byID = $this->checkAccessbyUserID($this->getIdentityForForms(), $createdBy);
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
	throw new Pas_Exception_NotAuthorised($this->_message);
	}
	}


}
