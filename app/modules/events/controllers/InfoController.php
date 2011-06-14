<?php
/**
* The information controller for the events package
*
* @category   Pas
* @package    Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Events_InfoController extends Pas_Controller_ActionAdmin {

	/**
	* Initialise the ACL for access levels and the context switches
	*/
    public function init() {
       	$contexts = array('xml','json','ics');
	  	$contextSwitch = $this->_helper->contextSwitch();
		$contextSwitch->addContext('ics',array('suffix' => 'ics'))
  			 ->addContext('rdf',array('suffix' => 'rdf'))
   			 ->addContext('xcs',array('suffix' => 'xcs'))
	  	     ->addActionContext('index', $contexts)
             ->initContext();
		$this->_helper->acl->allow('public',null);
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }
	/**
	* Render data for view on index action
	*/	
	function indexAction() {
	$events = new Events();
	$this->view->events = $events->getEventData($this->_getParam('id'));
	}


}