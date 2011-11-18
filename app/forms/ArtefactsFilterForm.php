<?php
/** Form for filtering finds
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Will need changing for the solr version
*/

class ArtefactsFilterForm extends Pas_Form {
	
public function __construct($options = null) {
	
	$periods = new Periods();
	$periodword_options = $periods->getPeriodFromWords();
	
	$counties = new Counties();
	$county_options = $counties->getCountyName2();

parent::__construct($options);

	$this->setAttrib('accept-charset', 'UTF-8');
 	$this->setMethod('get');  
	$this->setName('filterfinds');

	$decorator =  array('TableDecInput');

	$objectType = new Zend_Form_Element_Select('objectType');
	$objectType->setLabel('Filter by object type')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Alpha', false, array('allowWhiteSpace' => true))
	->addErrorMessage('Come on it\'s not that hard, enter a title!')
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper');

	

	$broadperiod = new Zend_Form_Element_Select('broadperiod');
	$broadperiod->setLabel('Filter by broadperiod')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('stringLength', false, array(1,200))
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper');

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('stringLength', false, array(1,200))
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper');

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setLabel('Filter:')
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->setAttrib('class','largefilter');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	$this->addElements(array(
	$objectType, $broadperiod,
	$county, $submit));
	}
}