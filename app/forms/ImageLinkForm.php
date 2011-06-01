<?php
class ImageLinkForm extends Pas_Form
{
public function __construct($options = null)
{

parent::__construct($options);
 $this->setMethod('post');  
$this->setName('imagelink');
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 

$decorator = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$old_findID = new Zend_Form_Element_Text('old_findID');
$old_findID->setLabel('Filter by find ID #')
->setRequired(true)
->addFilter('StripTags')
->setAttrib('size', 20)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorator);

$findID = new Zend_Form_Element_Hidden('findID');
$findID->setRequired(true)
->addFilter('StripTags')
->setAttrib('size', 11)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->setDecorators($decorator);

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setLabel('Link that image')
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->setAttrib('class','large');

$this->addElements(array(
$findID,
$old_findID,
$submit));
 $this->addDisplayGroup(array('old_findID','findID'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Link image: ');
$this->addDisplayGroup(array('submit'), 'submit');
 
}
}