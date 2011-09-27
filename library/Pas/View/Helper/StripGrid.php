<?php 
class Zend_View_Helper_StripGrid

{
public function StripGrid($string=""){
	$stripOut = array(" ","-","-",".");
	$gridRef = str_replace($stripOut,"",$string);
	$gridRef = strtoupper($gridRef);
	return $gridRef;
}

}