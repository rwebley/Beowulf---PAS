<?php

class AddTypeToRulerForm extends Pas_Form
{
public function __construct($options = null)
{
parent::__construct($options);
$decorators = array(
	
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'div')),
			
        );	  

$this->setAttrib('accept-charset', 'UTF-8');
$this->setName('MintToRuler');

$type = new Zend_Form_Element_Select('type');
$type->setLabel('Medieval coin type: ')
->setRequired(true)
->addFilters(array('StripTags','StringTrim','StringToLower'))
->setAttribs(array('class'=> 'textInput'))
->setDecorators($decorators);

$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
$ruler_id->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag')->removeDecorator('Label');


//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttribs(array('class'=> 'large'))->setAttribs(array('class'=> 'large'));


$this->addElements(array($type,$ruler_id,$submit))
->setLegend('Add a new type')
->setMethod('post')
->addDecorators(array('FieldSet',
'form',array('HtmlTag', array('tag' => 'div'))
));

}
}