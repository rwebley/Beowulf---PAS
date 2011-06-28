<?php
/** Form for editing and adding images
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ImageEditForm extends Pas_Form {
	
	public function __construct($options = null) {
		
	$counties = new Counties();
	$county_options = $counties->getCountyName2();
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

	parent::__construct($options);

	$this->setName('imageeditfind');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$imagelabel = new Zend_Form_Element_Text('label');
	$imagelabel->setLabel('Image label')
	->setRequired(true)
	->setAttrib('size',70)
	->addErrorMessage('You must enter a label')
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorators);
		
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
	->setRequired(true)
	->setDecorators($decorators)
	->addMultiOptions(array(NULL => NULL,'Choose a period' => $period_options))
	->addValidator('inArray', false, array(array_keys($period_options)));

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->setRequired(true)
	->setDecorators($decorators)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose a county' => $county_options))
	->addValidator('inArray', false, array(array_keys($county_options)));

	$copyright = new Zend_Form_Element_Text('imagerights');
	$copyright->setLabel('Image copyright')
	->setAttrib('size',70)
	->setDecorators($decorators)
	->addFilters(array('StringTrim','StripTags'));
		
	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Image type: ')
	->setRequired(true)
	->addMultiOptions(array('Please choose publish state' => array(
	'digital' => 'Digital image', 'illustration' => 'Scanned illustration')))
	->setValue('digital')
	->addFilters(array('StringTrim','StripTags'))
	->setDecorators($decorators);

	$rotate = new Zend_Form_Element_Radio('rotate');
	$rotate->setLabel('Rotate the image: ')
	->setRequired(false)
	->addValidator('Int')
	->addMultiOptions(array(
	'-90' => '90 degrees anticlockwise', '-180' => '180 degrees anticlockwise', 
	'-270' => '270 degrees anticlockwise', '90' => '90 degrees clockwise',
	'180' => '180 degrees clockwise', '270' => '270 degrees clockwise'));

	$regenerate = new Zend_Form_Element_Checkbox('regenerate');
	$regenerate->setLabel('Regenerate thumbnail: ')
	->setDecorators($decorators);

	$filename = new Zend_Form_Element_Hidden('filename');
	$filename->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->addFilters(array('StringTrim','StripTags'))
	->removeDecorator('DtDdWrapper');
		   
	$imagedir = new Zend_Form_Element_Hidden('imagedir');
	$imagedir->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper');

				
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->setAttrib('class','large')
	->removeDecorator('DtDdWrapper');

	$this->addElements(array(
	$imagelabel, $county, $period,
	$copyright, $type, $rotate,
	$regenerate, $filename, $imagedir,
	$submit));

	$this->setMethod('post');
	
	$this->addDisplayGroup(array(
	'label', 'county', 'period',
	'imagerights', 'type', 'rotate',
	'regenerate'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'submit')
	->removeDecorator('HtmlTag');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Attach an image');
	$this->details->setLegend('Attach an image')->addDecorators(array(
			'FieldSet',
			array('HtmlTag', array('tag' => 'ul')
			)
			));

	$this->details->removeDecorator('DtDdWrapper');
	}
}