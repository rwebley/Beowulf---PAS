<?php
/** Controller for displaying denominations from the Iron Age period
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_DenominationsController extends Pas_Controller_Action_Admin {
	
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addContext('rss',array('suffix' => 'rss','header' => 'application/rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('index', array('xml','json'))
		->addActionContext('denomination', array('xml','json'))
		->initContext();
    }
	/** Internal period number
	*/    
	protected $_period = '16';

	/** Set up index page for Iron Age denominations
	*/	
    public function indexAction() {
    $denoms = new Denominations();
    $this->view->denoms = $denoms->getIronAgeDenoms();
    }
	/** An individual denomination's entry details
	 * 
	*/    
    public function denominationAction() {
    if($this->_getParam('id',false)){
    $id = $this->_getParam('id');
    $this->view->id = $id;
    $denoms = new Denominations();
	$this->view->denoms = $denoms->getDenom($id,$this->_period);
    $regions = new Geography();
    $this->view->regions = $regions->getIronAgeDenomGeog($id);
    } else {
    	throw new Pas_Exception_Param($this->_missingParameter);
    }       
    }
}