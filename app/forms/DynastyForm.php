<?php
/** Form for entering data about Roman dynasties
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class DynastyForm extends Pas_Form
{
public function __construct($options = null)
{
	parent::__construct($options);
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	      
	$this->setName('dynasticDetails');

	$dynasty = new Zend_Form_Element_Text('dynasty');
	$dynasty->setLabel('Dynastic name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('NotEmpty')
	->addErrorMessage('Come on it\'s not that hard, enter a name for this dynasty!')
	->setDecorators($decorators);


	$date_from = new Zend_Form_Element_Text('date_from');
	$date_from->setLabel('Issued coins from: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->addErrorMessage('You must enter a date for the start of reign')
	->setDecorators($decorators);

	$date_to = new Zend_Form_Element_Text('date_to');
	$date_to->setLabel('Issued coins until: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->addErrorMessage('You must enter a date for the end of reign')
	->setDecorators($decorators);

	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Description: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('BasicHtml', 'EmptyParagraph', 'WordChars'))
	->addErrorMessage('You must enter a description')
	->addDecorator('Errors',array('placement' => 'append','class'=>'error','tag' => 'li'));


	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this dynasty valid?')
	->setDecorators($decorators)
	->addFilters(array('StripTags','StringTrim'));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
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
	$dynasty, $date_from, $date_to,
	$description, $valid, $submit));
	$this->removeDecorator('HtmlTag');
	$this->addDisplayGroup(array('dynasty','date_from','date_to','description','valid','submit'), 'details');
	$this->details->addDecorators(array(
	    'FormElements',
	    array('HtmlTag', array('tag' => 'ul'))
	));
	
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');

	}
}