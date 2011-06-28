<?php
/** Form for manipulating treasure valuation data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Sort out the currency validator for £
*/
class FinalValuationForm extends Pas_Form {

	public function __construct($options = null) {


	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);
	
	$this->setAttrib('accept-charset', 'UTF-8');
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	
	$this->setName('finalvaluation');


	$value = new Zend_Form_Element_Text('value');
	$value->setLabel('Estimated market value: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Currency')
	->setDecorators($decorators);

	$comments  = new Pas_Form_Element_RTE('comments');
	$comments->setLabel('Valuation comments: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StripTags','StringTrim', 'BasicHtml','EmptyParagraph'));

	$dateOfValuation = new ZendX_JQuery_Form_Element_DatePicker('dateOfValuation');
	$dateOfValuation->setLabel('Valuation provided on: ')
	->setRequired(true)
	->setJQueryParam('dateFormat', 'yy-mm-dd')
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Date')
	->addErrorMessage('You must enter a chase date')
	->setAttrib('size', 20)
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
	->removeDecorator('DtDdWrapper');

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);

	$this->addElements(array(
	$value,	$dateOfValuation, $comments, $submit
	));
	
	$this->addDisplayGroup(array(
	'value',
	'dateOfValuation',
	'comments'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	}
}