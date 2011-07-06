<?php
/** Form for adding and editing TVC dates and details
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class TVCForm extends Pas_Form {

public function __construct($options = null) {
	
	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
	
	$this->setName('tvcdates');

	$date = new ZendX_JQuery_Form_Element_DatePicker('date');
	$date->setLabel('Date of TVC: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a chase date')
		->addValidator('Date')
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
		->removeDecorator('DtDdWrapper');

	$location = new Zend_Form_Element_Text('location');
	$location->setLabel('Location of meeting: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a location')
		->setDecorators($decorators)
		->addValidator('Alnum',false,array('allowWhiteSpace' => true));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$this->addElements(array(
	$date, $location, $submit
	));

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);

	$this->addDisplayGroup(array('date','location'), 'details')
		->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');

	$this->addDisplayGroup(array('submit'), 'submit');

	}
}