<?php
/**
* Statistical events Controller
*
* @category   Pas
* @package    Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Events_StatisticsController extends Pas_Controller_ActionAdmin {

	/**
	* Initialise the ACL for access levels, context switch, messages
	*/
    public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->initView();
	$this->view->messages = $this->_flashMessenger->getMessages();
		
	$this->_helper->acl->allow('public',null);
	$contexts = array('xml','rss','json','atom','ics','rdf','xcs');
	$contextsindex = array('xml','rss','json','atom');

	$contextSwitch = $this->_helper->contextSwitch();
	$contextSwitch->setAutoDisableLayout(true)
		->addContext('csv',array('suffix' => 'csv'))
		->addContext('kml',array('suffix' => 'kml'))
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('georss',array('suffix' => 'georss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addContext('ics',array('suffix' => 'ics'))
		->addContext('rdf',array('suffix' => 'rdf'))
		->addContext('xcs',array('suffix' => 'xcs'))
		->addActionContext('index', $contextsindex)
		->addActionContext('upcoming', $contextsindex)
		->addActionContext('event',$contexts)
		->initContext();
    }

    /**
	* The index action
	* @todo move the headtitle to view
	*/
	public function indexAction() {
	$this->view->headTitle('Overall statistics for our events');
	$events = new Events();
	$this->view->stats = $events->getStatistics();
	}		
		
}