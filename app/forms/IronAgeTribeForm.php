<?php
/** Form for editing and creating Iron Age tribal data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeTribeForm extends Pas_Form {
	
public function __construct($options = null) {
	
	parent::__construct($options);

	$this->setName('ironagetribes');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$tribe = new Zend_Form_Element_Text('tribe');
	$tribe->setLabel('Tribe name: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('You must enter a name for the tribe.')
	->addValidator('Alnum',false, array('allowWhiteSpace' => true))
	->setDecorators($decorators);

	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Description of the tribe: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper');

	$this->addElements(array(
	$tribe,	$description, $submit)
	);

	$this->addDisplayGroup(array('tribe', 'description', 'submit'), 'details')
	->removeDecorator('HtmlTag');;
	$this->details->addDecorators(array('FormElements', array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	}
}