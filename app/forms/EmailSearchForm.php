<?php
/** Form for searching via email name
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class EmailSearchForm extends Pas_Form
{

public function __construct($options = null)
{

parent::__construct($options);

		$roles = new Roles();
		$role_options = $roles->getRoles();
		$inst = new Institutions();
		$inst_options = $inst->getInsts();       
		$projecttypes = new ProjectTypes();
		$projectype_list = $projecttypes->getTypes();
  
	$this->setName('emailsearch');
	ZendX_JQuery::enableForm($this);
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );


	$message = new Zend_Form_Element_Textarea('messageToUser');
	$message->setLabel('Message to user: ')
	->setRequired(true)
	->addFilters(array('StringTrim','WordChars','BasicHtml','EmptyParagraph'))
	->setAttribs(array('rows' => 10))
	->addFilter('BasicHtml')
	->addErrorMessage('You must enter a message to your recipient.');

	$fullname = new Zend_Form_Element_Text('fullname');
	$fullname->setLabel('Send this to: ')
	->addFilters(array('StringTrim','StripTags'))
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->setAttrib('size',30)
	->setDecorators($decorators);

	$email = $this->addElement('text', 'email',array('label' => 'Their email Address', 'size' => '30'))->email;
	$email->addValidator('EmailAddress')
	->addFilters(array('StringTrim','StripTags','StringToLower'))
	->setRequired(true)
	->addErrorMessage('Please enter a valid address!');
	$email->setDecorators($decorators);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->setLabel('Send to a friend');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);

	$this->addElements(array(
	$fullname,
	$submit,
	$message));

	$this->addDisplayGroup(array('fullname','email','messageToUser'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Details: ');
	$this->addDisplayGroup(array('submit'), 'submit');

}
}