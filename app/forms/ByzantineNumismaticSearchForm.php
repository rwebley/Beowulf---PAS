<?php

class ByzantineNumismaticSearchForm extends Pas_Form
{

public function __construct($options = null)
{
$institutions = new Institutions();
$inst_options = $institutions->getInsts();
//Get data to form select menu for primary and secondary material
$primaries = new Materials();
$primary_options = $primaries->getPrimaries();
//Get data to form select menu for periods
//Get Rally data
$rallies = new Rallies();
$rally_options = $rallies->getRallies();
//Get Hoard data
$hoards = new Hoards();
$hoard_options = $hoards->getHoards();

$counties = new Counties();
$county_options = $counties->getCountyName2();

$rulers = new Rulers();
$ruler_options = $rulers->getRulersByzantine();

$denominations = new Denominations();
$denomination_options = $denominations->getDenomsByzantine();
$mints = new Mints();
$mint_options = $mints->getMintsByzantine();
$axis = new Dieaxes();
$axis_options = $axis->getAxes();
$reece = new Reeces();
$reece_options = $reece->getReeces();


$regions = new Regions();
$region_options = $regions->getRegionName();

parent::__construct($options);



$this->setAttrib('accept-charset', 'UTF-8');
       
$this->clearDecorators();
	

$decorator =  array('SimpleInput');
$decoratorButton =  array('NormalDecButton');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
					


	$this->setName('byzantine-search');
	
	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addErrorMessage('Please enter a valid number!')
		->setDecorators($decorators)
		->setDisableTranslator(true);

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators)
		->setDisableTranslator(true);


	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addMultiOptions(array(NULL => NULL ,'Choose Worklow stage' => array('1'=> 'Quarantine','2' => 'On review',
		 '3' => 'Awaiting validation', '4' => 'Published')))
		->setDecorators($decorators)
		->setDisableTranslator(true);

	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setUncheckedValue(NULL)
		->setDecorators($decorators)
		->setDisableTranslator(true);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addMultiOptions(array(NULL => NULL,'Choose rally name' => $rally_options))
		->setDecorators($decorators)
		->setDisableTranslator(true);

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setUncheckedValue(NULL)
		->setDecorators($decorators);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose rally name' => $hoard_options))
		->setDecorators($decorators);



	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidators(array('NotEmpty'))
		->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
		->setDecorators($decorators);

	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
		->addMultiOptions(array(NULL => 'Choose district after county'))
		->setRegisterInArrayValidator(false)
		->setDecorators($decorators)
		->disabled =true;

	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose parish after county'))
		->setDecorators($decorators)
		->disabled = true;

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a region for a wide result',
		'Choose region' => $region_options))
		->setDecorators($decorators);

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidators(array('NotEmpty','ValidGridRef'))
		->setDecorators($decorators);

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidators(array('NotEmpty','ValidGridRef'))
		->setDecorators($decorators);
	###
	##Numismatic data
	###
	//Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose denomination type' => $denomination_options))
		->setDecorators($decorators);

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose primary ruler' => $ruler_options))
		->setDecorators($decorators);
	
	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose denomination type' => $mint_options))
		->setDecorators($decorators);

	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->setAttrib('size',60)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);

	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);

	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);

	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);

	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose measurement' => $axis_options))
		->setDecorators($decorators);

	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose institution' => $inst_options))
	->setDecorators($decorators); 	
	
	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin');
	$objecttype->removeDecorator('HtmlTag')
		->addFilters(array('StripTags','StringTrim'))
		->removeDecorator('DtDdWrapper')
		->removeDecorator('label');

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Byzantine')
		->addFilters(array('StripTags','StringTrim','StringToUpper'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('label');

	//	Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setAttrib('class', 'large');

	$this->addElements(array(
	$old_findID, $description, $workflow,
	$rally, $rallyID, $hoard,
	$hoardID, $county, $regionID,
	$district, $parish, $fourFigure,
	$gridref, $denomination, $ruler,
	$mint, $axis, $obverseinsc,
	$obversedesc, $reverseinsc, $reversedesc,
	$objecttype, $broadperiod, $institution, 
	$submit));
	
	$this->addDisplayGroup(array(
	'denomination', 'ruler', 'mint',
	'moneyer', 'axis', 'obverseLegend',
	'obverseDescription','reverseLegend','reverseDescription'), 
	'numismatics');
	
	$this->addDisplayGroup(array(
	'old_findID', 'description', 'rally',
	'rallyID', 'hoard', 'hID',
	'workflow'), 'details');
	$this->addDisplayGroup(array(
	'county','regionID','district',
	'parish','gridref','fourFigure',
	'institution'), 'spatial');
	$this->numismatics->setLegend('Numismatic details');
	$this->numismatics->removeDecorator('DtDdWrapper');
	$this->numismatics->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	
	
	$this->details->setLegend('Artefact details');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	
	$this->spatial->setLegend('Spatial details');
	$this->spatial->removeDecorator('DtDdWrapper');
	$this->spatial->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));

	$this->addDisplayGroup(array('submit'), 'submit');
	$this->addDecorator('FormElements')
		 ->addDecorator('Form')
	     ->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'div'));
	$this->removeDecorator('DtDdWrapper');			 

}
}