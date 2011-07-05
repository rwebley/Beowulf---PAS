<?php
/** Form for setting up and editing provisional valuations
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ProvisionalValuationForm extends Pas_Form {

public function __construct($options = null) {
	
	$curators = new Peoples();
	$assigned = $curators->getValuers();

	ZendX_JQuery::enableForm($this);

	parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$this->setName('provisionalvaluations');


	$valuerID = new Zend_Form_Element_Select('valuerID');
	$valuerID->setLabel('Valuation provided by: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,25))
		->addValidator('InArray', false, array(array_keys($assigned)))
		->addMultiOptions($assigned)
		->setDecorators($decorators);

	$value = new Zend_Form_Element_Text('value');
	$value->setLabel('Estimated market value: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Float')
		->setDecorators($decorators);
	
	$comments  = new Pas_Form_Element_RTE('comments');
	$comments->setLabel('Valuation comments: ')
		->setRequired(false)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	$dateOfValuation = new ZendX_JQuery_Form_Element_DatePicker('dateOfValuation');
	$dateOfValuation->setLabel('Valuation provided on: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addValidator('Date')
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
		->removeDecorator('DtDdWrapper');
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');
	
	
	$this->addElements(array(
	$valuerID, $value, $dateOfValuation,
	$comments, $submit
	));
	
	$this->addDisplayGroup(array(
	'valuerID', 'value', 'dateOfValuation',
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