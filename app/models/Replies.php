<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add edit and delete functions and cache
*/
class Replies extends Zend_Db_Table_Abstract{

	protected $_name = 'replies';
	
	protected $_primary = 'id';	

}
