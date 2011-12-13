<?php
/** Controller for displaying Post medieval coins index pages
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedievalCoins_DenominationsController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/		
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('denomination', array('xml','json'))
		->initContext();
    }
	/** Internal period ID number
	*/		
    protected $_period = 36;
	/** Denomination index pages
	*/		
    public function indexAction() {
    $denominations = new Denominations();
    $denominations = $denominations->getDenominations($this->_period,$this->_getParam('page'));
	$data = array(
	'pageNumber' => $denominations->getCurrentPageNumber(),
	'total' => number_format($denominations->getTotalItemCount(),0),
	'itemsReturned' => $denominations->getCurrentItemCount(),
	'totalPages' => number_format($denominations->getTotalItemCount()/$denominations->getCurrentItemCount(),0));
	$this->view->data = $data;
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('json','xml'))) {
	$d = array();
	foreach($denominations->getCurrentItems() as $k => $v) {
		$d[$k] = $v;
	}
	    $this->view->denominations = $d;
	} else {
	    $this->view->denominations = $denominations;
	}
	}
	/** Individual denomination page details
	*/		
    public function denominationAction()  {
    if($this->_getParam('id',false)){
    $id = $this->_getParam('id');
    
    $denoms = new Denominations();
    $this->view->denoms = $denoms->getDenom($id,(int)$this->_period);

    $rulers = new Denominations();
    $this->view->rulers = $rulers->getRulerDenomination($id);
    
    } else {
    	throw new Pas_Exception_Param($this->_missingParameter);
    }
    }
}