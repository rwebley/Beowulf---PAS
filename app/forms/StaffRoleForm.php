<?php
/** Form for setting up types of staff role
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class StaffRoleForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);
       
	$this->setName('staffroles');

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
		->setAttrib('size',60)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Choose title for the role.')
		->addValidator('Alnum',true, array('allowWhiteSpace' => true))
		->setDecorators($decorators);
	
	$description = new Zend_Form_Element_Textarea('description');
	$description->setLabel('Role description: ')
	->setRequired(true)
	->setAttribs(array('rows' => 10, 'cols' => 80))
	->addDecorator('HtmlTag',array('tag' => 'li'))
	->addValidators(array('BasicHtml', 'WordChars', 'EmptyParagraph', 'StringTrim'));
	
	
	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
		->setDecorators($decorators)
		->removeDecorator('HtmlTag');
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	
	$this->addElements(array( $role, $description, $valid, $hash, $submit));
	
	$this->addDisplayGroup(array('role', 'description', 'valid', 'submit'), 'details')
	     ->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'div'))
	     ->removeDecorator('HtmlTag');

	$this->details->setLegend('Activity details: ');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->details->addDecorators(array(
	    'FormElements',
	    array('HtmlTag', array('tag' => 'ul'))
	));
      

}
}