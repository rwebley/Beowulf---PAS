<?php
/**
* Screen scraped British Museum events controller. 
* 
* This has been implemented because they don't have RSS that works properly or feeds
*
* @category		Pas
* @package		Pas_Controller
* @subpackage	ActionAdmin
* @copyright	Copyright (c) 2011 DEJ PETT
* @license		GNU General Public License
* @uses			Simple_html_dom
* @author		Daniel Pett
* @version		1
* @since		23 Sept. 2011
*/
class Events_BritishmuseumController extends Zend_Controller_Action {

	/** Initialise the ACL for access levels and the contexts
	*/
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
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