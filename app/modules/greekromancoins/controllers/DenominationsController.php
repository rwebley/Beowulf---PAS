<?php
/** Controller for displaying denominations from the Greek and Roman provincial world
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GreekRomanCoins_DenominationsController extends Pas_Controller_Action_Admin
{
	/** Initialise the ACL and contexts
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
	/** Internal period number
	*/ 
	protected $_period = '66';

	/** Set up the index display page
	*/ 
	public function indexAction() {
	$denominations = new Denominations();
	$denominations = $denominations->getDenominations($this->_period,$this->_getParam('page'));
    $contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $denominations->getCurrentPageNumber(),
				  'total' => number_format($denominations->getTotalItemCount(),0),
				  'itemsReturned' => $denominations->getCurrentItemCount(),
				  'totalPages' => number_format($denominations->getTotalItemCount()/$denominations->getCurrentItemCount(),0));
	$this->view->data = $data;
	$denomsa = array();
	foreach($denominations as $r => $v){
		$denomsa['type'][$r] = $v;
	}
		$this->view->denominations = $denomsa;	
	} else {
		$this->view->denominations = $denominations;
	}
    }
    
	/** Display individual denomination
	*/     
    public function denominationAction() {
	if($this->_getParam('id',false)){
   	
	$denoms = new Denominations();
	$this->view->denoms = $denoms->getDenom((int)$this->_getParam('id'),(int)$this->_period);

	$counts = new Finds;
	$this->view->counts = $counts->getDenominationTotals($this->_getParam('id'));
	
    } else {
	throw new Pas_Exception_Param($this->_missingParameter);		
	}
	}
	
}