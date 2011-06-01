<?php

require_once 'Materials.php';
require_once 'Periods.php';
require_once 'Cultures.php';
require_once 'Rallies.php';
require_once 'Hoards.php';
require_once 'Counties.php';


class NumismaticSearchForm extends Pas_Form
{



public function __construct($options = null)
{

//Get data to form select menu for primary and secondary material
$primaries = new Materials();
$primary_options = $primaries->getPrimaries();
//Get data to form select menu for periods
$periods = new Periods();
$period_options = $periods->getPeriodFrom();
//Get data to form select menu for cultures
$cultures = new Cultures();
$culture_options = $cultures->getCultures();
//Get Rally data
$rallies = new Rallies();
$rally_options = $rallies->getRallies();
//Get Hoard data
$hoards = new Hoards();
$hoard_options = $hoards->getHoards();

$counties = new Counties();
$county_options = $counties->getCountyName2();


parent::__construct($options);



$this->setAttrib('accept-charset', 'UTF-8');
       
$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
		
		
$decorators = array(
	
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'li')),
			array('HtmlTag', array('tag' => 'ul')),
    		array('Label', array('tag' => 'li'))
			
        );


$this->setName('Advanced');
$old_findID = new Zend_Form_Element_Text('old_findID');
$old_findID->setLabel('Find number: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addErrorMessage('Please enter a valid number!')
->setDecorators($decorators);

$description = new Zend_Form_Element_Text('description');
$description->setLabel('Object description contains: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid term')
->setDecorators($decorators);



$broadperiod = new Zend_Form_Element_Select('broadperiod');
$broadperiod->setLabel('Broad period: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL ,'Choose period from' => $period_options))
->setDecorators($decorators);

$objdate1subperiod = new Zend_Form_Element_Select('fromsubperiod');
$objdate1subperiod->setLabel('Sub period from: ')
->addMultiOptions(array(NULL => NULL, 'Choose sub-period from' => array('1' => 'Early','2' => 'Middle','3' => 'Late')))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''));


//Period from: Assigned via dropdown
$objdate1period = new Zend_Form_Element_Select('periodfrom');
$objdate1period->setLabel('Period from: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL ,'Choose period from' => $period_options))
->setDecorators($decorators);

$objdate2subperiod = new Zend_Form_Element_Select('tosubperiod');
$objdate2subperiod->setLabel('Sub period to: ')
->addMultiOptions(array(NULL => NULL, 'Choose sub-period from' => array('1' => 'Early','2' => 'Middle','3' => 'Late')))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''));

//Period to: Assigned via dropdown
$objdate2period = new Zend_Form_Element_Select('periodto');
$objdate2period->setLabel('Period to: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL,'Choose period to' => $period_options))
->setDecorators($decorators);


$from = new Zend_Form_Element_Text('from');
$from->setLabel('Start date greater than: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('Int')
->addErrorMessage('Please enter a valid date')
->setDecorators($decorators);

$fromend = new Zend_Form_Element_Text('fromend');
$fromend->setLabel('Start date smaller than: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('Int')
->addErrorMessage('Please enter a valid date')
->setDecorators($decorators);

$to= new Zend_Form_Element_Text('to');
$to->setLabel('End date greater than: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('Int')
->addErrorMessage('Please enter a valid date')
->setDecorators($decorators);

$toend= new Zend_Form_Element_Text('toend');
$toend->setLabel('End date smaller than: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('Int')
->addErrorMessage('Please enter a valid date')
->setDecorators($decorators);


$workflow = new Zend_Form_Element_Select('workflow');
$workflow->setLabel('Workflow stage: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL ,'Choose Worklow stage' => array('1'=> 'Quarantine','2' => 'On review', '3' => 'Awaiting validation', '4' => 'Published')))
->setDecorators($decorators);

//Rally details
$rally = new Zend_Form_Element_Checkbox('rally');
$rally->setLabel('Rally find: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->setUncheckedValue(NULL)
->setDecorators($decorators);

$rallyID =  new Zend_Form_Element_Select('rallyID');
$rallyID->setLabel('Found at this rally: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL,'Choose rally name' => $rally_options))
->setDecorators($decorators);

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
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL,'Choose rally name' => $hoard_options))
->setDecorators($decorators);



$county = new Zend_Form_Element_Select('county');
$county->setLabel('County: ')
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options));

$district = new Zend_Form_Element_Select('district');
$district->setLabel('District: ')
->setRegisterInArrayValidator(false)
->disabled =true;

$parish = new Zend_Form_Element_Select('parish');
$parish->setLabel('Parish: ')
->setRegisterInArrayValidator(false)
->disabled=true;

$regionID = new Zend_Form_Element_Select('regionID');
$regionID->setLabel('European region: ')
->setRegisterInArrayValidator(false)
->disabled =true;

$gridref = new Zend_Form_Element_Text('gridref');
$gridref->setLabel('Grid reference: ')
->addValidators(array('NotEmpty'));

$fourFigure = new Zend_Form_Element_Text('fourfigure');
$fourFigure->setLabel('Four figure grid reference: ')
->addValidators(array('NotEmpty'));
###
##Numismatic data
###
//Denomination
$denomination = new Zend_Form_Element_Select('denomination');
$denomination->setLabel('Denomination: ')
->setRegisterInArrayValidator(false);
//Primary ruler

//Mint

//Reverse type

//Moneyer

//Secondary ruler

//Obverse inscription

//Obverse description

//reverse inscription

//reverse description

//Die axis

//Category

//Tribe

//Geography

//Type


//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton');

$this->addElements(array(

$old_findID,$broadperiod,$description,$from,$to,$workflow,$rally,$rallyID,$hoard,$hoardID,$fromend,$toend,$objdate1period,$objdate2period,$county,$regionID,$district,$parish,$fourFigure,$gridref,$objdate1subperiod,$objdate2subperiod,$denomination,

$submit));
$this->addDisplayGroup(array('denomination'), 'numismatics');

$this->addDisplayGroup(array('old_findID','description','rally','rallyID','hoard','hID','workflow'), 'details');
$this->addDisplayGroup(array('broadperiod','fromsubperiod','periodfrom','tosubperiod','periodto','from','fromend','to','toend'), 'Temporaldetails');
$this->addDisplayGroup(array('county','district','parish','gridref','fourfigure','regionID'), 'Spatial');

$this->setLegend('Perform an advanced search on our database: ');
$this->addDisplayGroup(array('submit'), 'submit');
$this->addDecorator('FormElements')
	 ->addDecorator('Form',array('HtmlTag', array('tag' => 'div')))
     ->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'div'))
	 ->addDecorator('FieldSet');
			 

$this->setMethod('get');
$this->setAction('../database/searchresults/'); 

}
}