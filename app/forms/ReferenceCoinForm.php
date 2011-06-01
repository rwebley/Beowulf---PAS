<?php
class ReferenceCoinForm extends Pas_Form
{
public function __construct($options = null)
{
$refs = new Coinclassifications();
$ref_list = $refs->getClass();

parent::__construct($options);
$this->setName('addcoinreference');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );




$classID = new Zend_Form_Element_Select('classID');
$classID->setLabel('Publication title: ')
->setRequired(true)
->addMultiOptions(array(NULL => 'Choose reference','Valid choices' => $ref_list))
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter a title')
->setDecorators($decorators);


$volume = new Zend_Form_Element_Text('vol_no');
$volume->setLabel('Volume number: ')
->setDecorators($decorators)
->setAttrib('size',9);



$reference = new Zend_Form_Element_Text('reference');
$reference->setLabel('Reference number: ')
->setDecorators($decorators)
->setAttrib('size', 15);



//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->setAttrib('class','large');

$this->addElements(array(
$classID, 
$volume,
$reference,
$submit));
$this->addDisplayGroup(array('classID','vol_no','reference'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Add a new reference');
$this->addDisplayGroup(array('submit'),'submit');

}
}