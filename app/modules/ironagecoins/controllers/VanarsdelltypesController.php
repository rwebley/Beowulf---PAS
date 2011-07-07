<?php
/** Controller for Iron Age Van Ardsell types
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_VanarsdelltypesController extends Pas_Controller_ActionAdmin {
	/** Setup the contexts by action and the ACL.
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('type', array('xml','json'))
		->initContext();
    }
	/** Setup the index page for Van Arsdell Types
	*/    
    public function indexAction() {
    $types = new VanArsdellTypes();
    $macks = $types->getVaTypes($this->_getAllParams());
     $contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $macks->getCurrentPageNumber(),
				  'total' => number_format($macks->getTotalItemCount(),0),
				  'itemsReturned' => $macks->getCurrentItemCount(),
				  'totalPages' => number_format($macks->getTotalItemCount() /
				 $macks->getCurrentItemCount(),0));
	$this->view->data = $data;
	$macksa = array();
	foreach($macks as $r => $v){
	$macksa['type'][$r] = $v;
	}
	$this->view->macks = $macksa;
	} else {
	$this->view->macks = $macks;
	}
    }
}