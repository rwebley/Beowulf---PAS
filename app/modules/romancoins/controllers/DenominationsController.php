<?php
/** Controller for displaying Roman denominations
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_DenominationsController extends Pas_Controller_Action_Admin {
	
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_helper->_acl->allow(null);
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('denomination',$contexts)
		->initContext();
    }
	/** Set up the index page
	*/	
	public function indexAction() {
	$denoms = new Denominations();
	$this->view->denominations = $denoms->getDenByPeriod((int)21);
	}
	/** Set up the individual denominations
	*/	
	public function denominationAction() {
	if($this->_getParam('id',false)) {
	$id = $this->_getParam('id');
	$denoms = new Denominations();
	$this->view->denoms = $denoms->getDenom($id,(int)21);
	$emps = new Emperors();
	$this->view->emps = $emps->getDenomEmperor($id);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}
