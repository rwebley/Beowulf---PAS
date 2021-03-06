<?php
/** Form for filtering personal data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PersonFilterForm extends Pas_Form {

public function __construct($options = null) {

	$periods = new Periods();
	$periodword_options = $periods->getPeriodFromWords();
	
	$activities = new PrimaryActivities();
	$activities_options = $activities->getTerms();
	
	$counties = new Counties();
	$county_options = $counties->getCountyName2();

parent::__construct($options);

	$decorator =  array('TableDecInput');

	$name = new Zend_Form_Element_Text('fullname');
	$name->setLabel('Filter by name')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->addErrorMessage('Come on it\'s not that hard, enter a title!')
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addDecorator('Label', array('tag' => 'span'));
	
	$organisation = new Zend_Form_Element_Text('organisation');
	$organisation->setLabel('Filter by organisation')
		->setRequired(false)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Enter a valid organisation')
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addDecorator('Label', array('tag' => 'span'));

	$organisationID = new Zend_Form_Element_Hidden('organisationID');
	$organisationID->removeDecorator('Label')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->addValidator('Alnum',false, array('allowWhiteSpace' => false))
		->addFilters(array('StripTags','StringTrim'));
				

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county')
		->setRequired(false)
		->addValidator('Alpha',false, array('allowWhiteSpace' => true))
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)))
		->addDecorator('Label', array('tag' => 'span'));

	$primary = new Zend_Form_Element_Select('primary_activity');
	$primary->setLabel('Filter by activity')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addMultiOptions(array(NULL => NULL,'Choose activity' => $activities_options))
		->addValidator('InArray', false, array(array_keys($county_options)))
		->addDecorator('Label', array('tag' => 'span'));


	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setLabel('Filter')
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setAttrib('class','buttonfilter');

	$this->addElements(array(
	$name, $county, $organisation,
	$organisationID, $primary, $submit));

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	}
}