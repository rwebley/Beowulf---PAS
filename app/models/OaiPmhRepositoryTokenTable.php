<?php
/** Retrieve and manipulate data for OAI-PMH tokens
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
*/
class OaiPmhRepositoryTokenTable extends Zend_Db_Table_Abstract {
    
	protected $_primary = 'id';
	protected $_name = 'oai_pmh_repository_tokens';
	
	/** Deletes the rows for expired tokens from the table.
	* @return array
	*/
    public function purgeExpiredTokens() {
	$where = $tokens->getAdapter()->quoteInto('expiration <= ?', 'NOW()');	
    }
	/** Get a specific token
	* @param integer $token
	* @return array
	* @todo add caching
	*/
    public function getToken($token){
	$records = $this->getAdapter();
	$select = $records->select()
		->from($this->_name)
		->where($this->_name . ' .id = ?', (int)$token);
	return $records->fetchRow($select);
    }
}
