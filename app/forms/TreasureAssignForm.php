<?php

class TreasureAssignForm extends Pas_Form
{

public function __construct($options = null)
{
$curators = new Peoples();
$assigned = $curators->getCurators();

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

$this->setName('actionsForTreasure');


$curatorID = new Zend_Form_Element_Select('curatorID');
$curatorID->setLabel('Curator assigned: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,50))
->addValidator('NotEmpty')
->addValidator('inArray', false, array(array_keys($assigned)))
->addMultiOptions($assigned)
->setDecorators($decorators);


$chaseDate = new ZendX_JQuery_Form_Element_DatePicker('chaseDate');
$chaseDate->setLabel('Chase date assigned: ')
->setRequired(true)
->addFilter('StripTags')
->setJQueryParam('dateFormat', 'yy-mm-dd')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter a chase date')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->removeDecorator('DtDdWrapper');

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$curatorID,
$chaseDate,
$submit
));

$this->addDisplayGroup(array('curatorID','chaseDate'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}