<?php
class CatalogueForm extends Pas_Form
{
public function __construct($options = null)
{


parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
       
	   $this->setDecorators(array(
            'FormElements',
            'Fieldset',
            'Form',
			
        ));
$this->setName('catalogue');

$title = new Zend_Form_Element_Text('title');
$title->setLabel('My research group title: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!');

$description = new Zend_Form_Element_Textarea('description');
$description->setLabel('Description of research: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('rows',10)
->setAttrib('cols',80)
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200));



//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton');

$this->addElements(array(
$title, 
$description,
$submit));

$this->addDisplayGroup(array('title','description'), 'details');
$this->setLegend('Create a research agenda ');
$this->addDisplayGroup(array('submit'), 'submit');
  
  

       

}
}