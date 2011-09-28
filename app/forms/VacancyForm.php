<?php

/** Form for manipulating vacancies on the system
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class VacancyForm extends Pas_Form {
	
public function __construct($options = null) {
	$staffregions = new StaffRegions();
	$staffregions_options = $staffregions->getOptions();

	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);

	$decorator =  array('SimpleInput');
	
	$decoratorSelect =  array('SelectInput');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
	
	$this->setName('vacancies');

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Role title: ')
		->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('Alnum', false, array('allowWhiteSpace' => true))
		->addErrorMessage('You must enter a title for this vacancy.')
		->setAttrib('size', 60)
		->setDecorators($decorators);
	
	$salary = new Zend_Form_Element_Text('salary');
	$salary->setLabel('Salary: ')
		->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 20)
		->addValidator('Currency')
		->addErrorMessage('You must enter a salary.')
		->setDecorators($decorators);
	
	$specification = new Pas_Form_Element_RTE('specification');
	$specification->setLabel('Job specification: ')
		->setRequired(true)
		->addFilters(array('BasicHtml', 'StringTrim'))
		->setAttribs(array('cols' => 50, 'rows' => 10, 'Height' => 400))
		->setAttrib('ToolbarSet','Basic')
		->addErrorMessage('You must enter a job description.');
	
	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('Location of role: ')
		->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('InArray', false, array(array_keys($staffregions_options)))
		->addMultiOptions(array(NULL => NULL,'Choose region' => $staffregions_options))
		->setDecorators($decorators)
		->addErrorMessage('You must choose a region');
	
	$live = new ZendX_JQuery_Form_Element_DatePicker('live');
	$live->setLabel('Date for advert to go live: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->setJQueryParam('maxDate', '+1y')
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('Date')
		->addErrorMessage('Come on it\'s not that hard, enter a title!')
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
		->removeDecorator('DtDdWrapper');
	
	$expire = new ZendX_JQuery_Form_Element_DatePicker('expire');
	$expire->setLabel('Date for advert to expire: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->setJQueryParam('maxDate', '+1y')
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('Date')
		->addErrorMessage('Come on it\'s not that hard, enter a title!')
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
		->removeDecorator('DtDdWrapper');
	
	$status = new Zend_Form_Element_Select('status');
	$status->SetLabel('Publish status: ')
		->setRequired(true)
		->addMultiOptions(array(NULL => 'Choose a status','2' => 'Publish','1' => 'Draft'))
		->setValue(2)
		->addFilters(array('StringTrim', 'StripTags'))
		->setDecorators($decorators)
		->addErrorMessage('You must choose a status');
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->setAttrib('class','large');
	
	$this->addElements(array(
	$title, $salary, $specification,
	$regionID, $live, $expire,
	$status, $submit));
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->removeDecorator('DtDdWrapper');
	
	$this->addDisplayGroup(array(
	'title', 'salary', 'specification',
	'regionID'), 'details');
	$this->details->setLegend('Vacancy details');
	$this->details->removeDecorator('DtDdWrapper');
	
	$this->addDisplayGroup(array(
	'live', 'expire', 'status'),
	'dates');
	$this->dates->setLegend('Publication details');
	$this->dates->removeDecorator('DtDdWrapper');
	
	$this->setLegend('Vacancy details');
	$this->addDisplayGroup(array('submit'), 'submit');
	}
}