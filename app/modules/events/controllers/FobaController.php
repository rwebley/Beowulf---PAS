<?php

/**
* Festival of British Archaeology Controller
*
* @category   Pas
* @package    Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Events_FobaController extends Pas_Controller_ActionAdmin {

	public function init() {
		$this->_helper->_acl->allow(NULL);
    }

	public function indexAction()	{
	$events = new Events();
	$this->view->events = $events->getEventByType(12,2011);
	}
}
