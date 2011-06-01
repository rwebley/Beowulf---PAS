<?php
class OrganisationFilterForm extends Pas_Form
{
public function __construct($options = null)
{

$periods = new Periods();
$periodword_options = $periods->getPeriodFromWords();
$activities = new PrimaryActivities();
$activities_options = $activities->getTerms();

$counties = new Counties();
$county_options = $counties->getCountyName2();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
 $this->setMethod('post');  
$this->setName('filterpeople');
$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 

$decorator =  array('TableDecInput');

$name = new Zend_Form_Element_Text('organisation');
$name->setLabel('Filter by name')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 40)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$contact = new Zend_Form_Element_Text('contact');
$contact->setLabel('Filter by contact person: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Enter a valid organisation')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$contactpersonID = new Zend_Form_Element_Hidden('contactpersonID');
$contactpersonID->removeDecorator('Label')
				->removeDecorator('DtDdWrapper')
				->removeDecorator('HtmlTag');
				

$county = new Zend_Form_Element_Select('county');
$county->setLabel('Filter by county')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
->setDisableTranslator(true);


//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setLabel('Filter')
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$this->addElements(array(
$name, 
$county,
$contact,
$contactpersonID,
$submit));
  
}
}