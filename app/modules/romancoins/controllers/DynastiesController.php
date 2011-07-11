<?php
/** Controller for displaying Roman dynasties
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_DynastiesController extends Pas_Controller_ActionAdmin {
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('dynasty',$contexts)
		->initContext();
    }

	/** Set up the index pages
	*/	
	public function indexAction() {
	$dynasties = new Dynasties();
	$this->view->dynasties = $dynasties->getDynastyList();
	}
	/** Set up the individual dynasty
	*/		
	public function dynastyAction() {
	if($this->_getParam('id',false)) {
	$dynasties = new Dynasties();
	$this->view->dynasties = $dynasties->getDynasty($this->_getParam('id'));
	$emperors = new Emperors();
	$this->view->emperors = $emperors->getEmperorsDynasty($this->_getParam('id'));
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}

}