<?php
/** Controller for displaying rulers from the Greek and Roman provincial world
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GreekRomanCoins_RulersController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/ 
	public function init(){
 	$this->_helper->_acl->allow(null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
			 ->addActionContext('index', array('xml','json'))
			 ->addActionContext('ruler', array('xml','json'))
             ->initContext();
    }
	/** Internal period number
	*/ 
	protected $_period = '66';
	/** Set up the index page
	*/ 
    public function indexAction() {
	$greek = new Rulers();
	$greeks = $greek->getRulersGreekList($this->_getAllParams());
	$contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $greeks->getCurrentPageNumber(),
				  'total' => number_format($greeks->getTotalItemCount(),0),
				  'itemsReturned' => $greeks->getCurrentItemCount(),
				  'totalPages' => number_format($greeks->getTotalItemCount()/$greeks->getItemCountPerPage(),0));
	$this->view->data = $data;
	$greeksa = array();
	foreach($greeks as $r => $v){
	$greeksa[$r] = $v;
	}
		$this->view->greeks = $greeksa;
	} else {
		$this->view->greeks = $greeks;
	}
	}
	
	/** Individual ruler page
	*/ 	
	public function rulerAction() {
	if($this->_getParam('id',false)){
	$greeks = new Rulers();
	$this->view->greek= $greeks->getRulerProfile($this->_getParam('id'));
	$images = new Slides();
	$this->view->images = $images->getExamplesCoins($this->_getParam('id'),4);
	} else {
	throw new Pas_Exception_Param($this->_missingParameter);		
	}
	}
	
}