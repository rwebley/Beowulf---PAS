<?php
/**
* Form for adding mints to rulers
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddMintToRulerForm extends Pas_Form
{
public function __construct($options = null) {

	parent::__construct($options);
	$this->setName('MintToRuler');
	
	$mint = new Zend_Form_Element_Select('mint_id');
	$mint->setLabel('Active mint: ')
	->setRequired(true)
	->addValidator('Int')
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->setAttribs(array('class'=> 'textInput'));
	
	$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
	$ruler_id ->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->addValidator('Int')
	->addFilter('StringTrim');


	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Add mint')
	->setAttribs(array('class'=> 'large'));
	
	$this->addElements(array($mint,$ruler_id,$submit));
	
	$this->addDisplayGroup(array('mint_id'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Add an active Mint');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');

	}
}