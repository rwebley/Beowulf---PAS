<?php
/**
* Model for pulling bookmark system data.
*
*
* @category   Pas
* @package    Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
*/
class Bookmarks extends Pas_Db_Table_Abstract {

	protected $_name = 'bookmarks';
	protected $_primary = 'id';

	/** Get all valid bookmarks
	* @return array
	*/
	public function getValidBookmarks() {
	if (!$data = $this->_cache->load('bookmarksSite')) {
	$bookmarks = $this->getAdapter();
	$select = $bookmarks->select()
		->from($this->_name, array('image','url','service'))
		->where('valid = ?',(int)1);
	$data =  $bookmarks->fetchAll($select);
	$this->_cache->save($data, 'bookmarksSite');
	}
	return $data;
	}

}