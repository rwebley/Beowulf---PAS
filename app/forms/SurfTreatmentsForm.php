<?php
/** Form for setting up and editing types of surface treatments
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class SurfTreatmentsForm extends Pas_Form
{
public function __construct($options = null)
{
	
	parent::__construct($options);
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	$this->setName('surfmethods');
	
	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Decoration style term: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alpha', true, array('allowWhiteSpace' => true))
		->addErrorMessage('Please enter a valid title for this surface treatment')
		->setDecorators($decorators);
	
	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of decoration style: ')
		->setRequired(true)
		->setAttribs(array('rows' => 10, 'cols' => 80))
		->addFilter(array('BasicHtml', 'EmptyParagraph', 'StringTrim', 'WordChars'))
		->addErrorMessage('You must enter a description for this surface treatment');
	
	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Termis currently in use: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must set a status for this treatment term')
		->setDecorators($decorators);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');
	
	$this->addElements(array($term, $termdesc, $valid, $submit, $hash));
	
	$this->addDisplayGroup(array('term','termdesc','valid'), 'details')->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->details->setLegend('Surface treatment details: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	}
}