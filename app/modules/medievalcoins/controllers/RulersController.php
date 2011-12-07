<?php
/** Controller for displaying Medieval rulers pages
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MedievalCoins_RulersController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
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
	protected $_period = 29;
	/** Index page for the list of rulers
	*/	
	public function indexAction() {
	$normans = new Rulers();
	$this->view->normans = $normans->getMedievalRulersListed('2','29');
	$shortlong = new Rulers();
	$this->view->shortlong = $shortlong->getMedievalRulersListed('14','29');
	$edwardian = new Rulers();
	$this->view->edwardian = $edwardian->getMedievalRulersListed('15','29');
	$latemed = new Rulers();
	$this->view->latemed = $latemed->getMedievalRulersListed('16','29');
	}
	/** Index page for list of foreign rulers
	*/	
	public function foreignAction() {
	$ferengi = new Rulers();
	$this->view->ferengi = $ferengi->getMedievalRulersListed($this->_period,'29');
	$doges = new Rulers();
	$this->view->doges = $doges->getForeign($this->_period, $country = '1');
	$scots = new Rulers();
	$this->view->scots = $scots->getForeign($this->_period, $country = '2');
	$low = new Rulers();
	$this->view->low = $low->getForeign($this->_period, $country = '3');
	$imitate= new Rulers();
	$this->view->imitate = $imitate->getForeign($this->_period, $country = '4');
	$portugal= new Rulers();
	$this->view->portugal = $imitate->getForeign($this->_period, $country = '5');
	$shortlongs= new Rulers();
	$this->view->shortlongs = $shortlongs->getForeign($this->_period, $country = '6');
	}
	/** Individual ruler pages
	*/	
	public function rulerAction() {
	if($this->_getParam('id',false)){
	$id = (int)$this->_getParam('id');
	$this->view->id = $id;
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
	
}
