<?php
/** Controller for displaying user entered records
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Users_RecordsController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/
	public function init() {	
	$this->_helper->_acl->deny('public');
	$this->_helper->_acl->allow('member',NULL);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/** Set up the index list
	*/
	public function indexAction() {
	$finds = new Finds();
	$this->view->finds = $finds->getRecordsByUserAcct($this->getAccount()->id,$this->_getParam('page'));
	}
	/** Display the map
	*/	
	public function mappedAction() {
	}

}