<?php

/** Form for adding and editing ruler details etc
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RulerForm extends Pas_Form {

public function __construct($options = null) {
	
	$periods = new Periods();
	$period_options = $periods->getCoinsPeriod();
	
	parent::__construct($options);

	$this->setName('ruler');
	
	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
			
	$issuer = new Zend_Form_Element_Text('issuer');
	$issuer->setLabel('Ruler or issuer name: ')
		->setRequired(true)
		->addErrorMessage('Please enter a name for this issuer or ruler.')	
		->setDecorators($decorators)
		->setAttrib('size',70)
		->addValidator('Alnum', false, array('allowWhiteSpace' => true))
		->addFilters(array('StripTags', 'StringTrim'));

	$date1 = new Zend_Form_Element_Text('date1');
	$date1->setLabel('Date issued from: ')
		->setRequired(true)
		->setDecorators($decorators)
		->addErrorMessage('You must enter a date for the start of their issue.')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits');

	$date2 = new Zend_Form_Element_Text('date2');
	$date2->setLabel('Date issued to: ')
		->setRequired(true)
		->setDecorators($decorators)
		->addErrorMessage('You must enter a date for the end of their issue.')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->SetLabel('Is this ruler or issuer currently valid: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int')
		->setDecorators($decorators);


	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Broad period attributed to: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose reason' => $period_options))
		->addValidator('InArray', false, array(array_keys($period_options)))
		->setDecorators($decorators)
		->addErrorMessage('You must enter a period for this ruler/issuer');

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
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$this->addElements(array(
	$issuer, $date1, $date2,
	$period, $valid, $submit, 
	$hash));
	
	$this->addDisplayGroup(array('issuer','date1','date2','period','valid','submit'), 'details');
	$this->details->addDecorators(array(
	    'FormElements',
	    array('HtmlTag', array('tag' => 'ul'))
	));
	$this->details->setLegend('Issuer or ruler details: ');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	}
}