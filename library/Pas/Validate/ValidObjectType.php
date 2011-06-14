<?php 
class Pas_Validate_ValidObjectType extends Zend_Validate_Abstract
{
const NOT_VALID = 'notValid';

protected $_messageTemplates = array(self::NOT_VALID => 'You can only use terms in the database. 
These appear in the autocomplete in block capitals.');

public function flatten($ar) {
    $toflat = array($ar);
    $res = array();

    while (($r = array_shift($toflat)) !== NULL) {
        foreach ($r as $v) {
                if (is_array($v)) {
                        $toflat[] = $v;
                } else {
                        $res[] = $v;
                }
        }
    }

    return $res;
}
public function getTypes() 
	{
	
	
	$objects = new ObjectTerms();
	$o =  $objects->getObjectNames();
	$terms = $this->flatten($o);
	return $terms;
	 }

public function _checkNum($length){
  return ($length%2) ? TRUE : FALSE;
}
public function in_arrayi( $needle, $haystack ) { 
        $found = false; 
        foreach( $haystack as $value ) { 
            if( strtolower( $value ) == strtolower( $needle ) ) { 
                $found = true; 
            } 
        }    
        return $found; 
    }

public function isValid($value)
    {
        $value = (string) $value;
		$objecttypes = $this->getTypes();
		
		if(!$this->in_arrayi($value,$objecttypes)) {
         $this->_error(self::NOT_VALID);
         return false;
        }
        return true;
      
    }

}