<?php 
class Pas_View_Helper_ADBC extends Zend_View_Helper_Abstract {
	
	public function adbc($string = NULL, $suffix="BC", $prefix="AD") {
    if ($string  < 0) {    
    return  abs($string) . ' ' . $suffix;
    } else if ($string > 0) {
	return $prefix . ' ' . abs($string);
	} else if ($string == 0) {
	return false;
	}
	}
 }
