<?php

/**
* Archived events controller
*
* @category   Pas
* @package    Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Events_ArchivesController extends Pas_Controller_ActionAdmin
{
    protected $_contextSwitch;
    
    protected $_contexts;
	
    /**
	* Initialise the ACL for access levels and the contexts
	*/
	public function init() {
	$this->_helper->acl->allow('public',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_contextSwitch = $this->_helper->contextSwitch();
	$this->_contexts = array('xml','json','atom','rss');
	$this->_contextSwitch->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('index', $this->_contexts)
		->addActionContext('upcoming', $this->_contexts)
		 ->addActionContext('event',$this->_contexts)
		->initContext();
	    }
	/**
	* Return data for the index page
	*/	
	public function indexAction() {
	$events = new Events();
	$events = $events->getArchivedEventsList($this->_getAllParams());
	if(!in_array($this->_contextSwitch->getCurrentContext(),$this->_contexts)) {
	$current_year = date('Y');
	$years = range(1998, $current_year);
	$yearslist = array();
	foreach($years as $key => $value) {
	$yearslist[] = array('year' => $value);
	}
	$list = $yearslist;
	$this->view->years = $list;
	$this->view->events = $events;
	}  else {
	$data = array('pageNumber' => $events->getCurrentPageNumber(),
				  'total' => number_format($events->getTotalItemCount(),0),
				  'itemsReturned' => $events->getCurrentItemCount(),
				  'totalPages' => number_format($events->getTotalItemCount()
				  / $events->getItemCountPerPage(),0));
	$this->view->data = $data;
	$eventsData = array();
	foreach($events as $k => $v ){
		$eventsData[$k] = $v;
	}	
	$this->view->events = $eventsData;
	}
	}
	
	/**
	* Return data for the archive by years
	*/
	public function yearAction() {
	$date = $this->_getParam('date').'-01-01' ? $this->_getParam('date') 
	. '-01-01': Zend_Date::now()->toString('yyyy-MM-dd'); 
	$this->view->date = substr($date,0,4);
	$current_year = date('Y');
	$years = range(1998, $current_year);
	$yearslist = array();
	foreach($years as $key => $value) {
	$yearslist[] = array('year' => $value);
	}
	$list = $yearslist;
	$this->view->years = $list;
	$calendar= new Calendar($date); 
	$cases = new Events();
	$cases = $cases->getEventsByDayPast();
	$lists = array();
	foreach ($cases as $value) {
	$lists[] = $value['eventStartDate'];
	}
	$caseslisted = $lists;
	$calendar->highlighted_dates = $caseslisted;
	$url = $this->view->url(array('module' => 'events','controller' => 'archives','action' => 'list'),null,true);
	$calendar->formatted_link_to = $url . '/day/%Y-%m-%d';
	print '<div id="calendar">';
	print("<ul id=\"year\">\n"); 
	for($i=1;$i<=12;$i++){ 
		print("<li>"); 
		if( $i == $calendar->month ){ 
			print($calendar->output_calendar()); 
		} else { 
			print($calendar->output_calendar($calendar->year, $i)); 
		} 
		print("</li>\n"); 
	} 
	print("</ul></div>"); 
	}
	
	/**
	* Return data for the list page
	* @exception 
	*/
	public function listAction() {
	if($this->_getParam('day',false)){
	$this->view->day = $this->_getParam('day');
	$this->view->headTitle('List of events for ' . $this->_getParam('day'));
	$events = new Events();
	$this->view->events = $events->getEventsDate($this->_getParam('day'));
	} else {
	throw new Pas_ParamException('No date has been entered');
	}
	} 
	
	}