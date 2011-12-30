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
	
	protected $_auth, $_config;
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
	$this->_config = Zend_Registry::get('config');
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
	$users = new Users();
	$this->view->users = $users->getUserProfile($id);
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
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$users = new Users();
	$updateData = array(
	'username' => $this->getUsername(),
	'first_name' => $form->getValue('first_name'),
	'last_name' => $form->getValue('last_name'),
	'fullname' => $form->getValue('fullname'),
	'email' => $form->getValue('email'),
	'copyright' => $form->getValue('copyright'),
	'updated' => $this->getTimeForForms(), 
	'updatedBy' => $this->getIdentityForForms()
	 );
	$where = array();
	$where[] = $users->getAdapter()->quoteInto('id = ?', $this->getIdentityForForms());
	
	$users->update($updateData,$where);
	$this->_flashMessenger->addMessage('You updated your profile successfully.');
	$this->_redirect('/users/account/');
	} else {
	$form->populate($formData);
	$this->_flashMessenger->addMessage('You have some errors with your submission.');
	}
	} else {
	$id = (int)$this->getIdentityForForms();
	if ($id > 0) {
	$users = new Users();
	$user = $users->fetchRow('id =' . $this->getIdentityForForms());
	if(count($user))
	{
	$form->populate($user->toArray());
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
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData))  {
	$users = new Users();
	$userdata = $users->getUserByUsername($form->getValue('email'));
	foreach($userdata as $us) {
	$mail = new Zend_Mail();
	$mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme - Beowulf');
	$mail->setBodyHtml('<p>Dear ' . $u['fullname'] . '</p><p>You requested your username. 
	This is below.</p><ul><li>' . $us['username'] 
	. '</li></ul><p>If you have problems activating your account, please do contact us.</p>
	<p>Yours,</p><p>The Scheme.</p>');
	$mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
	$mail->addTo($form->getValue('email'), $us['username']);
	$mail->setSubject('Portable Antiquities Website username reminder');
	$mail->send();
	}
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
	reset your password if you have forgotten');
	$this->_redirect('/users');
	} 
	$form = new ForgotPasswordForm();
	$this->view->form = $form;		
	if ($this->_request->isPost()){
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$email = $formData['email'];
	$username = $formData['username'];
	$users = new Users();
	$results = $users->findUser($formData['email'],$formData['username']);
	if($results) {
	$length = 6;			
	$password = "";
	// define possible characters
	$possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
    		// set up a counter
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
	$salt = $this->_config->auth->salt;
	$updatesdata = array (
	'updated' => Zend_Date::now()->toString('yyyy-MM-dd HH:mm'),
	'password' => SHA1($salt.$password),
	);
	$where = array();
	$where[] = $users->getAdapter()->quoteInto('username = ?', (string)$username);
	$where[] = $users->getAdapter()->quoteInto('email = ?', (string)$email);
	$users->update($updatesdata, $where);
	$mail = new Zend_Mail();
	$mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme - Beowulf');
	$mail->setBodyText('Dear '.$form->getValue('username')
	. 'Thank you for requesting a new password. This is below'
	. $password
	. 'If you have problems activating your account, please do contact us.
	Yours,
	The Scheme.');
	$mail->setBodyHtml('<p>Dear ' . $form->getValue('username') 
	. '</p><p>Thank you for requesting a new password. This is below.</p><p>' 
	. $password.'</p><p>If you have problems activating your account, please do contact us.</p>
	<p>Yours,</p><p>The Scheme.</p>');
	$mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
	$mail->addTo($form->getValue('email'), $form->getValue('username'));
	$mail->setSubject('Password reset for Portable Antiquities Website');
	$mail->send();
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
	$config = Zend_Registry::get('config');
	$salt = $config->auth->salt;
	$form = new RegisterForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$users = new Users();
	$row = $users->createRow();
	$password = $form->getValue('password');
	$salted = SHA1($salt . $password);		
	$row->username = $form->getValue('username');
	$row->password = $salted;
	$row->activationKey = md5($form->getValue('username') . $form->getValue('first_name'));
	$row->created = Zend_Date::now()->toString('yyyy-MM-dd HH:mm');
	$row->fullname = $form->getValue('preferred_name');
	$row->first_name = $form->getValue('first_name');
	$row->last_name = $form->getValue('last_name');
	$row->email = $form->getValue('email');
	$row->valid = 0;
	$row->role = 'member';
	$row->institution = 'PUBLIC';
	$row->imagedir = 'images/'.$form->getValue('username').'/';
	$row->save();
	$message = 'Dear '.$form->getValue('preferred_name');
	$message .= "/n";
	$message .= 'Thank you for registering with the Scheme. By doing so you will get access to enhanced features and if required higher level access.';
	$message .= "/n";
	$message .= 'If you have problems activating your account, please do contact us.';
	$message .= 'To activate your account please vist your personal activation link:';
	$message .= "/n";
	$message .= '<a href="http://www.finds.org.uk/users/account/activate/username/'.$form->getValue('username').'/activationKey/'.md5($form->getValue('username').$form->getValue('first_name')).'">Activate me</a>';
	$message .= "/n";
	$message .= 'Yours,';
	$message .= 'The Scheme.';
	$mail = new Zend_Mail();
	$mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme - Beowulf');
	$mail->setBodyText($message);
	$mail->setBodyHtml('<p>Dear '.$form->getValue('preferred_name').'</p><p>Thank you for registering with the Scheme. By doing so you will get access to enhanced features and if required higher level access.</p><p>If you have problems activating your account, please do contact us.</p><p>To activate your account please vist your personal activation link: <a href="http://www.finds.org.uk/users/account/activate/username/'.$form->getValue('username').'/activationKey/'.md5($form->getValue('username').$form->getValue('first_name')).'">Activate me</a><p>Yours,</p><p>The Scheme.</p>');
	$mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
	$mail->addTo($form->getValue('email'), $form->getValue('preferred_name'));
	$mail->addCC('info@finds.org.uk','ICT Adviser - PAS');
	$mail->setSubject('Activation email for the Scheme\'s website');
	$mail->send();
	$this->_flashMessenger->addMessage('Your account has been created. Please check your email. <br>
	If an email does not arrive contact <a href=\"mailto:info@finds.org.uk?subject=Account email missing\">head office</a>');
	$this->_redirect('/users/');
	} else {
	$form->populate($formData);
	$this->_flashMessenger->addMessage('There are a few problems with your registration<br>
	Please review and correct them.');
	}
	}
	}
	}
	/** Activate an account
	*/	
	public function activateAction(){
	$params = $this->_getAllParams();		
	$username = $params['username']; 
	$key = $params['activationKey']; 
	$users = new Users();
	$results = $users->activation($key,$username);
	if ($results) {
	$updatesdata = array (
	'updated' => Zend_Date::now()->toString('yyyy-MM-dd HH:mm'),
	'valid' => '1',
	'activationKey' => NULL,
	'role' => 'member',
	'institution' => 'PUBLIC'
	);
	$where = array();
	$where[] = $users->getAdapter()->quoteInto('username = ?', $username);
	$where[] = $users->getAdapter()->quoteInto('activationKey = ?', $key);
	$where[] = $users->getAdapter()->quoteInto('valid = ?', '0');
	$users->update($updatesdata, $where);
	$imagepath = PATH . $username;
	$smallimagepath = PATH . $username . '/small/';
	$mediumimagepath = PATH . $username . '/medium/';
	$displayimagepath = PATH . $username . '/display/';
	mkdir($imagepath);
	mkdir($smallimagepath);
	mkdir($mediumimagepath);
	mkdir($displayimagepath);
	$this->_flashMessenger->addMessage('Your account has been activated');	
	$this->_redirect('/users/');
	} else {			{
	$this->view->title = 'Account activation problem';
	$this->_flashMessenger->addMessage('There has been a problem activating your account');		
	echo '<h2>Account cannot be activated</h2><p>There could be several reasons.</p><ul id="related"><li>The account has already been activated.</li><li>You have entered a truncated link from your email</li></ul>';
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
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$config = Zend_Registry::get('config');
	$salt = $config->auth->salt;
	$password = SHA1($salt . $form->getValue('password'));
	$updateData = array(
	'password' => $password,
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	$users = new Users();
	$where = array();
	$where[] = $users->getAdapter()->quoteInto('id = ?', $this->getIdentityForForms());
	
	$users->update($updateData,$where);
	
	$this->_flashMessenger->addMessage('You have changed your password, don\'t ring and ask us what it is now, we don\'t know!');
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
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$id = $user->id;
	$fullname = $user->fullname;
	$email = $user->email;
	$users = new Users();
	$updateData = array();
	$updateData['higherLevel'] = 1;
	$updateData['researchOutline'] = $form->getValue('researchOutline');
	$updateData['already'] = $form->getValue('already');
	$updateData['reference'] = $form->getValue('reference');
	$updateData['referenceEmail'] = $form->getValue('referenceEmail');
	$where = array();
	$where[] =  $users->getAdapter()->quoteInto('id = ?', (int)$id);
	$update = $users->update($updateData,$where);
	$message = 'Dear ' . $fullname . '
	Thank you for asking to be upgraded to research level on our database. This should be processed within 15 working days, and we may have to contact your referee. 
		
	Enclosed is a copy of our terms and conditions that you agree to abide by. These cannot be changed.
	Yours,
	The Scheme.';
	$htmlmessage = '<p>Dear '.$fullname.'</p><p>Thank you for asking to be upgraded to research level on our database. This should be processed within 15 working days, and we may have to contact your referee. 
	Enclosed is a copy of our terms and conditions that you agree to abide by. These cannot be changed.</p><p>Yours,</p><p>The Scheme.</p>';
	$tandc	 = file_get_contents('/home/beowulf/public_html/documents/tac.pdf'); 
	$mail = new Zend_Mail();
	$mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme - Beowulf');
	$mail->setBodyText($message);
	$mail->setBodyHtml($htmlmessage);
	$mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
	$mail->addTo($user->email, $user->fullname);
	$mail->addCC('info@finds.org.uk','ICT Adviser - PAS');
	$mail->setSubject('Re: your request for an account upgrade');
	$attachment = $mail->createAttachment($tandc); 
	$attachment->filename = 'Termsandconditions.pdf'; 
	$mail->send();
	$this->_flashMessenger->addMessage('Thanks '. $user->fullname .'!We have received your request. If you have not heard within 21 days then please <a href=\"mailto:info@finds.org.uk?subject=Account email missing\">head office</a> or ring 0207 323 8618');
	$this->_redirect('/users/account/');
	} else {
	$form->populate($formData);
	$this->_flashMessenger->addMessage('There are a few problems with your registration<br>
	Please review and correct them.');
	}
	}
	} else {
	$this->_flashMessenger->addMessage('You can\'t request an upgrade as you already have '.$role.' status!');	
	$this->_redirect('/users/account/');	
	}
	}
}