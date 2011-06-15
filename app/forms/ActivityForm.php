<?php
/**
* Form for adding and editing primary activities for people
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ActivityForm extends Pas_Form {

	public function __construct($options = null) {

	parent::__construct($options);
	  
	$this->setName('activity');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
			

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Activity title: ')
		->setRequired(true)
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->addErrorMessage('Choose title for the activity.')
		->setAttrib('size',70)
		->setDecorators($decorators);

	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Activity description: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilter('StringTrim')
		->addFilter('BasicHtml')
		->addFilter('EmptyParagraph')
		->addFilter('WordChars')
		->addDecorator('HtmlTag',array('tag' => 'li'));


	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
		->setRequired(false)
		->setDecorators($decorators);
	
		//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$this->addElements( array($term, $termdesc, $valid, $submit) );

	$this->addDisplayGroup(array('term','termdesc','valid','submit'), 'details')
		->removeDecorator('HtmlTag');
		
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Primary activity details: ');
      

}
}