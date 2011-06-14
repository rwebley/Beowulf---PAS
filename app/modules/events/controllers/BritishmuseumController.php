<?php
/**
* Screen scraped British Museum events controller. This is because they don't have RSS!!!
*
* @category   Pas
* @package    Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License
*/
class Events_BritishmuseumController extends Zend_Controller_Action {

	/**
	* Initialise the ACL for access levels and the contexts
	*/
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->view->messages = $this->_flashMessenger->getMessages();
		$this->_helper->acl->allow('public',null);
	}

	/**
	* Return data for the index page
	*/
	public function indexAction() {
	$this->view->headTitle('Latest events at the British Museum');
	include 'simple_html_dom.php';
	ini_set('user_agent', 'Portable Antiquities Scraper/GetRSSsorted');
	$html = file_get_html('http://www.britishmuseum.org/whats_on/events_calendar/full_events_calendar.aspx');
	$results = array();
	foreach($html->find('tr') as $element){
	$item['col1'] = $element->find('td',0)->plaintext;
	$item['col2'] = $element->find('td',1)->plaintext;
	$item['col3'] = $element->find('td',2)->plaintext;
	$item['col4'] = $element->find('td',3)->plaintext;
	$item['col5'] = $element->find('td',4)->plaintext;
	$item['href'] = $element->find('a',-1);
	$results[] = $item;
	}
	$this->view->data = $results;
	}

}