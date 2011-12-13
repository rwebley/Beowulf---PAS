<?php
/** Controller for displaying Post medieval category data
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedievalCoins_CategoriesController extends Pas_Controller_Action_Admin {
	/** Set up the ACL and contexts
	*/	
	public function init() {
 	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addActionContext('index', array('xml','json'))
		->addActionContext('category', array('xml','json'))
		->initContext();
    }
	/** Set up the category index pages
	*/	
	public function indexAction() {
	$categories = new CategoriesCoins();
	$this->view->categories = $categories->getCategoriesPeriod(36);
	}
	/** Individual category page
	*/	
	public function categoryAction() {
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
