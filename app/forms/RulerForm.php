<?php


class RulerForm extends Pas_Form
{

public function __construct($options = null)
{
$periods = new Periods();
$period_options = $periods->getCoinsPeriod();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
$this->setName('ruler');
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
			

$issuer = new Zend_Form_Element_Text('issuer');
$issuer->setLabel('Ruler or issuer name: ')
->setRequired(true)
->addErrorMessage('Please enter a name for this issuer or ruler.')
->setDecorators($decorators)
->setAttrib('size',70);

$date1 = new Zend_Form_Element_Text('date1');
$date1->setLabel('Date issued from: ')
->setRequired(true)
->setDecorators($decorators)
->addErrorMessage('You must enter a date for the start of their issue.');


$date2 = new Zend_Form_Element_Text('date2');
$date2->setLabel('Date issued to: ')
->setRequired(true)
->setDecorators($decorators)
->addErrorMessage('You must enter a date for the end of their issue.');


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->SetLabel('Is this ruler or issuer currently valid: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('NotEmpty')
->setDecorators($decorators);


$period = new Zend_Form_Element_Select('period');
$period->setLabel('Broad period attributed to: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL,'Choose reason' => $period_options))
->addValidator('inArray', false, array(array_keys($period_options)))
->setDecorators($decorators)
->addErrorMessage('You must enter a period for this ruler/issuer');



//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$issuer,
$date1,
$date2,
$period,
$valid,
$submit));

$this->addDisplayGroup(array('issuer','date1','date2','period','valid','submit'), 'details');
$this->details->addDecorators(array(
    'FormElements',
    array('HtmlTag', array('tag' => 'ul'))
));
$this->details->setLegend('Issuer or ruler details: ');
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');


      

}
}