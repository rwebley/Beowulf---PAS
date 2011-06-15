<?php
/**
* Advanced search form for database
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AdvancedSearchForm extends Pas_Form {

	protected function getRole(){
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()){
	$user = $auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}
	
	protected $higherlevel = array('admin','flos','fa','heros'); 
	
	protected $restricted = array('public','member','research');


	public function __construct($options = null) {

	//Get data to form select menu for discovery methods
	$discs = new DiscoMethods();
	$disc_options = $discs->getOptions();
	//Get data to form select menu for manufacture methods
	$mans = new Manufactures();
	$man_options = $mans->getOptions();
	//Get data to form select menu for primary and secondary material
	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();
	//Get data to form select menu for periods
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();
	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();
	
	$periods = new Periods();
	$periodword_options = $periods->getPeriodFromWords();
	//Get data to form select menu for cultures
	$cultures = new Cultures();
	$culture_options = $cultures->getCultures();
	//Get data to form Surface treatments menu
	$surfaces = new Surftreatments();
	$surface_options = $surfaces->getSurfaces();
	//Get data to form Decoration styles menu
	$decorations = new Decstyles();
	$decoration_options = $decorations->getStyles();
	//Get data to form Decoration methods menu
	$decmeths = new Decmethods();
	$decmeth_options = $decmeths->getDecmethods();
	//Get Find of note reason data
	$reasons = new Findofnotereasons();
	$reason_options = $reasons->getReasons();
	//Get Preservation data
	$preserves = new Preservations();
	$preserve_options = $preserves->getPreserves();
	//Get Rally data
	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();
	//Get Hoard data
	$hoards = new Hoards();
	$hoard_options = $hoards->getHoards();
	//Get county dropdown
	$counties = new Counties();
	$county_options = $counties->getCountyName2();
	
	$regions = new Regions();
	$region_options = $regions->getRegionName();
	
	$current_year = date('Y');
	$years = range(1950, $current_year);
	$years_list = array_combine($years,$years);


	parent::__construct($options);
	$decorator =  array('SimpleInput');
	$decoratorSelect =  array('SelectInput');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
		    );
			
	$decoratorsHide = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'hideme')),
            array('HtmlTag', array('tag' => 'li')),
		    );
	$decoratorsRally = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'hiderally')),
            array('HtmlTag', array('tag' => 'li')),
		    );
	$decoratorsHoard = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'hidehoard')),
            array('HtmlTag', array('tag' => 'li')),
		    );
	$decoratorsNote = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'hidenote')),
            array('HtmlTag', array('tag' => 'li')),
		    );
			
	$this->setName('Advanced');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
	->addFilters(array('StringTrim','StripTags'))
	->addValidator('StringLength', false, array(3,20))
	->addErrorMessage('Please enter a valid number!')
	->setDecorators($decorators);

	$objecttype = new Zend_Form_Element_Text('objecttype');
	$objecttype->setLabel('Object type: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addErrorMessage('Please enter a valid object type!')
	->setDecorators($decorator);

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags','HtmlBody'))
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorator);
	
	//Find of note
	$findofnote = new Zend_Form_Element_Checkbox('note');
	$findofnote->setLabel('Find of Note: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setUncheckedValue(NULL)
	->setDecorators($decorators);
	
	//Reason for find of note
	$findofnotereason = new Zend_Form_Element_Select('reason');
	$findofnotereason->setLabel('Reason for noteworthy status: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose reason' => $reason_options))
	->setDisableTranslator(true)
	->setDecorators($decoratorsNote); 

	$notes = new Zend_Form_Element_Text('notes');
	$notes->setLabel('Notes: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorator);


	$broadperiod = new Zend_Form_Element_Select('broadperiod');
	$broadperiod->setLabel('Broad period: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL ,'Choose period from' => $periodword_options))
	->setDecorators($decorators); 


	$objdate1subperiod = new Zend_Form_Element_Select('fromsubperiod');
	$objdate1subperiod->setLabel('Sub period from: ')
	->setRequired(false)
	->addMultiOptions(array(NULL => NULL, 'Choose sub-period from' => array('1' => 'Early',
	'2' => 'Middle','3' => 'Late')))
	->addFilters(array('StringTrim','StripTags'))
	->setOptions(array('separator' => ''))
	->setDecorators($decorators); 


	//Period from: Assigned via dropdown
	$objdate1period = new Zend_Form_Element_Select('periodfrom');
	$objdate1period->setLabel('Period from: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL ,'Choose period from' => $period_options))
	->setDisableTranslator(true)
	->setDecorators($decorators); 


	$objdate2subperiod = new Zend_Form_Element_Select('tosubperiod');
	$objdate2subperiod->setLabel('Sub period to: ')
	->addMultiOptions(array(NULL => NULL, 'Choose sub-period from' => array('1' => 'Early','2' => 'Middle','3' => 'Late')))
	->setDisableTranslator(true)
	->addFilters(array('StringTrim','StripTags'))
	->setOptions(array('separator' => ''))
	->setDecorators($decorators); 


	//Period to: Assigned via dropdown
	$objdate2period = new Zend_Form_Element_Select('periodto');
	$objdate2period->setLabel('Period to: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose period to' => $period_options))
	->setDecorators($decorators); 

	$culture = new Zend_Form_Element_Select('culture');
	$culture->setLabel('Ascribed culture: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose ascribed culture to' => $culture_options))
	->setDecorators($decorators); 


	$from = new Zend_Form_Element_Text('from');
	$from->setLabel('Start date greater than: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addValidators(array('NotEmpty','Int'))
	->addErrorMessage('Please enter a valid date')
	->setDisableTranslator(true)
	->setDecorators($decorator);

	$fromend = new Zend_Form_Element_Text('fromend');
	$fromend->setLabel('Start date smaller than: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addValidators(array('NotEmpty','Int'))
	->addErrorMessage('Please enter a valid date')
	->setDecorators($decorator)
	->setDisableTranslator(true);

	$to= new Zend_Form_Element_Text('to');
	$to->setLabel('End date greater than: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addValidators(array('NotEmpty','Int'))
	->addErrorMessage('Please enter a valid date')
	->setDecorators($decorator);

	$toend= new Zend_Form_Element_Text('toend');
	$toend->setLabel('End date smaller than: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addValidators(array('NotEmpty','Int'))
	->addErrorMessage('Please enter a valid date')
	->setDecorators($decorator);


	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addValidator('Int')
	->setDecorators($decorators);
	if(in_array($this->getRole(),$this->higherlevel)) {
	$workflow->addMultiOptions(array(NULL => NULL ,'Choose Worklow stage' => array('1'=> 'Quarantine','2' => 'On review', '4' => 'Awaiting validation', '3' => 'Published')));
	}
	if(in_array($this->getRole(),$this->restricted)) {
	$workflow->addMultiOptions(array(NULL => NULL ,'Choose Worklow stage' => array('4' => 'Awaiting validation', '3' => 'Published')));
	}


	$treasure = new Zend_Form_Element_Checkbox('treasure');
	$treasure->setLabel('Treasure find: ')
	->setRequired(false)	
	->addFilters(array('StringTrim','StripTags'))
	->setUncheckedValue(NULL)
	->setDecorators($decorators);

	$treasureID =  new Zend_Form_Element_Text('TID');
	$treasureID->setLabel('Treasure ID number: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decoratorsHide);


	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
	->setRequired(false)
	->addValidator('Int')
	->addFilters(array('StringTrim','StripTags'))
	->setUncheckedValue(NULL)
	->setDecorators($decorators);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addValidator('Int')
	->addMultiOptions(array(NULL => NULL,'Choose rally name' => $rally_options))
	->setDisableTranslator(true)
	->setDecorators($decoratorsRally);

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setUncheckedValue(NULL)
	->setDecorators($decorators);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose rally name' => $hoard_options))
	->setDecorators($decoratorsHoard);


	$other_ref = new Zend_Form_Element_Text('otherref');
	$other_ref->setLabel('Other reference: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorator);

	//Manufacture method
	$manmethod = new Zend_Form_Element_Select('manufacture');
	$manmethod->setLabel('Manufacture method: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addValidator('Int')
	->addMultiOptions(array(NULL => NULL,'Choose method of manufacture' => $man_options))
	->setDecorators($decorators);

	//Decoration method
	$decmethod = new Zend_Form_Element_Select('decoration');
	$decmethod->setLabel('Decoration method: ')
	->setRequired(false)
	->addValidator('Int')
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose decoration method' => $decmeth_options))
	->setDecorators($decorators);


	//Surface treatment
	$surftreat = new Zend_Form_Element_Select('surface');
	$surftreat->setLabel('Surface Treatment: ')
	->setRequired(false)
	->addValidator('Int')
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose surface treatment' => $surface_options))
	->setDecorators($decorators);

	//decoration style
	$decstyle = new Zend_Form_Element_Select('decstyle');
	$decstyle->setLabel('Decorative style: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose decorative style' => $decoration_options))
	->addValidator('Int')
	->setDecorators($decorators); 

	//Preservation of object
	$preservation = new Zend_Form_Element_Select('preservation');
	$preservation->setLabel('Preservation: ')
	->setRequired(false)
	->addFilter('StripTags')
	->addFilter('StringTrim')
	->addValidator('Int')
	->addMultiOptions(array(NULL => NULL,'Choose level of preservation' => $preserve_options))
	->setDecorators($decorators); 

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->addValidators(array('NotEmpty'))
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
	->setDecorators($decorators);

	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
	->addMultiOptions(array(NULL => 'Choose district after county'))
	->setRegisterInArrayValidator(false)
	->addFilters(array('StringTrim','StripTags'))
	->setDisableTranslator(true)->setDecorators($decorators);

	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => 'Choose parish after county'))
	->setDisableTranslator(true)->setDecorators($decorators);

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
	->setRegisterInArrayValidator(false)
	->addValidator('Int')
	->addMultiOptions(array(NULL => 'Choose a region for a wide result','Choose region' => $region_options))
	->setDisableTranslator(true)
	->setDecorators($decorators);

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
	->addValidators(array('NotEmpty'))
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorator);

	$fourFigure = new Zend_Form_Element_Text('fourfigure');
	$fourFigure->setLabel('Four figure grid reference: ')
	->addValidators(array('NotEmpty'))
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorator);

	$idBy = new Zend_Form_Element_Text('idby');
	$idBy->setLabel('Primary identifier: ')
	->addValidators(array('NotEmpty'))
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorator);

	$identifierID = new Zend_Form_Element_Hidden('identifierID');
	$identifierID->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->addFilters(array('StringTrim','StripTags'))
	->removeDecorator('Label');


	$created = new Zend_Form_Element_Text('createdBefore');
	$created->setLabel('Date record created on or before: ')
	->addValidator('Date')
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorator);

	$created2 = new Zend_Form_Element_Text('createdAfter');
	$created2->setLabel('Date record created on or after: ')
	->addValidator('Date')
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorator);

	$finder = new Zend_Form_Element_Text('finder');
	$finder->setLabel('Found by: ')
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorator);

	$finderID = new Zend_Form_Element_Hidden('finderID');
	$finderID->removeDecorator('HtmlTag')
	->addFilters(array('StringTrim','StripTags'))
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label');


	$recordby = new Zend_Form_Element_Text('recordby');
	$recordby->setLabel('Recorded by: ')
	->addValidators(array('NotEmpty'))
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('autoComplete', 'true')
	->setDecorators($decorator);
	
	$recorderID = new Zend_Form_Element_Hidden('recorderID');
	$recorderID->removeDecorator('HtmlTag')
	->addFilters(array('StringTrim','StripTags'))
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label');


	$discoverydate = new Zend_Form_Element_Select('discovered');
	$discoverydate->setLabel('Year of discovery')
	->setMultiOptions(array(NULL=>'Choose a year of discovery','Date range' => $years_list))
	->addValidator('Int')
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorators);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->setLabel('Submit your search');

	$material1 = new Zend_Form_Element_Select('material');
	$material1->setLabel('Primary material: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => NULL,'Choose primary material' => $primary_options))
	->setDecorators($decorators);

	$woeid = new Zend_Form_Element_Text('woeid');
	$woeid->setLabel('Where on earth ID: ')
	->addValidator('Int')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorator);

	$elevation  = new Zend_Form_Element_Text('elevation');
	$elevation->setLabel('Elevation: ')
	->addValidator('Int')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorator);

	if(in_array($this->getRole(),$this->restricted)) {
	$this->addElements(array(	
	$old_findID, $objecttype, $broadperiod,
	$description, $from, $to,
	$workflow, $findofnote, $findofnotereason,
	$rally, $rallyID, $hoard,
	$hoardID, $other_ref, $manmethod,
	$fromend, $toend, $notes,
	$objdate1period, $objdate2period, $county,
	$regionID, $district, $parish,
	$fourFigure, $objdate1subperiod, $objdate2subperiod,
	$treasure, $treasureID, $discoverydate,
	$created, $created2, $idBy,
	$recordby, $recorderID, $identifierID,
	$culture, $surftreat, $submit,
	$material1, $elevation, $woeid));
	} else {
	$this->addElements(array(
	$old_findID, $objecttype, $broadperiod,
	$description, $from, $to,
	$workflow, $findofnote, $findofnotereason,
	$rally, $rallyID, $hoard,
	$hoardID, $other_ref, $manmethod,
	$fromend, $toend, $notes,
	$objdate1period, $objdate2period, $county,
	$regionID, $district, $parish,
	$fourFigure, $elevation, $woeid,
	$objdate1subperiod, $objdate2subperiod, $treasure,
	$treasureID, $discoverydate, $created,
	$created2, $idBy, $finder,
	$finderID, $recordby, $recorderID,
	$identifierID, $culture, $surftreat,
	$submit, $material1));
	}

	$this->addDisplayGroup(array(
	'old_findID', 'objecttype', 'description',
	'notes', 'note', 'reason',
	'treasure', 'TID', 'rally',
	'rallyID', 'hoard', 'hID',
	'workflow', 'otherref', 'material',
	'manufacture','surface'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Main details: ');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array(
	'broadperiod', 'fromsubperiod', 'periodfrom',
	'tosubperiod', 'periodto', 'culture', 
	'from', 'fromend', 'to',
	'toend'), 'Temporaldetails')
	->removeDecorator('HtmlTag');
	$this->Temporaldetails->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->Temporaldetails->removeDecorator('DtDdWrapper');
	$this->Temporaldetails->setLegend('Temporal details: ');
	
	$this->addDisplayGroup(array(
	'county', 'regionID', 'district',
	'parish', 'fourfigure', 'elevation',
	'woeid'), 'Spatial')
	->removeDecorator('HtmlTag');
	$this->Spatial->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->Spatial->removeDecorator('DtDdWrapper');
	$this->Spatial->setLegend('Spatial details: ');
	
	if(in_array($this->getRole(),$this->restricted)) {
	$this->addDisplayGroup(array(
	'idby', 'identifierID', 'recordby',
	'recorderID', 'createdAfter', 'createdBefore',
	'discovered'), 'Discovery')
	->removeDecorator('HtmlTag');
	} else {
	$this->addDisplayGroup(array(
	'finder', 'idby', 'identifierID',
	'recordby', 'recorderID', 'createdAfter',
	'createdBefore','discovered'), 'Discovery')
	->removeDecorator('HtmlTag');
	}
	
	$this->Discovery->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->Discovery->removeDecorator('DtDdWrapper');
	$this->Discovery->setLegend('Discovery details: ');
	
	
	//$this->setLegend('Perform an advanced search on our database: ');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	
	
	
	}
	}