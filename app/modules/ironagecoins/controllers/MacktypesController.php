<?php
/** Controller for Iron Age period's mack types
* This listing is now pretty much obsolete, but is retained for concordance. 
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_MacktypesController extends Pas_Controller_Action_Admin {
    
	/** Set up the ACL and the contexts
	*/    
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('type', array('xml','json'))
		->initContext();
    }
    
	/** Internal period ID number for the Iron Age
	*/       
	protected $_period = '16';
    
	/** Set up the Mack type index pages
	*/    
	public function indexAction() {
    $this->view->headTitle('Mack types listed');
    $types = new MackTypes();
    $macks = $types->getMackTypes($this->_getAllParams());
    $contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $macks->getCurrentPageNumber(),
				  'total' => number_format($macks->getTotalItemCount(),0),
				  'itemsReturned' => $macks->getCurrentItemCount(),
				  'totalPages' => number_format($macks->getTotalItemCount()/$macks->getCurrentItemCount(),0));
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
