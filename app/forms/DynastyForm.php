<?php

class DynastyForm extends Pas_Form
{
public function __construct($options = null)
{
parent::__construct($options);

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
      
$this->setName('dynasticDetails');

$dynasty = new Zend_Form_Element_Text('dynasty');
$dynasty->setLabel('Dynastic name: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a name for this dynasty!')
->setDecorators($decorators);


$date_from = new Zend_Form_Element_Text('date_from');
$date_from->setLabel('Issued coins from: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('Int')
->addErrorMessage('You must enter a date for the start of reign')
->setDecorators($decorators);

$date_to = new Zend_Form_Element_Text('date_to');
$date_to->setLabel('Issued coins until: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('Int')
->addErrorMessage('You must enter a date for the end of reign')
->setDecorators($decorators);

$description = new Pas_Form_Element_TinyMce('description');
$description->setLabel('Description: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttribs(array('cols' => 70, 'rows' => 20))
->addErrorMessage('You must enter a description')
->addFilter('HtmlBody')
->addDecorator('Errors',array('placement' => 'append','class'=>'error','tag' => 'li'));


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Is this dynasty valid?')
->setDecorators($decorators);

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$dynasty, 
$date_from,
$date_to,
$description,
$valid,
$submit));
$this->removeDecorator('HtmlTag');
$this->addDisplayGroup(array('dynasty','date_from','date_to','description','valid','submit'), 'details');
$this->details->addDecorators(array(
    'FormElements',
    array('HtmlTag', array('tag' => 'ul'))
));


$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

  
  

       

}
}