<?php

class AddStaffLogoForm extends Pas_Form
{

public function __construct($options = null)
{

parent::__construct($options);

$this->setAttrib('enctype', 'multipart/form-data');
$this->setName('Addlogo');

$avatar = new Zend_Form_Element_File('logo');
$avatar->setLabel('Upload logo: ')
		->setRequired(true)
		->setDestination('./images/logos/')
        ->addValidator('NotEmpty')
        ->addValidator('Size', false, 512000)
		->addValidator('Extension', false, 'jpeg,tif,jpg,png,gif') 
        ->setMaxFileSize(512000)
		->setAttribs(array('class'=> 'textInput'))
		->addValidator('Count', false, array('min' => 1, 'max' => 1));
$replace = new Zend_Form_Element_Checkbox('replace');
$replace->setLabel('Replace all current logos?: ')
->setCheckedValue(1);
//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setLabel('Upload a logo')
->setAttribs(array('class'=> 'large'));

$this->addElements(array($avatar,$replace,$submit));
$this->addDisplayGroup(array('logo','replace'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');


}
}