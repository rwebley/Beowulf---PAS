<?php
/** Controller for displaying Medieval mint pages
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MedievalCoins_MintsController extends Pas_Controller_ActionAdmin {
	/** Setup the contexts by action and the ACL.
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('mint', array('xml','json'))
		->initContext();
    }
	/** Internal period ID page
	*/	
	protected $_period = 29;
	/** Index page for Medieval mints
	*/	
	public function indexAction() {
	$mints = new Mints();
	$this->view->mints = $mints->getListMints($this->_period);
	}
	/** Individual mint page
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
	$slides = new Slides();
	$this->view->images = $slides->getExamplesCoinsMints($id,4);
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}
	

}