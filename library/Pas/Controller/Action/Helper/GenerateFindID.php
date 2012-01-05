<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * GenerateFindID Action Helper 
 * 
 */
class Pas_Controller_Action_Helper_GenerateFindID 
	extends Zend_Controller_Action_Helper_Abstract {
	
	protected function _getAccount(){
		$user = new Pas_UserDetails();
		return $user->getPerson()->institution;
	}
	/**
	 * Strategy pattern: call helper as broker method
	 */
	public function direct() {
	if(!is_null($this->_getAccount())) {
	list($usec, $sec) = explode(" ", microtime());
	$suffix =  strtoupper(substr(dechex($sec), 3) . dechex(round($usec * 8)));
	return $this->_getAccount() . '-' . $suffix;
	} else {
		throw new Pas_Exception_NotAuthorised('Institution missing');
	}
	}
}

