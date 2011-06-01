<?php

class CoinClassForm extends Pas_Form
{

public function __construct($options = null)
{
$periods = new Periods();
$period_actives = $periods->getCoinsPeriod();

parent::__construct($options);
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
       
$this->setName('coinsclass');


$referenceName = new Zend_Form_Element_Text('referenceName');
$referenceName->setLabel('Reference volume title: ')
->setRequired(true)
->addFilter('StripTags')
->setDecorators($decorators)
->setAttrib('size',60);


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->SetLabel('Is this volume currently valid: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('NotEmpty')
->setDecorators($decorators);


$period = new Zend_Form_Element_Select('period');
$period->setLabel('Period: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('inArray', false, array(array_keys($period_actives)))
->addMultiOptions(array(NULL=> NULL,'Choose period:' => $period_actives))
->addErrorMessage('You must enter a period for this mint')
->setDecorators($decorators);


//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$referenceName,
$valid,
$period,
$submit));

$this->addDisplayGroup(array('referenceName','period','valid'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));

$this->details->setLegend('Mint details: ');
$this->details->removeDecorator('DtDdWrapper');
$this->addDisplayGroup(array('submit'),'submit');

      

}
}