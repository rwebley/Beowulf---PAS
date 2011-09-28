<?php
/** Form for setting up and editing map grid reference origins.
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class OriginForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);
       
	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
	
	$this->setName('origingridref');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Grid reference origin term: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid grid reference origin term!')
		->setDecorators($decorators);

	$termdesc = new Zend_Form_Element_Textarea('termdesc');
	$termdesc->setLabel('Description of term: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim','WordChars','BasicHtml', 'EmptyParagraph'))
		->setAttrib('rows',10)
		->setAttrib('cols',80)
		->addErrorMessage('You must enter a descriptive term or David Williams will eat you.')
		->addDecorator('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
		->setRequired(true)
		->setDecorators($decorators)
		->addValidator('Int')
		->addErrorMessage('You must set the status of this term');

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');
	
	$this->addElements(array(
	$term, 	$termdesc,	$valid,
	$submit));
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array('term','termdesc','valid'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Ascribed culture');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	}
}