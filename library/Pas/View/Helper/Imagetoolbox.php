<?php
/** A view helper for image link toolbox generation
 * 
 * @author dpett
 *
 */
class Pas_View_Helper_ImageToolbox 
		extends Zend_View_Helper_Abstract {
	
	protected $noaccess = array('public');
	protected $restricted = array('member','research','hero');
	protected $recorders = array('flos');
	protected $higherLevel = array('admin','fa','treasure');
	protected $_missingGroup = 'User is not assigned to a group';
	protected $_auth = NULL;
	
	/** Construct the auth object
	* 
	*/
	public function __construct() { 
	$auth = Zend_Auth::getInstance();
	$this->_auth = $auth; 
	}
	
    /** Get the user's role
     * 
     */
	public function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	/** Get the user ID
	* 
	*/
	public function getUserID() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}

	/** Check access by user ID
	 * 
	 * @param int $userID
	 * @param int $createdBy
	 */
	public function checkAccessbyUserID($userID, $createdBy) {
	if($userID === $createdBy) {
	return true;
	}
	}
	
	/** Build the html based on the id number of image
	 * @return string $string
	 * @param int $id
	 */
	public function buildHtml($id) {
	$editurl = $this->view->url(array('module' => 'database','controller' => 'images',
	'action' => 'edit','id' => $id),null,TRUE);
	$deleteurl = $this->view->url(array('module' => 'database','controller' => 'images',
	'action' => 'delete','id' => $id),null,TRUE);
	$string = 	' | <a href="'.$editurl.'" title="Edit image">Edit</a> | <a href="' . $deleteurl 
	. '" title="Delete this image">Delete</a>';
	return $string;
	}
	
	/** Create the image toolbox
	 * 
	 * @param int $id
	 * @param int $createdBy
	 */
	public function ImageToolbox($id, $createdBy) {
	$byID = $this->checkAccessbyUserID($this->getUserID(),$createdBy);
	if(in_array($this->getRole(),$this->noaccess)) {
	return FALSE;
	} else if(in_array($this->getRole(),$this->higherLevel))	{
	return $this->buildHtml($id);
	} else if(in_array($this->getRole(),$this->recorders)){
	if(($byID == TRUE)){
	return $this->buildHtml($id);	
	}	
	} else if(in_array($this->getRole(),$this->restricted)){
	if(($byID == TRUE)){
	return $this->buildHtml($id);	
	}	
	} else {
	return FALSE;	
	}
	}
		
}