<?php
/** Controller for displaying Roman index pages
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_MintsController extends Pas_Controller_Action_Admin {
	/** Configuration and google apikey
	*/		
	protected $_config, $_googleapikey;
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_config = Zend_Registry::get('config');
	$this->_googleapikey = $this->_config->googlemaps->apikey; 
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('mint',$contexts)
		->initContext();
    }
	/** Set up the index action
	* 
	*/	
	public function indexAction() {
	$rommints = new Romanmints();
	$this->view->rommints = $rommints->getRomanMintsList();
	}
	/** Set up the mint action
	* @todo move the config and key to view
	*/	
	public function mintAction() {
	if($this->_getParam('id',false)) {
	$this->view->headScript()->appendFile('http://maps.google.com/maps?file=api&amp;v=2.x&key='
	. $this->_googleapikey, $type='text/javascript');
	$id = $this->_getParam('id');
	$rommints = new Romanmints();
	$this->view->rommints = $rommints->getMintDetails($id);
	$actives = new Rulers();
	$this->view->actives = $actives->getRomanMintRulerList($id);
	$counts = new Finds();
	$this->view->counts = $counts->getCountMint($id);
	$images = new Slides();
	$this->view->images = $images->getExamplesCoinsMints($id,4);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}