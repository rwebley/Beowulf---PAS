<?php

class VolunteerForm extends Pas_Form
{

public function __construct($options = null)
{
$projecttypes = new ProjectTypes();
$projectype_list = $projecttypes->getTypes();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
       
	  
$this->setName('activity');
$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 


$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
			
$decorators2 = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            //array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );


$title = new Zend_Form_Element_Text('title');
$title->setLabel('Project title: ')
->setRequired(true)
->setAttrib('size',60)
->addErrorMessage('Choose title for the project.')
->setDecorators($decorators);

$description = new Zend_Form_Element_Textarea('description');
$description->setLabel('Short description of project: ')
->setRequired(true)
->setAttrib('rows',10)
->setAttrib('cols',40)
->addDecorator('HtmlTag',array('tag' => 'li'));

$length = new Zend_Form_Element_Text('length');
$length->setLabel('Length of project: ')
->setAttrib('size',12)
->setRequired(true)
->addErrorMessage('You must enter a duration for this project')
->setDecorators($decorators);

$managedBy = new Zend_Form_Element_Text('managedBy');
$managedBy->setLabel('Managed by: ')
->setAttrib('size',12)
->setRequired(true)
->addErrorMessage('You must enter a manager for this project.')
->setDecorators($decorators);

$suitableFor = new Zend_Form_Element_Select('suitableFor');
$suitableFor->setLabel('Suitable for: ')
		->addMultiOptions(array(NULL => NULL,'Choose type of research' => $projectype_list))
		->setRequired(true)
		->addErrorMessage('You must enter suitability for this task.')
		->setDecorators($decorators);

$location = new Zend_Form_Element_Text('location');
$location->setLabel('Where would this be located?: ')
->setAttrib('size',12)
->setRequired(true)
->addErrorMessage('You must enter a location for the task.')
->setDecorators($decorators);


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Publish this task? ')
->setRequired(true)
->setDecorators($decorators)
->removeDecorator('HtmlTag');

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
			  ->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setDecorators($decorators2);


$this->addElements(array(
$title,
$description,
$length,
$valid,
$managedBy,
$suitableFor,
$location,
$submit));

$this->addDisplayGroup(array('title','description','length','location','suitableFor','managedBy','valid','submit'), 'details')
     ->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'div'))->removeDecorator('HtmlTag');
$this->details->setLegend('Activity details: ');
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->details->addDecorators(array(
    'FormElements',
    array('HtmlTag', array('tag' => 'ul'))
));
      

}
}