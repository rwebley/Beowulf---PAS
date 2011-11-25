<?php
/** Form for searching for medieval numismatic material
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class MedNumismaticSearchForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$this->setName('medNumismaticsSearch');

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
		->addMultiOptions(array(NULL => NULL , 
		'Choose Worklow stage' => array('1'=> 'Quarantine','2' => 'On review', 
		'3' => 'Awaiting validation', '4' => 'Published')))
		->setDecorators($decorators);

	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->setUncheckedValue(NULL)
		->setDecorators($decorators);
	
	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose rally name' => $rally_options))
		->addValidator('InArray', false, array(array_keys($rally_options)))
		->setDecorators($decorators);

	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->setUncheckedValue(NULL)
		->setDecorators($decorators);

	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose rally name' => $hoard_options))
		->addValidator('InArray', false, array(array_keys($hoard_options)))
		->setDecorators($decorators);

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)))
		->setDecorators($decorators);

	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
		->addMultiOptions(array(NULL => 'Choose district after county'))
		->setRegisterInArrayValidator(false)
		->setDecorators($decorators);

	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
		->setRegisterInArrayValidator(false)
		->addMultiOptions(array(NULL => 'Choose parish after county'))
		->setDecorators($decorators);

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
		->setRegisterInArrayValidator(false)
		->addMultiOptions(array(NULL => 'Choose a region for a wide result',
		'Choose region' => $region_options))
		->setDecorators($decorators);

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addValidators(array('NotEmpty','ValidGridRef'))
		->setDecorators($decorators);
	
	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addValidators(array('NotEmpty','ValidGridRef'))
		->setDecorators($decorators);

	###
	##Numismatic data
	###
	//	Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => NULL, 
		'Choose denomination type' => $denomination_options))
		->addValidator('InArray', false, array(array_keys($denomination_options)))
		->setDecorators($decorators);

	$cat = new Zend_Form_Element_Select('category');
	$cat->setLabel('Category: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose category' => $cat_options))
		->addValidator('InArray', false, array(array_keys($cat_options)))
		->setDecorators($decorators);

	$type = new Zend_Form_Element_Select('typeID');
	$type->setLabel('Coin type: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->setDecorators($decorators);

	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => NULL, 'Choose primary ruler' => $ruler_options))
		->addValidator('InArray', false, array(array_keys($ruler_options)))
		->setDecorators($decorators);

	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addMultiOptions(array(NULL => NULL, 'Choose denomination type' => $mint_options))
		->addValidator('InArray', false, array(array_keys($mint_options)))
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
		->addMultiOptions(array(NULL => NULL, 'Choose measurement' => $axis_options))
		->addValidator('InArray', false, array(array_keys($axis_options)))
		->setDecorators($decorators);

	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('COIN')
		->addFilters(array('StripTags', 'StringTrim'))	
		->setAttrib('class', 'none')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addValidator('Alpha');

	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('MEDIEVAL')
		->setAttrib('class', 'none')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alpha');
		
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setLabel('Submit your search ..')
		->setAttrib('class', 'large');
	
	$this->addElements(array(
	$old_findID, $type, $description,
	$workflow, $rally, $rallyID,
	$hoard, $hoardID, $county,
	$regionID, $district, $parish,
	$fourFigure, $gridref, $denomination,
	$ruler,$mint,$axis,
	$obverseinsc, $obversedesc,$reverseinsc,
	$reversedesc, $objecttype, $broadperiod,
	$cat, $submit));
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array(
	'category', 'ruler', 'typeID',
	'denomination', 'mint','moneyer',
	'axis',  'obinsc','obdesc',
	'revinsc','revdesc'), 'numismatics')
	->removeDecorator('HtmlTag');
	
	$this->numismatics->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->numismatics->removeDecorator('DtDdWrapper');
	$this->numismatics->setLegend('Numismatic details: ');
	$this->addDisplayGroup(array('old_findID','description','rally','rallyID','hoard','hID','workflow'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->setLegend('Object details:');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	
	$this->addDisplayGroup(array('county','regionID','district','parish','gridref','fourFigure'), 'spatial')->removeDecorator('HtmlTag');
	$this->spatial->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->spatial->removeDecorator('DtDdWrapper');
	$this->spatial->setLegend('Spatial details: ');
	
	$this->setLegend('Perform an advanced search on our database: ');
	
	$this->addDisplayGroup(array('submit'), 'submit');
				 
	$this->setMethod('get');
	
	}
}
