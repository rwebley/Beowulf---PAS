<?php
/** Controller for scrollintg through users. Minimum access to members only.
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_CommentsController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/
	public function init()  {	
	$this->_helper->_acl->allow('member',NULL);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/** No access to the index page, redirect to the comments you made
	*/	
    public function indexAction(){
	return $this->_redirect('/users/comments/imade/');
	}
	/** Comments made my user
	*/
	public function imadeAction() {
	$params = $this->_getAllParams();
	$this->view->params = $params;
	$comments = new Comments();
	$this->view->comments = $comments->getComments($params, $this->getIdentityForForms());
	}
	/** Comments on your records
	*/	
	public function onmineAction() {
	$params = $this->_getAllParams();
	$this->view->params = $params;
	$comments = new Comments();
	$this->view->comments = $comments->getCommentsOnMyRecords($this->getIdentityForForms(), 
	$this->_getParam('page'), $this->_getParam('approval'));
	}
	
}
