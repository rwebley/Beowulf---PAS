<?php
/** Form for creating and editing organisational data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class OrganisationForm extends Pas_Form {

public function __construct($options = null) {

	$countries = new Countries();
	$countries_options = $countries->getOptions();
	$counties = new Counties();
	$counties_options = $counties->getCountyname2();
	$peoples = new Peoples();
	$people_options = $peoples->getNames2();

	parent::__construct($options);
	
	$this->setName('organisation');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$name = new Zend_Form_Element_Text('name');
	$name->setLabel('Organisation name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter an organisation name: ')
		->setDecorators($decorators)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$website = new Zend_Form_Element_Text('website');
	$website->setLabel('Organisation website: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator(new Pas_Validate_Url())
		->addErrorMessage('Please enter a valid URL')
		->setAttrib('size',60)
		->setDecorators($decorators);

	$address1 = new Zend_Form_Element_Text('address1');
	$address1->setLabel('Address line one: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->setDecorators($decorators)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$address2 = new Zend_Form_Element_Text('address2');
	$address2->setLabel('Address line two: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->setDecorators($decorators)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$address3 = new Zend_Form_Element_Text('address3');
	$address3->setLabel('Address line three: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->setDecorators($decorators)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$address = new Zend_Form_Element_Text('address');
	$address->setLabel('Full address: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->setDecorators($decorators)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$town_city = new Zend_Form_Element_Text('town_city');
	$town_city->setLabel('Town or city: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->setDecorators($decorators)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Please choose a county',
		'Valid counties' => $counties_options))
		->addValidator('InArray', false, array(array_keys($counties_options)))
		->setDecorators($decorators);

	$country = new Zend_Form_Element_Select('country');
	$country->SetLabel('Country: ')
		->setRequired(true)
		->setValue('GB')
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Please choose a country', 
		'Valid countries' => $countries_options))
		->addValidator('InArray', false, array(array_keys($countries_options)))
		->setDecorators($decorators);

	$postcode = new Zend_Form_Element_Text('postcode');
	$postcode->setLabel('Postcode: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,10))
		->addValidator('PostCode')
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->addErrorMessage('Please enter a valid postcode')
		->setAttrib('size',10)
		->setDecorators($decorators);

	$contactperson = new Zend_Form_Element_Text('contact');
	$contactperson->setLabel('Organisation\'s lead contact: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->setAttrib('size',50)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->setDecorators($decorators);

	$contactpersonID = new Zend_Form_Element_Hidden('contactpersonID');
	$contactpersonID->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('Label');

	$submit = $this->addElement('submit', 'submit' , array('label' => 'Login...'));
	$submit = $this->getElement('submit');
	$submit->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->setAttrib('class','large');

	$this->addElements(array(
		$name, $website, $address1, 
		$address2, $address3, $address, 
		$town_city, $county, $country, 
		$postcode, $contactperson, $contactpersonID, 
	));

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array(
		'name', 'website', 'address1',
		'address2', 'address3', 'address',
		'town_city', 'county', 'country',
		'postcode','contact','contactpersonID'),
	 'details');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Organisation details: ');
	$this->addDisplayGroup(array('submit'), 'submit');

	}
}
