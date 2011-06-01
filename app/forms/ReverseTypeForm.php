<?php

class ReverseTypeForm extends Zend_Form
{

public function __construct($options = null)
{
$reeces = new Reeces();
$reeces_options = $reeces->getRevTypes();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
       
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
			


$this->setName('reversetype');

$type = new Zend_Form_Element_Text('type');
$type->setLabel('Reverse type inscription: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addErrorMessage('Please enter an inscription.')->setDecorators($decorators)->setAttrib('size',70);



$translation = new Zend_Form_Element_Text('translation');
$translation->setLabel('Translation: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addErrorMessage('You must enter a translation.')->setDecorators($decorators)->setAttrib('size',70);


$description = new Zend_Form_Element_Text('description');
$description->setLabel('Description: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')

->addErrorMessage('You must enter a translation.')->setDecorators($decorators)->setAttrib('size',70);

$gendate = new Zend_Form_Element_Text('gendate');
$gendate->setLabel('General date for reverse type: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addErrorMessage('You must enter a general date for this reverse type.')->setDecorators($decorators)->setAttrib('size',30);




$reeceID = new Zend_Form_Element_Select('reeceID');
$reeceID->setLabel('Reece period: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL,'Choose reason' => $reeces_options))
->addValidator('inArray', false, array(array_keys($reeces_options)))->setDecorators($decorators);


$common = new Zend_Form_Element_Radio('common');
$common->setLabel('Is this reverse type commonly found: ')
->setRequired(false)
->addMultiOptions(array('1' => 'Yes','2' => 'No'))
->setValue(1)
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->setDecorators($decorators);


//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$type,
$gendate,
$description,
$translation,
$reeceID,
$common,
$submit));

$this->addDisplayGroup(array('type','translation','description','gendate','reeceID','common','submit'), 'details');
$this->details->setLegend('Reverse type details: ');
$this->details->addDecorators(array(
    'FormElements',
    array('HtmlTag', array('tag' => 'ul'))
));
$this->details->setLegend('Issuer or ruler details: ');
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
      

}
}