<?php
/** Form for entering and editing research projects
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ResearchForm extends Pas_Form {

public function __construct($options = null) {
	
	$projecttypes = new ProjectTypes();
	$projectype_list = $projecttypes->getTypes();

	ZendX_JQuery::enableForm($this);
	
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
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a lead for this project.')
		->setDecorators($decorators)
		->addValidator('Alnum', false, array('allowWhiteSpace' => true));

	$level = new Zend_Form_Element_Select('level');
	$level->setLabel('Level of research: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose type of research' => $projectype_list))
		->addValidator('inArray', false, array(array_keys($projectype_list)))
		->setDecorators($decorators);

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Project title: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Choose title for the project.')
		->setDecorators($decorators)
		->addValidator('Alnum', false, array('allowWhiteSpace' => true));;

	$description = $this->addElement('RTE', 'description',array(
	'label' => 'Short description of project: ')); 
	$description = $this->getElement('description')->setRequired(false)
		->addFilter('stringTrim')
		->setAttribs(array('cols' => 80, 'rows' => 10))
		->addFilters(array('BasicHtml', 'StringTrim', 'EmptyParagraph'))
		->addDecorator('HtmlTag',array('tag' => 'li'));

	$startDate = new ZendX_JQuery_Form_Element_DatePicker('startDate');
	$startDate->setLabel('Start date of project')
		->setAttrib('size',20)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addValidator('Date')
		->setRequired(false)
		->addErrorMessage('You must enter a start date for this project')
		->setDecorators($decorators);

	$endDate = new ZendX_JQuery_Form_Element_DatePicker('endDate');
	$endDate->setLabel('End date of project')
		->setAttrib('size',20)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->setRequired(false)
		->addValidator('Date')
		->addErrorMessage('You must enter an end date for this project')
		->setDecorators($decorators);

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Make public: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits')
		->setDecorators($decorators);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
		->removeDecorator('label')
		->setAttrib('class','large')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
		
	$this->addElements(array(
		$title, $description, $level,
		$startDate, $endDate, $valid,
		$investigator, $submit, $hash
		));

	$this->addDisplayGroup(array(
		'title','investigator','level',
		'description','startDate','endDate',
		'valid',), 'details')
		->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->addDisplayGroup(array('submit'), 'submit');

	}
}
