<?php

class ResearchForm extends Pas_Form
{

public function __construct($options = null)
{
$projecttypes = new ProjectTypes();
$projectype_list = $projecttypes->getTypes();

parent::__construct($options);
       
	  
$this->setName('research');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
			
$investigator = new Zend_Form_Element_Text('investigator');
$investigator->setLabel('Principal work conducted by: ')
->setRequired(true)
->setAttrib('size',60)
->addErrorMessage('You must enter a lead for this project.')
->setDecorators($decorators);


$level = new Zend_Form_Element_Select('level');
$level->setLabel('Level of research: ')
	  ->setRequired(true)
	  ->addMultiOptions(array(NULL => NULL,'Choose type of research' => $projectype_list))
	  ->setDecorators($decorators);

$title = new Zend_Form_Element_Text('title');
$title->setLabel('Project title: ')
->setRequired(true)
->setAttrib('size',60)
->addErrorMessage('Choose title for the project.')
->setDecorators($decorators);


$description = $this->addElement('RTE', 'description',array('label' => 'Short description of project: ')); 
$description = $this->getElement('description')->setRequired(false)
->addFilter('stringTrim')
->setAttribs(array('cols' => 80, 'rows' => 10))
->addFilter('HtmlBody')
->addDecorator('HtmlTag',array('tag' => 'li'));



$startDate = new Zend_Form_Element_Text('startDate');
$startDate->setLabel('Start date of project')
->setAttrib('size',12)
->setRequired(false)
->addErrorMessage('You must enter a start date for this project')
->setDecorators($decorators);

$endDate = new Zend_Form_Element_Text('endDate');
$endDate->setLabel('End date of project')
->setAttrib('size',12)
->setRequired(false)
->addErrorMessage('You must enter an end date for this project')
->setDecorators($decorators);


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Make public: ')
->setRequired(true)
->setDecorators($decorators);

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
				->removeDecorator('label')
				->setAttrib('class','large')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper');


$this->addElements(array(
$title,
$description,
$level,
$startDate,
$endDate,
$valid,
$investigator,$submit));

$this->addDisplayGroup(array('title','investigator','level','description','startDate','endDate','valid',), 'details')->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');

$this->addDisplayGroup(array('submit'), 'submit');


}
}