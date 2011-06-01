<?php


class IronAgeNumismaticSearchForm extends Pas_Form
{

protected function getRole()
{
$auth = Zend_Auth::getInstance();
if($auth->hasIdentity())
{
$user = $auth->getIdentity();
$role = $user->role;
return $role;
}
else
{
$role = 'public';
return $role;
}
}



protected $higherlevel = array('admin','flos','fa','heros'); 
protected $restricted = array('public','member','research');


public function __construct($options = null)
{

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

$denominations = new Denominations();
$denom_options = $denominations->getOptionsIronAge();

$rulers = new Rulers();
$ruler_options = $rulers->getIronAgeRulers();
$mints = new Mints();
$mint_options = $mints->getIronAgeMints();
$axis = new Dieaxes();
$axis_options = $axis->getAxes();
$geog = new Geography();
$geog_options = $geog->getIronAgeGeographyDD();


$regions = new Regions();
$region_options = $regions->getRegionName();

$tribes = new Tribes();
$tribe_options = $tribes->getTribes();


parent::__construct($options);



       
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );


$this->setName('Advanced');
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
->setDecorators($decorators)
->setDisableTranslator(true);
if(in_array($this->getRole(),$this->higherlevel)) {
$workflow->addMultiOptions(array(NULL => 'Choose a workflow stage' ,'Available workflow stages' => array('1'=> 'Quarantine','2' => 'On review', '4' => 'Awaiting validation', '3' => 'Published')));
}
if(in_array($this->getRole(),$this->restricted)) {
$workflow->addMultiOptions(array(NULL => 'Choose a workflow stage' ,'Available workflow stages' => array('4' => 'Awaiting validation', '3' => 'Published')));
}


//Rally details
$rally = new Zend_Form_Element_Checkbox('rally');
$rally->setLabel('Rally find: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->setUncheckedValue(NULL)
->setDecorators($decorators)
->setDisableTranslator(true)
->setDisableTranslator(true);

$geographyID = new Zend_Form_Element_Select('geographyID');
$geographyID->setLabel('Geographic area: ')
->setRegisterInArrayValidator(false)
->addValidators(array('NotEmpty'))
->setDecorators($decorators)
->addMultiOptions(array(NULL => 'Choose a geography' ,'Available geographies' => $geog_options));


$rallyID =  new Zend_Form_Element_Select('rallyID');
$rallyID->setLabel('Found at this rally: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => 'Choose a rally','Available rallies' => $rally_options))
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
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => 'Choose a hoard','Available hoards' => $hoard_options))
->setDecorators($decorators);



$county = new Zend_Form_Element_Select('county');
$county->setLabel('County: ')
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => 'Choose a county','Available counties' => $county_options))->setDecorators($decorators);

$district = new Zend_Form_Element_Select('district');
$district->setLabel('District: ')
->addMultiOptions(array(NULL => 'Choose district after county'))
->setRegisterInArrayValidator(false)->setDecorators($decorators)
->disabled =true;

$parish = new Zend_Form_Element_Select('parish');
$parish->setLabel('Parish: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => 'Choose parish after county'))
->setDecorators($decorators)
->disabled=true;

$regionID = new Zend_Form_Element_Select('regionID');
$regionID->setLabel('European region: ')
->setRegisterInArrayValidator(false)->setDecorators($decorators)
->addMultiOptions(array(NULL => 'Choose a region for a wide result','Available regions' => $region_options));

$gridref = new Zend_Form_Element_Text('gridref');
$gridref->setLabel('Grid reference: ')
->setDecorators($decorators)
->addValidators(array('NotEmpty'));

$fourFigure = new Zend_Form_Element_Text('fourfigure');
$fourFigure->setLabel('Four figure grid reference: ')
->setDecorators($decorators)
->addValidators(array('NotEmpty'));
###
##Numismatic data
###
//Denomination
$denomination = new Zend_Form_Element_Select('denomination');
$denomination->setLabel('Denomination: ')
->setRegisterInArrayValidator(false)
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => 'Choose denomination type','Available denominations' => $denom_options))
->setDecorators($decorators);

//Primary ruler
$ruler = new Zend_Form_Element_Select('ruler');
$ruler->setLabel('Ruler / issuer: ')
->setRegisterInArrayValidator(false)
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => 'Choose primary ruler' , 'Available rulers' => $ruler_options))
->setDecorators($decorators);
//Mint
$mint = new Zend_Form_Element_Select('mint');
$mint->setLabel('Issuing mint: ')
->setRegisterInArrayValidator(false)
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => 'Choose issuing mint','Available mints' => $mint_options))
->setDecorators($decorators);
//Secondary ruler

