<?php

class EmperorForm extends Pas_Form
{
public function __construct($options = null)
{


$reeces = new Reeces();
$reeces_options = $reeces->getOptions();

$rulers = new Rulers();
$rulers_options = $rulers->getOptions();

$dynasties = new Dynasties();
$dynasties_options = $dynasties->getOptions();


parent::__construct($options);

 $decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
      
$this->setName('EmperorDetails');

$name = new Zend_Form_Element_Text('name');
$name->setLabel('Emperor\'s name: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a firstname!')
->setDecorators($decorators);

$reeceID = new Zend_Form_Element_Select('reeceID');
$reeceID->setLabel('Reece period assigned: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('inArray', false, array(array_keys($reeces_options)))
->addMultiOptions(array(NULL => NULL,'Choose a Reece period' => $reeces_options))
->addErrorMessage('You must select a Reece Period')
->setDecorators($decorators);

$pasID = new Zend_Form_Element_Select('pasID');
$pasID->setLabel('Database ID: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('inArray', false, array(array_keys($rulers_options)))
->addMultiOptions(array(NULL => NULL, 'Choose a database id' => $rulers_options))
->addErrorMessage('You must assign the bio to an existing entry')
->setDecorators($decorators);

$date_from = new Zend_Form_Element_Text('date_from');
$date_from->setLabel('Issued coins from: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('Int')
->addErrorMessage('You must enter a date for the start of reign')
->setDecorators($decorators);

$date_to = new Zend_Form_Element_Text('date_to');
$date_to->setLabel('Issued coins until: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('Int')
->addErrorMessage('You must enter a date for the end of reign')
->setDecorators($decorators);

$biography = new Pas_Form_Element_RTE('biography');
$biography->setLabel('Biography: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttribs(array('cols' => 50, 'rows' => 20))
->addErrorMessage('You must enter a biography')
->addFilter('HtmlBody')
->addDecorator('Errors',array('placement' => 'append','class'=>'error','tag' => 'li'));


$dynasty = new Zend_Form_Element_Select('dynasty');
$dynasty->setLabel('Dynastic grouping: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('inArray', false, array(array_keys($dynasties_options)))
->addMultiOptions(array(NULL => NULL, 'Choose a dynasty' => $dynasties_options))
->addErrorMessage('You must select a dynastic grouping')
->setDecorators($decorators);


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$name, 
$reeceID,
$pasID,
$date_from,
$date_to,
$biography,
$dynasty,
$submit));

$this->addDisplayGroup(array('name','reeceID','pasID','date_from','date_to','biography','dynasty','submit'), 'details');
$this->details->addDecorators(array( array('HtmlTag', array('tag' => 'ul'))
));

$this->details->removeDecorator('HtmlTag');
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

  
  

       

}
}