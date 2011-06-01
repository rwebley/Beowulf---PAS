<?php

class PeopleForm extends Zend_Form
{

public function __construct($options = null)
{
$users = new Users();
$users_options = $users->getOptions();

$countries = new Countries();
$countries_options = $countries->getOptions();
$counties = new Counties();
$counties_options = $counties->getCountyname2();

$activities = new PrimaryActivities();
$activities_options = $activities->getTerms();

$organisations = new Organisations;
$organisations_options = $organisations->getOrgs();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
$this->setName('people');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );


$title = new Zend_Form_Element_Select('title');
$title->setLabel('Title: ')
->setRequired(false)
->addFilter('StripTags')
->setValue('Mr')
->addErrorMessage('Choose title of person')
->addMultiOptions(array('Mr' => 'Mr','Mrs' => 'Mrs','Miss' => 'Miss','Ms' => 'Ms','Dr' => 'Dr.','Prof' => 'Prof.','Sir' => 'Sir','Lady' => 'Lady','Other' => 'Other','Captain' => 'Captain','Master' => 'Master','Dame' => 'Dame','Duke' => 'Duke'))
->setDecorators($decorators);

$forename = new Zend_Form_Element_Text('forename');
$forename->setLabel('Forename: ')
->setRequired(true)
->addFilter('StripTags')
->addValidator('NotEmpty')
->addErrorMessage('Please enter person\'s forename')
->setDecorators($decorators);

$surname = new Zend_Form_Element_Text('surname');
$surname->setLabel('Surname: ')
->setRequired(true)
->addFilter('StripTags')
->addValidator('NotEmpty')
->addErrorMessage('Please enter person\'s surname')
->setDecorators($decorators);

$fullname = new Zend_Form_Element_Text('fullname');
$fullname->setLabel('Fullname: ')
->setRequired(true)
->addFilter('StripTags')
->addValidator('NotEmpty')
->addErrorMessage('Please enter person\'s fullname')
->setDecorators($decorators);


$email = new Zend_Form_Element_Text('email');
$email->SetLabel('Email address: ')
->setRequired(false)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->addValidator('emailAddress', false)
->setAttrib('size','60')
->setDecorators($decorators);

$dbaseID = new Zend_Form_Element_Select('dbaseID');
$dbaseID->setLabel('User account: ')
->setRequired(false)
->addFilters(array('StripTags','StringTrim'))
->addValidator('int')
->addValidator('inArray', false, array(array_keys($users_options),null))
->addMultiOptions(array(NULL => 'Choose a user account','Existing accounts' => $users_options))
->addErrorMessage('You must enter a database account.')
->setDecorators($decorators);

$address = new Zend_Form_Element_Text('address');
$address->SetLabel('Address: ')
->setRequired(false)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

$town_city = new Zend_Form_Element_Text('town_city');
$town_city->SetLabel('Town: ')
->setRequired(false)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

$county = new Zend_Form_Element_Select('county');
$county->setLabel('County: ')
->setRequired(false)
->addFilter('StripTags')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => 'Please choose a county','Valid counties' => $counties_options))
->setDecorators($decorators);

$postcode = new Zend_Form_Element_Text('postcode');
$postcode->SetLabel('Postcode: ')
->setRequired(false)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

$country = new Zend_Form_Element_Select('country');
$country->SetLabel('Country: ')
->setRequired(false)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
//->addValidator('inArray', false, array(array_keys($countries_options)))
->addMultiOptions(array(NULL => 'Please choose a country of residence','Valid countries' => $countries_options))
->setValue('GB')
->setDecorators($decorators);

$hometel = new Zend_Form_Element_Text('hometel');
$hometel->SetLabel('Home telephone number: ')
->setRequired(false)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

$worktel = new Zend_Form_Element_Text('worktel');
$worktel->SetLabel('Work telephone number: ')
->setRequired(false)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

$fax = new Zend_Form_Element_Text('fax');
$fax->SetLabel('Fax number: ')
->setRequired(false)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

$comments = new Pas_Form_Element_TinyMce('comments');
$comments->SetLabel('Comments: ')
->setRequired(false)
->addFilter('stringTrim')
->setAttribs(array('cols' => 50, 'rows' => 10))
->setAttrib('class','expanding');

$organisationID = new Zend_Form_Element_Select('organisationID');
$organisationID->SetLabel('Organisation attached to: ')
->setRequired(false)
->addFilters(array('StripTags','StringTrim'))
->addMultiOptions(array(NULL => 'Please choose an organisation','Valid organisations' => $organisations_options))
->setDecorators($decorators);

$primary_activity = new Zend_Form_Element_Select('primary_activity');
$primary_activity->SetLabel('Person\'s primary activity: ')
->setRequired(true)
->addFilters(array('StripTags','StringTrim'))
->addValidator('int')
->addValidator('inArray', false, array(array_keys($activities_options)))
->addMultiOptions(array(NULL => 'Choose a primary activity','Valid activities' => $activities_options))
->addErrorMessage('You must enter an activity for this person.')
->setDecorators($decorators);

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class','large');

$this->addElements(array(
$title,
$forename,
$surname,
$fullname,
$email,
$address,
$town_city,
$county,
$postcode,
$country,
$dbaseID,
$hometel,
$worktel,
$fax,
$comments,
$organisationID,
$primary_activity,
$submit));

$this->addDisplayGroup(array('title','forename','surname','fullname','email','address','town_city','county','postcode','country','dbaseID','hometel','worktel','fax','comments','organisationID','primary_activity'), 'details');
$this->details->setLegend('Person details: ');
$this->addDisplayGroup(array('submit'), 'submit');
   $this->details->removeDecorator('DtDdWrapper');

$this->details->addDecorators(array(
    'FormElements',
    array('HtmlTag', array('tag' => 'ul'))
));
   

}
}