<?php

class LanduseForm extends Pas_Form
{
public function __construct($options = null)
{
$landuses = new Landuses();
$landuse_opts = $landuses->getUsesValid();
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

parent::__construct($options);


$this->setName('Landuse');

$term = new Zend_Form_Element_Text('term');
$term->setLabel('Landuse term name: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid title for this landuse!')
->setDecorators($decorators);

$oldID = new Zend_Form_Element_Text('oldID');
$oldID->setLabel('Old landuse type code: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid title for this landuse!')
->setDecorators($decorators);

$termdesc = new Pas_Form_Element_TinyMce('termdesc');
$termdesc->setLabel('Description of landuse type: ')
->setRequired(true)
->addErrorMessage('You must enter a description')
->addFilter('StringTrim')
->setAttrib('rows',10)
->setAttrib('cols',80)
->addFilter('HtmlBody');

$belongsto = new Zend_Form_Element_Select('belongsto');
$belongsto->setLabel('Belongs to landuse type: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
//->addValidator('inArray', false, array(array_keys($landuse_opts)))
->addMultiOptions(array(NULL,'Choose period:' => $landuse_opts))
->setDecorators($decorators);


$valid = new Zend_Form_Element_Checkbox('valid');
$valid->setLabel('Landuse type is currently in use: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')->setDecorators($decorators);

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$term,
$termdesc,
$oldID,
$valid,
$belongsto,
$submit));

$this->addDisplayGroup(array('term','termdesc','oldID','belongsto','valid'), 'details')->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');
}
}