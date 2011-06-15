<?php

class My_Decorator_SimpleInput extends Zend_Form_Decorator_Abstract
{
    protected $_format = '<label for="%s">%s</label><input id="%s" name="%s" type="text" value="%s"/>';

    public function render($content)
    {
        $element = $this->getElement();
        $name    = htmlentities($element->getFullyQualifiedName());
        $label   = htmlentities($element->getLabel());
        $id      = htmlentities($element->getId());
        $value   = htmlentities($element->getValue());
        $markup  = sprintf($this->_format, $id, $label, $id, $name, $value);
        return $markup;
    }
}
class DatePickerForm extends Pas_Form
{
public function __construct($options = null) {
	$decorator = new My_Decorator_SimpleInput();
	parent::__construct($options);
	       
	$this->setDecorators(array(
	            'FormElements',
	            'Fieldset',
	            'Form'
	        ));
	$this->setName('datepicker');


$datefrom = new Zend_Form_Element_Text('datefrom');
$datefrom->setLabel('Date from: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('date')
->addErrorMessage('Boo!');
$datefrom->addDecorators(
array($decorator));

$dateto = new Zend_Form_Element_Text('dateto');
$dateto->setLabel('Date to: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty');
$dateto->addDecorators(array($decorator));

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton');
$submit->setAttrib('class','datepick');
$submit->removeDecorator('DtDdWrapper');
$submit->removeDecorator('Label');
$this->addElements(array($datefrom,$dateto,$submit));

$this->setLegend('Choose your own dates: ');
$this->addDisplayGroup(array('submit'), 'submit');

}
}