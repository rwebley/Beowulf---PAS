<?php
/** Controller for displaying Post medieval type lists
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedievalCoins_TypesController extends Pas_Controller_ActionAdmin {
	/** Setup the contexts by action and the ACL.
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('ruler', array('xml','json'))
		->initContext();
    }
	/** Internal period ID number
	*/	
    protected $_period = 36;
	
	/** Index page for the Post Medieval types list
	*/	
	public function indexAction(){
	$type = new MedievalTypes();
	$types = $type->getTypesByPeriod($this->_period,$this->_getParam('page'));
	$contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array(
	'pageNumber' => $types->getCurrentPageNumber(),
	'total' => number_format($types->getTotalItemCount(),0),
	'itemsReturned' => $types->getCurrentItemCount(),
	'totalPages' => number_format($types->getTotalItemCount()/$types->getCurrentItemCount(),0));
	$this->view->data = $data;
	$typesa = array();
	foreach($types as $r => $v){
	$typesa[$r] = $v;
	}
	$this->view->types = $typesa;
	} else {
	$this->view->types = $types;
	}
	}
	
	/** Individual details for Post Medieval types
	*/		
	public function typeAction() {
	if($this->_getParam('id',false)){
	$types = new MedievalTypes();
	$this->view->types = $types->getTypeDetails($this->_getParam('id'));
		$images = new Slides();
	$this->view->images = $images->getExamplesCoinsMedTypes($this->_getParam('id'),4);
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}

}
