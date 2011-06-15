<?php
/** Form for entering data about denominations
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class DenominationForm extends Pas_Form
{

public function __construct($options = null)
{
	//Get data to form select menu for periods
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();
	
	//Materials menu
	$materials = new Materials();
	$material_options = $materials->getMetals();

	parent::__construct($options);
	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$this->setName('denomination');
	 
	$denomination = new Zend_Form_Element_Text('denomination');
	$denomination->setLabel('Denomination name: ')
	->setRequired(true)
	->setAttrib('size',70)
	->addErrorMessage('Please enter a term.')
	->setDecorators($decorators);
	
	//Period from: Assigned via dropdown
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period assigned to: ')
	->setRequired(true)
	->addFilter(array('StripTags','StringTrim'))
	->addValidator('InArray', false, array(array_keys($period_options)))
	->addMultiOptions(array(NULL => NULL, 'Choose period from' => $period_options))
	->setDecorators($decorators)
	->addErrorMessage('You must enter a period for this denomination');
	
	//Primary material
	$material = new Zend_Form_Element_Select('material');
	$material->setLabel('Material: ')
	->setRequired(false)
	->addFilter(array('StripTags','StringTrim'))
	->addValidator('InArray', false, array(array_keys($material_options)))
	->addMultiOptions(array(NULL => NULL,'Choose material' => $material_options))
	->addErrorMessage('You must enter a material for this denomination.')
	->setDecorators($decorators);
	
	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Description: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilter('BasicHtml')
	->addFilter('EmptyParagraph')
	->addFilter('WordChars')
	->addErrorMessage('You must enter a description for this denomination.');
	
	$rarity = new Zend_Form_Element_Textarea('rarity');
	$rarity->setLabel('Rarity: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',70)
	->addFilter('BasicHtml')
	->addFilter('EmptyParagraph')
	->addFilter('WordChars');
	
	$weight = new Zend_Form_Element_Text('weight');
	$weight->setLabel('Weight: ')
	->setRequired(false)
	->addFilter(array('StripTags','StringTrim'))
	->setAttrib('size',5)
	->setDecorators($decorators);
	
	$diameter = new Zend_Form_Element_Text('diameter');
	$diameter->setLabel('Diameter: ')
	->setRequired(false)
	->setAttrib('size',5)
	->addFilter(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	$thickness = new Zend_Form_Element_Text('thickness');
	$thickness->setLabel('Thickness: ')
	->setRequired(false)
	->setAttrib('size',5)
	->addFilter(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Denomination in use: ')
	->setRequired(true)
	->addFilter(array('StripTags','StringTrim'))
	->addErrorMessage('You must set a status')
	->setDecorators($decorators);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');
	
	$this->addElements(array(
	$denomination, $period,	$material,
	$description, $weight, $rarity,
	$thickness, $diameter, $valid,
	$submit));
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array('denomination','period','material','description','rarity','thickness','diameter','weight','valid'), 'details')->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	$this->details->setLegend('Denomination details: ');
	}
}