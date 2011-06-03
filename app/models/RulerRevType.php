<?php

/**
* Data model for accessing and manipulating rulers and reverse type link table
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @version 1
* @since 22 October 2010, 17:12:34
*/
class RulerRevType extends Zend_Db_Table_Abstract {

	protected $_primary = 'id';

	protected $_name = 'ruler_reversetype';

}