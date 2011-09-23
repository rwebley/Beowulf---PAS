<?php
/** Action admin controller; an extension of the zend controller action
 * 
 * This class allows for various functions and variables to be made 
 * available to all actions that utilise it. Probably could be stream 
 * lined.
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Action
 * @version 1
 * @author Daniel Pett
 * @license GNU
 * @since 23 Sept 2011
 */
class Pas_Controller_Action_Admin extends Zend_Controller_Action {
    
	/**Database ID constant 
	 * 
	 */
	const  DBASE_ID = 'PAS';

	/**The secure ID instance
	 * 
	 */
	const  SECURE_ID = '001';
	
	/** Array of groups in higher level zone
	 * 
	 * @var arrayunknown_type
	 */
	protected $_higherLevel = array('admin','flos','fa','treasure'); 
	protected $_researchLevel = array('member','hero','research');
	protected $_restricted = array('public');
 	protected $_missingParameter = 'The url is missing a parameter. Please check your entry point.';
	protected $_nothingFound = 'We can\'t find anything with that parameter. Please check your entry url carefully.';
	protected $_formErrors = 'Your form submission has some errors. Please check and resubmit.';
	protected $_noChange = 'No changes have been implemented';
	
	protected $_config, $_auth, $_identity;
	
	
	
	public function preDispatch(){
	$this->_config = Zend_Registry::get('config');
	$this->_auth = Zend_Registry::get('auth');
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_identity = new Pas_UserDetails();
	}
	
	public function postDispatch() {
	$this->view->messages = $this->_flashMessenger->getMessages();
    }
    
	protected function getInstitution() {
	return $this->_identity->getPerson()->institution;
	}
	
 	public function getIdentityForForms() {
	return $this->_identity->getIdentityForForms();
	}
	
	
	public function getUsername(){
	return $this->_identity->getPerson()->username;
	}
	
	
	public function getRole() {
	$role = $this->_identity->getPerson()->role;
	if(is_null($role)){
	return 'public';
	} else {
	return $role;
	}
	}
	
	public function getAccount() {
	return $this->_identity->getPerson();
	}
	
	public function getTimeForForms() {
	return Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	}
	
	public function secuid() {
	list($usec,$sec)=explode(" ", microtime());
	$ms=dechex(round($usec*4080));
    while(strlen($ms)<3) {$ms="0".$ms; }
    $secuid=strtoupper(self::DBASE_ID.dechex($sec).self::SECURE_ID.$ms);
    while(strlen($ms)<3) {$ms="0".$ms; }
    $secuid=strtoupper(self::DBASE_ID.dechex($sec).self::SECURE_ID.$ms);
	return $secuid;
	}

	public function FindUid() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	list($usec,$sec)=explode(" ", microtime());
	$suffix =  strtoupper(substr(dechex($sec),3).dechex(round($usec*8)));
	$findid = $inst.'-'.$suffix;
	return $findid;
	}
	}
	
}
