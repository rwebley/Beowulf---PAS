<?php
/** Controller for Iron Age period's rulers
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_RulersController extends Pas_Controller_ActionAdmin {
	
	/** Setup the contexts by action and the ACL.
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('ruler', array('xml','json'))
		->initContext();
    }
	/** Setup the index page of Iron Age rulers in a list
	*/	
    public function indexAction() {
    $rulers = new Rulers();
    $this->view->rulers = $rulers->getIronAgeRulersListed();
    }

	/** Set up an individual's details
	*/	
    public function rulerAction() {
    if($this->_getParam('id',false)){
    $this->view->headTitle('Details for  ');
    $id = (int)$this->_getParam('id');
    $rulers = new Rulers;
    $this->view->rulers = $rulers->getIronAgeRuler($id);
    $counts = new Finds;
    $this->view->counts = $counts->getCount($id);
    $regions = new Geography();    
    $this->view->regions = $regions->getIronAgeRegionToRuler($id);
	$images = new Slides();
	$this->view->images = $images->getExamplesCoins($id,4);
    } else {
    throw new Pas_ParamException($this->_missingParameter);
    }
    }
}