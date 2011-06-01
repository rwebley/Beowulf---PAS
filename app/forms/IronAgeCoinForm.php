<?php

class IronAgeCoinForm extends Pas_Form
{
public function __construct($options = null)
{
// Construct the select menu data
$denominations = new Denominations();
$denomination_options = $denominations->getOptionsIronAge();

$statuses = new Statuses();
$status_options = $statuses->getCoinStatus();

$dies = new Dieaxes;
$die_options = $dies->getAxes();

$wears = new Weartypes;
$wear_options = $wears->getWears();

$rulers = new Rulers();
$ro = $rulers->getIronAgeRulers();

$mints = new Mints;
$mint_options = $mints->getIronAgeMints();

$tribes = new Tribes();
$to = $tribes->getTribes();

$atypes = new AllenTypes();
$atypelist = $atypes->getATypes();
$vatypes = new VanArsdellTypes();
$vatypelist = $vatypes->getVATypesDD();
$macktypes = new MackTypes();
$macktypelist = $macktypes->getMackTypesDD();
parent::__construct($options);

$this->setAttrib('accept-charset', 'UTF-8');
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 


$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
      
		
$this->setName('ironagecoin');
		
$denomination = new Zend_Form_Element_Select('denomination');
$denomination->setLabel('Denomination: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => NULL,'Choose denomination' => $denomination_options))
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

$geographyID = new Zend_Form_Element_Select('geographyID');
$geographyID->setLabel('Geographic area: ')
->setRegisterInArrayValidator(false)
->addValidators(array('NotEmpty'))
->setDecorators($decorators);


$geography_qualifier = new Zend_Form_Element_Radio('geography_qualifier');
$geography_qualifier->setLabel('Geographic qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);

$ruler_id= new Zend_Form_Element_Select('ruler');
$ruler_id->setLabel('Ruler: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => 'Choose primary ruler','Available rulers' => $ro));


$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
$ruler_qualifier->setLabel('Issuer qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);

$ruler2_id= new Zend_Form_Element_Select('ruler2_id');
$ruler2_id->setLabel('Secondary ruler: ')
->addFilter('StripTags')
->addFilter('StringTrim')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => NULL,'Choose issuing secondary ruler' => $ro));


$ruler2_qualifier = new Zend_Form_Element_Radio('ruler2_qualifier');
$ruler2_qualifier->setLabel('Secondary issuer qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);

$mint_id= new Zend_Form_Element_Select('mint_id');
$mint_id->setLabel('Issuing mint: ')
->setRegisterInArrayValidator(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL,'Choose issuing mint' => $mint_options))
->setDecorators($decorators);



$tribe= new Zend_Form_Element_Select('tribe');
$tribe->setLabel('Tribe: ')
->addFilter('StripTags')
->addFilter('StringTrim')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => NULL,'Choose tribe' => $to));


$tribe_qualifier = new Zend_Form_Element_Radio('tribe_qualifier');
$tribe_qualifier->setLabel('Tribe qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);

$status = new Zend_Form_Element_Select('status');
$status->setLabel('Status: ')
->addFilter('StripTags')
->addFilter('StringTrim')
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
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('size',60)
->setDecorators($decorators);


$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
$reverse_inscription->setLabel('Reverse inscription: ')
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('size',60)
->setDecorators($decorators);


$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
$obverse_description->setLabel('Obverse description: ')
->setAttrib('rows',8)
->setAttrib('cols',80)
->addFilter('StringTrim')
->setAttrib('class','expanding');

$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
$reverse_description->setLabel('Reverse description: ')
->setAttrib('rows',8)
->setAttrib('cols',80)
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


$mack_type = new Zend_Form_Element_Select('mack_type');
$mack_type->setLabel('Mack Type: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => 'Choose a Mack type','Valid types' => $macktypelist))
->setDecorators($decorators);

$bmc_type = new Zend_Form_Element_Text('bmc_type');
$bmc_type->setLabel('British Museum catalogue number: ')
->setDecorators($decorators);


$allen_type = new Zend_Form_Element_Select('allen_type');
$allen_type->setLabel('Allen Type: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => 'Choose an Allen type','Valid types' => $atypelist))
->setDecorators($decorators);


$va_type = new Zend_Form_Element_Select('va_type');
$va_type->setLabel('Van Arsdell Number: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => 'Choose Van Arsdell type','Valid types' => $vatypelist))
->setDecorators($decorators);

$cciNumber  = new Zend_Form_Element_Text('cciNumber');
$cciNumber->setLabel('Celtic Coin Index Number: ')
->setAttrib('size',12)
->setDescription('This is the coin\'s unique CCI number, not a comparison field!')
->setDecorators($decorators);

$rudd_type = new Zend_Form_Element_Text('rudd_type');
$rudd_type->setLabel('Ancient British Coinage number: ')
->setDecorators($decorators);

$phase_date_1 = new Zend_Form_Element_Text('phase_date_1');
$phase_date_1->setLabel('Phase date 1: ')
->setDecorators($decorators);

$phase_date_2 = new Zend_Form_Element_Text('phase_date_2');
$phase_date_2->setLabel('Phase date 2: ')
			->setDecorators($decorators);

$context = new Zend_Form_Element_Text('context');
$context->setLabel('Context of coins: ')
		->setDecorators($decorators);


$depositionDate = new Zend_Form_Element_Text('depositionDate');
$depositionDate->setLabel('Date of deposition: ')
				->setDecorators($decorators);

$numChiab = new Zend_Form_Element_Text('numChiab');
$numChiab->setLabel('Coin hoards of Iron Age Britain number: ')
		 ->setDecorators($decorators);

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class','large');

/* $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
if($action == 'editcoin')
{
	$rulers = new Rulers();
	$ruler_options = $rulers->getIronAgeRulers();
	$ruler_id->addMultiOptions(array(NULL => NULL,'Choose primary ruler' => $ruler_options));
	$ruler2_id->addMultiOptions(array(NULL => NULL,'Choose secondary ruler' => $ruler_options));
	
	$tribes = new Tribes();
	$tribe_options = $tribes->getTribes();
	$tribe->addMultiOptions(array(NULL => NULL,'Choose tribe' => $tribe_options));
	
	$mints = new Mints();
	$mint_options = $mints->getIronAgeMints();
	$mint_id->addMultiOptions(array(NULL => NULL,'Choose Iron Age mint' => $mint_options));
	
}
 */


$this->addElements(array(
$ruler_id,
$ruler_qualifier,
$denomination,
$denomination_qualifier,
$mint_id,
$ruler2_id,
$ruler2_qualifier,
$geographyID,
$geography_qualifier,
$status,
$status_qualifier,
$degree_of_wear,
$obverse_description,
$obverse_inscription,
$reverse_description,
$reverse_inscription,
$die_axis_measurement,
$die_axis_certainty,
$tribe,
$tribe_qualifier,
$bmc_type,
$mack_type,
$allen_type,
$va_type,
$rudd_type,
$cciNumber,
$numChiab,
$context,
$depositionDate,
$phase_date_1,
$phase_date_2,

$submit));

$this->addDisplayGroup(array('denomination','denomination_qualifier',
'geographyID','geography_qualifier','tribe','tribe_qualifier','ruler',
'ruler_qualifier','ruler2_id','ruler2_qualifier','mint_id','status',
'status_qualifier','degree_of_wear','obverse_description','obverse_inscription',
'reverse_description','reverse_inscription','die_axis_measurement','die_axis_certainty',
'bmc_type','va_type','allen_type','rudd_type','mack_type','cciNumber','numChiab','context',
'phase_date_1','phase_date_2','depositionDate'), 'details');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->addDisplayGroup(array('submit'),'submit');

}
}