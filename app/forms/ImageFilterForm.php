<?php
class ImageFilterForm extends Pas_Form
{
public function __construct($options = null)
{

$periods = new Periods();
$periodword_options = $periods->getPeriodFrom();

$counties = new Counties();
$county_options = $counties->getCountyName2();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
 $this->setMethod('post');  
$this->setName('filterusers');
$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 

$decorator =  array('TableDecInput');

$old_findID = new Zend_Form_Element_Text('old_findID');
$old_findID->setLabel('Filter by ID #: ')
->setRequired(false)
->addFilter('StripTags')
->setAttrib('size', 11)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');


$label = new Zend_Form_Element_Text('label');
$label->setLabel('Filter by image label:')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 25)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');


$broadperiod = new Zend_Form_Element_Select('broadperiod');
$broadperiod->setLabel('Filter by broadperiod: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->addMultiOptions(array(NULL => NULL ,'Choose period from' => $periodword_options))
->setDisableTranslator(true)
;

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
->setLabel('Filter')
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$this->addElements(array(
$old_findID,
$label,
$broadperiod, 
$county,
$submit));
  
}
}