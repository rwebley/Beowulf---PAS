<?php

class WorkflowStageForm extends Zend_Form
{

protected $_config;
protected $_auth;
protected $noaccess  = array('public');
protected $restricted = array('member','research','hero');
protected $recorders = array('flos');
protected $higherLevel = array('admin','fa','treasure');
protected $_missingGroup = 'User is not assigned to a group';

public function init(){
	$this->_config = Zend_Registry::get('config');
	$this->_auth = Zend_Registry::get('auth');
	
}

	public function getRole(){
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	public function getUserID()	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	}
	
	public function getIdentityForForms(){
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else {
	$id = '3';
	return $id;
	}
	}
	
	public function checkAccessbyUserID($createdBy)	{
	if($createdBy == $this->getUserID()) {
	return TRUE;
	} else {
	return FALSE;
	}
	}
		
	public function getOldFindID()
	public function checkAccessbyInstitution($oldfindID){
	$find = explode('-', $oldfindID);
	$id = $find['0'];	
	$inst = $this->getInst();
	if((in_array($this->getRole(),$this->recorders) && ($id == 'PUBLIC'))) {
	return TRUE;
	} else if($id == $inst) {
	return TRUE;
	}
	}	
	
	public function getInst(){
	if($this->_auth->hasIdentity())	{
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	if(is_null($inst)){
	throw new Exception($this->_missingGroup);	
	}
	return $inst;
	} else {
	return FALSE;
	}	
	}

public function __construct($options = null)
{

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');

$this->clearDecorators();
$decorators = array(
	
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'div')),
        );	  

	   
$this->setName('workflow');
$id = new Zend_Form_Element_Hidden('id');
$id->removeDecorator('label');

$wfstage = new Zend_Form_Element_Radio('wfstage');
$wfstage->setRequired(false)
->addMultiOptions(array('1' => 'Quarantine','2' => 'Review','4' => 'Validation','3' => 'Published'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);;

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton');
$submit->clearDecorators();
        $submit->addDecorators(array(
            array('ViewHelper'),    // element's view helper
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit')),
        ));
$this->setLegend('Workflow status');
$this->addDecorator('FormElements')
	 ->addDecorator('Form')
	 ->addDecorator('Fieldset');


$this->addElements(array($id,$wfstage,$submit));
    

}
}