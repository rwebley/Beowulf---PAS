<?php

class TVCForm extends Pas_Form
{

public function __construct($options = null)
{


ZendX_JQuery::enableForm($this);

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


$date = new ZendX_JQuery_Form_Element_DatePicker('date');
$date->setLabel('Date of TVC: ')
->setRequired(true)
->addFilter('StripTags')
->setJQueryParam('dateFormat', 'yy-mm-dd')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter a chase date')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->removeDecorator('DtDdWrapper');

$location = new Zend_Form_Element_Text('location');
$location->setLabel('Location of meeting: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter a location')
->setDecorators($decorators);


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$date,
$location,
$submit
));

$this->addDisplayGroup(array('date','location'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}