<?php
/** Form for entering data about decorative styles
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class DecStylesForm extends Pas_Form
{
public function __construct($options = null) {

parent::__construct($options);

 	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );      
	$this->setName('Decstyles');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Decoration style term: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('NotEmpty')
	->addErrorMessage('Please enter a valid title for this decorative style')
	->setDecorators($decorators);

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of decoration style: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilter('BasicHtml')
	->addFilter('EmptyParagraph')
	->addFilter('WordChars');
	
	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
	->setRequired(true)
	->setDecorators($decorators);

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');

	$this->addElements(array($term,	$termdesc, $valid,	$submit));
	
	$this->addDisplayGroup(array('term','termdesc','valid'), 'details')->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Decoration style details: ');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	}
}