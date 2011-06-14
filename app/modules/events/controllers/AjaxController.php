<?php
/**
* Screen scraped British Museum events controller. This is because they don't have RSS!!!
*
* @category   Pas
* @package    Controller
* @subpackage ActionAjax
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
*/
class Events_AjaxController extends Pas_Controller_ActionAjax {

	/**
	* Initialise the ACL for access levels and the contexts
	*/
	public function init() {
		$this->_helper->_acl->allow(NULL);
		$this->_helper->layout->disableLayout();  
    }

    /** Return data for the index action
	*/
	public function indexAction(){
	}
	/** Return data for the event data ajax page
	*/
	public function eventdataAction() {
	$events = new Events();
	$this->view->mapping = $events->getMapdata();
	}
}