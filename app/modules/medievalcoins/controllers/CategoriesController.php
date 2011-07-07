<?php

class MedievalCoins_CategoriesController extends Pas_Controller_ActionAdmin
{

public function init() {
 		$this->_helper->_acl->allow(null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
			 ->addActionContext('index', array('xml','json'))
			 ->addActionContext('category', array('xml','json'))
             ->initContext();
    }
	protected $_period = '29';

    public function indexAction()
	{
	$categories = new CategoriesCoins();
	$this->view->categories = $categories->getCategoriesPeriod($this->_period);
	}
	
	public function categoryAction()
	{
	$id = $this->_getParam('id');
	$categories = new CategoriesCoins();
	$this->view->categories = $categories->getCategory($id);
	$types = new MedievalTypes();
	$this->view->types = $types->getCoinTypeCategory($id);
	$counts = new Finds();
	$this->view->counts = $counts->getCategoryTotals($id);
	$rulers =  new CategoriesCoins();
	$this->view->rulers = $rulers->getMedievalRulersToType($id);
	}

}