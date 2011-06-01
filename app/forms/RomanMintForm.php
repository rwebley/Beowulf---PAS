<?php

require_once 'Mints.php';

class RomanMintForm extends Zend_Form
{
public function __construct($options = null)
{


parent::__construct($options);

$mints = new Mints();
$mints_options = $mints->getRomanMints();


$this->setAttrib('accept-charset', 'UTF-8');
       
	   $this->setDecorators(array(
            'FormElements',
            'Fieldset',
            'Form',
			
        ));
$this->setName('romanmints');

$id = new Zend_Form_Element_Hidden('ID');
$id->removeDecorator('label')
   ->removeDecorator('HtmlTag');

$name = new Zend_Form_Element_Text('name');
$name->setLabel('Issuing mint known as: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a firstname!');

$description = new Zend_Form_Element_TextArea('description');
$description->setLabel('Description of mint: ')
->setRequired(false)
->addFilter('StringTrim')
->addFilter('StripTags')
->setAttribs(array('cols' => 50, 'rows' => 10))
->setAttrib('class','expanding');

$abbrev = new Zend_Form_Element_Text('abbrev');
$abbrev->setLabel('Abbreviation appearing on coins: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty');


$latitude = new Zend_Form_Element_Text('latitude');
$latitude->setLabel('Latitude: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty');

$longitude = new Zend_Form_Element_Text('longitude');
$longitude->setLabel('Longitude: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty');

$pasID = new Zend_Form_Element_Select('pasID');
$pasID->setLabel('Corresponding database entry: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('inArray', false, array(array_keys($mints_options)))
->addMultiOptions($mints_options)
;

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton');

$this->addElements(array(
$id, 
$name, 
$description,
$latitude,
$longitude,
$pasID,
$abbrev,
$submit));

$this->addDisplayGroup(array('name','description','abbrev','pasID','latitude','longitude'), 'details');
$this->setLegend('Active Roman Mints');
$this->addDisplayGroup(array('submit'), 'submit');
}
}