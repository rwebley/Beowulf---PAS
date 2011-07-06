<?php
/** Form for adding and editing Reece period data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class VolunteerForm extends Pas_Form {

public function __construct($options = null) {
	$projecttypes = new ProjectTypes();
	$projectype_list = $projecttypes->getTypes();
	
	parent::__construct($options);
		  
	$this->setName('activity');
	
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
	            array('HtmlTag', array('tag' => 'li')),
			    );
	
	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Project title: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Choose title for the project.')
		->addValidator('Alnum', false, array('allowWhiteSpace' => true))
		->setDecorators($decorators);
	
	$description = new Zend_Form_Element_Textarea('description');
	$description->setLabel('Short description of project: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->addFilters(array('BasicHtml', 'EmptyParagraph', 'StringTrim'))
		->addDecorator('HtmlTag',array('tag' => 'li'));
		
	$length = new Zend_Form_Element_Text('length');
	$length->setLabel('Length of project: ')
		->setAttrib('size',12)
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a duration for this project in months')
		->addValidator('Digits')
		->setDecorators($decorators);
	
	$managedBy = new Zend_Form_Element_Text('managedBy');
	$managedBy->setLabel('Managed by: ')
		->setAttrib('size',12)
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a manager for this project.')
		->setDecorators($decorators);

	$suitableFor = new Zend_Form_Element_Select('suitableFor');
	$suitableFor->setLabel('Suitable for: ')
		->addMultiOptions(array(NULL => NULL,'Choose type of research' => $projectype_list))
		->setRequired(true)
		->addValidator('InArray', false, array($projectype_list))
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter suitability for this task.')
		->setDecorators($decorators);

	$location = new Zend_Form_Element_Text('location');
	$location->setLabel('Where would this be located?: ')
		->setAttrib('size',12)
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a location for the task.')
		->setDecorators($decorators);

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Publish this task? ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators)
		->removeDecorator('HtmlTag');

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setDecorators($decorators2);

	$this->addElements(array(
	$title, $description, $length,
	$valid, $managedBy, $suitableFor,
	$location, $submit));

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array(
	'title', 'description', 'length',
	'location', 'suitableFor', 'managedBy',
	'valid','submit'), 'details')
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