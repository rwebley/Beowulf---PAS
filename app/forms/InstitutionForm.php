<?php

class InstitutionForm extends Pas_Form
{

public function __construct($options = null)
{

parent::__construct($options);
       
	  
$this->setName('institution');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
			

$institution = new Zend_Form_Element_Text('institution');
$institution->setLabel('Recording institution title: ')
->setRequired(true)
->setAttrib('size',60)
->addErrorMessage('Choose title for the role.')
->setDecorators($decorators);

$description = new Zend_Form_Element_Textarea('description');
$description->setLabel('Role description: ')
->setRequired(true)
->setAttrib('rows',10)
->setAttrib('cols',70)
->addDecorator('HtmlTag',array('tag' => 'li'));



//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$institution,
$description,

$submit));

$this->addDisplayGroup(array('institution','description'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');


$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');
      

}
}