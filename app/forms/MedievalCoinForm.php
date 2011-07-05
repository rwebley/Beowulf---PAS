<?php
/** Form for setting up and editing medieval coin data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MedievalCoinForm extends Pas_Form {

public function __construct($options = null) {
	
	// Construct the select menu data

	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodMed();
	
	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsMedieval();
	
	$rulers = new Rulers();
	$ro = $rulers->getMedievalRulers();
	
	$mints = new Mints();
	$mo = $mints->getMedievalMints();	
	
	$statuses = new Statuses();
	$status_options = $statuses->getCoinStatus();
	
	$dies = new Dieaxes;
	$die_options = $dies->getAxes();
	
	$wears = new Weartypes;
	$wear_options = $wears->getWears();

	parent::__construct($options);

	$this->setName('earlymedievalcoin');
	
	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
		
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
	->addMultiOptions(array(NULL => NULL, 'Choose denomination' => $denomination_options))
	->addValidator('InArray', false, array(array_keys($denomination_options)))
	->addValidator('Digits')
	->addFilters(array('StripTags', 'StringTrim'))
	->setDecorators($decorators);
	
	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StripTags', 'StringTrim'))
	->setOptions(array('separator' => ''))
	->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
	->setDecorators($decorators);

	$categoryID = new Zend_Form_Element_Select('categoryID');
	$categoryID->setLabel('Category of coin: ')
	->addValidators(array('NotEmpty','Digits'))
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => NULL,'Choose category' => $cat_options))
	->addValidator('InArray', false, array(array_keys($cat_options)))
	->setDecorators($decorators);
	
	$ruler= new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler: ')
	->setDecorators($decorators)
	->addValidators(array('NotEmpty','Digits'))
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => NULL,'Choose a ruler' => $ro))
	->addValidator('InArray', false, array(array_keys($ro)));
	
	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Issuer qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setOptions(array('separator' => ''))
	->addFilters(array('StripTags', 'StringTrim'))
	->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
	->setDecorators($decorators);
	
	$mint_id= new Zend_Form_Element_Select('mint_id');
	$mint_id->setLabel('Issuing mint: ')
	->setRegisterInArrayValidator(true)
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => NULL,'Choose a mint' => $mo))
	->addValidator('InArray', false, array(array_keys($mo)));
	
	$status = new Zend_Form_Element_Select('status');
	$status->setLabel('Status: ')
	->setRegisterInArrayValidator(true)
	->setValue(1)
	->addMultiOptions(array(NULL => NULL,'Choose coin status' => $status_options))
	->setDecorators($decorators)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('InArray', false, array(array_keys($status_options)))
	->addValidator('Digits');
	
	$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
	$status_qualifier->setLabel('Status qualifier: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StripTags', 'StringTrim'))
	->setOptions(array('separator' => ''))
	->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
	->setDecorators($decorators);
	
	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
	->setRegisterInArrayValidator(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => NULL,'Choose coin status' => $wear_options))
	->setDecorators($decorators)
	->addValidator('InArray', false, array(array_keys($wear_options)))
	->addValidator('Digits');
	
	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
	->setDecorators($decorators)
	->setAttrib('size',60)
	->addFilters(array('StripTags', 'StringTrim','BasicHtml','WordChars','EmptyParagraph'));
	
	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
	->setDecorators($decorators)
	->setAttrib('size',60)
	->addFilters(array('StripTags', 'StringTrim','BasicHtml','WordChars','EmptyParagraph'));
	
	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
	->addFilters(array('StripTags', 'StringTrim','BasicHtml','WordChars','EmptyParagraph'))
	->setAttrib('rows',3)
	->setAttrib('cols',80)
	->setAttrib('class','expanding');
	
	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',3)
	->setAttrib('cols',80)
	->addFilters(array('StripTags', 'StringTrim','BasicHtml','WordChars','EmptyParagraph'))
	->setAttrib('class','expanding');
	
	$rev_mm = new Zend_Form_Element_Textarea('reverse_mintmark');
	$rev_mm->setLabel('Reverse mintmark: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',3)
	->setAttrib('cols',80)
	->addFilters(array('StripTags', 'StringTrim','BasicHtml','WordChars','EmptyParagraph'))
	->setAttrib('class','expanding');
	
	$initial = new Zend_Form_Element_Textarea('initial_mark');
	$initial->setLabel('Initial mark: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',3)
	->setAttrib('cols',80)
	->addFilters(array('StripTags', 'StringTrim','BasicHtml','WordChars','EmptyParagraph'))
	->setAttrib('class','expanding');
	
	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
	->setRegisterInArrayValidator(false)
	->addMultiOptions(array(NULL => NULL,'Choose coin status' => $die_options))
	->addValidator('InArray', false, array(array_keys($die_options)))
	->setDecorators($decorators);
	
	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->addFilters(array('StripTags', 'StringTrim'))
	->setOptions(array('separator' => ''))
	->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
	->setDecorators($decorators);
	
	$type = new Zend_Form_Element_Select('typeID');
	$type->setLabel('Coin type: ')
	->setRegisterInArrayValidator(false)
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->setDecorators($decorators);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->setAttrib('class','large');
	
	$this->addElements(array(
	$ruler, $ruler_qualifier, $denomination,
	$denomination_qualifier, $mint_id, $type,
	$status, $categoryID, $status_qualifier,
	$degree_of_wear, $obverse_description, $obverse_inscription,	
	$reverse_description, $reverse_inscription, $die_axis_measurement,
	$die_axis_certainty, $rev_mm, $submit, $initial));
	
	$this->addDisplayGroup(array(
	'categoryID','ruler','typeID',
	'ruler_qualifier','denomination','denomination_qualifier',
	'mint_id','status','status_qualifier',
	'degree_of_wear','obverse_description','obverse_inscription',
	'reverse_description','reverse_inscription','reverse_mintmark',
	'initial_mark','die_axis_measurement','die_axis_certainty'
	), 'details');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	
	$this->addDisplayGroup(array('submit'),'submit');
	
	}
}
