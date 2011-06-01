<?php
class SAMFilterForm extends Pas_Form
{
public function __construct($options = null)
{


$counties = new Counties();
$county_options = $counties->getCountyName2();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
 $this->setMethod('post');  
$this->setName('filterfinds');
$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 

$decorator =  array('TableDecInput');

$monumentName = new Zend_Form_Element_Text('monumentName');
$monumentName->setLabel('Filter by name:')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$parish = new Zend_Form_Element_Select('parish');
$parish->setLabel('Filter by parish')
->setRequired(false)
->addFilter('StripTags')

->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$district = new Zend_Form_Element_Select('district');
$district->setLabel('Filter by district: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->setDisableTranslator(true);

$county = new Zend_Form_Element_Select('county');
$county->setLabel('Filter by county: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
;

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setLabel('Filter:')
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->setAttrib('class','largefilter');

$this->addElements(array(
$monumentName, 
$county,
$district,
$parish,
$submit));
  
}
}