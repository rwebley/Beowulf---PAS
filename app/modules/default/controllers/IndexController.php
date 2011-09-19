<?php
class IndexController extends Pas_Controller_ActionAdmin
{
	protected $_cache, $_config, $_wordpress, $_wpRoute, $_wpUser, $_wpPass;
	
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow(null);
	$this->_cache   = Zend_Registry::get('rulercache');
	$this->_config  = Zend_Registry::get('config');
	$this->_wpRoute = $this->_config->webservice->wordpress->xmlrpc;
	$this->_wpUser  = $this->_config->webservice->wordpress->user;
	$this->_wpPass  = $this->_config->webservice->wordpress->password;
    }

    private function wordpress(){
	return $this->_wordpress = new ZendX_Service_Wordpress($this->_wpRoute, $this->_wpUser, 
	$this->_wpPass);
    }
    
	public function indexAction() {
		
	$content = new Content();
	$this->view->contents = $content->getFrontContent('index');
	
	$quotes = new Quotes();
	$this->view->quotes  = $quotes->getValidQuotes();
	
	$finds = new Finds();
	$this->view->finds = $finds->getCountAllFinds();
	
	$people = new Peoples();
	$this->view->people = $people->getCountAllPeople();
	
	$users = new Users();
	$this->view->users = $users->getCounter();
	
	$news = new News();
	$this->view->news = $news->getHeadlines();
	
	$eventsList = new Events();
	$eventsListed = $eventsList->getUpcomingEvents();
	
	$calendar = new Calendar;
	$lists = array();
	foreach ($eventsListed as $value) {
	$lists[] = $value['eventStartDate'];
	}
	$listedDates = $lists;
	$calendar->highlighted_dates = $listedDates;
	$url = $this->view->url(array(
	'module' => 'events', 'controller' => 'upcoming', 'action' => 'index')
	,'upcoming',true);
	$calendar->formatted_link_to = $url . '/%Y-%m-%d';
	$cal = $calendar->output_calendar();
	$this->view->cal =$cal;

//	if (!($this->_cache->test('wordpressfront'))) {
//	$blog = $this->_wordpress()->getBlog();
//	$this->_cache->save($blog);
//	} else {
//	$blog = $this->_cache->load('wordpressfront');
//	}
//	$this->view->blogTitle = $blog->getTitle();        
//	$wp = array();      
//	foreach ($blog->getRecentPosts(7) as $post) {
//		$data = array();
//		$url =  $post->getUrl();
//		$title =  $post->getTitle();
//		$data['url'] = $url;
//		$data['ptitle'] = $title;
//		$wp[] = $data;
//	}
//	$this->view->wp = $wp;
	}

}