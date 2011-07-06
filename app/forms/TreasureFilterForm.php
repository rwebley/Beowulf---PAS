<?php
/** Form for filtering treasure cases
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class TreasureFilterForm extends Pas_Form {
	
public function __construct($options = null) {

	$periods = new Periods();
	$periodword_options = $periods->getPeriodFromWords();
	
	$counties = new Counties();
	$county_options = $counties->getCountyName2();

	parent::__construct($options);
	
	$this->setMethod('get');  
	
	$this->setName('filterfinds');

	$decorator =  array('TableDecInput');

	$objecttype = new Zend_Form_Element_Text('objecttype');
	$objecttype->setLabel('Filter by object type')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 15)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
		
	$oldfindID = new Zend_Form_Element_Text('old_findID');
	$oldfindID->setLabel('Filter by find ID #')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 15)
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	$TID = new Zend_Form_Element_Text('TID');
	$TID->setLabel('Filter by treasure ID #')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 15)
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	$broadperiod = new Zend_Form_Element_Select('broadperiod');
	$broadperiod->setLabel('Filter by broadperiod')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addMultiOptions(array(NULL => NULL ,'Choose period from' => $periodword_options))
		->addValidator('InArray', false, array(array_keys($periodword_options)));
	
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)));
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
		
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setLabel('Filter')
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	$this->addElements(array(
	$objecttype, $oldfindID, $TID,
	$broadperiod, $county, $submit,
	 $hash));
	  
	}
}
