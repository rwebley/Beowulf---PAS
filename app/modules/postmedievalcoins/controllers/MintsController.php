<?php
/** Controller for displaying Post medieval mints data
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedievalCoins_MintsController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow('public',null);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('mint', array('xml','json'))
		->initContext();
    }
	/** List of mints 
	*/	
    public function indexAction(){
	$mints = new Mints();
	$this->view->mints = $mints->getListMints(36);
	}
	/** Mint details
	*/		
	public function mintAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
	$mints = new Mints();
	$this->view->mints = $mints->getMintDetails($id);
	$actives = new Rulers();
	$this->view->actives = $actives->getMedievalMintRulerList($id);
	$counts = new Finds();
	$this->view->counts = $counts->getCountMedMint($id);
	$images = new Slides();
	$this->view->images = $images->getExamplesCoinsMints($id,4);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}
