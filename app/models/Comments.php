<?php
/**
* @category Zend
* @package Db_Table
* @subpackage Abstract
* 
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license GNU General Public License
* @todo add some caching to model
*/

class Comments extends Zend_Db_Table_Abstract {
	
	protected $_name = 'comments';
	protected $_primary = 'comment_ID';
	protected $_cache;
	
	/** Construct the cache object
	* @return object
	*/
	public function init(){
		$this->_cache = Zend_Registry::get('rulercache');
	}
	
	/** Get comments by id
	* @param integer $id
	* @return array
	*/
	public function getFindComments($id){
		$comments = $this->getAdapter();
		$select = $comments->select()
                       ->from($this->_name, array('comment_id','df' => 'DATE_FORMAT(comment_date,"%T on the  %D %M %Y")','comment_author','comment_author_url','comment_content','comment_author_email'))
					   ->joinLeft('finds','finds.id = comments.comment_findID',array())
					   ->where('finds.id = ?',$id)
					   ->where('comments.comment_type  = ?','recordcomment')
					   ->where('comments.comment_approved = ?','approved')
					   ->order('comment_date ASC');
        return $comments->fetchAll($select);
    }
    
    /** Get comments by id on news articles
	* @param integer $id
	* @todo remove date formating and put into view?
	* @return array
	*/
	public function getCommentsNews($id) {
		$comments = $this->getAdapter();
		$select = $comments->select()
	                       ->from($this->_name, array('comment_id','df' => 'DATE_FORMAT(comment_date,"%T on the  %D %M %Y")','comment_author','comment_author_url','comment_content','comment_author_email'))
						   ->joinLeft('finds','finds.id = comments.comment_findID',array())
						   ->where('finds.id = ?',$id)
						   ->where('comments.comment_type  = ?','newscomments')
						   ->where('comments.comment_approved = ?','approved')
						   ->order('comment_date ASC');
        return $comments->fetchAll($select);
    }
	
    /** Get comments list
	* @param array $params
	* @param integer $userid
	* @todo perhaps insert switch on approval status?
	* @todo remove date formating and put into view?
	* @return array
	*/
    public function getComments($params,$userID = NULL) {
		$comments = $this->getAdapter();
		$select = $comments->select()
	                       ->from($this->_name, array('comment_ID','df' => 'DATE_FORMAT(comment_date,"%T @ %D %M %Y")','comment_author','comment_author_url','comment_content','comment_approved','user_IP','comment_author_email','comment_type'))
						   ->joinLeft('finds','finds.id = comments.comment_findID',array('id','old_findID','broadperiod','objecttype'))
						   ->order('comment_date DESC');
		if(isset($params['approval']) && $params['approval'] == 'spam') {
		$select->where('comments.comment_approved = ?',(string)'spam');
		}
		if(isset($params['approval']) && $params['approval'] == 'approved'){
		$select->where('comments.comment_approved = ?',(string)'approved');
		}
		if(isset($params['approval']) && $params['approval'] == 'moderation'){
		$select->where('comments.comment_approved = ?',(string)'moderation');
		}
		if(isset($userID)){
		$select->where('comments.user_id = ?',(int)$userID);
		}
		$data = $comments->fetchAll($select);
    	$paginator = Zend_Paginator::factory($data);
		$paginator->setItemCountPerPage(30) 
		          ->setPageRange(20);
		if(isset($params['page']) && ($params['page'] != "")) 
		{
        $paginator->setCurrentPageNumber($params['page']); 
		}
	return $paginator;    
	}
	
	/** Get comments by id 
	* @param integer $id
	* @todo remove date formating and put into view?
	* @todo change to fetchrow?
	* @return array
	*/
	protected function getComment($id) {
		$comments = $this->getAdapter();
		$select = $comments->select()
                       ->from($this->_name)
					   ->where('comment_id = ?',$id);
        return $comments->fetchAll($select);
	}

	 /** Get comments by userid and level of approval
	* @param integer $userid
	* @param integer $page
	* @param integer $approval
	* @todo perhaps insert switch on approval status?
	* @todo remove date formating and put into view?
	* @return array
	*/
	
	public function getCommentsOnMyRecords($userid,$page,$approval) {
		$comments = $this->getAdapter();
		$select = $comments->select()
                       ->from($this->_name,array('comment_ID','df' => 'DATE_FORMAT(comment_date,"%T @ %D %M %Y")','comment_author','comment_author_url','comment_content','comment_approved','user_IP','comment_author_email'))
					   ->joinLeft('finds','finds.id = comments.comment_findID',array('id','old_findID','broadperiod','objecttype'))
					   ->where('finds.createdBy = ?',(int)$userid)
					   ->where('comments.comment_type = ?', 'recordcomment');
		if(isset($approval) && $approval == 'spam') {
		$select->where('comments.comment_approved = ?',(string)'spam');
		}
		if(isset($approval) && $approval== 'approved'){
		$select->where('comments.comment_approved = ?',(string)'approved');
		}
		if(isset($approval) && $approval == 'moderation'){
		$select->where('comments.comment_approved = ?',(string)'moderation');
		}
        $paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30) 
		          ->setPageRange(20);
		if(isset($page) && ($page != "")) {
        $paginator->setCurrentPageNumber($page); 
		}
		return $paginator;    
	}

	/** Get comments on finds records
	* @param integer $page
	* @todo remove date formating and put into view?
	* @return array
	*/
	
	public function getCommentsToFinds($page) {
		$comments = $this->getAdapter();
		$select = $comments->select()
                       	->from($this->_name,array('comment_ID','df' => 'DATE_FORMAT(comment_date,"%T @ %D %M %Y")','comment_author','comment_author_url','comment_content','comment_approved','user_IP','comment_author_email','comment_type','updated','comment_date'))
					   	->joinLeft('finds','finds.id = comments.comment_findID',array('id','old_findID','broadperiod','objecttype'))
						->joinLeft('finds_images','finds.secuid = finds_images.find_id',array())
						->joinLeft('slides','slides.secuid = finds_images.image_id',array('i' => 'imageID','f' => 'filename')) 
						->joinLeft(array('u' => 'users'),'slides.createdBy = u.id',array('imagedir'))
					   	->joinLeft('findspots','finds.secuid = findspots.findID',array('county'))
					   	->where('comment_type = ?','recordcomment')
					   	->where('comment_approved = ?',(string)'approved')
					   	->order('comment_date DESC,finds.id ')
					   	->group('comment_ID');
        $paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(30) 
		          ->setPageRange(20);
		if(isset($page) && ($page != "")) {
        $paginator->setCurrentPageNumber($page); 
		}
	return $paginator;    
	}

}
