<?php
class TreasureFilterForm extends Pas_Form
{
public function __construct($options = null)
{

$periods = new Periods();
$periodword_options = $periods->getPeriodFromWords();

$counties = new Counties();
$county_options = $counties->getCountyName2();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
 $this->setMethod('get');  
$this->setName('filterfinds');
$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 

$decorator =  array('TableDecInput');

$objecttype = new Zend_Form_Element_Text('objecttype');
$objecttype->setLabel('Filter by object type')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 15)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$oldfindID = new Zend_Form_Element_Text('old_findID');
$oldfindID->setLabel('Filter by find ID #')
->setRequired(false)
->addFilter('StripTags')
->setAttrib('size', 15)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$TID = new Zend_Form_Element_Text('TID');
$TID->setLabel('Filter by treasure ID #')
->setRequired(false)
->addFilter('StripTags')
->setAttrib('size', 15)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$broadperiod = new Zend_Form_Element_Select('broadperiod');
$broadperiod->setLabel('Filter by broadperiod')
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
;

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setLabel('Filter')
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');


$this->addElements(array(
$objecttype, 
$oldfindID,
$TID,
$broadperiod,
$county,
$submit));
  
}
}