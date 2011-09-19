<?php
/**
 * A view helper for creating a formatted url query string
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett
 */
class Pas_View_Helper_Query extends Zend_View_Helper_Abstract {

	/** Create and print the query string separated by /
	 * @access public
	 * @param array $params the array of params taken from the request
	 * @return string $query The formatted query string
	 */
	public function Query($params) {
		unset($params['controller']);
		unset($params['module']);
		unset($params['action']);
		unset($params['submit']);
		$where = array();
        foreach($params as $key => $value)
        {
			if($value != NULL){
            $where[] = $key . '/' . urlencode($value);
			}
        }
   	$whereString = implode('/', $where);
	$query = $whereString;
	return $query;
	}
}