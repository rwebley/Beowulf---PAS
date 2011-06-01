<?php

class HoardForm extends Pas_Form
{

public function __construct($options = null)
{
$periods = new Periods();
$period_options = $periods->getPeriodFrom();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
$this->setName('hoard');
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$term = new Zend_Form_Element_Text('term');
$term->setLabel('Hoard title: ')
->setRequired(true)
->addFilter('StripTags')
->setDecorators($decorators)
->addErrorMessage('You must enter a title for this hoard');


$termdesc = new Pas_Form_Element_RTE('termdesc');
$termdesc->setLabel('Description of hoard: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttrib('rows',10)
->setAttrib('cols',60)
->addFilter('HtmlBody')
->setAttrib('Height',400)
->addFilter('EmptyParagraph')
->addFilter('StringTrim')
->addFilter('WordChars')
->addErrorMessage('You must enter a description for this hoard');

$period = new Zend_Form_Element_Select('period');
$period->setLabel('Broad period attributed to: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL,'Choose reason' => $period_options))
->addValidator('inArray', false, array(array_keys($period_options)))
->setDecorators($decorators)
->addErrorMessage('You must choose a period');


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$term,
$termdesc,
$period,
$submit));

$this->addDisplayGroup(array('term','termdesc','period'), 'details');
$this->addDisplayGroup(array('submit'), 'submit');

$this->setLegend('Hoards: ');
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');


$this->addDisplayGroup(array('submit'), 'submit');
      

}
}