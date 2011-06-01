<?php

class WorkflowForm extends Pas_Form
{

public function __construct($options = null)
{

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
$this->setName('workflow');

$workflowstage = new Zend_Form_Element_Text('workflowstage');
$workflowstage->setLabel('Work flow stage title: ')
->setRequired(true)
->setAttrib('size',60)
->addFilter('StripTags')
->setDecorators($decorators);


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Workflow stage is currently in use: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);


$termdesc = new Pas_Form_Element_TinyMce('termdesc');
$termdesc->setLabel('Description of workflow stage: ')
->setRequired(true)
->addFilter('StringTrim')
->addFilter('HtmlBody')
->setAttrib('rows',10)
->setAttrib('cols',60);



//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$workflowstage,
$valid,
$termdesc,
$submit));

$this->addDisplayGroup(array('workflowstage','termdesc','valid'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('HER details: ');
$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');


}
}