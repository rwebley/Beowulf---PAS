<?php
class IronAgeRegionForm extends Pas_Form
{
public function __construct($options = null)
{
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
->addFilter('StripTags')
->setAttrib('size',60)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter an area name.')
->setDecorators($decorators);

$region = new Zend_Form_Element_Text('region');
$region->setLabel('Region name: ')
->setRequired(true)
->setAttrib('size',60)
->addFilter('StripTags')
->addFilter('StringTrim')
->addErrorMessage('You must enter a region name')
->setDecorators($decorators);

$description = new Pas_Form_Element_TinyMce('description');
$description->setLabel('Description: ')
->setRequired(true)
->setAttrib('cols',70)
->setAttrib('rows',20)
->addFilter('StringTrim')
->addFilter('HtmlBody')
->addErrorMessage('You must enter a description');


$tribe = new Zend_Form_Element_Select('tribe');
$tribe->setLabel('Associated tribe: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addMultioptions(array(NULL => NULL,'Choose a tribe' => $tribes_options))
->addErrorMessage('You must enter a tribe.')
->setDecorators($decorators);


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Is this area valid: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must set the validity')
->setDecorators($decorators);



$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper');

$this->addElements(array(
$area,
$region,
$tribe,
$valid,
$description,
$submit));

$this->addDisplayGroup(array('area','region','tribe','description','valid','submit'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

       

}
}