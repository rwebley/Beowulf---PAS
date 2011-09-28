<?php
/** Form for suggesting research topics
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SuggestedForm extends Pas_Form {

public function __construct($options = null) {
	
	$projecttypes = new ProjectTypes();
	$projectype_list = $projecttypes->getTypes();
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

parent::__construct($options);
       
	$this->setName('suggested');
	
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
		->addMultiOptions(array('Please choose a level' => NULL,
		'Research levels' => $projectype_list))
		->addValidator('InArray', false, array(array_keys($projectype_list)))
		->addFilters(array('StringTrim', 'StripTags'))		
		->setDecorators($decorators);
	
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Broad research period: ')
		->setRequired(true)
		->addMultiOptions(array('Please choose a period' => NULL, 
		'Periods available' => $period_options))
		->addValidator('InArray', false, array(array_keys($period_options)))
		->addFilters(array('StringTrim', 'StripTags'))
		->setDecorators($decorators);
	
	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Project title: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StringTrim', 'StripTags'))
		->addErrorMessage('Choose title for the project.')
		->setDecorators($decorators);
	
	$description = $this->addElement('Textarea', 'description',array(
	'label' => 'Short description of project: ')); 
	$description = $this->getElement('description')
		->setRequired(false)
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'))
		->setAttribs(array('cols' => 80, 'rows' => 10))
		->addDecorator('HtmlTag',array('tag' => 'li'));
	
	$valid = new Zend_Form_Element_Checkbox('taken');
	$valid->setLabel('Is the topic taken: ')
		->setRequired(true)
		->setDecorators($decorators)#
		->addValidator('Int');

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
		
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->setAttrib('class','large')
		->removeDecorator('DtDdWrapper');
	
	$this->addElements(array(
	$title, $level, $period,
	$description, $valid, $submit,
	$hash));

	$this->addDisplayGroup(array('title','level','period','description','taken'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->addDisplayGroup(array('submit'),'submit');

	}
}