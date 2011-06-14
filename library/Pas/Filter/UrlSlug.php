<?php
/**
* Filter extension for producing URL slugs
*
*
* @category   Pas
* @package    Filter
* @subpackage Interface
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Pas_Filter_UrlSlug implements Zend_Filter_Interface_Abstract {
   
	/** Filter the input
	* @param string $slug The string to sanitise
	* @return string $result the cleaned result
	*/
	function generateSlug($slug) {
    $result = strtolower($slug);
    $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
    $result = trim(preg_replace("/\s+/", " ", $result));
    $result = trim(substr($result, 0, 45));
    $result = preg_replace("/\s/", "-", $result);
    return $result;
	}
}