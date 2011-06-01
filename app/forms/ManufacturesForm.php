<?php
class ManufacturesForm extends Pas_Form
{
public function __construct($options = null)
{

parent::__construct($options);
$this->setName('Manufactures');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );


$term = new Zend_Form_Element_Text('term');
$term->setLabel('Method of manufacture term: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid title for this method!')
->setDecorators($decorators);

$termdesc = new Zend_Form_Element_Textarea('termdesc');
$termdesc->setLabel('Description of manufacture method: ')
->setRequired(false)
->addFilter('StringTrim')
->setAttrib('rows',10)
->setAttrib('cols',80)
->addFilter('HtmlBody');

$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Is this term valid?: ')
->setRequired(true)
->setDecorators($decorators);



$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$term,
$termdesc,
$valid,
$submit));

$this->addDisplayGroup(array('term','termdesc','valid'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->details->setLegend('Method of manufacture details: ');
$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');
}
}