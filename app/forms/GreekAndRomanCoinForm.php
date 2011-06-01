<?php

class GreekAndRomanCoinForm extends Pas_Form
{
public function __construct($options = null)
{
// Construct the select menu data
$rulers = new Rulers();
$ruler_options = $rulers->getRulersGreek();

$denominations = new Denominations();
$denomination_options = $denominations->getDenomsGreek();

$mints = new Mints();
$mint_options = $mints->getMintsGreek();


$statuses = new Statuses();
$status_options = $statuses->getCoinStatus();

$dies = new Dieaxes;
$die_options = $dies->getAxes();

$wears = new Weartypes;
$wear_options = $wears->getWears();

parent::__construct($options);

       		
$this->setName('greekcoin');
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
					
		
$denomination = new Zend_Form_Element_Select('denomination');
$denomination->setLabel('Denomination: ')
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => 'Choose a denomination','Valid denominations' => $denomination_options))
->setRegisterInArrayValidator(false)
->setDecorators($decorators);

$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
$denomination_qualifier->setLabel('Denomination qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->setValue(1)
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);


$ruler= new Zend_Form_Element_Select('ruler');
$ruler->setLabel('Ruler: ')
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => 'Choose a ruler','Valid coin issuers' => $ruler_options))
->setRegisterInArrayValidator(false)
->setDecorators($decorators);

$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
$ruler_qualifier->setLabel('Issuer qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);

$mint_ID= new Zend_Form_Element_Select('mint_id');
$mint_ID->setLabel('Issuing mint: ')
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => NULL,'Choose denomination' => $mint_options))
->setRegisterInArrayValidator(false)
->setDecorators($decorators);

$mint_qualifier = new Zend_Form_Element_Radio('mint_qualifier');
$mint_qualifier->setLabel('Mint qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);


$status = new Zend_Form_Element_Select('status');
$status->setLabel('Status: ')
->setRegisterInArrayValidator(false)
->setValue(1)
->addMultiOptions(array(NULL => NULL,'Choose coin status' => $status_options))
->setDecorators($decorators);

$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
$status_qualifier->setLabel('Status qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->setValue(1)
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);


$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
$degree_of_wear->setLabel('Degree of wear: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => NULL,'Choose coin status' => $wear_options))
->setDecorators($decorators);

$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
$obverse_inscription->setLabel('Obverse inscription: ')
->setAttrib('size',60)
->setDecorators($decorators);

$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
$reverse_inscription->setLabel('Reverse inscription: ')
->setAttrib('size',60)->setDecorators($decorators);

$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
$obverse_description->setLabel('Obverse description: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',8)
->setAttrib('cols',60)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('class','expanding');

$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
$reverse_description->setLabel('Reverse description: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',8)
->setAttrib('cols',60)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('class','expanding');

$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
$die_axis_measurement->setLabel('Die axis measurement: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => NULL,'Choose die axis' => $die_options))
->setDecorators($decorators);

$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
$die_axis_certainty->setLabel('Die axis certainty: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);



$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class','large');

$this->addElements(array(
$ruler,
$denomination,
$mint_ID,
$status,
$degree_of_wear,
$obverse_description,
$obverse_inscription,
$reverse_description,
$reverse_inscription,
$die_axis_measurement,
$die_axis_certainty,
$mint_qualifier,
$ruler_qualifier,
$denomination_qualifier,
$status_qualifier,
$submit));

$this->addDisplayGroup(array('denomination','denomination_qualifier','ruler','ruler_qualifier','mint_id','mint_qualifier','status','status_qualifier','degree_of_wear','obverse_description',
'obverse_inscription','reverse_description','reverse_inscription','die_axis_measurement','die_axis_certainty'), 'details');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->addDisplayGroup(array('submit'),'submit');
}
}