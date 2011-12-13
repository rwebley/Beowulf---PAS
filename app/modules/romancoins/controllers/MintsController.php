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
	$id = $this->_getParam('id');
	$rommints = new Romanmints();
	$this->view->rommints = $rommints->getMintDetails($id);
	$actives = new Rulers();
	$this->view->actives = $actives->getRomanMintRulerList($id);
	$counts = new Finds();
	$this->view->counts = $counts->getCountMint($id);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}