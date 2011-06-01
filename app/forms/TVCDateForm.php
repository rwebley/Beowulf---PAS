<?php

class TVCDateForm extends Pas_Form
{

public function __construct($options = null)
{

$dates = new TvcDates();
$list = $dates->dropdown();

parent::__construct($options);

$this->setAttrib('accept-charset', 'UTF-8');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$this->setName('tvcdates');


$date = new Zend_Form_Element_Select('tvcID');
$date->setLabel('Date of TVC: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must choose a TVC date')
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->addMultiOptions(array('NULL' => 'Select a TVC','Valid dates' => $list))
->setDecorators($decorators);


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$date,
$submit
));

$this->addDisplayGroup(array('tvcID'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}