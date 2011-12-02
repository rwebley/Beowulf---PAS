<?php
/** Controller for the Staffordshire symposium
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class Search_IndexController extends Pas_Controller_Action_Admin {
	
	protected $_solr;
	/**
	 * Set up the ACL
	 */
	public function init() {
	$this->_helper->_acl->allow('public',null);	
	$config = array(
    'adapteroptions' => array(
    'host' => '127.0.0.1',
    'port' => 8983,
    'path' => '/solr/beowulf/',
    ));
	$this->_solr = new Solarium_Client($config);
	}
	
	/** List of the papers available
	 */
	public function indexAction() {
	$ping = $this->_solr->createPing();
	if (  !$this->_solr->ping($ping) ) {
 	echo '<h2>Search engine system error</h2>';
	echo '<p>Solr service not responding.</p>';
	} else {
	$form = new SolrForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$data = $this->_getAllParams();
	if ($form->isValid($data)) {
	$this->_redirect($this->view->url(array('module' => 'search',
	'controller' => 'results','action' => 'index','q' => $data['q'])));
	} else {
	if(isset($q)){
	$form->populate($q);
	}
	}
	}
	}	
	}

}

