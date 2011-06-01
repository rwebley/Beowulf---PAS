<?php

class ImageEditForm extends Pas_Form
{

public function __construct($options = null)
{
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
		->setDecorators($decorators);
		
$period = new Zend_Form_Element_Select('period');
$period->setLabel('Period: ')
		->setRequired(true)
		->setDecorators($decorators)
		->addMultiOptions(array(NULL => NULL,'Choose a period' => $period_options));

$county = new Zend_Form_Element_Select('county');
$county->setLabel('County: ')
		->setRequired(true)
		->setDecorators($decorators)
		->addMultiOptions(array(NULL => NULL,'Choose a county' => $county_options));


$copyright = new Zend_Form_Element_Text('imagerights');
$copyright->setLabel('Image copyright')
->setAttrib('size',70)
		->setDecorators($decorators);
		
$type = new Zend_Form_Element_Select('type');
$type->setLabel('Image type: ')
->setRequired(true)
->addMultiOptions(array('Please choose publish state' => array('digital' => 'Digital image','illustration' => 'Scanned illustration')))
->setValue('digital')
->setDecorators($decorators);

$rotate = new Zend_Form_Element_Radio('rotate');
$rotate->setLabel('Rotate the image: ')
->setRequired(false)
->addMultiOptions(array('-90' => '90 degrees anticlockwise','-180' => '180 degrees anticlockwise', '-270' => '270 degrees anticlockwise','90' => '90 degrees clockwise','180' => '180 degrees clockwise', '270' => '270 degrees clockwise'));

$regenerate = new Zend_Form_Element_Checkbox('regenerate');
$regenerate->setLabel('Regenerate thumbnail: ')
->setDecorators($decorators);

$filename = new Zend_Form_Element_Hidden('filename');
$filename->removeDecorator('label')
       ->removeDecorator('HtmlTag')
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

$this->addElements(array($imagelabel,$county,$period,$copyright,$type,$rotate,$regenerate,$filename,$imagedir,$submit));
$this->setMethod('post');
$this->addDisplayGroup(array(
	'label',
	'county',
	'period',
	'imagerights',
	'type',
	'rotate',
	'regenerate',
	),
	'details');
	
$this->addDisplayGroup(array('submit'), 'submit')
	->removeDecorator('HtmlTag');
$this->submit->removeDecorator('DtDdWrapper');
$this->details->setLegend('Attach an image');


$this->details->setLegend('Attach an image')->addDecorators(array(
			'FieldSet',
			array('HtmlTag', array('tag' => 'ul')
			)
			));
;
$this->details->removeDecorator('DtDdWrapper');

}
}