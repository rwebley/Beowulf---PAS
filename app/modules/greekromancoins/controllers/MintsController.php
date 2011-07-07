<?php
/** Controller for displaying mints from the Greek and Roman provincial world
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GreekRomanCoins_MintsController extends Pas_Controller_ActionAdmin {
	/** Initialise the ACL and contexts
	*/ 
	public function init()  {
 	$this->_helper->_acl->allow(null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
			 ->addActionContext('index', array('xml','json'))
			 ->addActionContext('mint', array('xml','json'))
             ->initContext();
    }
	/** Internal period number
	*/ 
	protected $_period = '66';
	
	/** Initialise the index pages
	*/  
    public function indexAction()  {
    $greeks = new Mints();
    $this->view->greeks = $greeks->getMintsGreekList();
    }
	/** Set up the mint action
	*/     
    public function mintAction() {
    
    $byzantium = new Mints();
    $this->view->byzantium = $byzantium->getMintDetails($this->_getParam('id'));
	
    $images = new Slides();
	$this->view->images = $images->getExamplesCoinsMints($this->_getParam('id'),4);
    }
}