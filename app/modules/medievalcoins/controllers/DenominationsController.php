<?php
/** Controller for displaying Medieval denominations
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MedievalCoins_DenominationsController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
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
    
	/** Setup the contexts by action and the ACL.
	*/	
    protected $_period = 29;

	/** Setup index page for Medieval denominations
	*/	
	public function indexAction() {
	$denominations = new Denominations();
	$denominations = $denominations->getDenominations((int)$this->_period, (int)$this->_getParam('page'));
	$data = array('pageNumber' => $denominations->getCurrentPageNumber(),
				  'total' => number_format($denominations->getTotalItemCount(),0),
				  'itemsReturned' => $denominations->getCurrentItemCount(),
				  'totalPages' => number_format($denominations->getTotalItemCount()/$denominations->getCurrentItemCount(),0));
	$this->view->data = $data;
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('json'))) {
		
	    $d = array();
	    foreach($denominations->getCurrentItems() as $k => $v) {
	        $d[$k] = $v;
	    }
	    $this->view->denominations = $d;
	} else {
	    $this->view->denominations = $denominations;
	}
	}
	
	/** Setup the denomination details
	*/	
	public function denominationAction() {
	if($this->_getParam('id',false)){
	$id = (int)$this->_getParam('id');
	$denoms = new Denominations();
	$this->view->denoms = $denoms->getDenom($id,$this->_period);
	$rulers = new Denominations();
	$this->view->rulers = $rulers->getRulerDenomination($id);
	$counts = new Finds;
	$this->view->counts = $counts->getDenominationTotals($id);
	$images = new Slides();
	$this->view->images = $images->getExamplesCoinsDenominations($id,4); 
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
}
