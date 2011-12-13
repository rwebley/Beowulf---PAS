<?php
/** Controller for displaying Medieval coin categories
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MedievalCoins_CategoriesController extends Pas_Controller_Action_Admin {
	/** Setup the contexts by action and the ACL.
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('category', array('xml','json'))
		->initContext();
    }
	/** Internal period ID number
	*/		
    protected $_period = '29';
    
	/** Setup the index action for Medieval categories
	*/	
    public function indexAction() {
	$categories = new CategoriesCoins();
	$this->view->categories = $categories->getCategoriesPeriod($this->_period);
	}

	/** Individual category details.
	*/	
	public function categoryAction(){
	if($this->_getParam('id',false)){	
	$id = $this->_getParam('id');
	$categories = new CategoriesCoins();
	$this->view->categories = $categories->getCategory($id);
	$types = new MedievalTypes();
	$this->view->types = $types->getCoinTypeCategory($id);
	$rulers =  new CategoriesCoins();
	$this->view->rulers = $rulers->getMedievalRulersToType($id);
	} else {
    throw new Pas_Exception_Param($this->_missingParameter);
	}
	
	}

}