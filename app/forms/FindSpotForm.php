<?php


class FindSpotForm extends Pas_Form
{
public function __construct($options = null)
{
// Construct the select menu data
$counties = new Counties();
$county_options = $counties->getCountyName2();

$origins = new MapOrigins();
$origin_options = $origins->getValidOrigins();

$landusevalues = new Landuses();
$landuse_options = $landusevalues->getUsesValid();

$landusecodes = new Landuses();
$landcodes_options = $landusecodes->getCodesValid();

parent::__construct($options);

$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
        $this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');


$decorator =  array('SimpleInput');
$decoratorButton =  array('NormalDecButton');
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$this->setName('findspots');

$old_findspotid = new Zend_Form_Element_Hidden('old_findspotid');
$old_findspotid->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->removeDecorator('Label');

$highsensitivity = new Zend_Form_Element_Checkbox('highsensitivity');
$highsensitivity->setLabel('Highly sensitive findspot: ')
->setRequired(false)
->setCheckedValue('1')
->setUncheckedValue(NULL)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);

$returnID = new Zend_Form_Element_Hidden('returnID');
$returnID->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->removeDecorator('Label');


$findsecuid = new Zend_Form_Element_Hidden('findsecuid');
$findsecuid->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->removeDecorator('Label');

// Object specifics
$county = new Zend_Form_Element_Select('county');
$county->setLabel('County: ')
->setRequired(true)
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => 'Choose county','Valid counties' => $county_options))
->setDecorators($decorators);

$district = new Zend_Form_Element_Select('district');
$district->setLabel('District: ')
->setRequired(false)
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => 'Choose district after county'));




$parish = new Zend_Form_Element_Select('parish');
$parish->setLabel('Parish: ')
->setRequired(false)
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => 'Choose parish after district'));


