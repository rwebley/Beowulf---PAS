<?php 
class Pas_View_Helper_ADBC extends Zend_View_Helper_Abstract {
	
	public function ADBC($date = NULL, $suffix="BC", $prefix="AD") {
	$validator = new Zend_Validate_Int();
	if($validator->isValid($date)){
	if ($date  < 0) {    
   	 return  abs($date) . ' ' . $suffix;
    } else if ($date > 0) {
		return $prefix . ' ' . abs($date);
	} else if ($date === 0) {
		return false;
	}
	} else {
		return false;
	}
	}
 }
