<?php

class AddDenomToRulerForm extends Pas_Form
{
public function __construct($options = null)
{

$decorators = array(
	
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'div')),
			
        );	  


parent::__construct($options);

$this->setName('MintToRuler');

$denomination_id = new Zend_Form_Element_Select('denomination_id');
$denomination_id->setLabel('Denomination: ')
->setRequired(true)
->addFilters(array('StripTags','StringTrim','StringToLower'))
->setAttribs(array('class'=> 'textInput'))
->setDecorators($decorators);

$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
$ruler_id ->removeDecorator('label')
              ->removeDecorator('HtmlTag');

$period_id = new Zend_Form_Element_Hidden('period_id');
$period_id ->removeDecorator('label')
              ->removeDecorator('HtmlTag');



//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setLabel('Add a denomination for this ruler')
->setAttribs(array('class'=> 'large'));

$this->addElements(array($denomination_id,$ruler_id,$period_id,$submit))
->setLegend('Add an active denomination');
$this->addDisplayGroup(array('denomination_id'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}