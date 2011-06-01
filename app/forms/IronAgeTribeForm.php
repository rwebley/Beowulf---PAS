<?php
class IronAgeTribeForm extends Pas_Form
{
public function __construct($options = null)
{
parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
$this->setName('ironagetribes');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$tribe = new Zend_Form_Element_Text('tribe');
$tribe->setLabel('Tribe name: ')
->setRequired(true)
->addFilter('StripTags')
->setAttrib('size',60)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter a name for the tribe.')
->setDecorators($decorators);

$description = new Pas_Form_Element_TinyMce('description');
$description->setLabel('Description of the tribe: ')
->setRequired(true)
->setAttrib('cols',70)
->setAttrib('rows',20)
->addFilter('StringTrim')
->addFilter('HtmlBody')
->addErrorMessage('You must enter a description for the tribe');

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
				->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper');

$this->addElements(array(
$tribe,
$description,
$submit));

$this->addDisplayGroup(array('tribe','description','submit'), 'details')->removeDecorator('HtmlTag');;
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');



  
  

       

}
}