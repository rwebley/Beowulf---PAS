<?php

/**
 * This class is to display the breadcrumbs 
 * Load of rubbish, needs a rewrite
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2008
 * @todo change the class to use zend_navigation
*/
class Pas_View_Helper_Breadcrumb
	extends Zend_View_Helper_Abstract {


	/** Build the breadcrumbs
	 * @access public
	 * @return string $html
	 */
	public function breadCrumb() {
	$module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
	$l_m = $module;

	switch ($module) {
		case $module == 'getinvolved':
			$nicemodule = 'Getting involved';
			break;
		case $module == 'admin':
			$nicemodule = 'Administration centre';
			break;
		case $module == 'conservation':
			$nicemodule = 'Conservation advice';
			break;
		case $module == 'research':
			$nicemodule = 'research';
			break;
		case $module == 'treasure':
			$nicemodule = 'Treasure Act';
			break;
		case $module == 'news':
			$nicemodule = 'news &amp; reports';
			break;
		case $module == 'events':
			$nicemodule = 'events';
			break;
		case $module == 'info':
			$nicemodule = 'Site information';
			break;
		case $module == 'romancoins':
			$nicemodule = 'Roman Numismatic guide';
			break;
		case $module == 'greekromancoins':
			$nicemodule = 'Greek and Roman Provincial Numismatic guide';
		 	break;
		case $module == 'api':
			$nicemodule = 'Application programming interface';
			break;
		case $module == 'staffshoardsymposium':
			$nicemodule  = 'Staffordshire Hoard Symposium';
			break;
		 default: 
		 $nicemodule = $module;
		 break;
	}

	$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
	$l_c = strtolower($controller);

	switch ($controller) {
		case $controller == 'error':
			$nicename = 'Error manager';
			break;
		case $controller == 'romancoins':
			$nicename = 'Roman coin guide';
			break;
		case $controller == 'database':
			$nicename = 'Finds database';
			break;
		case $controller == 'medievalcoins':
			$nicename = 'Medieval coin guide';
			break;
		case $controller == 'ironagecoins':
			$nicename = 'Iron Age coin guide';
			break;
		case $controller == 'earlymedievalcoins':
			$nicename = 'Early Medieval coin guide';
			break; 
		case $controller == 'greekandromancoins':
			$nicename = 'Greek &amp; Roman Provincial coin guide';
			break; 
		case $controller == 'byzantinecoins':
			$nicename = 'Byzantine coin guide';
			break; 
		case $controller == 'postmedievalcoins':
			$nicename = 'Post Medieval coin guide';
			break; 
		case $controller == 'getinvolved':
			$nicename = 'Get involved';
			break;
		case $controller == 'contacts':
			$nicename = 'Scheme contacts';
			break;
		case $controller == 'events':
			$nicename = 'Scheme events';
			break;
		case $controller == 'users':
			$nicename = 'Users\' section';
			break;
		case $controller == 'admin':
			$nicename = 'Site Administration';
			break;
		case $controller == 'britishmuseum':
			$nicename = 'British Museum events';
			break;
		case $controller == 'datatransfer':
			$nicename = 'Data transfer';
			break;
		case $controller == 'info':
			$nicename = 'Event information';
			break;
		case $controller == 'foi':
			$nicename = 'Freedom of Information Act';
			break;
		case $controller == 'her':
			$nicename = 'Historic Enviroment Signatories';
			break;
		default:
			$nicename = $controller;
			break;
	}		


	$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
	$l_a = strtolower($action);

	switch ($action) {
		case $action == 'mapsearchresults':
			$nicenameaction = 'Map search results';
			break;
		case $action == 'countystats':
			$nicenameaction = 'County statistics';
			break;
		case $action == 'regionalstats':
			$nicenameaction = 'Regional statistics';
			break;		
		case $action == 'institutionstats':
			$nicenameaction = 'Institutional statistics';
			break;
		case $action == 'numismaticsearch':
			$nicenameaction = 'Numismatic search';
			break;
		case $action == 'profile':
			$nicenameaction = 'Profile details';
			break;
		case $action == 'add':
			$nicenameaction = 'Add a new find';
			break;
		case $action == 'myresearch':
			$nicenameaction = 'My research agendas';
			break;
		case $action == 'myinstitution':
			$nicenameaction = 'My institution\'s finds';
			break;
		case $action == 'forgot':
			$nicenameaction = 'Reset forgotten password';
			break;
		case $action == 'login':
			$nicenameaction = 'Login to Beowulf';
			break;
		case $action == 'advanced':
			$nicenameaction = 'Advanced search interface';
			break;
		case $action == 'basicsearch':
			$nicenameaction = 'Basic what/where/when search interface';
			break;
		case $action == 'searchresults':
			$nicenameaction = 'Search results';
			break;
		case $action == 'organisations':
			$nicenameaction = 'Registered Organisations';
			break;
		case $action == 'addfindspot':
			$nicenameaction = 'Add a findspot';
			break;
		case $action == 'editfindspot':
			$nicenameaction = 'Edit findspot';
			break;
		case $action == 'editpublication':
			$nicenameaction = 'Edit a publication\'s details';
			break;
		case $action == 'publication':
			$nicenameaction = 'Publication\'s details';
			break;
			case $action == 'addromancoin':
			$nicenameaction = 'Add Roman numismatic data';
			break;
			case $action == 'romannumismatics':
			$nicenameaction = 'Roman numismatic search';
			break;
			case $action == 'record':
			$nicenameaction = 'Object/coin record';
			break;
			case $action == 'emperorbios':
			$nicenameaction = 'Emperor biographies';
			break;
			case $action == 'postmednumismatics':
			$nicenameaction ='Post Medieval numismatic search';
			break;
			case $action == 'project':
			$nicenameaction = 'Project details';
			break;
			case $action == 'hers':
			$nicenameaction = 'HER offices signed up';
			break;
			case $action == 'ruler':
			$nicenameaction = 'Ruler details';
			break;
			case $action == 'error':
			$nicenameaction = 'Error details';
			break;
			case $action == 'errorreport':
			$nicenameaction = 'Submit an error';
			break;
			
			default:
			$nicenameaction = $action;
			break;
	}

	// HomePage = No Breadcrumb
	if($l_m == 'default' && $l_c == 'index' && $l_a == 'index'){
	return;
	}

	// Get our url and create a home crumb
	$url = $this->view->baseUrl();
	$homeLink = "<a href='{$url}/' title='Scheme website home page'>Home</a>";
	// Start crumbs
	$crumbs = $homeLink . " &raquo; ";

	// If our module is default
	if($l_m == 'default') {
	
	if($l_a == 'index'){
	$crumbs .= ucfirst($nicename);
	} else {
	$crumbs .= " <a href='{$url}/{$controller}/' title='Return to {$nicename} section'>$nicename</a> &raquo; $nicenameaction ";
	
	
	}
	} else {
	// Non Default Module
	if($l_c == 'index' && $l_a == 'index') {
	$crumbs .= ucfirst($nicemodule);
	} else {
	$crumbs .= "<a href='{$url}/{$module}/' title='Return to $nicemodule home'>" . ucfirst($nicemodule) . "</a> &raquo; ";
	
	if($l_a == 'index') {
	$crumbs .= ucfirst($nicename);
	} else {
	$crumbs .= " <a href='{$url}/{$module}/{$controller}/' title='Return to $nicename home'> " . ucfirst($nicename) . "</a> &raquo; " . ucfirst($nicenameaction);
	}
	}
	
	}
	return $crumbs;
	}

}
