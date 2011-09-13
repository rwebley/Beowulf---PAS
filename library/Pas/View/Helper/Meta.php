<?php
/**
 * A view helper for displaying the correct meta data
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */


 class Pas_View_Helper_Meta extends Zend_View_Helper_Abstract {

	/** Display meta data
	 * 
	 * @param string $keywords
	 * @uses Pas_View_Helper_CurUrl
	 * @uses Zend_View_Helper_PartialLoop
	 * @uses Zend_View_Helper_HeadMeta
	 * @uses Pas_View_Helper_Title
	 * 
	 */
	public function meta($keywords){
	$date = new Zend_Date();
	$date->add('72',Zend_Date::HOUR);
	$this->view->headMeta()->appendHttpEquiv('expires', $date->get(Zend_Date::RFC_1123))
		->appendHttpEquiv('Content-Type','text/html; charset=UTF-8')
		->appendHttpEquiv('Content-Language', 'en-GB')
		->appendHttpEquiv('imagetoolbar', 'no');
	$this->view->headMeta($this->view->partialLoop('partials/database/author.phtml', 
	$this->view->peoples),'dc.creator');
	$this->view->headMeta($this->view->CurUrl(),'dc.identifier');
	$this->view->headMeta($this->view->title(),'dc.title');
	$this->view->headMeta($keywords,'dc.keywords');
	$this->view->headMeta('The Portable Antiquities Scheme and the British Museum','dc.publisher');
	$this->view->headMeta(strip_tags($this->view->partialLoop('partials/database/description.phtml', 
	$this->view->finds)),'dc.description');
	$this->view->headMeta($this->view->partialLoop('partials/database/datecreated.phtml',
	$this->view->finds),'dc.date.created');
	$this->view->headMeta('Archaeological artefact found in England or Wales','dc.subject');
	}

}