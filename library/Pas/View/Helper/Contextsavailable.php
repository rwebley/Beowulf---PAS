<?php
/**
 * A view helper for determining which contexts are available and displaying links 
 * to obtain them
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_Url
 * @uses Zend_View_Helper_Baseurl
 */ 
class Pas_View_Helper_Contextsavailable extends Zend_View_Helper_Abstract {
	
	/** A list of contexts can be turned into urls
	 * 
	 * @param string $contexts
	 */
	public function contextsavailable($contexts) {
	if(!is_null($contexts)) {
	$module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();	
	$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();	
	$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();	
	$string = '<div id="contexts"><p>This page is available in: ';
	foreach($contexts as $key => $value) {
	$url = $this->view->url(array(
	'module' => $module,
	'controller' => $controller, 
	'action' => $action, 
	'format' => $value),null,false);
	if(in_array($value,array('csv'))){
	$string .= '<a href="' . $url . '" title="Obtain data in '. $value 
	.' representation" rel="nofollow">' . $value . '</a> ';
	} else {
	$string .= '<a href="' . $url . '" title="Obtain data in ' . $value 
	. ' representation">' . $value . '</a> ';	
	}
	}
	$string .=' representations.</p></div>';
	echo $string;
	}
}
}