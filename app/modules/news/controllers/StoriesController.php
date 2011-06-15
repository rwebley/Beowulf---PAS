<?php
/** Controller for all the Scheme's news stories
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_StoriesController extends Pas_Controller_ActionAdmin {
	
	public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
			 ->addActionContext('article', array('xml','json'))
             ->initContext();
    }

	public function indexAction(){
		$format = $this->_request->getParam('format');
		
		if(in_array($format,array('georss','rss','atom'))){ 
		$news = new News();
		$news = $news->getNews();
		// prepare an array that our feed is based on
		$feedArray = array(
			'title' => 'Latest news from the Portable Antiquities Scheme',
			'link' => Zend_Registry::get('siteurl').'/news/',
			'charset' => 'utf-8',
			'description' => 'The latest news stories published by the Portable Antiquities Scheme',
			'author' => 'The Portable Antiquities Scheme',
			'image' => Zend_Registry::get('siteurl').'/images/logos/pas.gif',
			'email' => 'info@finds.org.uk',
			'copyright' => 'Creative Commons Licenced',
			'generator' => 'The Scheme database powered by Zend Framework and Dan\'s magic',
			'language' => 'en',
			'entries' => array()
		);
		foreach ($news as $new) {
		$auth = Zend_Auth::getInstance();
		//$latlong = $new['declat'] .' ' .$new['declong'];
		$feedArray['entries'][] = array(
				'title' => $new['title'],
				'link' => Zend_Registry::get('siteurl').'/news/story/id/'.$new['id'],
				'guid' => Zend_Registry::get('siteurl').'/news/story/id/'.$new['id'],
				'description' => $this->EllipsisString($new['contents'],200),
				'lastUpdate' => strtotime($new['datePublished']),
				//'georss'=> $latlong,  
				//'enclosure' => array()
				);
				
				/*if($object['i'] != NULL) {
				$feedArray['enclosure'][] = array(array(
				'url' => 'http://www.findsdatabase.org.uk/view/thumbnails/pas/'.$object['i'].'.jpg',
				'type' => 'image/jpeg' //always sets to jpeg as the thumbnails are derived from there.
				));
				}*/
		}
		   $feed = Zend_Feed::importArray($feedArray, $format);
		 $feed->send();
		 } else {
	$this->_redirect('/news/');
	}
	}
	
	
	public function articleAction() {
	if($this->_getParam('id',false)){
	$news = new News();
	$this->view->news = $news->getStory($this->_getParam('id'));
	$comments = new Comments();
	$this->view->comments = $comments->getCommentsNews($this->_getParam('id'));
	
	$form = new CommentFindForm();
	$form->submit->setLabel('Add a new comment');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$data = array();
	$data['comment_findID'] = $this->_getParam('id');
	$data['user_ip'] = $form->getValue('comment_author_IP');
	$data['user_agent'] = $form->getValue('comment_agent');
	$data['comment_type'] = 'newscomment';
	$data['comment_author'] = $form->getValue('comment_author');
	$data['comment_author_email'] = $form->getValue('comment_author_email');
	$data['comment_content'] = $form->getValue('comment_content');
	$data['comment_date'] = $this->getTimeForForms();
	$data['user_id'] = $this->getIdentityForForms();
	$config = Zend_Registry::get('config');
	$akismetkey = $config->webservice->akismetkey;
	$akismet = new Zend_Service_Akismet($akismetkey, 'http://www.finds.org.uk');
		
	if ($akismet->isSpam($data)) { 
	$data['comment_approved'] = 'spam';
	} else 
	{
	$data['comment_approved'] =  'moderation';
	} 
	$comments = new Comments();
	$insert = $comments->insert($data);
	$this->_flashMessenger->addMessage('Your comment has been entered and will appear shortly!');
	$this->_redirect('/news/stories/article/id/'.$this->_getParam('id'));
	$this->_request->setMethod('GET'); 
	} else {
	$this->_flashMessenger->addMessage('There are problems with your comment submission');
	$form->populate($formData);
	}
	}
	
	} else {
	throw new Exception('No parameter on the url string');
	}
	
	}

	public function newsfeedAction(){
	} 

	public function ellipsisString($string, $max = 300, $rep = '...') {
		if (strlen($string) < $max) {
		return $string;
		} else { 
		$leave = $max - strlen ($rep);
		}
	    return strip_tags(substr_replace($string, $rep, $leave),'<br><a><em>');
	}


}