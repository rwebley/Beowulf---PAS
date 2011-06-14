<?php 

class Pas_Validate_ValidGridRef extends Zend_Validate_Abstract
{
const NOT_VALID = 'notValid';
const NOT_EVEN = 'notEven';

protected $_messageTemplates = array(
		self::NOT_VALID => 'That grid reference does not appear to have valid starting letters.',
		self::NOT_EVEN => 'That grid reference does not appear to be the correct length.'
		);

protected $letters = array(
	   'SV','SW','SX','SY','SZ','TV','TW',
	   'SQ','SR','SS','ST','SU','TQ','TR',
	   'SL','SM','SN','SO','SP','TL','TM',
       'SF','SG','SH','SJ','SK','TF','TG',
       'SA','SB','SC','SD','SE','TA','TB',
       'NV','NW','NX','NY','NZ','OV','OW',
       'NQ','NR','NS','NT','NU','OQ','OR',
       'NL','NM','NN','NO','NP','OL','OM',
       'NF','NG','NH','NJ','NK','OF','OG',
       'NA','NB','NC','ND','NE','OA','OB',
       'HV','HW','HX','HY','HZ','JV','JW',
       'HQ','HR','HS','HT','HU','JQ','JR',
       'HL','HM','HN','HO','HP','JL','JM',
        );

public function _checkNum($length){
  return ($length%2) ? TRUE : FALSE;
}



public function isValid($value)
    {
        $value = (string) $value;
		$value = str_replace(' ','',$value);
		$length = strlen($value);
		if($this->_checkNum($length) === TRUE) {
		$this->_error(self::NOT_EVEN);
        return false;		
		}
        $letterpair = substr($value,0,2); //strips off first two characters as National grid has 2 left
		$letterpair = strtoupper($letterpair); //transform smallcase to capital
		
		if(!in_array($letterpair,$this->letters)) {
         $this->_error(self::NOT_VALID);
         return false;
        }
        return true;
      
    }

}