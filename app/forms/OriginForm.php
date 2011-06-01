<?php
class OriginForm extends Pas_Form
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
$this->setName('origingridref');


$term = new Zend_Form_Element_Text('term');
$term->setLabel('Grid reference origin term: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',60)
->addErrorMessage('Please enter a valid grid reference origin term!')
->setDecorators($decorators);

$termdesc = new Zend_Form_Element_Textarea('termdesc');
$termdesc->setLabel('Description of term: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttrib('rows',10)
->setAttrib('cols',80)
->addFilter('HtmlBody')
->addErrorMessage('You must enter a descriptive term or David Williams will eat you.')
->addDecorator('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li'));

$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Is this term valid?: ')
->setRequired(true)
->setDecorators($decorators)
->addErrorMessage('You must set the status of this term');



$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
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
$this->details->setLegend('Ascribed culture');
$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');
}
}