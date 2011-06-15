<?php
/** Controller for getting information on volunteer roles
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GetInvolved_VolunteeringController extends Pas_Controller_ActionAdmin {
	
	/** Initialise the ACL and set up contexts
	*/ 
	public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->_helper->acl->allow('public',null);
		$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
  			 ->addContext('rss',array('suffix' => 'rss'))
			 ->addContext('atom',array('suffix' => 'atom'))
			 ->addActionContext('index', array('xml','json','rss','atom'))
  			 ->addActionContext('role', array('xml','json'))
             ->initContext();
	    }
		
	/** Render the index page
	*/ 
	public function indexAction() {
	$volunteers = new Volunteers();
	$vols = $volunteers->getCurrentOpps($this->_getAllParams());
	$contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $vols->getCurrentPageNumber(),
				  'total' => number_format($vols->getTotalItemCount(),0),
				  'itemsReturned' => $vols->getCurrentItemCount(),
				  'totalPages' => number_format($vols->getTotalItemCount() / 
				  $vols->getItemCountPerPage(),0));
	$this->view->data = $data;
	$volsa = array();
	foreach($vols as $k => $v){
	$volsa[$k] = $v;
	}
	$this->view->vols = $volsa;
	} else {
	$this->view->vols = $vols;
	}
	
	}
	
	/** Render individual role
	*/ 
	public function roleAction(){
	if($this->_getParam('id',false)){
		$volunteers = new Volunteers();
		$this->view->vols = $volunteers->getOppDetails($this->_getParam('id'));
	} else {
			throw new Pas_ParamException($this->_missingParameter);
	}
	}


}