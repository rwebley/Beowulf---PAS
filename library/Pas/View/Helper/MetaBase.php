<?php 
/**
 * A view helper to display the meta data for a page
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_MetaBase 
	extends Zend_View_Helper_Abstract {

 	/** View helper to produce metadata for the head section
 	 * @access public
 	 * @param $description
 	 * @param $subject
 	 * @param $keywords array 
 	 */
	public function metabase($description,$subject = 'archaeology', $keywords) {
	$date = new Zend_Date();
	$date->add('72',Zend_Date::HOUR);
	$this->view->headMeta()
		->appendHttpEquiv('expires',$date->get(Zend_Date::RFC_1123))
		->appendHttpEquiv('Content-Type','text/html; charset=UTF-8')
		->appendHttpEquiv('Content-Language', 'en-GB')
		->appendHttpEquiv('imagetoolbar', 'no')
		->headMeta($this->view->title(),'dc.title')
		->headMeta($this->view->CurUrl(),'dc.identifier og:url')
		->headMeta($keywords,'keywords')
		->headMeta('The Portable Antiquities Scheme and the British Museum','dc.publisher')
		->headMeta($this->view->escape($description),'description')
		->headMeta()->setProperty('dc.subject',$this->view->escape($subject))
		->headMeta()->setProperty('og:site_name','Portable Antiquities')
		->headMeta()->setProperty('dc.rights','Creative Commons NC-BY-SA');
	}

}