<?php
/**
 * A front controller plugin for rendering the correct styles.
 * @category   Pas
 * @package    Pas_Controller
 * @subpackage Pas_Controller_Plugin
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @author 	   Daniel Pett
 * @todo	   Change the headlink to a database call for urls to append
 */
class Pas_Controller_Plugin_StyleAndAlternate
	extends Zend_Controller_Plugin_Abstract {
	
	public function postDispatch(Zend_Controller_Request_Abstract $request) {
	$view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;
	$view->headMeta('Built using the awesome Zend Framework (but customised by Daniel Pett): '
	. Zend_Version::VERSION,'generator');
	$view->baseUrl = $request->getBaseUrl();
	$view->jQuery()->addJavascriptFile($view->baseUrl() . '/js/JQuery/jquery.menu.js', $type='text/javascript');
	$view->jQuery()->addJavascriptFile($view->baseUrl() . '/js/JQuery/corner.js', $type='text/javascript');
	$module = strtolower($request->getModuleName());
	if($module == 'default') {
	$view->headLink()->appendStylesheet($view->baseUrl() . '/css/home.css', $type='screen');
	} else {
	$view->headLink()->appendStylesheet($view->baseUrl() . '/css/default.css', $type='screen');
	}
	$view->headLink()->appendStylesheet($view->baseUrl().'/css/print.css', $type='print')
		->appendStylesheet($view->baseUrl().'/css/style.css', $type='screen');
	$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
	$view->headLink()->appendAlternate($view->baseUrl().'/database/artefacts/index/format/atom/', 
		'application/rss+xml', 'Latest recorded finds feed')
		->appendAlternate($view->baseUrl() . '/news/format/atom', 
		'application/rss+xml', 'Latest Scheme news feed')
		->appendAlternate($view->baseUrl() . '/getinvolved/vacancies/format/atom', 'application/atom+xml', 
		'Latest Scheme vacancies atom Feed')
		->appendAlternate($view->baseUrl() . '/research/projects/index/format/atom', 
		'application/atom+xml', 'Research projects based on Scheme data')
		->appendAlternate('http://finds.org.uk/blogs/centralunit/feed/', 'application/atom+xml', 
		'Central unit blog posts')
		->appendAlternate('http://api.flickr.com/services/feeds/photos_public.gne?id=10257668@N04&lang=en-us&format=atom', 
		'application/atom+xml', 'Our flickr images feed')
		->appendAlternate('http://www.finds.org.uk/events/upcoming/index/format/atom', 'application/atom+xml',
		'Scheme and external events as they are posted')
		->appendAlternate('http://www.finds.org.uk/database/search/results/note/1/format/atom', 'application/atom+xml',
		'Amazing finds recorded on the database');
		   
		/* $view->headLink(array('rel' => 'search',
                                  'href' => $view->baseUrl().'/OpenSearchDatabase.xml',
								  'type' =>  'application/opensearchdescription+xml',
								  'title' => 'Portable Antiquities Database search',
                                  'APPEND'));  */
	}

}
