<?php
/** Controller for displaying Early Medieval coin index page
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedievalCoins_IndexController extends Pas_Controller_ActionAdmin {
	
	/** Initialise the ACL and contexts
	*/
	public function init() {
	$this->_helper->_acl->allow(null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->contextSwitch()
		->setAutoDisableLayout(true)
		->addContext('csv',array('suffix' => 'csv'))
		->addContext('kml',array('suffix' => 'kml'))
		->addContext('rss',array('suffix' => 'rss'))
		->addContext('atom',array('suffix' => 'atom'))
		->addActionContext('index', array('xml','json','rss','atom'))
		->addActionContext('tribe', array('xml','json','rss','atom'))
		->initContext();
    }

	/** Set up index page
	*/ 
    public  function indexAction() {
		$content = new Content();
		$this->view->content =  $content->getFrontContent('earlymedievalcoins');
		$images = new Slides();
		$this->view->images = $images->getExamplesCoinsPeriod('Early Medieval',4);
    }
}