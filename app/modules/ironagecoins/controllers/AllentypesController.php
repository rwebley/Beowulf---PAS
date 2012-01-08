<?php
/** Controller for displaying Iron Age coins Allen Types
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeCoins_AllentypesController extends Pas_Controller_Action_Admin {
	
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
    
    /** Create index pages for Allen Types available to the user
    */
    public function indexAction() {
    $types = new AllenTypes();
    $allens = $types->getAllenTypes($this->_getAllParams());
    $contexts = array('json','xml');
    if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
    $data = array(
        'pageNumber' => $allens->getCurrentPageNumber(),
        'total' => number_format($allens->getTotalItemCount(),0),
        'itemsReturned' => $allens->getCurrentItemCount(),
        'totalPages' => number_format($allens->getTotalItemCount() 
                            / $allens->getCurrentItemCount(),0)
        );
    $this->view->data = $data;
    $allensa = array();
    foreach($allens as $r => $v){
    $allensa['type'][$r] = $v;
    }
    $this->view->allenTypes = $allensa;
    } else {
            $this->view->allens = $allens;
    }
    }

    public function typeAction(){
    $types = new AllenTypes();
    $this->view->type = $types->fetchRow($types->select()->where('type = ?', 
            $this->_getParam('id')));
    }
}
