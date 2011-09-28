<?php
/** Controller for Iron Age geographical regions
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_RegionsController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
 		$this->_helper->_acl->allow(null);
		$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
			 ->addActionContext('index', array('xml','json'))
			 ->addActionContext('region', array('xml','json'))
             ->initContext();
    }
	/** Internal period ID number for the Iron Age
	*/
	protected $_period = '16';
	
	/** Setup the index page for Iron Age geography
	*/
    public function indexAction() {
	$regions = new Geography;
	$this->view->regions = $regions->getIronAgeRegions();
	}
	
	/** Individual region's details
	*/	
	public function regionAction(){
	if($this->_getParam('id',false)){
	$regions = new Geography;
	$this->view->regions = $regions->getIronAgeRegion($this->_getParam('id'));
	$id = $this->_getParam('id');
	$denominations = new Denominations();
	$this->view->denominations = $denominations->getDenByPeriod($this->_period);
	$rulers = new Rulers();
	$this->view->rulers = $rulers->getIronAgeRulerToRegion($id);
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
    
 }