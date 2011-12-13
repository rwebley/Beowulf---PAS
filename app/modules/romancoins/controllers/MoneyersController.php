<?php
/** Controller for displaying Roman republican moneyers
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Romancoins_MoneyersController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/	
	public function init() {
	$this->_helper->_acl->allow(NULL);
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('called',$contexts)
		->initContext();
	}
	/** Set up the index page
	*/	
	public function indexAction() {
	$moneyers = new Moneyers();
	$moneyers = $moneyers->getValidMoneyers($this->_getAllParams());
    $contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $moneyers->getCurrentPageNumber(),
				  'total' => number_format($moneyers->getTotalItemCount(),0),
				  'itemsReturned' => $moneyers->getCurrentItemCount(),
				  'totalPages' => number_format($moneyers->getTotalItemCount()
												/$moneyers->getItemCountPerPage(),0));
	$this->view->data = $data;
	$moneyersa = array();
	$this->view->moneyers = (array)$moneyers->getCurrentItems();
	} else {
	$this->view->moneyers = $moneyers;
	}
	}
	/** Set up the moneyer individual pages
	*/		
	public function calledAction() {
	if($this->_getParam('by',false)){
	$moneyers = new Moneyers();
	$this->view->moneyer = $moneyers->getMoneyer($this->_getParam('by'));
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}


}