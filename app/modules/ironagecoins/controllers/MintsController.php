<?php
/** Controller for Iron Age period's mint listing page
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_MintsController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('mint', array('xml','json'))
		->initContext();
    }
	/** Internal period ID number
	*/	
    protected $_period = '16';
	
	/** Iron Age mints listing pages
	*/
    public function indexAction() {
	$mints = new Mints();
	$this->view->mints = $mints->listIronAgeMints();
	}
	/** Iron Age individual mint page
	*/
	public function mintAction() {
	if($this->_getParam('id',false)){
	$mints = new Mints;
	$this->view->mints = $mints->getIronAgeMint((int)$this->_getParam('id'));
	$images = new Slides();
	$this->view->images = $images->getExamplesCoinsMints((int)$this->_getParam('id'),4);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
}