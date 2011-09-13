<?php 
/**
 * A view helper for displaying toolbox of links
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_RecordEditDeleteLinks
 */


class Pas_View_helper_Toolbox extends Zend_View_Helper_Abstract {

	protected $_allowed = array('fa','flos','admin');

	/** Display the toolbox, crappy code
	 * 
	 * @param integer $id
	 * @param string $oldfindID
	 * @param string $createdBy
	 */
	public function toolbox($id, $oldfindID, $createdBy) {
	$this->view->inlineScript()->captureStart();
	echo '$(document).ready(function() {
	$(\'.print\').click(function() {
	window.print();
	return false;
	});
	});';
	$this->view->inlineScript()->captureEnd();
	echo '<div id="toolBox"><p>';
	echo '<a href="'
	. $this->view->url(array('module' => 'database','controller' => 'ajax','action' => 'webcite','id' => $id),null,true)
	. '" rel="facebox" title="Get citation information">Cite record</a> | <a href="'
	. $this->view->url(array('module' => 'database','controller' => 'ajax', 'action' => 'embed', 'id' =>  $id),null,true)
	. '" rel="facebox" title="Get code to embed this record in your webpage">Embed record</a> ';
	echo $this->view->RecordEditDeleteLinks($id,$oldfindID,$createdBy);
	echo ' | <a href="#print" class="print">Print</a> | ';
	echo $this->view->Href(array('module' => 'database','controller'=>'artefacts', 'action'=>'add', 'checkAcl'=>true,
	'acl'=>'Zend_Acl',  'content'=>'Add record','attribs' => array('title' => 'Add new object','accesskey' => 'a')));
	echo ' | <a href="'.$this->view->url(array('module' => 'database','controller' => 'artefacts','action' => 'record','id' => $id,'format' => 'pdf'),null,true)
	. '" title="Report format">Report</a>';
	echo'</p></div>';
	}

}
