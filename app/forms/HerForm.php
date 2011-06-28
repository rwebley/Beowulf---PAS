<?php
/** Form for editing and adding HER signups
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class HerForm extends Pas_Form {
	
public function __construct($options = null) {

parent::__construct($options);
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	$this->setName('Her');
	
	$name = new Zend_Form_Element_Text('name');
	$name->setLabel('HER name: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->addErrorMessage('Please enter a HER name')
	->setDecorators($decorators);

	$contact_name = new Zend_Form_Element_Text('contact_name');
	$contact_name->setLabel('Contact name: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->setAttrib('size',40)
	->addErrorMessage('Please enter a contact name')
	->setDecorators($decorators);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');
	$this->addElements(array($name,$contact_name,$submit));

	$this->addDisplayGroup(array('name','contact_name'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('HER details: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	}
	
}