<?php
/* SCRIPT: gridcalc.class.php
* PURPOSE: Calculate derivative information on OS grid references
* MODULE: map
* ASSUMPTIONS:
* 	Requires logger.class.php; instance already declared as '$logger'
* © Oxford ArchDigital Ltd. 2001-2002
*/
class Zend_View_Helper_GetAccuracy

{
private function stripgrid($string=""){
	$stripOut = array(" ","-","-",".");
	$gridRef = str_replace($stripOut,"",$string);
	$gridRef = strtoupper($gridRef);
	return $gridRef;
}

public function GetAccuracy($gridref,$clean=1){

	if ($clean == 1){$gridref = $this->stripgrid($gridref);}
	$coordCount = strlen($gridref)-2; //count length and strip off fist two characters

	switch ($coordCount) {
		case 0:
			$acc = 100000;
			break;
		case 2:
			$acc = 10000;
			break;
		case 4:
			$acc = 1000;
			break; 
		case 6:
			$acc = 100;
			break;
		case 8:
			$acc = 10;
			break;
		case 10:
			$acc = 1;
			break;
		case 12:
			$acc = 0.1;
			break;
		case 14:
			$acc = 0.01;
			break;
		default:
			return false;
			break;
	}		
	
	$gridAcc = $acc;
	return $acc;	
}

}