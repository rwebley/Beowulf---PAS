<?php
/**
* Form for adding a profile photo to a user's account (staff only)
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddStaffPhotoForm extends Pas_Form
{

public function __construct($options = null) {

	parent::__construct($options);
	
	$this->setAttrib('enctype', 'multipart/form-data');
	$this->setName('AddAvatar');

	$avatar = new Zend_Form_Element_File('image');
	$avatar->setLabel('Upload staff photo: ')
		->setRequired(true)
		->setDestination('./images/staffphotos/')
        ->addValidator('NotEmpty')
        ->addValidator('Size', false, 3145728)
		->addValidator('Extension', false, 'jpeg,tif,jpg,png,gif') 
        ->setMaxFileSize(3145728)
		->setAttribs(array('class'=> 'textInput'))
		->addValidator('Count', false, array('min' => 1, 'max' => 1));
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
		
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Upload a photo')
	->setAttribs(array('class'=> 'large'));

	$this->addElements(array($avatar,$submit))
	->setLegend('Add an active denomination');
	$this->addDisplayGroup(array('image'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Add a staff photograph: ');

	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	
	}
}