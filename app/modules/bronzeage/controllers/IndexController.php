<?php
/** Controller for accessing Bronze Age guide index page
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Bronzeage_IndexController extends Pas_Controller_ActionAdmin {

	/** Initialise the ACL and contexts
	*/ 
	public function init(){
 	$this->_helper->_acl->allow(null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

	/** Render the index pages
	*/ 
	public function indexAction(){
	$content = new Content();
	$this->view->content =  $content->getFrontContent('bronzeage');
	}
}