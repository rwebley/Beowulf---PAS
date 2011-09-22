<?php
/** Model for interacting with the user's table
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @todo 		add edit and delete functions, caching
*/

class Users extends Pas_Db_Table_Abstract {
	
	protected $_name = 'users';
	
	protected $_primary = 'id';

	/** Work out who created a user account
	* @param integer $createdby The userid of the created by, returns the username of creator 
	* @return array
	*/
	public function getCreatedBy($createdby) {
	$users = $this->getAdapter();
	$select = $users->select()
	->from($this->_name, array('fullname'))
	->joinLeft('staff','staff.dbaseID = users.id', array('i' => 'id'))
	->where('users.id = ?' , (int)$createdby)
	->limit(1);
	return $users->fetchAll($select);
	}

	/** Get a key value list of userid and fullname for a dropdown population 
	* @param integer $createby The userid of the created by, returns the username of creator 
	* @return array
	* @todo is this sustainable as the system grows? 
	*/
	public function getOptions() {
	$select = $this->select()
	->from($this->_name, array('ID', 'CONCAT(username," - ",fullname)'))
	->where('institution IS NOT NULL')
	->order('username ASC');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

	/** Get a key value list of userid and fullname for a dropdown population for members higher than basic level 
	* and where institution is inserted.
	* @return array
	* @todo is this sustainable as the system grows? 
	*/
	public function getUserNamesSearch() {
	$select = $this->select()
	->from($this->_name, array('ID', 'username'))
	->where('institution IS NOT NULL')
	->where('role != ?','member')
	->order('fullname ASC');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    
	/** Work out whether activation key exists
	* @param integer $valid
	* @param string $username The user's name on system
	* @param string $key The user's activation key
	* @return array
	* @todo is this sustainable as the system grows? 
	*/
	public function activation($key = NULL , $username = NULL, $valid = '0') {
	$select = $this->select()
	->from($this->_name, array('activationKey', 'username', 'valid'))
	->where('users.activationKey = ?', (string)$key)
	->where('users.username = ?', (string)$username)
	->where('users.valid = ?', (int)$valid);
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}

	/** Retrieve a user based around their email and their username
	* @param string $username The user's name on system
	* @param string $email The user's email address
	* @return array
	* @todo add caching? used infrequently, so probably not?
	*/
	public function findUser($email,$username){	
	$users = $this->getAdapter();
	$select = $this->select()
	->from($this->_name, array('username'))
	->where('users.email = ?', (string) $email)
	->where('users.username = ?', (string)$username);
	return $users->fetchAll($select);
	}

	/** Retrieve a user based around their email 
	* @param string $email The user's email address
	* @return array
	* @todo add caching?
	*/
	public function getUserByUsername($email) {	
	$users = $this->getAdapter();
	$select = $this->select()
	->from($this->_name, array('username', 'fullname'))
	->where('users.email = ?', (string)$email);
	return $users->fetchAll($select);
	}

	/** Retrieve a user's profile with additional info, such as creator 
	* @param string $integer The user's identification number
	* @return array
	* @todo add caching?
	*/
	public function getUserProfile($id) {	
	$users = $this->getAdapter();
	$select = $this->select()
	->from($this->_name)
	->joinLeft($this->_name,$this->_name . '.createdBy = ' . $this->_name . '_2.id', 
	array('creator' => 'fullname'))
	->joinLeft($this->_name,$this->_name . '.updatedBy = ' . $this->_name . '_3.id', 
	array('updater' => 'fullname'))
	->where('users.id = ?', (int)$id);
	    return $users->fetchAll($select);
	}
	
	/** Retrieve a list of authors for the content section. 
	* @return array
	* @todo add caching?
	*/
	public function getAuthors() {	
	if (!$accounts = $this->_cache->load('authorlist')) {	
	$users = $this->getAdapter();
	$select = $this->select()
	->from($this->_name, array('id', 'fullname'))
	->where('role IN ("admin", "flos", "fa", "treasure")')
	->order('fullname');
	$accounts =  $users->fetchPairs($select);
	$this->_cache->save($accounts, 'authorlist');
	}
	return $accounts; 
	}

	/** Retrieve a list of users and paginate 
	* @param integer $params['page']
	* @param string $params['username'] 
	* @param string $params['fullname']
	* @param integer $params['visits']
	* @return array
	* @todo rewrite this rubbish function
	*/
	public function getUsersAdmin($params) {	
	$users = $this->getAdapter();
	$select = $this->select()
		->from($this->_name)
		->order('lastlogin DESC');
	if(isset($params['username']) && ($params['username'] != "")) {
	$un = strip_tags($params['username']);
	$select->where('username LIKE ?', (string)'%'.$un.'%');
	}
	if(isset($params['role']) && ($params['role'] != "")) {
	$r = strip_tags($params['role']);
	$select->where('role = ?', (string)$r);
	}
	if(isset($params['fullname']) && ($params['fullname'] != "")) {
	$fn = strip_tags($params['fullname']);
	$select->where('fullname = ?', (string)$fn);
	}
	if(isset($params['visits']) && ($params['visits'] != ""))  {
	$v = strip_tags($params['visits']);
	$select->where('visits >= ?', (string)$v);
	}
	if(isset($params['lastlogin']) && ($params['lastlogin'] != "")) {
	$ll = strip_tags($params['lastlogin']);
	$select->where('lastLogin >= ?', $ll.' 00:00:00');
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(20) 
	->setPageRange(10) ;
	if(isset($params['page']) && ($params['page'] != ""))  {
	$paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}
	
	/** Retrieve a list of authors for ajax list
	* @param string $username  
	* @return array
	* @todo rewrite for jquery autocomplete zend method
	*/
	public function findUserAccountAjax($username) {	
	$users = $this->getAdapter();
	$select = $this->select()
	->from($this->_name,array('id' => 'username','term' => 'username'))
	->where('users.username LIKE ?', (string)'%'.$username.'%');
	return $users->fetchAll($select);
	}

	/** Retrieve a user with additional info using their username as the lookup
	* @param string $username  
	* @return array
	* @todo rewrite for jquery autocomplete zend method
	*/
	public function findUserAccount($username) {	
	$users = $this->getAdapter();
	$select = $this->select()
	->from($this->_name)
	->joinLeft($this->_name,$this->_name . '.createdBy = ' . $this->_name . '_2.id', 
	array('creator' => 'fullname'))
	->joinLeft($this->_name,$this->_name . '.updatedBy = ' . $this->_name . '_3.id',
	array('updater' => 'fullname'))
	->where('users.username = ?', (string)$username);
    $data =  $users->fetchAll($select);
	return $data;
	}

	/** Retrieve a list of members attached to an institution on the system
	* @param integer $instID  
	* @return array
	*/
	public function getMembersInstitution($instID) {
	$roles = $this->getAdapter();
    $select = $roles->select()
	->from($this->_name)
	->joinLeft('institutions',$this->_name.'.institution = institutions.institution', array())
	->where('institutions.id = ?',(int)$instID);
	return $roles->fetchAll($select);
	}
	
	/** Retrieve a count of registered users for front page and to annoy the Polish archaeologist
	* @param integer $instID  
	* @return array
	*/
	public function getCounter() {
	$users = $this->getAdapter();
	$select = $users->select()
	->from($this->_name, array('count' => 'COUNT(id)'));
	return $users->fetchAll($select);
	}

	/** Retrieve a count of registered users who have been more active than you
	* @param integer $visits
	* @return array
	*/
	public function getMoreTotals($visits) {
	$roles = $this->getAdapter();
	$select = $roles->select()
	->from($this->_name ,array('morethan' => 'COUNT(*)'))
	->where($this->_name.'.visits > ?',(int)$visits);
	return $roles->fetchAll($select);
	}

	/** Retrieve a paginated list of members with certain privilege
	* @param integer $visits
	* @return array
	*/
	public function getRolesMembers($role,$page) {
	$roles = $this->getAdapter();
	$select = $roles->select()
	->from($this->_name, array('username', 'createdBy', 'updatedBy', 'id', 'fullname'))
	->joinLeft('roles',$this->_name.'.role = roles.role', array())
	->where('roles.id = ?',(int)$role);
	$paginator = Zend_Paginator::factory($select);
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(50) 
	->setPageRange(10); 
	return $paginator;
	}
	
	/** Retrieve a user's data via username
	* @param string $username
	* @return array
	* @todo change to fetchrow? Delete?
	*/
	public function getUserAccountData($username) {	
	$users = $this->getAdapter();
	$select = $this->select()
	->from($this->_name)
	->where('users.username = ?', (string)$username);
	return $users->fetchAll($select);
	}

	/** Retrieve a user's count and quantity of finds recorded
	* @param integer $id
	* @return array
	*/
	public function getCountFinds($id) {		
	$users = $this->getAdapter();
	$select = $users->select()
	->from('finds', array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(finds.quantity)'))
	->where('finds.createdBy = ?', (int)$id);
	return $users->fetchAll($select);
	}
	
	/** Retrieve a user's ID number
	* @param string $username
	* @return array
	* @todo Why isn't this fetchrow you muppet?
	*/
	public function getUserID($username){
	$users = $this->getAdapter();
	$select = $users->select()
	->from($this->_name,array('id'))
	->where($this->_name . '.username = ?', (string)$username);
	return $users->fetchAll($select);
	}

	/** Retrieve a count of who has requested higher level access
	* @return array
	*/
	public function getNewHigherLevelRequests()	{
	$users = $this->getAdapter();
	$select = $users->select()
	->from($this->_name,array('applicants' => 'COUNT(id)'))
	->where($this->_name.'.higherLevel = ?', (int)1)
	->where('role IN ( "public" ,"member" )');
	return $users->fetchAll($select);
	}
	
	/** Retrieve a list of requested upgrades paginated
	* @param integer $params['page'] page number
	* @return array
	*/
	public function getUpgrades($params) {
	$users = $this->getAdapter();
	$select = $users->select()
	->from($this->_name)
	->where($this->_name.'.higherLevel = ?', (int)1)
	->where('role IN ( "public" ,"member" )');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(20) 
	->setPageRange(10) ;
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;	
	}

	/** Retrieve a cached list of who signed up today
	* @return array
	*/
	public function newPeople() {
	if (!$accounts = $this->_cache->load('newusers')) {
	$users = $this->getAdapter();
	$select = $users->select()
	->from($this->_name,array('username'))
	->where('created >= CURDATE()');
    $accounts = $users->fetchAll($select);
	$this->_cache->save($accounts, 'newusers');
	}
	return $accounts; 	
	}
	
	/** Retrieve the owner of a find record
	* @param integer $findID the find record ID number 
	* @return array
	*/
	public function getOwner($findID) {
	if (!$accounts = $this->_cache->load('owneroffind'.$findID)) {
	$users = $this->getAdapter();
	$select = $users->select()
	->from($this->_name,array('fullname','email'))
	->joinLeft('finds','finds.createdBy = users.id')
	->where('finds.id = ?',(int)$findID);
	$accounts = $users->fetchAll($select);
	$this->_cache->save($accounts, 'owneroffind'.$findID);
	}
	return $accounts; 	
	}
	
}