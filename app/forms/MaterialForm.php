<?php
/** Form for editing and creating materials
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MaterialForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	
	$this->setName('Material');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Material type name: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilter(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a title for this material type')
	->setDecorators($decorators);

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of material type: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
	->setRequired(true)
	->setDecorators($decorators)
	->addValidator('Digits');

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');
	
	$this->addElements(array(
	$term, $termdesc, $valid,
	$submit));

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details')
	->removeDecorator('HtmlTag');
	
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Material details: ');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
}
}