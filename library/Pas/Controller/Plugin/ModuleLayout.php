<?php

/** A front controller plugin for layouts
* 
* 
* This class can choose whether to enable or disable layouts after the 
* request has been dispatched.
* 
* @category   Pas
* @package    Pas_Controller_
* @subpackage Plugin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @author	  Daniel Pett
* @version    1
* @since	  September 22 2011
*/

class Pas_Controller_Plugin_ModuleLayout
	extends Zend_Controller_Plugin_Abstract {
	
	/** Set up the available array of contexts
	 * @var array $_contexts
	 */
	protected $_contexts = array(
	'xml','rss','json',
	'atom','kml','georss',
	'ics','rdf','xcs',
	'vcf','csv','foaf',
	'pdf','qrcode');
	
	/** Set up contexts to disable layout for based on modules
	 * @var array $_disabled
	 */
	protected $_disabled = array('ajax','oai','sitemap');
	
	/** Create the layout after the request has been dispatched
	 *  Disable or enable layouts depending on type.
	 * @access public
	 * @param  object $request The request being made
	 * @todo   change this to a database or config.ini method
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request) {
	$ctrllr = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();	
	$module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();	
	$contextSwitch = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch');
	if(!in_array($ctrllr, $this->_disabled)) {
	if(!in_array($contextSwitch->getCurrentContext(), $this->_contexts)) {
	$module = strtolower($request->getModuleName());
	$response = $this->getResponse();
	$view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
	$view->contexts = Zend_Controller_Action_HelperBroker::getStaticHelper('ContextSwitch')
		->getActionContexts(Zend_Controller_Front::getInstance()->getRequest()->getActionName());
	$view->messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')
		->getMessages();
	switch($module) {
	case $module == 'experiments':
		$layouttype = 'flickr';
		break;
	case $module == 'getinvolved':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/involvedSidebar.phtml'));
		$view->headTitle('Get involved section ')->setSeparator(' - ');
		break;
	case $module == 'research':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/researchSidebar.phtml'));
		$view->headTitle('Research section ')->setSeparator(' - ');
		break;
	case $module == 'admin':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/adminSidebar.phtml'));
		$view->headTitle('Administration section ')->setSeparator(' - ');
		break;
	case $module == 'database':
		$layouttype = 'database';
		$view->headTitle('Database ')->setSeparator(' - ');
		$response->insert('sidebar', $view->render('structure/databaseSidebar.phtml'));
		break;
	case $module == 'news':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/newsSidebar.phtml'));
		$view->headTitle('News section ')->setSeparator(' - ');
		break;
	case $module == 'events':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/eventsSidebar.phtml'));
		$view->headTitle('Scheme Events ')->setSeparator(' - ');
		break;
	case $module == 'users':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/UserSidebar.phtml'));
		break;
	case $module == 'contacts':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/contactsSidebar.phtml'));
	    $view->headTitle('Scheme and external contacts ')->setSeparator(' - ');
		break;
	case $module == 'romancoins':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/romanCoinsSidebar.phtml'));
	    $view->headTitle('A guide to the coins of the Roman Empire ')->setSeparator(' - ');
		break;
	case $module == 'postmedievalcoins':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/postMedievalCoinsSidebar.phtml'));
	    $view->headTitle('A guide to the coins of the Post Medieval period ')->setSeparator(' - ');
		break;
	case $module == 'medievalcoins':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/medievalCoinsSidebar.phtml'));
	    $view->headTitle('A guide to the coins of the Medieval period ')->setSeparator(' - ');
		break;
	case $module == 'earlymedievalcoins':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/earlyMedievalCoinsSidebar.phtml'));
	    $view->headTitle('A guide to the coins of the Early Medieval period ')->setSeparator(' - ');
		break;
	case $module == 'ironagecoins':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/ironageCoinsSidebar.phtml'));
	    $view->headTitle('A guide to Iron Age coins ')->setSeparator(' - ');
		break;
	case $module == 'byzantinecoins':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/byzantineCoinsSidebar.phtml'));
	    $view->headTitle('A guide to Byzantine coins ')->setSeparator(' - ');
		break;
	case $module == 'greekromancoins':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/greekCoinsSidebar.phtml'));
	    $view->headTitle('A guide to Greek & Roman Provincial coins ')->setSeparator(' - ');
		break;
	case $module == 'conservation':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/conservationSidebar.phtml'));
	    $view->headTitle('A practical guide to conservation of objects ')->setSeparator(' - ');
		break;
	case $module == 'treasure':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/treasureSidebar.phtml'));
	    $view->headTitle('The Treasure Act')->setSeparator(' - ');
		break;
	case $module == 'help':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/helpSidebar.phtml'));
	    $view->headTitle('Help with our website')->setSeparator(' - ');
		break;
	case $module == 'info':
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/infoSidebar.phtml'));
	    $view->headTitle('Information about our site')->setSeparator(' - ');
		break;
	case $module == 'flickr':
		$layouttype = 'flickr';
		$view->headTitle('The Scheme on flickr')->setSeparator(' - ');
		$response->insert('sidebar', $view->render('structure/flickrSidebar.phtml'));
		break;
	case $module == 'staffshoardsymposium';
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/staffsSidebar.phtml'));
	    $view->headTitle('The Symposium')->setSeparator(' - ');
		break;
	case $module == 'ebay';
		$layouttype = 'database';
	    $view->headTitle('ebay monitoring')->setSeparator(' - ');
		break;
	case $module == 'guide';
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/guideSidebar.phtml'));
	    $view->headTitle('Volunteer recording guide')->setSeparator(' - ');
		break;
	case $module == 'bronzeage';
		$layouttype = 'database';
		$response->insert('sidebar', $view->render('structure/bronzeageSidebar.phtml'));
	    $view->headTitle('A guide to Bronze Age objects')->setSeparator(' - ');
		break;
		default:
		$layouttype = 'home';
		break;
		}
		
	$response->insert('userdata', $view->render('structure/userdata.phtml')); 
	$response->insert('header', $view->render('structure/header.phtml'));
	$response->insert('breadcrumb', $view->render('structure/breadcrumb.phtml'));
	$response->insert('navigation', $view->render('structure/navigation.phtml'));
	$response->insert('footer', $view->render('structure/footer.phtml'));
	$response->insert('messages', $view->render('structure/messages.phtml'));
	$response->insert('contexts',$view->render('structure/contexts.phtml'));
	$response->insert('analytics',$view->render('structure/analytics.phtml'));
	$layout = Zend_Layout::getMvcInstance();
	if ($layout->getMvcEnabled() ) {
	$layout->setLayoutPath('app/layouts/');
	if($ctrllr != 'error') {
	$layout->setLayout($layouttype);
	} else {
	$layout->setLayout('database');
	}
	} 
	} else {
	$contextSwitch->setAutoDisableLayout(true)
		->initContext();
	}
	}
	}
}