$ruler2 = new Zend_Form_Element_Select('ruler2');
$ruler2->setLabel('Secondary ruler / issuer: ')
->setRegisterInArrayValidator(false)
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => 'Choose secondary ruler','Available rulers' => $ruler_options))
->setDecorators($decorators);


//Obverse inscription
$obverseinsc = new Zend_Form_Element_Text('obinsc');
$obverseinsc->setLabel('Obverse inscription contains: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid term')
->setDecorators($decorators);

//Obverse description
$obversedesc = new Zend_Form_Element_Text('obdesc');
$obversedesc->setLabel('Obverse description contains: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid term')
->setDecorators($decorators);

//reverse inscription
$reverseinsc = new Zend_Form_Element_Text('revinsc');
$reverseinsc->setLabel('Reverse inscription contains: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid term')
->setDecorators($decorators);

//reverse description
$reversedesc = new Zend_Form_Element_Text('revdesc');
$reversedesc->setLabel('Reverse description contains: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid term')
->setDecorators($decorators);

//Die axis
$axis = new Zend_Form_Element_Select('axis');
$axis->setLabel('Die axis measurement: ')
->setRegisterInArrayValidator(false)
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => 'Choose measurement','Available die axes' => $axis_options))
->setDecorators($decorators);

//Tribe
$tribe = new Zend_Form_Element_Select('tribe');
$tribe->setLabel('Iron Age tribe: ')
->setRegisterInArrayValidator(false)
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => 'Choose a tribe','Available tribes' => $tribe_options))
->setDecorators($decorators);

$objecttype = new Zend_Form_Element_Hidden('objecttype');
$objecttype->setValue('coin')
->setAttrib('class', 'none')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag')
->removeDecorator('Label');


$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
$broadperiod->setValue('Iron Age')
->setAttrib('class', 'none')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag')
->removeDecorator('Label');

$mack_type = new Zend_Form_Element_Text('mack');
$mack_type->setLabel('Mack Type: ')
->setDecorators($decorators);

$bmc_type = new Zend_Form_Element_Text('bmc');
$bmc_type->setLabel('British Museum catalogue number: ')
->setDecorators($decorators);


$allen_type = new Zend_Form_Element_Text('allen');
$allen_type->setLabel('Allen Type: ')
->setDecorators($decorators);


$va_type = new Zend_Form_Element_Text('va');
$va_type->setLabel('Van Arsdell Number: ')
->setDecorators($decorators);


$rudd_type = new Zend_Form_Element_Text('rudd');
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
//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->setLabel('Submit your search...');
 $config = Zend_Registry::get('config');
		$_formsalt = $config->form->salt;
 $hash = new Zend_Form_Element_Hash('csrf');
		$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		//->addErrorMessage('Possible CSRF attack, your form tokens do not match.')
		->setTimeout(4800);
		$this->addElement($hash);


$this->addElements(array(

$old_findID,$description,$workflow,$rally,$rallyID,$hoard,$hoardID,$county,$regionID,$district,$parish,$fourFigure,$gridref,$denomination,$ruler,$mint,$axis,$obverseinsc,$obversedesc,$reverseinsc,$reversedesc,$ruler2,$tribe,$objecttype,$broadperiod,
$geographyID,$bmc_type,
$mack_type,
$allen_type,
$va_type,
$rudd_type,
$numChiab,
$context,
$depositionDate,
$phase_date_1,
$phase_date_2,
$submit));
$this->addDisplayGroup(array('denomination','geographyID','ruler','ruler2','tribe','mint','axis','obinsc','obdesc','revinsc','revdesc','bmc','va','allen','rudd','mack','numChiab','context','phase_date_1','phase_date_2','depositionDate'), 'numismatics')
->removeDecorator('HtmlTag');
$this->numismatics->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->numismatics->removeDecorator('DtDdWrapper');
$this->numismatics->setLegend('Numismatic details: ');



$this->addDisplayGroup(array('old_findID','description','rally','rallyID','hoard','hID','workflow'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->setLegend('Object details: ');


$this->addDisplayGroup(array('county','regionID','district','parish','gridref','fourfigure'), 'spatial')->removeDecorator('HtmlTag');
$this->spatial->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->spatial->removeDecorator('DtDdWrapper');

$this->spatial->setLegend('Spatial details: ');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');


$this->setMethod('get');

}
}