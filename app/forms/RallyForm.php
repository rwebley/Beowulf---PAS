<?php

/** Form for adding and editing rally data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RallyForm extends Pas_Form {
	
public function __construct($options = null) {
	$counties = new Counties();
	$county_options = $counties->getCountyName2();

	parent::__construct($options);
		
	ZendX_JQuery::enableForm($this);
	
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
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->setAttrib('size',60)
		->addErrorMessage('Come on it\'s not that hard, enter a name for the rally!')
		->setDecorators($decorators);
	
	$organisername = new Zend_Form_Element_Text('organisername');
	$organisername->setLabel('Rally Organiser: ')
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators);
	
	$organiser = new Zend_Form_Element_Hidden('organiser');
	$organiser->removeDecorator('Label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addFilters(array('StripTags','StringTrim'));
	
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
		->addMultiOptions(array(NULL => 'Choose a county' ,'Valid counties' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)))
		->setDecorators($decorators)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alpha',false,array('allowWhiteSpace' => true));;
	
	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
		->setRegisterInArrayValidator(false)
		->setDecorators($decorators)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose district after county'))
		->addValidator('Alpha',false,array('allowWhiteSpace' => true));
	
	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
		->setRegisterInArrayValidator(false)
		->setDecorators($decorators)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => 'Choose parish after district'))
		->addValidator('Alpha',false,array('allowWhiteSpace' => true));
	
	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Centred on field at NGR: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('maxlength',16)
		->setDecorators($decorators)
		->addValidators(array('NotEmpty','ValidGridRef'));
	
	$record_method = new Pas_Form_Element_RTE('record_method');
	$record_method->setLabel('Recording methodology employed: ')
		->setRequired(false)
		->setAttrib('rows',10)
		->setAttrib('cols',80)//->addFilter('StripTags')
		->addFilters(array('BasicHtml','EmptyParagraph','StringTrim'));
	
	$comments = new Zend_Form_Element_Textarea('comments');
	$comments->setLabel('Comments on rally: ')
		->setRequired(false)
		->setAttrib('rows',10)
		->setAttrib('cols',80)
		->addFilters(array('BasicHtml','EmptyParagraph','StringTrim'));
	
	//Date found from
	$date_from = new ZendX_JQuery_Form_Element_DatePicker('date_from');
	$date_from->setLabel('Start date of rally: ')
		->setRequired(false)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Date')
		->setDecorators($decorators);
	
	//Date found to
	$date_to = new ZendX_JQuery_Form_Element_DatePicker('date_to');
	$date_to->setLabel('End date of rally: ')
		->setRequired(false)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Date')
		->setDecorators($decorators);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setAttrib('class','large');
	
	$this->addElements(array(
	$rally_name, $date_from, $date_to,
	$organiser, $organisername, $county,
	$district, $parish, $gridref, $comments,
	$record_method,	$submit));
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array(
	'rally_name', 'comments','record_method',
	'date_from', 'date_to', 'organiser',
	'organisername', 'county', 'district',
	'parish', 'gridref'), 'details');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	
	$this->details->setLegend('Rally details: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	  
	}
}
