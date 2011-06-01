<?php

class QuoteForm extends Pas_Form
{

public function __construct($options = null)
{

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
 $decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
      
$this->setName('quotes');


$quote = new Pas_Form_Element_RTE('quote');
$quote->setLabel('Quote or announcement: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttrib('Height',400)
->setAttrib('ToolbarSet','Basic')
->addFilter('StringTrim')
->addFilter('WordChars')
->setAttrib('class','expanding')
->addFilter('HtmlBody')
->addErrorMessage('Please enter an announcement/quote.');

$quotedBy = new Zend_Form_Element_Text('quotedBy');
$quotedBy->setLabel('Origin of quote/announcement: ')
->setRequired(true)
->setAttrib('size',60)
->addErrorMessage('Please state where this comes from.');

$expire = new Zend_Form_Element_Text('expire');
$expire->setLabel('Expires from use: ')
->setRequired(true)
->setAttrib('size',10)
->addErrorMessage('Please provide expiry date.')
->setDecorators($decorators);


$valid = new Zend_Form_Element_Checkbox('status');
$valid->setLabel('Quote/Announcement is in use: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);

$type = new Zend_Form_Element_Select('type');
$type->setLabel('Type: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setValue('quote')
->addMultiOptions(array(NULL => 'Choose type', 'quote' => 'Quote','announcement' => 'Announcement'))
->setDecorators($decorators);


//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton');
$submit->removeDecorator('DtDdWrapper');
$submit->removeDecorator('HtmlTag');

$this->addElements(array(

$quote,
$quotedBy,
$valid,
$expire,
$type,
$submit));

$this->addDisplayGroup(array('quote','quotedBy','status','expire','type','submit'), 'details');
$this->details->removeDecorator('HtmlTag');
$this->details->removeDecorator('DtDdWrapper');
      

}
}