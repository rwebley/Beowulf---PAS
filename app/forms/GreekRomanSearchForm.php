<?php
/** Form for retrieval of Greek and Roman coin data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class GreekRomanSearchForm extends Pas_Form {

public function __construct($options = null) {

	//Get data to form select menu for primary and secondary material
	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();
	
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
	$denomination_options = $denominations->getDenomsGreek();
	
	$mints = new Mints();
	$mint_options = $mints->getMintsGreek();
	
	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();
	
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
					
	$this->setName('greek-search');

	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addErrorMessage('Please enter a valid number!')
	->setDecorators($decorators);

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);

	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL ,'Choose Worklow stage' => 
	array('1'=> 'Quarantine','2' => 'On review', '3' => 'Awaiting validation', '4' => 'Published')))
	->setDecorators($decorators)
	->addValidator('Digits');

	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setUncheckedValue(NULL)
	->setDecorators($decorators);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addValidator('Int')
	->addMultiOptions(array(NULL => NULL,'Choose rally name' => $rally_options))
	->addValidator('InArray', false, array(array_keys($rally_options)))
	->setDecorators($decorators);


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
	->addValidator('InArray', false, array(array_keys($hoard_options)))
	->setDecorators($decorators);

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
	->addValidator('InArray', false, array(array_keys($county_options)))
	->setDecorators($decorators);

	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorators)
	->disabled = true;

	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => 'Choose parish after county'))
	->setDecorators($decorators)
	->disabled = true;

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
	->addMultiOptions(array(NULL => 'Choose a region for a wide result',
	'Choose region' => $region_options))
	->setDecorators($decorators)
	->addValidator('Digits')
	->addFilters(array('StringTrim','StripTags'));

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
	->addValidators(array('NotEmpty','ValidGridRef','Alnum'))
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorators);

	$fourFigure = new Zend_Form_Element_Text('fourfigure');
	$fourFigure->setLabel('Four figure grid reference: ')
	->addValidators(array('NotEmpty','ValidGridRef','Alnum'))
	->setDecorators($decorators)
	->addFilters(array('StringTrim','StripTags'));
	
	###
	##Numismatic data
	###
	//Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose denomination type' => $denomination_options))
	->addValidator('InArray', false, array(array_keys($denomination_options)))
	->setDecorators($decorators);
	
	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose primary ruler' => $ruler_options))
	->addValidator('InArray', false, array(array_keys($ruler_options)))
	->setDecorators($decorators);
	
	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
	->setRegisterInArrayValidator(false)
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose denomination type' => $mint_options))
	->addValidator('InArray', false, array(array_keys($mint_options)))
	->setDecorators($decorators);
	
	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obinsc');
	$obverseinsc->setLabel('Obverse inscription contains: ')
	->setRequired(false)
	->setAttrib('size',50)
	->addFilters(array('StringTrim','StripTags'))
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);
	
	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obdesc');
	$obversedesc->setLabel('Obverse description contains: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',50)
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);
	
	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('revinsc');
	$reverseinsc->setLabel('Reverse inscription contains: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',50)
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);
	
	//reverse description
	$reversedesc = new Zend_Form_Element_Text('revdesc');
	$reversedesc->setLabel('Reverse description contains: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',50)
	->addErrorMessage('Please enter a valid term')
	->setDecorators($decorators);
	
	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose measurement' => $axis_options))
	->addValidator('InArray', false, array(array_keys($axis_options)))
	->setDecorators($decorators);
	
	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin');
	$objecttype->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('label')
	->addFilters(array('StringTrim','StripTags'));
	
	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Greek and Roman Provincial')
	->addFilters(array('StringTrim','StripTags'))
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('label');
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->setAttribs(array('class' => 'large'))
	->setLabel('Search..');
	
	$this->addElements(array(
	$old_findID, $description, $workflow,
	$rally,$rallyID,$hoard,
	$hoardID,$county,$regionID,
	$district,$parish,$fourFigure,
	$gridref,$denomination,$ruler
	,$mint,$axis,$obverseinsc,
	$obversedesc,$reverseinsc,$reversedesc,
	$objecttype,$broadperiod, $submit));
	
	$this->addDisplayGroup(array(
	'denomination','ruler','mint',
	'moneyer','axis','obinsc',
	'obdesc','revinsc','revdesc'), 'numismatics');
	
	$this->addDisplayGroup(array(
	'old_findID','description','rally',
	'rallyID','hoard','hID',
	'workflow'), 'details');
	
	$this->addDisplayGroup(array(
	'county','regionID','district',
	'parish','gridref','fourfigure'), 'spatial');
	
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
	
	$this->setMethod('get');
	}
}