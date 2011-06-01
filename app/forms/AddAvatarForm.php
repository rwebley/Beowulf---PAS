<?php

class AddAvatarForm extends Pas_Form
{

public function __construct($options = null)
{

parent::__construct($options);

$this->setAttrib('enctype', 'multipart/form-data');
$this->setName('AddAvatar');

$avatar = new Zend_Form_Element_File('avatar');
$avatar->setLabel('Upload an avatar: ')
		->setRequired(true)
		->setDestination('./images/avatars/')
        ->addValidator('NotEmpty')
        ->addValidator('Size', false, 512000)
		->addValidator('Extension', false, 'jpeg,tif,jpg,png,gif') 
        ->setMaxFileSize(512000)
		->setAttribs(array('class'=> 'textInput'))
		->addValidator('Count', false, array('min' => 1, 'max' => 1));


//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setLabel('Upload an avatar')
->setAttribs(array('class'=> 'large'));

$this->addElements(array($avatar,$submit))
->setLegend('Add an avatar')
->setMethod('post')
->addDecorators(array('FieldSet',
'form',array('HtmlTag', array('tag' => 'div'))
));

}
}