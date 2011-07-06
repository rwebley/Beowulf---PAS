<?php
/** Form for linking cases to a specific tvc date
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class TVCDateForm extends Pas_Form {

public function __construct($options = null) {
	$dates = new TvcDates();
	$list = $dates->dropdown();
	
	parent::__construct($options);
	
	$this->setAttrib('accept-charset', 'UTF-8');
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	
	$this->setName('tvcdates');
	
	$date = new Zend_Form_Element_Select('tvcID');
	$date->setLabel('Date of TVC: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must choose a TVC date')
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
		->addMultiOptions(array('NULL' => 'Select a TVC','Valid dates' => $list))
		->addValidator('InArray', false, array(array_keys($list)))
		->setDecorators($decorators);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
		
	$this->addElements(array(
	$date, $submit, $hash
	));
	
	$this->addDisplayGroup(array('tvcID'), 'details')
		->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	
	}
}
