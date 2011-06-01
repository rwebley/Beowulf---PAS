<?php
class DiscoMethodsForm extends Pas_Form
{
public function __construct($options = null)
{


parent::__construct($options);

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
      
$this->setName('discoverymethods');


$method = new Zend_Form_Element_Text('method');
$method->setLabel('Discovery method term: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('size',50)
->addValidator('NotEmpty')
->addErrorMessage('You must enter a valid term')
->setDecorators($decorators);

$termdesc = new Zend_Form_Element_Textarea('termdesc');
$termdesc->setLabel('Description of method: ')
->setRequired(true)
->setAttrib('rows',10)
->setAttrib('cols',80)
->addFilter('StringTrim')
->addFilter('HtmlBody')
->addErrorMessage('You must enter a description for this term');

$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Is this term valid?: ')
->setRequired(true)
->addFilter('StripTags')
->addErrorMessage('You must set a status for this term')->setDecorators($decorators);;


//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$method, 
$termdesc,
$valid,
$submit));

$this->addDisplayGroup(array('method','termdesc','valid'), 'details');
$this->details->setLegend('Discovery methods');
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}