<?php 
/** Controller for scrollintg through users. Minimum access to members only.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_NamedController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow('member',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/** Set up the index page
	*/
	public function indexAction(){
	$users = new Users();
	$this->view->users = $users->getUsersAdmin($this->_getAllParams());
	}
	/** View the individual person's account
	*/
	public function personAction() {
	if($this->_getParam('as',0)){
	$id = $this->_getParam('as');
	$users = new Users();
	$this->view->accountdata = $users->getUserAccountData($id);
	$this->view->totals = $users->getCountFinds($this->getIdentityForForms());
	$slides = new Slides();
	$this->view->images = $slides->recentFinds($id);
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
}
