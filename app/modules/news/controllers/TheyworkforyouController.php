<?php
/** Controller for accessing they work for you based news
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_TheyworkforyouController extends Pas_Controller_Action_Admin {
	
	const TWFYURL = 'http://www.theyworkforyou.com/api/';
	
	const TWFYAPIKEY = 'CzhqDaDMAgkMEcjdvuGZeRtR';
	
	protected $_cache = NULL;

	protected $_remove = array('Airdrie and Shotts','Ayr, Carrick and Cumnock',
	'Belfast North','Belfast East','Belfast South',
	'Belfast West','Aberdeen North', 'Aberdeen South',
	'Berwick-upon-Tweed','Dundee East','Dundee West',
	'Dunfermline and West Fife', 'Berwickshire, Roxburgh and Selkirk','Banff and Buchan',
	'Caithness, Sutherland and Easter Ross','Cumbernauld, 
	Kilsyth and Kirkintilloch East',
	'Dumfriesshire, Clydesdale and Tweeddale','Dumfries and Galloway',
	'East Kilbride, Strathaven and Lesmahagow','East Londonderry','East Antrim',
	'East Dunbartonshire','East Londonderry','East Lothian',
	'East Renfrewshire', 'Edinburgh East','Edinburgh North and Leith',
	'Edinburgh South','Edinburgh South West','Edinburgh West',
	'Falkirk','Fermanagh and South Tyrone','Foyle',
	'Glasgow Central','Glasgow East','Glasgow North',
	'Glasgow North East', 'Glasgow North West','Glasgow South',
	'Glasgow South West','Glenrothes','Inverclyde',
	'Inverness, Nairn, Badenoch and Strathspey', 'Kilmarnock and Loudoun',
	'Kirkcaldy and Cowdenbeath','Lanark and Hamilton East','Mid Ulster',
	'Midlothian', 'Na h-Eileanan an Iar','Newry and Armagh',
	'North Antrim','North Down','North East Fife',
	'Ochil and South Perthshire', 'Paisley and Renfrewshire North','Paisley and Renfrewshire South',
	'Ross, Skye and Lochaber','Rutherglen and Hamilton West','South Antrim',
	'Upper Bann','West Aberdeenshire and Kincardine',
	'West Dunbartonshire','West Tyrone','Lagan Valley',
	'Strangford'
	);
	
	public function init() {
 		$this->_helper->_acl->allow(null);
      	$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
 			 ->addContext('kml',array('suffix' => 'kml'))
  			 ->addContext('rss',array('suffix' => 'rss'))
			 ->addContext('atom',array('suffix' => 'atom'))
			 ->addActionContext('finds', array('xml','json','kml','rss','atom'))
			 ->addActionContext('members',array('xml','json'))
			 ->addActionContext('constituencies',array('xml','json'))
			 ->addActionContext('index',array('xml','json'))
             ->initContext();
         $this->_cache = Zend_Registry::get('cache');
    }

	private function get($url){
	$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(CURLOPT_POST =>  true,
						   CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
						   CURLOPT_FOLLOWLOCATION => true,
						  // CURLOPT_HEADER => false,
						   CURLOPT_RETURNTRANSFER => true,
						   CURLOPT_LOW_SPEED_TIME => 1
						   ),
	);
	$request = $url;
	$client = new Zend_Http_Client($request, $config);
	$response = $client->request();
	
	$code = $this->getStatus($response);
	if($code == true){
	return $response->getBody();	
	} else {
	return NULL;
	}
	
	}
	
	private function getStatus($response) {
    $code = $response->getStatus();
    switch($code) {
    	case ($code == 200):
    		return true;
    		break;
    	case ($code == 400):
    		throw new Exception('A valid appid parameter is required for this resource');
    		break;
    	case ($code == 404):
    		throw new Exception('The resource could not be found');
    		break;
    	case ($code == 406):
    		throw new Exception('You asked for an unknown representation');
    		break;
    	default;
    		return false;
    		break;	
    }
	}
	
	public function indexAction() {
	$page = $this->_getParam('page');
	$term = $this->_getParam('term');
	$search = $term ? $term : 'portable antiquities scheme'; 
	$this->view->headTitle('Data mined from theyworkforyou website');
	$query = '&search='.urlencode($search);
	$output = '&output=xml';
	$order = '&order=d';
	$key = 'getHansard?key=CzhqDaDMAgkMEcjdvuGZeRtR';
	$num = '&num=100';
	$twfy = self::TWFYURL.$key.$query.$order.$num.$output;
	if (!($this->_cache->test('portantstqwfy'.str_replace(' ','',$term)))) {
	$twfy = self::TWFYURL.$key.$query.$order.$num.$output;
	//Zend_Debug::dump($twfy);
	$arts = Zend_Json::fromXml($this->get($twfy), true);
	$articles = json_decode($arts);
	$this->_cache->save($articles);
	} else {
	$articles = $this->_cache->load('portantstqwfy'.str_replace(' ','',$term));
	} 

	$data = array();
	foreach ($articles->twfy->rows->match as $a) {
	$speaker = array();
	if(isset($a->speaker)) {
	foreach($a->speaker as $b => $v) {
	$speaker[$b] = $v;
	}
	}
	
	$office = array();
	if(isset($a->speaker->office)){
	foreach($a->speaker->office as $o => $v)
	{
	$office[$o] = $v;
	}
	}
	
	$parent = array();
	if(isset($a->parent)){
	foreach($a->parent as $p => $z)
	{
	$parent[$p] = $z;
	}
	}
	$data[] = array(
	'gid' => $a->gid,
    'hdate' => $a->hdate,
    'htime' => $a->htime,
     'relevance' => $a->relevance,
     'speaker_id' => $a->speaker_id,
     'video_status' => $a->video_status,
     'body' => $a->body,
     'listurl' => $a->listurl,
	 'speaker' => $speaker,
	 'office' => $office,
	 'parent' => $parent,
	 //'member_id' => $a->member_id
	);
	}

	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data));
	if(isset($page) && ($page != "")){
	$paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(20) 
    	      ->setPageRange(10); 	
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('xml','json'))){
   	$data = array('pageNumber' => $paginator->getCurrentPageNumber(),
				  'total' => number_format($paginator->getTotalItemCount(),0),
				  'itemsReturned' => $paginator->getCurrentItemCount(),
				  'totalPages' => number_format($paginator->getTotalItemCount()/$paginator->getItemCountPerPage(),0));
	$this->view->data = $data;
   	$members = array();
   	foreach($paginator as $k => $v){
   	$members[]=array();
	$members[$k] = $v;
   	}
   	$this->view->stories = array('story' => $members);	
   	} else {	
	$this->view->data = $paginator;
   	}	
	}

	public function mpAction()
	{
	$id = $this->_getParam('id');
	if($this->_getParam('id',false)) {
	if (!($this->_cache->test('mpdetails'.$id))) {
	$query = '&id='.$id;
	$output = '&output=js';
	$order = '&order=d';
	$key = 'getPerson?key=CzhqDaDMAgkMEcjdvuGZeRtR';
	$twfy = self::TWFYURL.$key.$query;
	$data = json_decode($this->get($twfy));
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('mpdetails'.$id);
	}
	
	$this->view->data = $data;
	
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}
	
	public function findsAction()
	{
	if($this->_getParam('constituency',false)){
	ini_set("memory_limit","256M");
	$params = $this->_getAllParams();
	$this->view->constituency = $params['constituency'];
	$finds = new Finds();
	$finds = $finds->getFindsConstituency($params['constituency']);
	$this->cs = $this->_helper->contextSwitch();
	$kml = array('kml');
	if(!in_array($this->cs->getCurrentContext(),$kml )) {
	$paginator = Zend_Paginator::factory($finds);
	$paginator->setItemCountPerPage(30) 
	          ->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
    $paginator->setCurrentPageNumber((int)$params['page']); 
	}
	$data = array(
	'pageNumber' => $paginator->getCurrentPageNumber(),
	'total' => number_format($paginator->getTotalItemCount(),0),
	'itemsReturned' => $paginator->getCurrentItemCount(),
	'totalPages' => number_format($paginator->getTotalItemCount()/
	$paginator->getItemCountPerPage(),0)
	);
	$this->view->paging = $data;
	$contexts = array('json');
	if(in_array($this->cs->getCurrentContext(),$contexts )) {
	$findsjson = array();
	foreach($paginator as $k => $v) {
	$findsjson[$k] = $v;
	}
	$this->view->objects = array('object' => $findsjson);
	} else {
	$this->view->finds = $paginator;
	} 
	} else {
	$this->view->finds = $finds;	
	}
	} else {
	throw new Pas_ParamException($this->_missingParameter);	
	} 
 	}
	
	public function constituenciesAction()
	{
	$this->view->headTitle('Parliamentary constituencies');
	$page = $this->_getParam('page');
	if (!($this->_cache->test('const'))) {
	$query = 'getConstituencies?date=2010-05-07';
	$output = '&output=xml';
	$key = '&key=CzhqDaDMAgkMEcjdvuGZeRtR';
	$twfy = self::TWFYURL.$query.$output.$key;
	$data = Zend_Json::fromXml($this->get($twfy),true);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('const');
	}
	$data = json_decode($data);
	$data2 = array();
	foreach ($data->twfy->match as $a) {
	if(in_array($a->name,$this->_remove)){
	unset($a->name);	
	}
	if(isset($a->name)){
	$data2[] = array('name' => $a->name);
	}
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data2));
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(40) 
    	      ->setPageRange(10); 
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('xml','json'))){
   	$data = array('pageNumber' => $paginator->getCurrentPageNumber(),
				  'total' => number_format($paginator->getTotalItemCount(),0),
				  'itemsReturned' => $paginator->getCurrentItemCount(),
				  'totalPages' => number_format($paginator->getTotalItemCount()/
   				$paginator->getItemCountPerPage(),0));
	$this->view->data = $data;
   	$constituencies = array();
   	foreach($paginator as $k => $v){
   	$constituencies[]=array();
	$constituencies[$k] = $v;
   	}
   	$this->view->constituencies = $constituencies;	
   	} else {	
	$this->view->data = $paginator;
   	}	
	}
	
	public function membersAction()
	{
	$this->view->headTitle('Members of Parliament');
	$page = $this->_getParam('page');
	if (!($this->_cache->test('members'))) {
	$query = 'getMps';
	$output = '&output=xml';
	$key = '&key=CzhqDaDMAgkMEcjdvuGZeRtR';
	$twfy = self::TWFYURL.$query.$output.$key;
	$data = Zend_Json::fromXml($this->get($twfy),true);
	$data = json_decode($data);
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('members');
	}
	$data2 = array();
	foreach ($data->twfy->match as $a){
	if(in_array($a->constituency,$this->_remove)){
	unset($a->name);
	unset($a->person_id);
	unset($a->party);
	unset($a->constituency);	
	}
	if(isset($a->name)){
	$data2[] = array('name' => $a->name,
	'person_id' => $a->person_id,
	'constituency' => $a->constituency,
	'party' => $a->party
	);
	}
	}
	$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($data2));
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber((int)$page); 
	}
	$paginator->setItemCountPerPage(40) 
    	      ->setPageRange(10); 
   	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),array('xml','json'))){
   	$data = array('pageNumber' => $paginator->getCurrentPageNumber(),
				  'total' => number_format($paginator->getTotalItemCount(),0),
				  'itemsReturned' => $paginator->getCurrentItemCount(),
				  'totalPages' => number_format($paginator->getTotalItemCount()/
   												$paginator->getItemCountPerPage(),0));
	$this->view->data = $data;
   	$members = array();
   	foreach($paginator as $k => $v){
   	$members[]=array();
	$members[$k] = $v;
   	}
   	$this->view->members = $members;	
   	} else {	
	$this->view->data = $paginator;
   	}	
	}

}
