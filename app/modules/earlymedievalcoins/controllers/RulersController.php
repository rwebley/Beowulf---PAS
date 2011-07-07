<?php
/** Controller for displaying Early Medieval coin rulers page
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedievalCoins_RulersController extends Pas_Controller_ActionAdmin {

	/** Initialise the ACL and contexts
	*/
	public function init()  {
 	$this->_helper->_acl->allow(null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('ruler', array('xml','json'))
		->addActionContext('foreign', array('xml','json'))
		->initContext();
    }

	/** Internal period number for querying the database
	*/
	protected $_period = '47';
   	/** Set up the index page for rulers of each period or dynastic group.
	*/
	public function indexAction() {
	$names = new CategoriesCoins();
	$this->view->names = $names->getCategoryName();

	$allengland = new Rulers();
	$this->view->allengland = $allengland->getEarlyMedievalRulers('3');
	
	$eastanglia = new Rulers();
	$this->view->eastanglia = $allengland->getEarlyMedievalRulers('4');
	
	$mercia = new Rulers();
	$this->view->mercia = $mercia->getEarlyMedievalRulers('5');
	
	$wessex= new Rulers();
	$this->view->wessex = $wessex->getEarlyMedievalRulers('6');
	
	$canterbury = new Rulers();
	$this->view->canterbury = $canterbury->getEarlyMedievalRulers('11');
	
	$kent = new Rulers();
	$this->view->kent = $kent->getEarlyMedievalRulers('12');
	
	$viking = new Rulers();
	$this->view->viking = $viking->getEarlyMedievalRulers('7');
	
	$northumbria = new Rulers();
	$this->view->northumbria = $northumbria->getEarlyMedievalRulers('13');
	
	$earlysilver = new Rulers();
	$this->view->earlysilver = $earlysilver->getEarlyMedievalRulers('9');
	
	$earlygold = new Rulers();
	$this->view->earlygold = $earlygold->getEarlyMedievalRulers('8');
	}

	/** Set up the individual page per ruler with examples, map and types
	*/
	public function rulerAction() {
	if($this->_getParam('id',false)){
	$id = $this->_getParam('id');
		
	$rulers = new Rulers();
	$this->view->rulers = $rulers->getRulerImage($id);
	$this->view->monarchs = $rulers->getRulerProfileMed($id);

	$denominations = new Denominations();
	$this->view->denominations = $denominations->getEarlyMedRulerToDenomination($id);
	
	$images = new Slides();
	$this->view->images = $images->getExamplesCoins($id,4);
	
	$types = new MedievalTypes();
	$this->view->types = $types->getMedievalTypeToRuler($id);
	
	$mints = new Mints();
	$this->view->mints = $mints->getMedMintRuler($id);
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}

	/** Set up the foreign rulers page
	*/
	public function foreignAction() {
	$names = new CategoriesCoins();
	$this->view->names = $names->getCategoryName();
	$rulers = new Rulers();
	$this->view->francia = $rulers->getEarlyMedievalRulers('1');
	$this->view->islamic = $rulers->getEarlyMedievalRulers('10');
	$this->view->hiberno = $rulers->getEarlyMedievalRulers('28');
	}
	
}