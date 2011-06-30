<?php
/** Form for manipulating Iron Age data via search interface
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class IronAgeNumismaticSearchForm extends Pas_Form {

	protected function getRole() {
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()) {
	$user = $auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}
	
	protected $_higherlevel = array('admin','flos','fa','heros', 'treasure', 'research'); 
	
	protected $_restricted = array('public','member');

	public function __construct($options = null) {

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
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a valid number!')
	->setDecorators($decorators);

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);

	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->setDecorators($decorators);
	
	if(in_array($this->getRole(),$this->_higherlevel)) {
	$workflow->addMultiOptions(array(NULL => 'Choose a workflow stage', 
	'Available workflow stages' => array('1'=> 'Quarantine','2' => 'On review', 
	'4' => 'Awaiting validation', '3' => 'Published')));
	}
	if(in_array($this->getRole(),$this->_restricted)) {
	$workflow->addMultiOptions(array(NULL => 'Choose a workflow stage', 
	'Available workflow stages' => array('4' => 'Awaiting validation', '3' => 'Published')));
	}

	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->setUncheckedValue(NULL)
	->setDecorators($decorators);

	$geographyID = new Zend_Form_Element_Select('geographyID');
	$geographyID->setLabel('Geographic area: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a geography',
	'Available geographies' => $geog_options))
	->addValidator('inArray', false, array(array_keys($geog_options)))
	->addValidator('Int');

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a rally', 
	'Available rallies' => $rally_options))
	->setDecorators($decorators)
	->addValidator('inArray', false, array(array_keys($rally_options)))
	->addValidator('Int');

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->setUncheckedValue(NULL)
	->setDecorators($decorators);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a hoard',
	'Available hoards' => $hoard_options))
	->setDecorators($decorators)
	->addValidator('inArray', false, array(array_keys($hoard_options)))
	->addValidator('Int');

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a county', 
	'Available counties' => $county_options))
	->addValidator('inArray', false, array(array_keys($county_options)))	
	->setDecorators($decorators);

	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose district after county'))
	->setDecorators($decorators)
	->disabled = true;

	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose parish after county'))
	->setDecorators($decorators)
	->disabled = true;

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
	->setDecorators($decorators)
	->addMultiOptions(array(NULL => 'Choose a region for a wide result', 
	'Available regions' => $region_options))
	->addValidator('Int');

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
	->setDecorators($decorators)
	->addValidator('ValidGridRef')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum');

	$fourFigure = new Zend_Form_Element_Text('fourfigure');
	$fourFigure->setLabel('Four figure grid reference: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('ValidGridRef')
	->addValidator('Alnum');

	###
	##Numismatic data
	###
	//	Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
	->setRegisterInArrayValidator(false)
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose denomination type', 
	'Available denominations' => $denom_options))
	->addValidator('inArray', false, array(array_keys($denom_options)))	
	->setDecorators($decorators);

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose primary ruler' , 
	'Available rulers' => $ruler_options))
	->addValidator('inArray', false, array(array_keys($denom_options)))	
	->setDecorators($decorators);
	
	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose issuing mint',
	'Available mints' => $mint_options))
	->addValidator('inArray', false, array(array_keys($mint_options)))	
	->setDecorators($decorators);
	
	//Secondary ruler
	$ruler2 = new Zend_Form_Element_Select('ruler2');
	$ruler2->setLabel('Secondary ruler / issuer: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose secondary ruler',
	'Available rulers' => $ruler_options))
	->addValidator('inArray', false, array(array_keys($ruler_options)))
	->setDecorators($decorators);


	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obinsc');
	$obverseinsc->setLabel('Obverse inscription contains: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);

	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obdesc');
	$obversedesc->setLabel('Obverse description contains: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);

	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('revinsc');
	$reverseinsc->setLabel('Reverse inscription contains: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);

	//reverse description
	$reversedesc = new Zend_Form_Element_Text('revdesc');
	$reversedesc->setLabel('Reverse description contains: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);

	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose measurement',
	'Available die axes' => $axis_options))
	->addValidator('inArray', false, array(array_keys($axis_options)))
	->addErrorMessage('That option is not a valid choice')
	->addValidator('Int')
	->setDecorators($decorators);

	//Tribe
	$tribe = new Zend_Form_Element_Select('tribe');
	$tribe->setLabel('Iron Age tribe: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a tribe',
	'Available tribes' => $tribe_options))
	->addValidator('inArray', false, array(array_keys($tribe_options)))
	->addErrorMessage('That option is not a valid choice')
	->addValidator('Int')
	->setDecorators($decorators);

	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('COIN')
	->setAttrib('class', 'none')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alpha', false, array('allowWhiteSpace' => true))
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('Label');

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Iron Age')
	->addValidator('Alnum',false, array('allowWhiteSpace' => true))
	->setAttrib('class', 'none')
	->addFilters(array('StripTags', 'StringTrim'))
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('Label');

	$mack_type = new Zend_Form_Element_Text('mack');
	$mack_type->setLabel('Mack Type: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$bmc_type = new Zend_Form_Element_Text('bmc');
	$bmc_type->setLabel('British Museum catalogue number: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$allen_type = new Zend_Form_Element_Text('allen');
	$allen_type->setLabel('Allen Type: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$va_type = new Zend_Form_Element_Text('va');
	$va_type->setLabel('Van Arsdell Number: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$rudd_type = new Zend_Form_Element_Text('rudd');
	$rudd_type->setLabel('Ancient British Coinage number: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$phase_date_1 = new Zend_Form_Element_Text('phase_date_1');
	$phase_date_1->setLabel('Phase date 1: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$phase_date_2 = new Zend_Form_Element_Text('phase_date_2');
	$phase_date_2->setLabel('Phase date 2: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$context = new Zend_Form_Element_Text('context');
	$context->setLabel('Context of coins: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$depositionDate = new Zend_Form_Element_Text('depositionDate');
	$depositionDate->setLabel('Date of deposition: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$numChiab = new Zend_Form_Element_Text('numChiab');
	$numChiab->setLabel('Coin hoards of Iron Age Britain number: ')
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum',false, array('allowWhiteSpace' => true));
	
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
	->setTimeout(4800);
	$this->addElement($hash);

	$this->addElements(array(
	$old_findID, $description, $workflow,
	$rally, $rallyID, $hoard,
	$hoardID, $county, $regionID,
	$district, $parish, $fourFigure,
	$gridref, $denomination, $ruler,
	$mint, $axis, $obverseinsc,
	$obversedesc, $reverseinsc, $reversedesc,
	$ruler2, $tribe, $objecttype,
	$broadperiod, $geographyID, $bmc_type,
	$mack_type, $allen_type, $va_type,
	$rudd_type, $numChiab, $context,
	$depositionDate, $phase_date_1, $phase_date_2,
	$submit));
	
	$this->addDisplayGroup(array(
	'denomination', 'geographyID','ruler',
	'ruler2', 'tribe', 'mint',
	'axis', 'obinsc', 'obdesc',
	'revinsc', 'revdesc', 'bmc',
	'va', 'allen', 'rudd',
	'mack', 'numChiab', 'context',
	'phase_date_1', 'phase_date_2',
	'depositionDate'),
	'numismatics')
	->removeDecorator('HtmlTag');
	
	$this->numismatics->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->numismatics->removeDecorator('DtDdWrapper');
	$this->numismatics->setLegend('Numismatic details: ');

	$this->addDisplayGroup(array(
	'old_findID','description','rally',
	'rallyID','hoard','hID',
	'workflow'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Object details: ');
	
	$this->addDisplayGroup(array(
	'county', 'regionID', 'district',
	'parish', 'gridref', 'fourfigure'), 
	'spatial')
	->removeDecorator('HtmlTag');
	
	$this->spatial->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->spatial->removeDecorator('DtDdWrapper');
	$this->spatial->setLegend('Spatial details: ');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	$this->setMethod('get');
	}
}