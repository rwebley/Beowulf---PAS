<?php
/** Index controller for news module
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_IndexController extends Pas_Controller_ActionAdmin {

	/** Initialise the ACL and contexts
	*/ 
	public function init() {
 	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()->setAutoJsonSerialization(true);
	$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
  			 ->addContext('rss',array('suffix' => 'rss','header' => 'application/rss+xml'))
			 ->addContext('atom',array('suffix' => 'atom','header' => 'application/atom+xml'))
			 ->addActionContext('index', array('xml','json','rss','atom'))
             ->initContext();
    }

	public function indexAction() {
	$this->view->headTitle('Scheme news stories');
	$news = new News();
	$news = $news->getAllNewsArticles($this->_getAllParams());
 	$contexts = array('json');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $news->getCurrentPageNumber(),
				  'total' => number_format($news->getTotalItemCount(),0),
				  'itemsReturned' => $news->getCurrentItemCount(),
				  'totalPages' => number_format($news->getTotalItemCount()/$news->getCurrentItemCount(),0));
	$this->view->data = $data;
	$newsa = array();
	foreach($news as $r => $v){
	$newsa['story'][$r] = $v;
	}
	$this->view->allenTypes = $newsa;
	} else {
		$this->view->news = $news;
	}
	}

}