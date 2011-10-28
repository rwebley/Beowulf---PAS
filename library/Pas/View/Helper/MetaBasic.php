<?php 
 class Zend_View_Helper_MetaBasic extends Zend_View_Helper_Abstract
 {

private function CurUrl()
	{
	$isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
$port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
$port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
$url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port.$_SERVER["REQUEST_URI"];
return $url;
}

private function title()
    {
        $headTitle = $this->view->headTitle();
        return strip_tags($headTitle->toString());
    }

function metaBasic()
	{
$date = new Zend_Date();
$date->add('72',Zend_Date::HOUR);
$this->view->headMeta()->appendHttpEquiv('expires',$date->get(Zend_Date::RFC_1123))
					   ->appendHttpEquiv('Content-Type','text/html; charset=UTF-8')
                 	   ->appendHttpEquiv('Content-Language', 'en-GB')
					   ->appendHttpEquiv('imagetoolbar', 'no');
$this->view->headMeta('Daniel Pett','DC.Creator');
$this->view->headMeta($this->CurUrl(),'DC.Identifier');
$this->view->headMeta($this->title(),'DC.Title');
$this->view->headMeta('basic,search,what,where,when,portable antiquities','DC.Keywords');
$this->view->headMeta('The Portable Antiquities Scheme and the British Museum','DC.Publisher');
$this->view->headMeta('Search the Portable Antiquities Scheme Database using our basic what where when search interface.','DC.Description');
$this->view->headMeta('','DC.date.created');
$this->view->headMeta('Archaeology','DC.subject');
	}

}