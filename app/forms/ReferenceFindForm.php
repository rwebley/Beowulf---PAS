<?php
class ReferenceFindForm extends Pas_Form
{
public function __construct($options = null)
{


parent::__construct($options);
$this->setName('addreference');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );




$title = new Zend_Form_Element_Text('publicationtitle');
$title->setLabel('Publication title: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter a title')
->setAttrib('size', 60)
->setDescription('As the bibliographic details that have been entered are such a mess, this is slightly tricky. Try one word from the title of the book/journal or an author surname. Then click on the one that comes up.')
->setDecorators($decorators);


$id = new Zend_Form_Element_Hidden('pubID');
$id->setRequired(true)
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->removeDecorator('Label');

$pages = new Zend_Form_Element_Text('pages_plates');
$pages->setLabel('Pages or plate number: ')
->setDecorators($decorators)
->setAttrib('size',9);



$reference = new Zend_Form_Element_Text('reference');
$reference->setLabel('Reference number: ')
->setDecorators($decorators)
->setAttrib('size', 15);



//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->setAttrib('class','large');

$this->addElements(array(
$title, 
$id,
$pages,
$reference,
$submit));
$this->addDisplayGroup(array('publicationtitle','pubID','pages_plates','reference'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Add a new reference');
$this->addDisplayGroup(array('submit'),'submit');

}
}