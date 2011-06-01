<?php
class RulerFilterForm extends Pas_Form
{
public function __construct($options = null)
{


parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
$this->setMethod('post');  
$this->setName('filterruler');
$decorator =  array('TableDecInput');

$ruler = new Zend_Form_Element_Text('ruler');
$ruler->setLabel('Filter by name')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');



//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setLabel('Filter')
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$this->addElements(array(
$ruler, 
$submit));
  
}
}