<?php

class TreasureActionForm extends Pas_Form
{

public function __construct($options = null)
{
$actionTypes = new TreasureActionTypes();
$actionlist = $actionTypes->getList();

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


$actionDescription = new Pas_Form_Element_RTE('actionTaken');
$actionDescription->setLabel('Action summary: ')
->setRequired(true)
->setAttrib('rows',10)
->setAttrib('cols',40)
->setAttrib('Height',400)
->setAttrib('ToolbarSet','Basic')
->addFilter('StringTrim')
->addFilter('WordChars')
->addFilter('BasicHtml');

$action = new Zend_Form_Element_Select('actionID');
$action->setLabel('Type of action taken: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,2))
->addValidator('NotEmpty')
->addValidator('inArray', false, array(array_keys($actionlist)))
->addMultiOptions($actionlist)
->setDecorators($decorators);

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$action,
$actionDescription,
$submit
));

$this->addDisplayGroup(array('actionID','actionTaken',), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}