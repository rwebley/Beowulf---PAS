<?php
/** Controller for displaying byzantine mint pages with recent examples
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ByzantineCoins_MintsController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/ 
	public function init()  {
 	$this->_helper->_acl->allow(null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
			 ->addContext('csv',array('suffix' => 'csv'))
 			 ->addContext('kml',array('suffix' => 'kml'))
			 ->addActionContext('index', array('xml','json'))
			 ->addActionContext('mint', array('xml','json'))
             ->initContext();
    }

    protected $_period = '67';
	/** Set up the index pages
	*/ 
    public function indexAction() {
		$byzantium = new Mints();
		$this->view->byzantium = $byzantium->getMintsByzantineList();
	}
	/** Set up the specific mint page
	*/
	public function mintAction() {
	if($this->_getParam('id',false)){
		$byzantium = new Mints();
		$this->view->byzantium = $byzantium->getMintDetails($this->_getParam('id'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}