<?php
/** Controller for displaying Roman reece periods
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_ReeceperiodsController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_helper->_acl->allow(null);
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('period',$contexts)
		->initContext();
    }
	/** Set up the index page
	*/	
	public function indexAction() {
	$reeces = new Reeces();
	$this->view->reeces = $reeces->getReeceTotals();
	}
	/** Set up the individual period
	*/		
	public function periodAction() {
	if($this->_getParam('id',false)) {
	$id = (int)$this->_getParam('id');
	$periods = new Reeces();
	$this->view->periods = $periods->getReecePeriodDetail($id);
	$reeces = new Emperors();
	$this->view->reeces = $reeces->getReeceDetail($id);
	$reverses = new Revtypes();
	$this->view->reverses = $reverses->getRevTypeReece($id);    
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}

}