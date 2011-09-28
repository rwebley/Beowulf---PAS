<?php
/** Form for filtering Scheduled Monuments
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SAMFilterForm extends Pas_Form
{
public function __construct($options = null) {

	
	$counties = new Counties();
	$county_options = $counties->getCountyName2();
	
	parent::__construct($options);
	$this->setName('filtersams');
	
	$decorator =  array('TableDecInput');
	
	$monumentName = new Zend_Form_Element_Text('monumentName');
	$monumentName->setLabel('Filter by name:')
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addValidator('Alnum', false, array('allowWhiteSpace' => true));
	
	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Filter by parish')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('Filter by district: ')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county: ')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options)) 
		->addValidator('InArray', false, array(array_keys($county_options)));
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
		
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setLabel('Filter:')
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setAttrib('class','largefilter');
	
	$this->addElements(array(
	$monumentName, $county, $district,
	$parish, $submit, $hash));
	  
	}
}