$regionID = new Zend_Form_Element_Select('regionID');
$regionID->setLabel('European region: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => 'Choose region after county'));


$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
if($action != 'editfindspot'){
/* $regionID->disabled =true;
$district->disabled =true;
$parish->disabled =true;
 */
}


$gridref = new Zend_Form_Element_Text('gridref');
$gridref->setLabel('Grid reference: ')
->addValidators(array('NotEmpty','ValidGridRef'))
->setDecorators($decorators)
->addFilter('StringTrim')
->addFilter('StripTags');

$gridrefsrc = new Zend_Form_Element_Select('gridrefsrc');
$gridrefsrc->setLabel('Grid reference source: ')
->addMultioptions(array(NULL => NULL,'Choose source' => $origin_options))
->setRegisterInArrayValidator(false)
->setDecorators($decorators);


$gridrefcert = new Zend_Form_Element_Radio('gridrefcert');
$gridrefcert->setLabel('Grid reference certainty: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->setValue(1)
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);

	if($action == 'edit'){
	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
	->addValidators(array('NotEmpty'))
	->setDecorators($decorators)
	->disabled =true;
	
	$easting = new Zend_Form_Element_Text('easting');
	$easting->setLabel('Easting: ')
	->addValidators(array('NotEmpty'))
	->setDecorators($decorators)
	->disabled =true;
	
	$northing = new Zend_Form_Element_Text('northing');
	$northing->setLabel('Northing: ')
	->addValidators(array('NotEmpty'))
	->setDecorators($decorators)
	->disabled =true;
	
	$map10k = new Zend_Form_Element_Text('map10k');
	$map10k->setLabel('10 km map: ')
	->addValidators(array('NotEmpty'))
	->setDecorators($decorators)
	->disabled =true;
	
	$map25k = new Zend_Form_Element_Text('map25k');
	$map25k->setLabel('25 km map: ')
	->addValidators(array('NotEmpty'))
	->setDecorators($decorators)
	->disabled =true;
	
	$declong = new Zend_Form_Element_Text('declong');
	$declong->setLabel('Longitude: ')
	->addValidators(array('NotEmpty'))
	->setDecorators($decorators)
	->disabled =true;
	
	
	$declat = new Zend_Form_Element_Text('declat');
	$declat->setLabel('Latitude: ')
	->addValidators(array('NotEmpty'))
	->setDecorators($decorators)
	->disabled =true;
	
	$woeid = new Zend_Form_Element_Text('woeid');
	$woeid->setLabel('Where on Earth ID: ')
	->addValidators(array('NotEmpty'))
	->setDecorators($decorators)
	->disabled =true;
	
	$elevation = new Zend_Form_Element_Text('elevation');
	$elevation->setLabel('Elevation: ')
	->addValidators(array('NotEmpty'))
	->setDecorators($decorators)
	->disabled =true;
	
	}

$depthdiscovery = new Zend_Form_Element_Select('depthdiscovery');
$depthdiscovery->setLabel('Depth of discovery')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addFilter('StringTrim')
->addFilter('StripTags')
->addMultiOptions(array(NULL => NULL,'Approximate depth' => array('10' => '0 - 10cm','20' => '10 - 20cm','30' => '20 - 30cm','40' => '30 - 40cm', '50' => '40 - 50cm','60' => 'Are you digging to find Oz?')));


$soiltype = new Zend_Form_Element_Select('soiltype');
$soiltype->setLabel('Type of soil around findspot: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addFilter('StringTrim')
->addFilter('StripTags')
->addMultiOptions(array(NULL => NULL));


$landusevalue = new Zend_Form_Element_Select('landusevalue');
$landusevalue->setLabel('Landuse type: ')
->addValidators(array('NotEmpty'))
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addFilter('StringTrim')
->addFilter('StripTags')
->addMultiOptions(array(NULL => 'Choose landuse' ,'Valid landuses' => $landuse_options));

$landusecode = new Zend_Form_Element_Select('landusecode');
$landusecode->setLabel('Specific landuse: ')
->setRegisterInArrayValidator(false)
->addValidators(array('NotEmpty'))
->setDecorators($decorators)
->addFilter('StringTrim')
->addFilter('StripTags')
->addMultiOptions(array(NULL => 'Specific landuse will be enabled after type'));


$address = new Zend_Form_Element_Textarea('address');
$address->setLabel('Address: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',5)
->setAttrib('cols',40)//->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('class','expanding')
->setAttrib('class','privatedata');

$postcode = new Zend_Form_Element_Text('postcode');
$postcode->setLabel('Postcode: ')
->addValidators(array('NotEmpty'))
->setDecorators($decorators)
->addFilter('StringTrim')
->addFilter('StripTags');

$knownas = new Zend_Form_Element_Text('knownas');
$knownas->setLabel('Findspot to be known as: ')
->addValidators(array('NotEmpty'))
->setDecorators($decorators)
->setAttrib('class','privatedata');

$landownername = new Zend_Form_Element_Text('landownername');
$landownername->setLabel('Landowner: ')
->addValidators(array('NotEmpty'))
->setDecorators($decorators)
->setAttrib('class','privatedata');

$landowner = new Zend_Form_Element_Hidden('landowner');
$landowner->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->removeDecorator('Label');


$description = new Pas_Form_Element_RTE('description');
$description->setLabel('Findspot description: ')
->addValidators(array('NotEmpty'))
->setAttrib('Height',300)
->setAttrib('ToolbarSet','Finds')
->addFilter('StringTrim')
->addFilter('WordChars')
->addFilter('BasicHtml')
->setAttrib('class','expanding')
->setAttrib('class','privatedata');

$comments = new Pas_Form_Element_RTE('comments');
$comments->setLabel('Findspot comments: ')
->setAttrib('Height',300)
->setAttrib('ToolbarSet','Finds')
->addFilter('StringTrim')
->addFilter('WordChars')
->addFilter('BasicHtml')
->setAttrib('class','expanding')
->setAttrib('class','privatedata');


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class','large');

if($action == 'edit')
{
$this->addElements(array(
$county,
$district,
$parish,
$knownas,
$description,
$comments,
$regionID,
$gridref,
$fourFigure,
$easting,
$northing,
$map10k,
$map25k,
$declong,
$declat,
$woeid,
$elevation,
$highsensitivity,
$findsecuid,
$gridrefsrc,
$gridrefcert,
$returnID,
$depthdiscovery,
//$soiltype,
$address,
$postcode,
$landusevalue,
$landusecode,
$landownername,
$landowner,
$submit));
}
else
{
$this->addElements(array(
$county,
$district,
$parish,
$knownas,
$highsensitivity,
$description,
$comments,
$regionID,
$gridref,
$findsecuid,
$gridrefsrc,
$gridrefcert,
$returnID,
$old_findspotid,
$depthdiscovery,
//$soiltype,
$address,
$postcode,
$landusevalue,
$landusecode,
$landownername,
$landowner,

$submit));
}


$this->addDisplayGroup(array(
	'county',
	'regionID',
	'district',
	'parish',
	'knownas',
	'highsensitivity',
	'address',
	'postcode',
	'landownername',
	'landowner'
	),
	'details');
$this->details->setLegend('Findspot information');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
if($action == 'edit')
{

$this->addDisplayGroup(array(
	'gridref',
	'gridrefcert',
	'gridrefsrc',
	'fourFigure',
	'easting',
	'northing',
	'map25k',
	'map10k',
	'declat',
	'declong',
	'woeid',
	'elevation',
	'landusevalue',
	'landusecode',
	'depthdiscovery',
	//'soiltype'
	)
	,
	'spatial');
	} else {
	$this->addDisplayGroup(array(
	'gridref',
	'gridrefcert',
	'gridrefsrc',
	'landusevalue',
	'landusecode',
	'depthdiscovery',
	'soiltype')
	,
	'spatial');

	
	}
$this->spatial->setLegend('Spatial information');
$this->spatial->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->spatial->removeDecorator('DtDdWrapper');

$this->addDisplayGroup(array('description','comments'),'commentary');
$this->commentary->setLegend('Findspot comments');
$this->commentary->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->commentary->removeDecorator('DtDdWrapper');

$this->addDisplayGroup(array('submit'), 'submit');

}
}