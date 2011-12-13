<?php
/** Controller for displaying Post medieval rulers data
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedievalCoins_RulersController extends Pas_Controller_Action_Admin {
	/** Set up ACL and action contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('ruler', array('xml','json'))
		->addActionContext('foreign', array('xml','json'))
		->initContext();
    }
	/** Internal period ID number
	*/	
	protected $_period = 36;
	
	/** Index page for Post Medieval rulers
	*/		
	public function indexAction() {
	$rulers = new Rulers();
	$this->view->rulers = $rulers->getMedievalRulersListedMain($period = $this->_period);
	}
	/** Individual ruler page
	*/	
	public function rulerAction() {
	if($this->_getParam('id',false)){
	$id = $this->_getParam('id');
	$rulers = new Rulers();
	$this->view->rulers = $rulers->getRulerImage($id);
	$this->view->monarchs = $rulers->getRulerProfileMed($id);
	$denominations = new Denominations();
	$this->view->denominations = $denominations->getEarlyMedRulerToDenomination($id);
	$types = new MedievalTypes();
	$this->view->types = $types->getMedievalTypeToRuler($id);
	$mints = new Mints();
	$this->view->mints = $mints->getMedMintRuler($id);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);	
	}
	}
	/** List of foreign Post medieval rulers
	*/	
	public function foreignAction() {
	$rulers = new Rulers();
	$this->view->doges = $rulers->getForeign($this->_period, $country = 1);
	$this->view->scots = $rulers->getForeign($this->_period, $country = 2);
	$this->view->low = $rulers->getForeign($this->_period, $country = 3);
	$this->view->imitate = $rulers->getForeign($this->_period, $country = 4);
	$this->view->portugal = $rulers->getForeign($this->_period, $country = 5);
	$this->view->shortlongs = $rulers->getForeign($this->_period, $country = 6);
	$this->view->france = $rulers->getForeign($this->_period,$country = 7);
	}
}
