<?php

/** Form for manipulating emperor data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class EmperorForm extends Pas_Form {
	
public function __construct($options = null) {

$reeces = new Reeces();
$reeces_options = $reeces->getOptions();

$rulers = new Rulers();
$rulers_options = $rulers->getOptions();

$dynasties = new Dynasties();
$dynasties_options = $dynasties->getOptions();


parent::__construct($options);

 $decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
      
	$this->setName('EmperorDetails');

	$name = new Zend_Form_Element_Text('name');
	$name->setLabel('Emperor\'s name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->addErrorMessage('Come on it\'s not that hard, enter a firstname!')
	->setDecorators($decorators);

	$reeceID = new Zend_Form_Element_Select('reeceID');
	$reeceID->setLabel('Reece period assigned: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => NULL,'Choose a Reece period' => $reeces_options))
	->addValidator('InArray', false, array(array_keys($reeces_options)))
	->addValidator('Int')
	->setDecorators($decorators);

	$pasID = new Zend_Form_Element_Select('pasID');
	$pasID->setLabel('Database ID: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('InArray', false, array(array_keys($rulers_options)))
	->addMultiOptions(array(NULL => NULL, 'Choose a database id' => $rulers_options))
	->addValidator('Int')
	->addErrorMessage('You must assign the bio to an existing entry')
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

	$biography = new Pas_Form_Element_RTE('biography');
	$biography->setLabel('Biography: ')
	->setRequired(true)
	->addFilters('StringTrim','WordChars','BasicHtml','EmptyParagraph')
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addErrorMessage('You must enter a biography')
	->addDecorator('Errors',array('placement' => 'append','class'=>'error','tag' => 'li'));


	$dynasty = new Zend_Form_Element_Select('dynasty');
	$dynasty->setLabel('Dynastic grouping: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('InArray', false, array(array_keys($dynasties_options)))
	->addMultiOptions(array(NULL => NULL, 'Choose a dynasty' => $dynasties_options))
	->addErrorMessage('You must select a dynastic grouping')
	->setDecorators($decorators);


	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);

	$this->addElements(array(
	$name, 
	$reeceID,
	$pasID,
	$date_from,
	$date_to,
	$biography,
	$dynasty,
	$submit));

	$this->addDisplayGroup(array('name','reeceID','pasID','date_from','date_to','biography','dynasty','submit'), 'details');
	$this->details->addDecorators(array( array('HtmlTag', array('tag' => 'ul'))
	));
	
	$this->details->removeDecorator('HtmlTag');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');

}
}