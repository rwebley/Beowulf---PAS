<?php
/** Form for searching for early medieval coin data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EarlyMedNumismaticSearchForm extends Pas_Form {

	public function __construct($options = null) {
		
	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();
		
	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();
	
	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();
	
	$hoards = new Hoards();
	$hoard_options = $hoards->getHoards();
	
	$counties = new Counties();
	$county_options = $counties->getCountyName2();
	
	$rulers = new Rulers();
	$ruler_options = $rulers->getEarlyMedRulers();
	
	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsEarlyMedieval();
	
	$mints = new Mints();
	$mint_options = $mints->getEarlyMedievalMints();
	
	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();
	
	$reece = new Reeces();
	$reece_options = $reece->getReeces();
	
	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodEarlyMed();
	
	$regions = new Regions();
	$region_options = $regions->getRegionName();

	parent::__construct($options);


	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$this->setName('earlymedsearch');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int')
		->addErrorMessage('Please enter a valid number!')
		->setDecorators($decorators);

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);

	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL ,'Choose Worklow stage' => array('1'=> 'Quarantine',
		'2' => 'On review', '3' => 'Awaiting validation', '4' => 'Published')))
		->setDecorators($decorators);

	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setUncheckedValue(NULL)
		->setDecorators($decorators);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose rally name' => $rally_options))
		->setDecorators($decorators);

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
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
		->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators);

	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
		->addMultiOptions(array(NULL => 'Choose district after county'))
		->addFilters(array('StripTags','StringTrim'))
		->setRegisterInArrayValidator(false)
		->setDecorators($decorators)
		->disabled = true;

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
		->addMultiOptions(array(NULL => 'Choose a region for a wide result',
		'Choose region' => $region_options))
		->setDecorators($decorators);

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('ValidGridRef')
		->setDecorators($decorators);

	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('ValidGridRef')
		->setDecorators($decorators);

	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose denomination type',
		'Available denominations' => $denomination_options))
		->setDecorators($decorators);


	$cat = new Zend_Form_Element_Select('category');
	$cat->setLabel('Category: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose an Early Medieval category',
		'Available categories' => $cat_options))
		->setDecorators($decorators);

	$type = new Zend_Form_Element_Select('typeID');
	$type->setLabel('Coin type: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators);

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose primary ruler',
		'Available options' => $ruler_options))
		->setDecorators($decorators);

	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose issuing mint', 
		'Available mints' => $mint_options))
		->setDecorators($decorators);

	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);

	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);

	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);

	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);

	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setRegisterInArrayValidator(false)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a die axis measurement',
		'Available options' => $axis_options))
		->setDecorators($decorators);

	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin')
		->setAttrib('class', 'none')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Early Medieval')
		->setAttrib('class', 'none')
		->removeDecorator('label')
		->addFilter('StringToUpper')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->setLabel('Submit');
	
	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(null => 'Choose an institution','Available institutions' => $inst_options))
	->setDecorators($decorators); 	
	
	$this->addElements(array(
	$old_findID, $type, $description,
	$workflow, $rally, $rallyID,
	$hoard, $hoardID, $county,
	$regionID, $district, $parish,
	$fourFigure, $gridref, $denomination,
	$ruler, $mint, $axis,
	$obverseinsc, $obversedesc, $reverseinsc,
	$reversedesc, $objecttype, $broadperiod,
	$cat, $submit, $institution));
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(60);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array('category', 'ruler', 'typeID',
	'denomination', 'mint','moneyer',
	'axis', 'obverseLegend', 'obverseDescription',
	'reverseLegend','reverseDescription'),'numismatics')
	->removeDecorator('HtmlTag');
	$this->numismatics->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->numismatics->removeDecorator('DtDdWrapper');
	$this->numismatics->setLegend('Numismatic details: ');
	$this->addDisplayGroup(array('old_findID','description','rally','rallyID','hoard','hID','workflow'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	
	$this->details->setLegend('Object details: ');
	$this->addDisplayGroup(array('county','regionID','district','parish','gridref','fourFigure', 'institution'), 'spatial')
	->removeDecorator('HtmlTag');
	$this->spatial->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->spatial->removeDecorator('DtDdWrapper');
	$this->spatial->setLegend('Spatial details: ');
	
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->addDecorator('FormElements')
		 ->addDecorator('Form')
	     ->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'div'));
	
	}
}