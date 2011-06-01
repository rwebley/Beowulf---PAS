<?php
class WhatWhereWhenForm extends Pas_Form
{
public function __construct($options = null)
{
$periods = new Periods();
$period_options = $periods->getPeriodFromWords();
$counties = new Counties();
$counties_options = $counties->getCountyname2();

parent::__construct($options);

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$this->setName('whatwherewhen')
->removeDecorator('HtmlTag');

$old_findID = new Zend_Form_Element_Text('old_findID');
$old_findID->setLabel('Find number: ')
->setRequired(false)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size', 20)
->addErrorMessage('Please enter a valid string!')
->setDecorators($decorators);

	
//Objecttype - autocomplete from thesaurus
$objecttype = new Zend_Form_Element_Text('objecttype');
$objecttype->setLabel('What: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size', 20)
->addErrorMessage('Please enter a valid string!')
->setDecorators($decorators);



$broadperiod = new Zend_Form_Element_Select('broadperiod');
$broadperiod->setLabel('When: ')
->setRequired(false)
->addFilters(array('StripTags','StringTrim'))
//->addValidator('inArray', false, array($period_options))
->addMultiOptions(array(NULL => NULL,'Choose period from' => $period_options))
->setDecorators($decorators);


$county = new Zend_Form_Element_Select('county');
$county->setLabel('Where: ')
->setRequired(false)
->addFilters(array('StripTags','StringTrim'))
->addMultiOptions(array(NULL => NULL,'Choose county' => $counties_options))
->setDecorators($decorators);




//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setLabel('Search!')
->setAttribs(array('class'=> 'large'))
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array($old_findID,$objecttype,$county,$broadperiod,$submit));


$this->addDisplayGroup(array('old_findID','objecttype','broadperiod','county','submit'), 'Search');
$this->Search->removeDecorator('DtDdWrapper');
$this->Search->removeDecorator('HtmlTag');
$this->Search->addDecorators(array(array('HtmlTag', array('tag' => 'ul','id' => 'www'))
))->setLegend('What/Where/When search')
->addDecorator('FieldSet');
/* $this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');
 */

}
}