<?php
/** Form for adding and editing coin references. Never understood why we need this.
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ReferenceCoinForm extends Pas_Form {
	
public function __construct($options = null) {
	$refs = new Coinclassifications();
	$ref_list = $refs->getClass();
	
	parent::__construct($options);
	$this->setName('addcoinreference');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$classID = new Zend_Form_Element_Select('classID');
	$classID->setLabel('Publication title: ')
		->setRequired(true)
		->addMultiOptions(array(NULL => 'Choose reference','Valid choices' => $ref_list))
		->addValidator('InArray', false, array(array_keys($ref_list)))
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a title')
		->setDecorators($decorators);


	$volume = new Zend_Form_Element_Text('vol_no');
	$volume->setLabel('Volume number: ')
		->setDecorators($decorators)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size',9)
		->addValidator('Alnum', false, array('allowWhiteSpace' => true));

	$reference = new Zend_Form_Element_Text('reference');
	$reference->setLabel('Reference number: ')
		->setDecorators($decorators)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size', 15)
		->addValidator('Alnum', false, array('allowWhiteSpace' => true));

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setAttrib('class','large');

	$this->addElements(array(
	$classID, $volume, $reference,
	$submit));

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array('classID','vol_no','reference'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Add a new reference');
	$this->addDisplayGroup(array('submit'),'submit');

	}
}
