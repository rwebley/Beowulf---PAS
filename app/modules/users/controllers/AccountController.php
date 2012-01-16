<?php
/** Controller for accessing user account stuff
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_AccountController extends Pas_Controller_Action_Admin {
	
	protected $_auth, $_config, $_users;
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow('public',array(
	'forgotten', 'register', 'activate',
	'index', 'logout', 'edit',
	'forgotusername'));
	$this->_helper->_acl->allow('member',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_auth = Zend_Registry::get('auth');
	$this->_users = new Users();
	}
	
	const PATH = './images/';
	/** Set up index page
	*/		
	public function indexAction() {
	// If user isn't logged in, show login form
	if (null === $this->_auth->getIdentity()) {
	$this->_helper->redirector->gotoRouteAndExit(array(
	'module' => 'users', 'controller' => 'index'));
	} else {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	$this->view->users = $this->_users->getUserProfile($id);
	$finds = new Finds();
	$this->view->finds = $finds->getFindsRecorded($id);
	$this->view->totals = $finds->getTotalFindsRecorded($id);
	}
    }


	/** Log a user out and clear identity
	*/	
    public function logoutAction() {
	$this->_auth->clearIdentity();
	$this->_flashMessenger->addMessage('You have now logged out');
	return $this->_redirect('/users/');
    }
	/** Edit the user details
	*/	
	public function editAction() {
	$form = new ProfileForm();
	$form->removeElement('password');
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$where = array();
	$where[] = $users->getAdapter()->quoteInto('id = ?', $this->getIdentityForForms());
	
	$this->_users->update($form->getValues(), $where);
	$this->_flashMessenger->addMessage('You updated your profile successfully.');
	$this->_redirect('/users/account/');
	} else {
	$form->populate($form->getValues());
	$this->_flashMessenger->addMessage('You have some errors with your submission.');
	}
	} else {
	$id = (int)$this->getIdentityForForms();
	if ($id > 0) {
	$user = $this->_users->fetchRow('id =' . $this->getIdentityForForms())->toArray();
	if($user) {
	$form->populate($user);
	} else {
		throw new Exception('No user account found with that id');
	}
	}
	}
	}
	/** Retrieve the username
	*/		
	public function forgotusernameAction() {
	if ($this->_auth->getIdentity()) {
	$this->_flashMessenger->addMessage('You are already logged in! 
	Your username is under your account!');
    $this->_redirect('/users');
    } else {
	$form = new ForgotUsernameForm();
	$this->view->form = $form; 
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$userData = $this->_users->getUserByUsername($form->getValue('email'));
	$to = array(array(
		'email' => $form->getValue('email'), 
		'name' => $userData[0]['fullname'])
	);
	$this->_helper->mailer($userData[0], 'forgottenUsername', $to);
	$this->_flashMessenger->addMessage('Account reminder sent to your email address');
	$this->_redirect('/users/');
	} else {
	$this->_flashMessenger->addMessage('Problems have been found with your submission');
	$form->populate($formData);
	} 
	}
	}
	}
	/** Retrieve a password
	*/	
	public function forgottenAction() {
	if ($this->_auth->getIdentity()) {
	$this->_flashMessenger->addMessage('You are already logged in, 
	reset your password if you have forgotten it!');
	$this->_redirect('/users');
	} 
	$form = new ForgotPasswordForm();
	$this->view->form = $form;		
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$email = $formData['email'];
	$username = $formData['username'];
	$results = $this->_users->findUser($form->getValue('email'), $form->getValue('username'));
	if($results) {
	$length = 6;			
	$password = "";
	// define possible characters
	$possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
	$i = 0; 
	// add random characters to $password until $length is reached
	while ($i < $length) { 
	// pick a random character from the possible ones
	$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
	// we don't want this character if it's already in the password
	if (!strstr($password, $char)) { 
	$password .= $char;
	$i++;
	}
	} 
	$updatesdata = array (
	'password' => SHA1($this->_helper->config->auth->salt . $password),
	);
	$to = array(array('email' => $form->getValue('email'), 'name' => $results[0]['fullname']));
	$assignData = array_merge($results[0],array('password' => $password),$form->getValues());
	$this->_helper->mailer($assignData, 'forgottenPassword', $to );
	$where = array();
	$where[] = $this->_users->getAdapter()->quoteInto('username = ?', (string)$username);
	$where[] = $this->_users->getAdapter()->quoteInto('email = ?', (string)$email);
	$this->_users->update($updatesdata, $where);
	$assignData = array_merge($updatesdata,$form->getValues());
	
	$this->_flashMessenger->addMessage('A new password has been sent to you');
	$this->_redirect('/users/');
	} else {
	$this->_flashMessenger->addMessage('Either your email address/or username is incorrect.');
	}
	} else {
	$this->_flashMessenger->addMessage('You have not filled in the form correctly. 
	Please check the error messages below.');
	}
	}
	}

	/** Register for an account
	*/	
	public function registerAction() {
		
	if($auth->hasIdentity()) {
	$this->_flashMessenger->addMessage('You are already logged in and registered.');
	$this->_redirect('/users/account');
	} else {
	$salt = $config->auth->salt;
	$form = new RegisterForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
    $to = array(array('email' => $form->getValue('email'),'name' => $form->getValue('fullname')));
	$this->_users->register($form->getValues());
	$this->_helper->mailer($form->getValues(), 'activateAccount', $to);
	$this->_flashMessenger->addMessage('Your account has been created. Please check your email.');
	$this->_redirect('/users/');
	} else {
	$form->populate($form->getValues());
	$this->_flashMessenger->addMessage('There are a few problems with your registration<br>
	Please review and correct them.');
	}
	}
	}
	}
	/** Activate an account
	*/	
	public function activateAction(){
	$results = $this->_users->activation($this->_getParam['activationKey'], $this->_getParam['username']);
	if ($results) {
	$this->_users->activate($results);
	$this->_flashMessenger->addMessage('Your account has been activated');	
	$this->_redirect('/users/');
	} else {			{
	$this->_flashMessenger->addMessage('There has been a problem activating your account');		
	$form->populate($form->getValues());
	}
	}
    }
	/** List user's logins
	*/	
	public function loginsAction() {
	$logins = new Logins();
	$this->view->logins = $logins->myLogins($this->getUsername(),$this->_getParam('page'));
	$this->view->ips = $logins->myIps($this->getUsername());
	}
	/** Change a password
	*/	
	public function changepasswordAction() {
	$form = new ChangePasswordForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$password = SHA1($this->_helper->config->auth->salt . $form->getValue('password'));
	$where = array();
	$where[] = $users->getAdapter()->quoteInto('id = ?', $this->getIdentityForForms());
	$users->update($form->getValues(),$where);
	$this->_flashMessenger->addMessage('You have changed your password');
	$this->_redirect('/users/account/');
	} else {
	$form->populate($formData);
	}
	}
	}	
	/** Upgrade an account
	*/	
	public function upgradeAction() {
	$role = $this->getRole();
	$allowed = array('public','member');
	if(in_array($role,$allowed)) {
	$user = $this->getAccount();
	$form = new AccountUpgradeForm();
	$this->view->form = $form;
	if($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())){
    if ($form->isValid($form->getValues())) {
	$where = array();
	$where[] =  $this->_users->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$update = $this->_users->update($updateData, $where);
	$to = array(array('email' => $user->email, 'name' => $user->fullname));
	$attachments = array('/home/beowulf/public_html/documents/tac.pdf');
	$assignData = array_merge($to[0], $form->getValues());
	$this->_helper->mailer($assignData, 'upgradeRequested', null, $to, $to, null, $attachments);
	$this->_flashMessenger->addMessage('Thank you! We have received your request.');
	$this->_redirect('/users/account/');
	} else {
	$form->populate($form->getValues());
	$this->_flashMessenger->addMessage('There are a few problems with your registration<br>
	Please review and correct them.');
	}
	}
	} else {
	$this->_flashMessenger->addMessage('You can\'t request an upgrade as you already have ' . $role . ' status!');	
	$this->_redirect('/users/account/');	
	}
	}
}