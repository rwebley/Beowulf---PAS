<?php
/** Form for adding and editing quotes
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class QuoteForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);
	
	$this->setAttrib('accept-charset', 'UTF-8');
 
	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
      
	$this->setName('quotes');

	$quote = new Pas_Form_Element_RTE('quote');
	$quote->setLabel('Quote or announcement: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Basic')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$quotedBy = new Zend_Form_Element_Text('quotedBy');
	$quotedBy->setLabel('Origin of quote/announcement: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please state where this comes from.');

	$expire = new Zend_Form_Element_Text('expire');
	$expire->setLabel('Expires from use: ')
		->setRequired(true)
		->setAttrib('size',10)
		->addValidator('Date')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Please provide expiry date.')
		->setDecorators($decorators);

	$valid = new Zend_Form_Element_Checkbox('status');
	$valid->setLabel('Quote/Announcement is in use: ')
		->setRequired(true)
		->addValidator('Digits')
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators);

	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Type: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setValue('quote')
		->addMultiOptions(array(NULL => 'Choose type', 'quote' => 'Quote', 
		'announcement' => 'Announcement'))
		->setDecorators($decorators);

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton');
	$submit->removeDecorator('DtDdWrapper');
	$submit->removeDecorator('HtmlTag');
	
	$this->addElements(array(
	$quote,	$quotedBy, $valid,
	$expire, $type, $submit));
	
	$this->addDisplayGroup(array(
	'quote', 'quotedBy', 'status',
	'expire', 'type', 'submit'),
	 'details');
	$this->details->removeDecorator('HtmlTag');
	$this->details->removeDecorator('DtDdWrapper');
	      
	
	}
}