<?php
/**
* Form for accepting an upgrade for a user's account
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License

*/
class AcceptUpgradeForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);
		$roles = new Roles();
		$role_options = $roles->getRoles();
		$inst = new Institutions();
		$inst_options = $inst->getInsts();       
		$projecttypes = new ProjectTypes();
		$projectype_list = $projecttypes->getTypes();
  
	$this->setName('acceptupgrades');

	ZendX_JQuery::enableForm($this);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
	
	$level = new Zend_Form_Element_Select('level');
	$level->setLabel('Level of research: ')
		->setRequired(true)
		->addMultiOptions(array(NULL => NULL,'Choose type of research' => $projectype_list))
		->setDecorators($decorators)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addErrorMessage('You must set the level of research');

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Project title: ')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->setAttrib('size',60)
		->addErrorMessage('This project needs a title.')
		->setDecorators($decorators);

	$researchOutline = new Pas_Form_Element_RTE('researchOutline');
	$researchOutline->setLabel('Research outline: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilter('StringTrim')
		->addFilter('BasicHtml')
		->addFilter('EmptyParagraph')
		->addFilter('WordChars')
		->addErrorMessage('Outline must be present.');
		
	$reference = new Zend_Form_Element_Text('reference');
	$reference->setLabel('Referee\'s name: ')
		->setAttrib('size',30)
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->setDecorators($decorators);

	$referenceEmail = new Zend_Form_Element_Text('referenceEmail');
	$referenceEmail->setLabel('Referee\'s email address: ')
		->setAttrib('size',30)
		->addValidator('EmailAddress')
		->addFilter('StringToLower')
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->setDecorators($decorators);

	$message = new Pas_Form_Element_RTE('message');
	$message->setLabel('Message to user: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilter('StringTrim')
		->addFilter('BasicHtml')
		->addFilter('EmptyParagraph')
		->addFilter('WordChars')
		->addErrorMessage('You must enter a message for the user to know they have been approved.');
		
	$fullname = new Zend_Form_Element_Text('fullname');
	$fullname->setLabel('Fullname: ')
		->setAttrib('size',30)
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->setDecorators($decorators);

	$institution = $this->addElement('select', 'institution',array('label' => 'Recording institution: '))->institution;
	$institution->setDecorators($decorators)
			->addMultiOptions(array(NULL => NULL, 'Choose institution' => $inst_options));
		
	$role = $this->addElement('select', 'role',array('label' => 'Site role: '))->role;
	$role->setDecorators($decorators);
	$role->addMultiOptions(array(NULL => NULL,'Choose role' => $role_options));
	$role->removeMultiOption('admin');

	$startDate = new ZendX_JQuery_Form_Element_DatePicker('startDate');
	$startDate->setLabel('Start date of project: ')
		->setAttrib('size',12)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->addValidator('Date')
		->setRequired(false)
		->addErrorMessage('You must enter a valid start date for this project');

	$endDate = new ZendX_JQuery_Form_Element_DatePicker('endDate');
	$endDate->setLabel('End date of project: ')
		->addValidator('Date')
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->setAttrib('size',12)
		->setRequired(false)
		->addErrorMessage('You must enter a valid end date for this project');

	$email = $this->addElement('text', 'email',array('label' => 'Email Address', 'size' => '30'))->email;
	$email->addValidator('emailAddress')
		->setRequired(true)
		->addFilter('StringToLower')
		->addErrorMessage('Please enter a valid address!')
		->setDecorators($decorators);

	$already = new Zend_Form_Element_Radio('already');
	$already->setLabel('Is your topic already listed on our research register?: ')
		->addMultiOptions(array( 1 => 'Yes it is', 0 => 'No it isn\'t' ))
		->setRequired(true)->setOptions(array('separator' => ''))
		->setDecorators($decorators);

	$insert = new Zend_Form_Element_Checkbox('insert');
	$insert->setLabel('Insert details into research register: ')
		->setCheckedValue(1)
		->setDecorators($decorators);


	$valid = new Zend_Form_Element_Radio('higherLevel');
	$valid->setLabel('Approve?: ')
		->addMultiOptions(array( 1 => 'Unauthorised', 0 => 'Authorised' ))
		->setRequired(true)->setOptions(array('separator' => ''))
		->setDecorators($decorators);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$this->addElements(array(
	$reference, $referenceEmail, $researchOutline,
	$startDate, $endDate, $fullname,
	$valid, $level, $title,
	$submit, $already, $insert, 
	$message));

	$this->addDisplayGroup(array(
	'fullname', 'username', 'email',
	'institution', 'level', 'role',
	'reference', 'referenceEmail', 'message',
	'researchOutline', 'title', 'startDate',
	'endDate', 'already', 'higherLevel',
	'insert'), 
	'details')
		->removeDecorator('HtmlTag');

	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Details: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	}
	
}