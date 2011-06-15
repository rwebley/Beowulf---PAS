<?php
/** Controller for all our staff profiles pages
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Contacts_StaffController extends Pas_Controller_ActionAdmin
{
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
		$this->_helper->_acl->allow('public',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$contexts = array('xml','json','foaf','vcf');
	  	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
			 ->addContext('foaf',array('suffix' => 'foaf'))
 			 ->addContext('vcf',array('suffix' => 'vcf'))
			 ->addActionContext('profile',$contexts)
             ->initContext();
    }
    
    /** Redirect away from this page, no root access
	*/ 
	public function indexAction() {
		$this->_redirect('contacts');
	}

	/** Profile page
	* @todo sort out the xml generated pages with proper class to generate data 
	*/ 
	public function profileAction()	{
		if($this->_getParam('id',false)) {
			$id = $this->_getParam('id');
			$staffs = new Contacts();
			$this->view->persons = $staffs->getPersonDetails($id);
			$findstotals = new Finds();
			$this->view->findstotals = $findstotals->getFindsFloQuarter($id);
			$periodtotals = new Finds();
			$this->view->periodtotals = $periodtotals->getFindsFloPeriod($id);
			$accts = new OnlineAccounts();
			$this->view->accts = $accts->getAccounts($id);
		} else {
			throw new Pas_ParamException($this->_missingParameter);
		}
	}
	
	/** Map of staff
	*/ 
	public function mapAction() {
	}


}