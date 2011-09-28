<?php
/** Controller for displaying byzantine coins denominations pages with recent examples
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ByzantineCoins_DenominationsController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/ 
	public function init(){
 		$this->_helper->_acl->allow(null);
		$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
			 ->addActionContext('index', array('xml','json'))
			 ->addActionContext('denomination', array('xml','json'))
             ->initContext();
    }

	protected $_period = '67';
	/** Set up index page for denominations
	*/ 
    public function indexAction() {
	$this->view->headTitle('Byzantine issued denominations');
	$denominations = new Denominations();
	$denominations = $denominations->getDenominations($this->_period,$this->_getParam('page'));
	$contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $denominations->getCurrentPageNumber(),
				  'total' => number_format($denominations->getTotalItemCount(),0),
				  'itemsReturned' => $denominations->getCurrentItemCount(),
				  'totalPages' => number_format($denominations->getTotalItemCount()/
				$denominations->getCurrentItemCount(),0));
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
    /** Set up specific page for a denomination
	*/ 
    public function denominationAction()  {
   	if($this->_getParam('id',false)){
    $id = $this->_getParam('id');
    $denoms = new Denominations();
    $this->view->denoms = $denoms->getDenom($id,(int)$this->_period);
    
    $images = new Slides();
	$this->view->images = $images->getExamplesCoinsDenominations($id,4);     
    
    $counts = new Finds;
    $this->view->counts = $counts->getDenominationTotals($id);
   	} else {
   		throw new Pas_Exception_Param($this->_missingParameter);
   	}
    }
}