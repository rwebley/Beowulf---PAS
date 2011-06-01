<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add edit and delete functions
*/
class OauthTokens extends Zend_Db_Table_Abstract {

	protected $_name = 'oauthTokens';
	
	protected $_primary = 'id';

	protected $_cache = NULL;
	
	/** Construct the cache object
	* @return object
	*/
	public function init(){
		$this->_cache = Zend_Registry::get('rulercache');
	}

	/** get the cached token for accessing twitter's oauth'd endpoint
	* @param string twitteraccess 
	* @return object
	*/
	public function getTokens(){
	if (!$data = $this->_cache->load('oauthtwitter')) {
		$tokens = $this->getAdapter();
		$select = $tokens->select()
		->from($this->_name)
		->where('service = ?', 'twitterAccess');
     	$data =  $tokens->fetchAll($select);
		$this->_cache->save($data, 'oauthtwitter');
	}
    return $data;
	}

}