<?php
/** Controller for all getting research projects out of system
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Research_ProjectsController extends Pas_Controller_Action_Admin {
	
	protected $higherLevel = array('admin','flos'); 
	
	protected $researchLevel = array('member','heros','research');
	
	protected $restricted = array('public');
	
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
 		$this->_helper->_acl->allow(null);
		$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
  			 ->addContext('rss',array('suffix' => 'rss'))
			 ->addContext('atom',array('suffix' => 'atom'))
			 ->addActionContext('project',array('xml','json'))
			 ->addActionContext('topic',array('xml','json'))
			 ->addActionContext('suggested',array('xml','json','rss','atom'))
			 ->addActionContext('index', array('xml','json','rss','atom'))
             ->initContext();
	}
	
	/** Set up index pages
	*/ 
	public function indexAction() {
	$projects = new ResearchProjects();
	$project = $projects->getAllProjects($this->_getAllParams());
	$contexts = array('json','xml');
	if(in_array($this->_helper->contextSwitch()->getCurrentContext(),$contexts)) {
	$data = array('pageNumber' => $project->getCurrentPageNumber(),
				  'total' => number_format($project->getTotalItemCount(),0),
				  'itemsReturned' => $project->getCurrentItemCount(),
				  'totalPages' => number_format($project->getTotalItemCount()/$project->getItemCountPerPage(),0));
	$this->view->data = $data;
	$projectsa = array();
	foreach($project as $r => $v){
	$projectsa[$r] = $v;
	}
	$this->view->projects = $projectsa;
	} else {
		$this->view->projects = $project;
	}
	}
	
	/** get an individual project
	*/ 
	public function projectAction(){
	if($this->_getParam('id',false)){
		$projects = new ResearchProjects();
		$this->view->projects = $projects->getProjectDetails($this->_getParam('id'));
	} else {
		throw new Exception($this->_missingParameter);
	}
    }
    
    /** List of suggested topics
	*/ 
	public function suggestedAction() {
	$contextSwitch = $this->_helper->contextSwitch();
	if(in_array($this->_helper->contextSwitch->getCurrentContext(),array('xml','json','rss','atom'))) {
		$suggested = new Suggested();
	$suggested = $suggested->getAll($this->_getAllParams(),0);
	$data = array('pageNumber' => $suggested->getCurrentPageNumber(),
				  'total' => number_format($suggested->getTotalItemCount(),0),
				  'itemsReturned' => $suggested->getCurrentItemCount(),
				  'totalPages' => number_format($suggested->getTotalItemCount()/$suggested->getItemCountPerPage(),0));
	$this->view->data = $data;
	$suggesteda = array();
	foreach($suggested as $r => $v){
	$suggesteda[$r] = $v;
	}
	$this->view->suggested = $suggesteda;
	} else {
		$suggested = new Suggested();
		$undergrad = $suggested->getTopicByType(1);
		$masters = $suggested->getTopicByType(2);
		$phd = $suggested->getTopicByType(3);
		$this->view->undergrad = $undergrad;
		$this->view->masters = $masters;
		$this->view->phd = $phd;
	
	}
	} 
	
	/** Get an individual topic
	*/ 
	public function topicAction() {
	if($this->_getParam('id',false)){
		$topic = new Suggested();
		$this->view->topic = $topic->getTopic($this->_getParam('id'));
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
}