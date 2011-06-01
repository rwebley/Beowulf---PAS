<?php
class PublicationFilterForm extends Pas_Form
{
public function __construct($options = null)
{


parent::__construct($options);
 $this->setMethod('get');  
$this->setName('filterpubs');

$decorator =  array('TableDecInput');

$title = new Zend_Form_Element_Text('title');
$title->setLabel('Filter by title')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 30)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$authorEditor = new Zend_Form_Element_Text('authorEditor');
$authorEditor->setLabel('Filter by author')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->setAttrib('size', 15)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$yearPub = new Zend_Form_Element_Text('pubYear');
$yearPub->setLabel('Filter by pub. year')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->setAttrib('size', 15)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$place = new Zend_Form_Element_Text('place');
$place->setLabel('Filter by place of pub.')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->setAttrib('size', 15)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setLabel('Filter')
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');


$this->addElements(array(
$title, 
$authorEditor,
$yearPub,
$place,
$submit));
  
}
}