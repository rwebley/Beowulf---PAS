<?php
/** Form for manipulating Roman reverse type information 
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class ReverseTypeForm extends Pas_Form {

public function __construct($options = null) {
	
	$reeces = new Reeces();
	$reeces_options = $reeces->getRevTypes();
	
	parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
			
	$this->setName('reversetype');

	$type = new Zend_Form_Element_Text('type');
	$type->setLabel('Reverse type inscription: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter an inscription.')
		->setDecorators($decorators)
		->setAttrib('size',70);

	$translation = new Zend_Form_Element_Text('translation');
	$translation->setLabel('Translation: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a translation.')
		->setDecorators($decorators)
		->setAttrib('size',70);

	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Description: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a translation.')
		->setDecorators($decorators)
		->setAttrib('size',70);

	$gendate = new Zend_Form_Element_Text('gendate');
	$gendate->setLabel('General date for reverse type: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->addErrorMessage('You must enter a general date for this reverse type.')
		->setDecorators($decorators)
		->setAttrib('size',30);

	$reeceID = new Zend_Form_Element_Select('reeceID');
	$reeceID->setLabel('Reece period: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL,'Choose reason' => $reeces_options))
		->addValidator('InArray', false, array(array_keys($reeces_options)))
		->setDecorators($decorators);

	$common = new Zend_Form_Element_Radio('common');
	$common->setLabel('Is this reverse type commonly found: ')
		->setRequired(false)
		->addMultiOptions(array('1' => 'Yes','2' => 'No'))
		->setValue(1)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->setOptions(array('separator' => ''))
		->setDecorators($decorators);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);

	$this->addElements(array(
	$type, $gendate, $description,
	$translation, $reeceID, $common,
	$submit, $hash));

	$this->addDisplayGroup(array(
	'type', 'translation', 'description',
	'gendate', 'reeceID', 'common',
	'submit'), 'details');
	$this->details->setLegend('Reverse type details: ');
	$this->details->addDecorators(array(
	    'FormElements',
	    array('HtmlTag', array('tag' => 'ul'))
	));
	$this->details->setLegend('Issuer or ruler details: ');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	}
}