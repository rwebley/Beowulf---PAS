<?php
/**
* Form for adding a type of coin to a specific ruler
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddTypeToRulerForm extends Pas_Form
{
public function __construct($options = null)
{
parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'div')),
      );	  

	$this->setAttrib('accept-charset', 'UTF-8');
	$this->setName('TypeToRuler');

	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Medieval coin type: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttribs(array('class'=> 'textInput'))
	->setDecorators($decorators);

	$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
	$ruler_id->removeDecorator('DtDdWrapper')
	->setRequired(true)
	->addValidator('Int')
	->removeDecorator('HtmlTag')
	->removeDecorator('Label');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttribs(array('class'=> 'large'));
	
	
	$this->addElements(array($type,$ruler_id,$submit))
	->setLegend('Add a new type')
	->setMethod('post')
	->addDecorators(array('FieldSet',
	'form',array('HtmlTag', array('tag' => 'div'))
	));
	
	}
}