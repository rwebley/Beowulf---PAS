<?php

class RejectUpgradeForm extends Pas_Form
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
  
$this->setName('acceptupgrades');
ZendX_JQuery::enableForm($this);
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );


$researchOutline = new Zend_Form_Element_Textarea('researchOutline');
$researchOutline->setLabel('Research outline: ')
->setRequired(true)
->addFilter('StringTrim')
->addFilter('BasicHtml')
->setAttribs(array('rows' => 10))
->addErrorMessage('Outline must be present.')
;
$message = new Zend_Form_Element_Textarea('messageToUser');
$message->setLabel('Message to user: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttribs(array('rows' => 10))
->addFilter('BasicHtml')
->addErrorMessage('You must enter a message for the user to know they have been approved.')
;

$reference = new Zend_Form_Element_Text('reference');
$reference->setLabel('Referee\'s name: ')
->setAttrib('size',30)
->setDecorators($decorators)
//->setAttrib('disabled',false)
;


$referenceEmail = new Zend_Form_Element_Text('referenceEmail');
$referenceEmail->setLabel('Referee\'s email address: ')
->setAttrib('size',30)
->setDecorators($decorators)
//->setAttrib('disabled',false)
;


$fullname = new Zend_Form_Element_Text('fullname');
$fullname->setLabel('Fullname: ')
->setAttrib('size',30)
->setDecorators($decorators);


$email = $this->addElement('text', 'email',array('label' => 'Email Address', 'size' => '30'))->email;
$email->addValidator('emailAddress')
			  ->setRequired(true)
			  ->addErrorMessage('Please enter a valid address!');
$email->setDecorators($decorators);

$already = new Zend_Form_Element_Radio('already');
$already->setLabel('Is your topic already listed on our research register?: ')
->addMultiOptions(array( 1 => 'Yes it is',0 => 'No it isn\'t' ))
->setRequired(true)->setOptions(array('separator' => ''))
->setDecorators($decorators);

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag')
->setLabel('Reject application');

$this->addElements(array(
$researchOutline,
$fullname,
$reference,
$referenceEmail,
$submit,
$message));

$this->addDisplayGroup(array('fullname','email','messageToUser','reference','referenceEmail','researchOutline'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Details: ');
$this->addDisplayGroup(array('submit'), 'submit');

}
}