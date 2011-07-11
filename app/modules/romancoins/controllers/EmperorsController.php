<?php
/** Controller for displaying Roman Emperors
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanCoins_EmperorsController extends Pas_Controller_ActionAdmin {

	protected $_config, $_googleapikey;
	
	/** Set up the ACL and contexts
	* @todo Move the api key to the view 
	*/	
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_config = Zend_Registry::get('config');
	$this->_googleapikey = $this->_config->googlemaps->apikey; 
	$contexts = array('xml','json');
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
		->addActionContext('index',$contexts)
		->addActionContext('emperor',$contexts)
		->initContext();
    }
	/** Set up the emperor index pages
	*/	
	public function indexAction() {
	$emperors = new Emperors();
	$this->view->julioclaudian = $emperors->getDynEmp(1);
	$this->view->civilwar = $emperors->getDynEmp(2);
	$this->view->flavian = $emperors->getDynEmp(3);
	$this->view->adoptive = $emperors->getDynEmp(4);
	$this->view->antonine = $emperors->getDynEmp(5);
	$this->view->waremperors = $emperors->getDynEmp(6);
	$this->view->severan = $emperors->getDynEmp(7);
	$this->view->thirdcentury = $emperors->getDynEmp(8);
	$this->view->british = $emperors->getDynEmp(9);
	$this->view->gallic = $emperors->getDynEmp(10);
	$this->view->tetrarchy = $emperors->getDynEmp(11);
	$this->view->constantine = $emperors->getDynEmp(12);
	$this->view->valentinian = $emperors->getDynEmp(13);
	$this->view->theodosius = $emperors->getDynEmp(14);
	$this->view->fourthcentury = $emperors->getDynEmp(16);
	}
	
	/** Set up the individual emperor
	*/		
	public function emperorAction() {
	if($this->_getParam('id',false)){
	$this->view->inlineScript()->appendFile('http://maps.google.com/maps?file=api&amp;v=2.x&key='
	. $this->_googleapikey,$type='text/javascript');
	$id = (int)$this->_getParam('id');
	$emps = new Emperors();
	$this->view->emps = $emps->getEmperorDetails($id);
	$denoms = new Denominations();
	$this->view->denoms = $denoms->getEmperorDenom($id);
	$mints = new Mints();
	$this->view->mints = $mints->getMintEmperorList($id);
	$counts = new Finds;
	$this->view->counts = $counts->getCountEmperor($this->_getParam('id'));
	$images = new Slides();
	$this->view->images = $images->getExamplesCoinsEmperors($id,4);
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}
	
}
	