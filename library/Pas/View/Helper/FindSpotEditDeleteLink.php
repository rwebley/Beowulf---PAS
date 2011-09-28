<?php
/** A view helper class to display the findspot edit delete links
* @todo Perhaps change to an asserts acl check
* @todo DRY the access groups to another class, this is used too often
* @category Pas
* @package  Pas_View_Helper
* @subpackage Abstract
* @since September 27 2011
* @author Daniel Pett
* @version 1
* @uses Zend_View_Helper_Url
*/
class Pas_View_Helper_FindSpotEditDeleteLink 
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
	public function getRole() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	}
	}
	/** Get the identity 
	 */
	public function getUserID() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}
	/** Check for access by userID
	 * @param int $createdBy
	 */	
	public function checkAccessbyUserID($createdBy) {
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
	/** Check for access by institution
	 * 
	 * @param string $findspotID
	 */
	public function checkAccessbyInstitution($findspotID){
	$find = explode('-', $findspotID);
	$id = $find['0'];	
	$inst = $this->getInst();
	if($id == $inst) {
	return true;
	} else if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return true;
	}
	}
	
	/** Get the user's institution
	 */
	public function getInst() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	} else {
	//throw new Exception($this->_missingGroup);
	return FALSE;
	}	
	}
	
	/** Check and display links for edit
	 * 
	 * @param string $findspotID
	 * @param int $ID
	 * @param int $createdBy
	 */
	public function FindSpotEditDeleteLink($findspotID, $ID, $createdBy) {
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
	
	/** Build the HTML links
	 * 
	 * @param int $ID
	 * @return string $html
	 */
	public function buildHtml($ID) {
	$editurl = $this->view->url(array('module' => 'database','controller' => 'findspots','action' => 'edit',
	'id' => $ID),null,true);
	$deleteurl = $this->view->url(array('module' => 'database','controller' => 'findspots','action' => 'delete',
	'id' => $ID),null,true);
	$html = '<p><a href="' . $editurl
	. '" title="Edit spatial data for this record">Edit findspot</a> | <a href="' . $deleteurl 
	. '" title="Delete spatial data">Delete findspot</a></p>';
	return $html;
	}

}
