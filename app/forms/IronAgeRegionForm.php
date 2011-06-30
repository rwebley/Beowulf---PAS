<?php
/** Form for editing and creating Iron Age regional data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class IronAgeRegionForm extends Pas_Form {
	
	public function __construct($options = null) {
	$tribes = new Tribes();
	$tribes_options = $tribes->getTribes();

	parent::__construct($options);

	$this->setAttrib('accept-charset', 'UTF-8');

	$this->setName('ironageregion');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$area = new Zend_Form_Element_Text('area');
	$area->setLabel('Area: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('You must enter an area name.')
	->setDecorators($decorators);

	$region = new Zend_Form_Element_Text('region');
	$region->setLabel('Region name: ')
	->setRequired(true)
	->setAttrib('size',60)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('You must enter a region name')
	->setDecorators($decorators);
	
	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Description: ')
	->setRequired(true)
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->setAttrib('ToolbarSet','Finds')
	->setAttrib('Height',250)
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	$tribe = new Zend_Form_Element_Select('tribe');
	$tribe->setLabel('Associated tribe: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultioptions(array(NULL => NULL,'Choose a tribe' => $tribes_options))
	->addValidator('inArray', false, array(array_keys($tribes_options)))
	->addErrorMessage('You must enter a tribe from the dropdown.')
	->addValidator('Int')
	->addErrorMessage('You must enter a tribe.')
	->setDecorators($decorators);
	
	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this area valid: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Digits')
	->addErrorMessage('You must set the validity')
	->setDecorators($decorators);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->removeDecorator('label')
	              ->removeDecorator('HtmlTag')
				  ->removeDecorator('DtDdWrapper');
	
	$this->addElements(array(
	$area, $region, $tribe,
	$valid, $description, $submit)
	);
	
	$this->addDisplayGroup(array(
	'area', 'region', 'tribe',
	'description','valid','submit'),
	'details')
	->removeDecorator('HtmlTag');
	
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	}
}
