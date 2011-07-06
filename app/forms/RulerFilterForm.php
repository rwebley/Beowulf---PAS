<?php
/** Form for filtering rulers
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RulerFilterForm extends Pas_Form
{
public function __construct($options = null)
{
	parent::__construct($options);
	$this->setName('filterruler');
	
	$ruler = new Zend_Form_Element_Text('ruler');
	$ruler->setLabel('Filter by name')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->addErrorMessage('Come on it\'s not that hard, enter a title!')
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setLabel('Filter')
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper');
	
	$this->addElements(array(
	$ruler,	$submit, $hash));
	  
	}
}