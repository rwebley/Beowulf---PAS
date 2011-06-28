<?php
/** Form for manipulating find of note reasons
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class FindNoteReasonForm extends Pas_Form {
	
	public function __construct($options = null) {
	
	parent::__construct($options);
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	$this->setName('FindNoteReason');
	
	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Title for reason: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilters(array('BasicHtml','EmptyParagraph', 'StringTrim'))
	->addErrorMessage('Please enter a valid title for the term!')
	->setDecorators($decorators);
	
	$termdesc = new Zend_Form_Element_Textarea('termdesc');
	$termdesc->setLabel('Description of reason: ')
	->setRequired(false)
	->addFilters(array('BasicHtml','EmptyParagraph', 'StringTrim'))
	->setAttrib('rows',10)
	->setAttrib('cols',80);
	
	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
	->setRequired(true)
	->addValidator('NotEmpty','Digits')
	->setDecorators($decorators);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');
	
	$this->addElements(array(
	$term,	$termdesc,	$valid,
	$submit));
	
	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Find of note reasoning details: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	}
}