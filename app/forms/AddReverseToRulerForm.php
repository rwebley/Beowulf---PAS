<?php
/**
* Form for adding reverse types to rulers
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddReverseToRulerForm extends Pas_Form
{
public function __construct($options = null) {

	parent::__construct($options);
	
	$this->setName('MintToRuler');

	$reverseID = new Zend_Form_Element_Select('reverseID');
	$reverseID->setLabel('Reverse type: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->setAttribs(array('class'=> 'textInput'));
	
	$rulerID = new Zend_Form_Element_Hidden('rulerID');
	$rulerID ->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->addValidator('Int');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Add a reverse type for this ruler')
	->setAttribs(array('class'=> 'large'));
	
	$this->addElements(array($reverseID,$rulerID,$submit));
	$this->addDisplayGroup(array('reverseID'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Add an active Mint');

	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	$this->details->setLegend('Add an active reverse type');
	
	}
}