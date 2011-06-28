<?php
/**
* Form for adding an image to a ruler's biographical page
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddRulerImageForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);

	$this->setAttrib('enctype', 'multipart/form-data');
	
	$this->setName('AddRulerImage');
	
	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
		
	$image = new Zend_Form_Element_File('image');
	$image->setLabel('Upload an image: ')
		->setRequired(true)
		->setDestination('./images/rulers/')
		->addValidator('Size', false, 2097152 )
		->addValidator('Extension', false, 'jpeg,tif,jpg,png,gif,JPG,TIFF') 
		->setMaxFileSize(1024000)
		->setAttribs(array('class'=> 'textInput'))
		->addValidator('Count', false, array('min' => 1, 'max' => 1))
		->addDecorator('File');

	$caption = new Zend_Form_Element_Text('caption');
	$caption->setLabel('Image caption')
		->setRequired(true)
		->setAttrib('size',60)
		->setDecorators($decorators)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a label');
		
	$rulerID = new Zend_Form_Element_Hidden('rulerID');
	$rulerID ->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->addValidator('Int')
		->setRequired(true);
			 
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Upload an image of a ruler')
	->setAttribs(array('class'=> 'large'));
	
	$this->addElements(array($image,$rulerID,$caption,$submit))
	->setLegend('Add an image to a ruler profile');
	
	$this->addDisplayGroup(array('image','caption'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	}
}