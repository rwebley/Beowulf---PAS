<?php
class PreservationsForm extends Pas_Form
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

$this->setName('preservations');

$term = new Zend_Form_Element_Text('term');
$term->setLabel('Title for preservation state: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid title for the state!')
->setDecorators($decorators);

$termdesc = new Pas_Form_Element_TinyMce('termdesc');
$termdesc->setLabel('Description of preservation state: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttrib('rows',15)
->setAttrib('cols',60)
->setAttrib('class','expanding')
->addFilter('HtmlBody')
->addErrorMessage('You must enter a description for this term.');


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Is this term valid?: ')
->setRequired(true)
->addFilter('StripTags')
->setDecorators($decorators);



$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag')
->removeDecorator('Label');

$this->addElements(array(
$term,
$termdesc,
$valid,
$submit));

$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
$this->details->setLegend('Preservation state details: ');
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');
  
  

       

}
}