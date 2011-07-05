<?php

/** Form for searching for Post Medieval data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostMedNumismaticSearchForm extends Pas_Form {

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

	protected $_higherlevel = array('admin','flos','fa','heros','treasure','research'); 
	
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
	
	$rulers = new Rulers();
	$ruler_options = $rulers->getPostMedievalRulers();
	
	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsPostMedieval();
	
	$mints = new Mints();
	$mint_options = $mints->getPostMedievalMints();
	
	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();
	
	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodPostMed();
	
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

	$this->setName('postmedsearch');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid number!')
		->setDecorators($decorators);
	
	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);
	
	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators)
		->addValidator('Digits');
	if(in_array($this->getRole(),$this->higherlevel)) {
	$workflow->addMultiOptions(array(NULL => 'Choose Worklow stage',
	'Available workflow stages' => array('1'=> 'Quarantine','2' => 'On review', '4' => 'Awaiting validation', '3' => 'Published')));
	}
	if(in_array($this->getRole(),$this->restricted)) {
	$workflow->addMultiOptions(array(NULL => 'Choose Worklow stage',
	'Available workflow stages' => array('4' => 'Awaiting validation', '3' => 'Published')));
	}
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setUncheckedValue(NULL)
		->addValidators('Digits')
		->setDecorators($decorators);
	
	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose rally name','Available rallies' => $rally_options))
		->addValidator('InArray', false, array(array_keys($rally_options)))
		->setDecorators($decorators);
	
	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
		->addFilters(array('StripTags','StringTrim'))
		->setUncheckedValue(NULL)
		->addValidator('Digits')
		->setDecorators($decorators);
	
	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose hoard',
		'Available hoards' => $hoard_options))
		->addValidator('InArray', false, array(array_keys($hoard_options)))
		->setDecorators($decorators);

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose a county',
		'Available counties' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)))	
		->setDecorators($decorators);
	
	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
		->addMultiOptions(array(NULL => 'Choose district after county'))
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
		->addFilters(array('StripTags','StringTrim'))	
		->addMultiOptions(array(NULL => 'Choose a region for a wide result',
		'Choose region' => $region_options))
		->setDecorators($decorators);
	
	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addFilters(array('StripTags','StringTrim'))	
		->addValidators(array('NotEmpty','ValidGridRef','Alnum'))
		->setDecorators($decorators);
	
	$fourFigure = new Zend_Form_Element_Text('fourfigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addFilters(array('StripTags','StringTrim'))
		->addValidators(array('NotEmpty','ValidGridRef','Alnum'))
		->setDecorators($decorators);
	###
	##Numismatic data
	###
	//Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose denomination type',
		'Available denominations' => $denomination_options))
		->addValidator('InArray', false, array(array_keys($denomination_options)))	
		->setDecorators($decorators);
	
	
	$cat = new Zend_Form_Element_Select('category');
	$cat->setLabel('Category: ')
		->setRegisterInArrayValidator(false)
		->addValidator('InArray', false, array(array_keys($cat_options)))	
		->addMultiOptions(array(NULL => 'Choose category',
		'Available categories' => $cat_options))
		->setDecorators($decorators)
		->addFilters(array('StripTags','StringTrim'));
	
	$type = new Zend_Form_Element_Select('typeID');
	$type->setLabel('Coin type: ')
		->setRegisterInArrayValidator(false)
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators)
		->addMultiOptions(array(NULL => 'Available types depend on choice of ruler'));
		
	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL =>'Choose primary ruler', 
		'Available rulers' => $ruler_options))
		->addValidator('InArray', false, array(array_keys($ruler_options)))	
		->setDecorators($decorators);
		
	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL =>'Choose active mint',
		'Available mints' => $mint_options))
		->addValidator('InArray', false, array(array_keys($mint_options)))	
		->setDecorators($decorators);
	
	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obinsc');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);
	
	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obdesc');
	$obversedesc->setLabel('Obverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);
	
	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('revinsc');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);
	
	//reverse description
	$reversedesc = new Zend_Form_Element_Text('revdesc');
	$reversedesc->setLabel('Reverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);
	
	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose measurement',
		'Available die axes' => $axis_options))
		->addValidator('InArray', false, array(array_keys($axis_options)))	
		->setDecorators($decorators);
	
	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin')
	
	->setAttrib('class', 'none')->removeDecorator('label')
	              ->removeDecorator('HtmlTag')
				  ->removeDecorator('DtDdWrapper');
	
	
	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Post Medieval')
		->setAttrib('class', 'none')
		->addFilters(array('StripTags','StringTrim', 'StringToUpper'))
		->addValidator('Alpha',false,array('allowWhiteSpace' => true))
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
		
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setLabel('Submit your search ..')
		->setAttrib('class', 'large');
	
	
	$this->addElements(array(
	$old_findID,$type,$description,
	$workflow,$rally,$rallyID,
	$hoard,$hoardID,$county,
	$regionID,$district,$parish,
	$fourFigure,$gridref,$denomination,
	$ruler,$mint,$axis,
	$obverseinsc,$obversedesc,$reverseinsc,
	$reversedesc,$objecttype,$broadperiod,
	$cat,$submit));
	
	$this->addDisplayGroup(array(
	'category','ruler','typeID',
	'denomination','mint','moneyer',
	'axis','obinsc','obdesc',
	'revinsc','revdesc'), 'numismatics')
	->removeDecorator('HtmlTag');
	$this->numismatics->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->numismatics->removeDecorator('DtDdWrapper');
	
	$this->addDisplayGroup(array('old_findID','description','rally','rallyID','hoard','hID','workflow'), 'details')->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	
	$this->addDisplayGroup(array('county','regionID','district','parish','gridref','fourfigure'), 'spatial')->removeDecorator('HtmlTag');
	$this->spatial->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->spatial->removeDecorator('DtDdWrapper');
	
	$this->numismatics->setLegend('Numismatic details');
	$this->spatial->setLegend('Spatial details');
	$this->details->setLegend('Object specific details: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	$this->setMethod('get');
	
	}
}