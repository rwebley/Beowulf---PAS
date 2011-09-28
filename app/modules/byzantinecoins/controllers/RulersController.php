<?php
/** Controller for displaying byzantine ruler pages with recent examples
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ByzantineCoins_RulersController extends Pas_Controller_Action_Admin {
	/** Initialise the ACL and contexts
	*/ 
	public function init()  {
 	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()
		->addActionContext('index', array('xml','json'))
		->addActionContext('ruler', array('xml','json'))
		->initContext();
    }
	/** Setup the index page for rulers
	*/ 
	public function indexAction() {
	$byzantium = new Rulers();
	$byz = $byzantium->getRulersByzantineList($this->_getParam('page'));
	$contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $byz->getCurrentPageNumber(),
				  'total' => number_format($byz->getTotalItemCount(),0),
				  'itemsReturned' => $byz->getCurrentItemCount(),
				  'totalPages' => number_format($byz->getTotalItemCount() 
				  /$byz->getCurrentItemCount(),0));
	$this->view->data = $data;
	$byza = array();
	foreach($byz as $r){
		$byza[]['ruler'] = array('id' => $r->id,'name' => $r->issuer);
	}
		$this->view->rulers = $byza;
	} else {
		$this->view->byzantium = $byz;
	}
	}
	/** Get individual ruler page
	*/ 
	public function rulerAction() {
	if($this->_getParam('id',false)){
		$byzantines = new Rulers();
		$this->view->byzantine = $byzantines->getRulerProfile((int)$this->_getParam('id'));
		$images = new Slides();
		$this->view->images = $images->getExamplesCoins((int)$this->_getParam('id'),4);
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}


}