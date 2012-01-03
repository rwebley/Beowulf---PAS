<?php
/** Form for setting up and editing period specific data.
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PeriodForm extends Pas_Form {
	
public function __construct($options = null) {
	
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$this->setName('period');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Period name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim','StringToUpper'))
		->addValidator('Alpha',false, array('allowWhiteSpace' => true))
		->setAttrib('size',60)
		->addErrorMessage('You must enter a period name')
		->setDecorators($decorators);

	$fromdate = new Zend_Form_Element_Text('fromdate');
	$fromdate->setLabel('Date period starts: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidators('Digits')
		->addErrorMessage('You must enter a start date')
		->setDecorators($decorators);

	$todate = new Zend_Form_Element_Text('todate');
	$todate->setLabel('Date period ends: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidators('Digits')
		->addErrorMessage('You must enter an end date')
		->setDecorators($decorators);

	$notes = new Pas_Form_Element_RTE('notes');
	$notes->setLabel('Period notes: ')
		->setRequired(false)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Period is currently in use: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int')
		->addErrorMessage('You must enter a status')
		->setDecorators($decorators);

	$parent = new Zend_Form_Element_Select('parent');
	$parent->setLabel('Period belongs to: ')
		->setRequired(false)
		->addMultiOptions(array(NULL => NULL,'Choose period to' => $period_options))
		->addValidator('InArray', false, array(array_keys($period_options)))
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int')
		->setDecorators($decorators);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$this->addElements(array(
		$term, $fromdate, $todate,
		$valid,	$notes, $parent,
		$submit));

	$this->addDisplayGroup(array(
		'term', 'fromdate', 'todate',
		'parent', 'notes', 'valid'), 
	'details')
	->removeDecorator('HtmlTag');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->details->addDecorators(array('FormElements',array('HtmlTag', 
            array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Period details: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	}
}