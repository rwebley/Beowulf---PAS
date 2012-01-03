<?php
/** Form for setting up system roles
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SystemRoleForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);
		  
	$this->setName('systemroles');
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
				
	
	$role = new Zend_Form_Element_Text('role');
	$role->setLabel('Staff role title: ')
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',60)
	->addErrorMessage('Choose title for the role.')
	->setDecorators($decorators);
	
	$description = new Zend_Form_Element_Textarea('description');
	$description->setLabel('Role description: ')
	->setRequired(true)
	->setAttribs(array('rows' => 10, 'cols' => 80))
	->addFilters(array('StringTrim','WordChars','BasicHtml','EmptyParagraph'))
	->addDecorator('HtmlTag',array('tag' => 'li'));
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');
	
	$this->addElements(array($hash, $role, $description, $submit));
	
	$this->addDisplayGroup(array('role','description','valid'), 'details')->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Activity details: ');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	
	}
}