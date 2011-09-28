<?php 
class Pas_View_Helper_ChangesFindSpot extends Zend_View_Helper_Abstract
{
public function buildHtml($a)
	{
	$html = '';
	$html .= '<li><a href="';
	$html .= $this->view->url(array('module' => 'database', 'controller' => 'ajax', 'action' => 'fsaudit','id' => $a['editID']),NULL,true);
	$html .= '" rel="facebox" title="View all changes on this date">';
	$html .= $this->view->timeagoinwords($a['created']);
	$html .= '</a> ';
	$html .= $a['fullname'];
	$html .= ' edited this record.</li>';
	echo $html;
	
	}
public function ChangesFindSpot($id) {
	$audit = new FindSpotsAudit();
	$auditdata = $audit->getChanges($id);
	if($auditdata) {
	echo '<h5>Find spot data audit</h5>';
	echo '<ul id="related">';
	foreach($auditdata as $a) {
	$this->buildHtml($a);
	}
	echo '</ul>';
	}

}

}