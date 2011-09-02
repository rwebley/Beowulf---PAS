<?php
/**
 * A view helper for checking if a user can do something
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @todo See if this is worth keeping, limited and bad code
 */
class Pas_View_Helper_Allowedtest extends Zend_View_Helper_Abstract {
	
	public function allowedtest($string) {
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()) {
	$higherLevel = array('admin'); 
	$user = $auth->getIdentity();
	$role = $user->role;
	if(in_array($role,$higherLevel)) {
	echo $string;
	}
	}
	}
}