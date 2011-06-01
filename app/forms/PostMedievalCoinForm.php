<?php

class PostMedievalCoinForm extends Pas_Form
{
public function __construct($options = null)
{
// Construct the select menu data

$cats = new CategoriesCoins();
$cat_options = $cats->getPeriodPostMed();

$denominations = new Denominations();
$denomination_options = $denominations->getOptionsMedieval();

$statuses = new Statuses();
$status_options = $statuses->getCoinStatus();


$dies = new Dieaxes;
$die_options = $dies->getAxes();

$wears = new Weartypes;
$wear_options = $wears->getWears();

$rulers = new Rulers();
$ro = $rulers->getPostMedievalRulers();

$mints = new Mints();
$mo = $mints->getPostMedievalMints();


parent::__construct($options);

       
$this->setName('postmedievalcoin');
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
		

$denomination = new Zend_Form_Element_Select('denomination');
$denomination->setLabel('Denomination: ')
->setRegisterInArrayValidator(false)
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => NULL,'Choose denomination' => $denomination_options))
->setDecorators($decorators);

$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
$denomination_qualifier->setLabel('Denomination qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);

$categoryID = new Zend_Form_Element_Select('categoryID');
$categoryID->setLabel('Category of coin: ')
->setRegisterInArrayValidator(false)
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => NULL,'Choose category' => $cat_options))
->setDecorators($decorators);

$ruler_id= new Zend_Form_Element_Select('ruler');
$ruler_id->setLabel('Ruler: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => NULL,'Choose ruler' => $ro))
->setDecorators($decorators);

$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
$ruler_qualifier->setLabel('Issuer qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);


$mint_id= new Zend_Form_Element_Select('mint_id');
$mint_id->setLabel('Issuing mint: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => NULL,'Choose mint' => $mo));


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
->setDecorators($decorators)
->addFilter('StripTags')
->addFilter('EmptyParagraph')
->setAttrib('size',60);

$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
$reverse_inscription->setLabel('Reverse inscription: ')
->setDecorators($decorators)
->addFilter('StripTags')
->addFilter('EmptyParagraph')
->setAttrib('size',60);

$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
$obverse_description->setLabel('Obverse description: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',5)
->setAttrib('cols',40)
->addFilter('StripTags')
->addFilter('EmptyParagraph')
->addFilter('StringTrim')
->setAttrib('class','expanding');

$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
$reverse_description->setLabel('Reverse description: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',5)
->setAttrib('cols',40)
->addFilter('StripTags')
->addFilter('EmptyParagraph')
->addFilter('StringTrim')
->setAttrib('class','expanding');

$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
$die_axis_measurement->setLabel('Die axis measurement: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => NULL,'Choose coin status' => $die_options))
->setDecorators($decorators);

$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
$die_axis_certainty->setLabel('Die axis certainty: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);

$typeID = new Zend_Form_Element_Select('typeID');
$typeID->setLabel('Coin type: ')
->setRegisterInArrayValidator(false)
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);

$rev_mm = new Zend_Form_Element_Textarea('reverse_mintmark');
$rev_mm->setLabel('Reverse mintmark: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',3)
->setAttrib('cols',80)
->addFilter('StripTags')
->addFilter('EmptyParagraph')
->addFilter('StringTrim')
->setAttrib('class','expanding');

$initial = new Zend_Form_Element_Textarea('initial_mark');
$initial->setLabel('Initial mark: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',3)
->setAttrib('cols',80)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('class','expanding');


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class','large')
		->removeDecorator('label')
        ->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

$this->addElements(array(
$ruler_id,
$ruler_qualifier,
$denomination,
$denomination_qualifier,
$mint_id,
$typeID,
$status,
$categoryID,
$status_qualifier,
$degree_of_wear,
$obverse_description,
$obverse_inscription,
$reverse_description,
$reverse_inscription,
$die_axis_measurement,
$die_axis_certainty,
$submit,$rev_mm,$initial));

$this->addDisplayGroup(array('categoryID','ruler','typeID','ruler_qualifier','denomination','denomination_qualifier','mint_id','status','status_qualifier','degree_of_wear','obverse_description','obverse_inscription','reverse_description','reverse_inscription','reverse_mintmark','initial_mark','die_axis_measurement','die_axis_certainty','submit'), 'details');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('HtmlTag');
$this->details->removeDecorator('DtDdWrapper');


}
}