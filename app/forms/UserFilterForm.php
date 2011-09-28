<?php

/** Form for filtering user names in the admin interfaces
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class UserFilterForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);
	
	$this->setMethod('post');  
	
	$this->setName('filterusers');
	
	$decorator =  array('TableDecInput');

	$username = new Zend_Form_Element_Text('username');
	$username->setLabel('Filter by username')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 15)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$name = new Zend_Form_Element_Text('fullname');
	$name->setLabel('Filter by name')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$role = new Zend_Form_Element_Select('role');
	$role->setLabel('Filter by role')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('StringLength', false, array(1,200))
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addMultiOptions(array(NULL => NULL,'Choose role' => array(
		'admin' => 'Admin', 'hero' => 'HER officer', 'flos' => 'Finds Liaison',
		'member' => 'Member', 'fa' => 'Finds Adviser', 'research' => 'Researcher')))
		->setDisableTranslator(true);

	$login = new ZendX_JQuery_Form_Element_DatePicker('lastLogin');
	$login->setLabel('Filter last login: ')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$visits = new Zend_Form_Element_Text('visits');
	$visits->setLabel('Filter by visit count: ')
		->setRequired(false)
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttribs(array('size' => 5, 'maxlength' => '6'))
		->addValidator('Int')
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setLabel('Filter')
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
		
	$this->addElements(array(
	$username, $name, $role,
	$login, $visits, $submit)
	);
	  
	}
}