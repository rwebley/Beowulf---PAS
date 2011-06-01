<?php
class RallyForm extends Pas_Form
{
public function __construct($options = null)
{
$counties = new Counties();
$county_options = $counties->getCountyName2();

parent::__construct($options);
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$this->setName('rally');

$rally_name = new Zend_Form_Element_Text('rally_name');
$rally_name->setLabel('Rally name: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',60)
->addErrorMessage('Come on it\'s not that hard, enter a name for the rally!')
->setDecorators($decorators);

$organisername = new Zend_Form_Element_Text('organisername');
$organisername->setLabel('Rally Organiser: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);

$organiser = new Zend_Form_Element_Hidden('organiser');
$organiser->removeDecorator('Label')
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$county = new Zend_Form_Element_Select('county');
$county->setLabel('County: ')
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => 'Choose a county' ,'Valid counties' => $county_options))
->setDecorators($decorators);

$district = new Zend_Form_Element_Select('district');
$district->setLabel('District: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => 'Choose district after county'));



$parish = new Zend_Form_Element_Select('parish');
$parish->setLabel('Parish: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => 'Choose parish after district'));

$gridref = new Zend_Form_Element_Text('gridref');
$gridref->setLabel('Centred on field at NGR: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('maxlength',16)
->setDecorators($decorators)
->addValidators(array('NotEmpty','ValidGridRef'));


$record_method = new Pas_Form_Element_RTE('record_method');
$record_method->setLabel('Recording methodology employed: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addFilter('HtmlBody')
->setAttrib('rows',10)
->setAttrib('cols',80)//->addFilter('StripTags')
->setAttrib('class','expanding');

$comments = new Zend_Form_Element_Textarea('comments');
$comments->setLabel('Comments on rally: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('HtmlBody')
->setAttrib('rows',10)
->setAttrib('cols',80)//->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('class','expanding');

//Date found from
$date_from = new Zend_Form_Element_Text('date_from');
$date_from->setLabel('Start date of rally: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);
//Date found to
$date_to = new Zend_Form_Element_Text('date_to');
$date_to->setLabel('End date of rally: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class','large');

$this->addElements(array(
$rally_name,
$date_from,
$date_to,
$organiser,
$organisername,
$county,
$district,
$parish,
$gridref,
$comments,
$record_method,
$submit));

$this->addDisplayGroup(array('rally_name','comments','record_method','date_from','date_to','organiser','organisername','county','district','parish','gridref'), 'details');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');

$this->details->setLegend('Rally details: ');
$this->addDisplayGroup(array('submit'), 'submit');
  
  

       

}
}