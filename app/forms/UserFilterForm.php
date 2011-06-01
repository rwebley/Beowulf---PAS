<?php
class UserFilterForm extends Pas_Form
{
public function __construct($options = null)
{


parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
 $this->setMethod('post');  
$this->setName('filterusers');
$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 

$decorator =  array('TableDecInput');


$username = new Zend_Form_Element_Text('username');
$username->setLabel('Filter by username')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 15)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');


$name = new Zend_Form_Element_Text('fullname');
$name->setLabel('Filter by name')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');


$role = new Zend_Form_Element_Select('role');
$role->setLabel('Filter by role')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addValidator('stringLength', false, array(1,200))
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->addMultiOptions(array(NULL => NULL,'Choose role' => array('admin' => 'Admin','hero' => 'HER officer', 'flos' => 'Finds Liaison','member' => 'Member','fa' => 'Finds Adviser','research' => 'Researcher')))
->setDisableTranslator(true);

$login = new ZendX_JQuery_Form_Element_DatePicker('lastLogin');
$login->setLabel('Filter last login: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper');

$visits = new Zend_Form_Element_Text('visits');
$visits->setLabel('Filter by visit count: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 5)
->setAttrib('maxlength','6')
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
$username,
$name, 
$role,
$login,
$visits,
$submit));
  
}
}