<?php

class WorkflowStageForm extends Zend_Form
{

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