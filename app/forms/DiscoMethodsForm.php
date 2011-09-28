<?php

/** Form for entering data about discovery methods
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class DiscoMethodsForm extends Pas_Form
{
public function __construct($options = null)
{
parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
      
	$this->setName('discoverymethods');

	$method = new Zend_Form_Element_Text('method');
	$method->setLabel('Discovery method term: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',50)
	->addValidator('NotEmpty')
	->addErrorMessage('You must enter a valid term')
	->setDecorators($decorators);

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of method: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('BasicHtml', 'EmptyParagraph', 'WordChars'))
	->addErrorMessage('You must enter a description for this term');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('You must set a status for this term')
	->setDecorators($decorators);;

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');

	$this->addElements(array(
	$method, 
	$termdesc,
	$valid,
	$submit));

	$this->addDisplayGroup(array('method','termdesc','valid'), 'details');
	$this->details->setLegend('Discovery methods');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
}

}