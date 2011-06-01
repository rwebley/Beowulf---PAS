<?php

class DieAxisForm extends Pas_Form
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
      
$this->setName('dieaxis');


$die_axis_name = new Zend_Form_Element_Text('die_axis_name');
$die_axis_name->setLabel('Die axis term: ')
->setRequired(true)
->setAttrib('size',70)
->addErrorMessage('Please enter a term.')
->setDecorators($decorators);


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Die axis term is in use: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton');
$submit->removeDecorator('DtDdWrapper');
$submit->removeDecorator('HtmlTag');

$this->addElements(array(

$die_axis_name,
$valid,
$submit));

$this->addDisplayGroup(array('die_axis_name','valid'), 'details');
$this->details->setLegend('Die axis details: ');
$this->details->removeDecorator('HtmlTag');
$this->details->removeDecorator('DtDdWrapper');
$this->addDisplayGroup(array('submit'), 'submit');
      

}
}