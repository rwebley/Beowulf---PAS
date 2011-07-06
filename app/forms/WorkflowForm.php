<?php
/** Form for editing workflow stages
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class WorkflowForm extends Pas_Form {
public function __construct($options = null) {

	parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
	
	$this->setName('workflow');

	$workflowstage = new Zend_Form_Element_Text('workflowstage');
	$workflowstage->setLabel('Work flow stage title: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags', 'StringTrim'))
		->setDecorators($decorators)
		->addValidator('Alnum', false, array('allowWhiteSpace' => true));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Workflow stage is currently in use: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->setDecorators($decorators)
		->addValidator('Digits');
	
	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of workflow stage: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Basic')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');
	
	$this->addElements(array(
	$workflowstage, $valid, $termdesc,
	$submit));

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array('workflowstage','termdesc','valid'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('HER details: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');

	}
}