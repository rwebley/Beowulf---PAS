<?php
/** Controller for administering oauth and setting up tokens
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Admin_OauthController extends Pas_Controller_Action_Admin {
	
	protected $_config;
	
	protected $_tokens; 
	
	/** Set up the ACL and resources
	*/		
	public function init() {
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_tokens = new OauthTokens();
    }
    
	/** List available Oauth tokens
	*/	
    public function indexAction() {
    $this->view->tokens = $this->_tokens->fetchAll();
    }
    
	/** Initiate request to create a yahoo token. This can only be done when logged into Yahoo
	 * and also as an admin
	*/	
    public function yahooAction() {
    $yahoo = new Yahoo();
    $request = $yahoo->request();
	}
    
	/** Initiate request to create a yahoo token. This can only be done when logged into Yahoo
	 * and also as an admin
	*/	
    public function yahooaccessAction(){
	$yahoo = new Yahoo();
	$data = $yahoo->access();
	}
	
	public function twitterAction(){
	}
	
	public function googleAction(){
		
	}
}