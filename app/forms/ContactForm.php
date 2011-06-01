<?php
class ContactForm extends Pas_Form
{
public function __construct($options = null)
{

$roles = new StaffRoles();
$role_options = $roles->getOptions();

$institutions = new Institutions();
$insts = $institutions->getInsts();

$staffregions = new StaffRegions();
$staffregions_options = $staffregions->getOptions();

$countries = new Countries();
$countries_options = $countries->getOptions();

$users = new Users();
$users_options = $users->getOptions();

parent::__construct($options);

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
			
$this->setName('contact');

$firstname = new Zend_Form_Element_Text('firstname');
$firstname->setLabel('First name: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')

->addErrorMessage('You must enter a firstname')
->setDecorators($decorators);

$lastname = new Zend_Form_Element_Text('lastname');
$lastname->setLabel('Last name: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('stringLength', false, array(1,200))
->addErrorMessage('You must enter a lastname')
->setDecorators($decorators);

$role = new Zend_Form_Element_Select('role');
$role->setLabel('Role within the Scheme: ')
->setRequired(true)
->addFilters(array('StripTags','StringTrim'))

->addMultiOptions(array(NULL => NULL,'Choose a role' => $role_options))
->addErrorMessage('You must choose a role')
->setDecorators($decorators);

$dbaseID = new Zend_Form_Element_Select('dbaseID');
$dbaseID->setLabel('Database account: ')
->setRequired(false)
->addFilters(array('StripTags','StringTrim'))
->addMultiOptions(array(NULL => NULL, 'Choose account' => $users_options))
->addErrorMessage('You must enter a database account.')
->setDecorators($decorators);


$email_one = new Zend_Form_Element_Text('email_one');
$email_one->SetLabel('Primary email address: ')
->setRequired(true)
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->addValidator('emailAddress', false)
->addErrorMessage('You must enter an email address')
->setDecorators($decorators);

$email_two = new Zend_Form_Element_Text('email_two');
$email_two->SetLabel('Secondary email address: ')
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->addValidator('emailAddress', false)
->setDecorators($decorators);

$address_1 = new Zend_Form_Element_Text('address_1');
$address_1->SetLabel('Address line one: ')
->setRequired(true)
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->addErrorMessage('You must enter a first line for the address')
->setDecorators($decorators);

$address_2 = new Zend_Form_Element_Text('address_2');
$address_2->SetLabel('Address line two: ')
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))

->setDecorators($decorators);

$town = new Zend_Form_Element_Text('town');
$town->SetLabel('Town: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))

->addErrorMessage('You must enter a town')
->setDecorators($decorators);

$county = new Zend_Form_Element_Text('county');
$county->SetLabel('County: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))

->addErrorMessage('You must enter a county or unitary authority')
->setDecorators($decorators);

$euroregion = new Zend_Form_Element_Text('euroregion');
$euroregion->SetLabel('Administrative region: ')
->setRequired(false)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))

->setDecorators($decorators);


$postcode = new Zend_Form_Element_Text('postcode');
$postcode->SetLabel('Postcode: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))

->addErrorMessage('You must enter a postal code')
->setDecorators($decorators);

$country = new Zend_Form_Element_Select('country');
$country->SetLabel('Country: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))

->addValidator('inArray', false, array(array_keys($countries_options)))
//->addMultiOptions(array(NULL => NULL,'Choose country' => $countries_options))
->setDecorators($decorators);


$telephone = new Zend_Form_Element_Text('telephone');
$telephone->SetLabel('Telephone number: ')
->setRequired(true)
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->addErrorMessage('You must enter a telephone number')
->setDecorators($decorators);

$fax = new Zend_Form_Element_Text('fax');
$fax->SetLabel('Fax number: ')
->setRequired(false)
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))

->setDecorators($decorators);


$identifier = new Zend_Form_Element_Select('identifier');
$identifier->SetLabel('Database entry identifier: ')
->setRequired(true)
->addMultiOptions(array(NULL => NULL, 'Choose institution' => $insts))
->addValidator('inArray', false, array(array_keys($insts)))
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,6))

->setDecorators($decorators);

$region = new Zend_Form_Element_Select('region');
$region->SetLabel('Recording region: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,10))

->addValidator('inArray', false, array(array_keys($staffregions_options)))
->addMultiOptions(array(NULL => NULL, 'Choose staff region' => $staffregions_options))
->setDecorators($decorators);

$profile= $this->addElement('RTE', 'profile',array('label' => 'Profile: ')); 
$profile = $this->getElement('profile')->setRequired(false)
->addFilter('stringTrim')
->setAttribs(array('cols' => 70, 'rows' => 10))
->addFilter('HtmlBody');
		
$website = new Zend_Form_Element_Text('website');
$website->SetLabel('Employer\'s website address: ')
->setRequired(false)
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,150))

->setDecorators($decorators);



$alumni = new Zend_Form_Element_Checkbox('alumni');
$alumni->SetLabel('Currently employed by the Scheme: ')
->setRequired(true)
->addFilter('stringTrim')

->setDecorators($decorators);

/* $longitude = new Zend_Form_Element_Text('longitude');
$longitude->SetLabel('Longitude: ')
->setRequired(false)
->addFilter('stringTrim')
->setDecorators($decorators);

$latitude = new Zend_Form_Element_Text('latitude');
$latitude->SetLabel('Latitude: ')
->setRequired(false)
->addFilter('stringTrim')
->setDecorators($decorators);
 */

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setLabel('Submit:')
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->setAttrib('class','large');

$this->addElements(array(
$firstname, 
$lastname,
$role,
$dbaseID,
$email_one,
$email_two,
$address_1,
$address_2,
$town,
$postcode,
$county,
//$country,
$identifier,
$telephone,
$fax,
$region,
$profile,
$website,
$alumni,
/* $longitude,
$latitude,
 */$submit));

$this->addDisplayGroup(array('firstname','lastname','role','dbaseID','identifier','region','profile','email_one','email_two',
'address_1','address_2','town','postcode','county','telephone','fax','website','alumni'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');


$this->details->setLegend('Contact details');

  

       

}
}