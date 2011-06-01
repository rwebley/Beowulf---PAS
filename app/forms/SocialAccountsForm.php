<?php
class SocialAccountsForm extends Pas_Form
{
public function __construct($options = null)
{

parent::__construct($options);

$services = new WebServices();
$servicesListed = $services->getValidServices();      

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
$this->setName('socialweb');


$username = new Zend_Form_Element_Text('account');
$username->setLabel('Account username: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',30)
->addErrorMessage('Please enter a valid username!')
->setDecorators($decorators);

$service = new Zend_Form_Element_Select('accountName');
$service->setLabel('Social services: ')
->addMultiOptions(array( NULL => 'Choose a service', 'Valid services' => $servicesListed))
->setDecorators($decorators);


$public = new Zend_Form_Element_Checkbox('public');
$public->setLabel('Show this to public users?: ')
->setRequired(true)
->setDecorators($decorators)
->addErrorMessage('You must set the status of this account');


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$service,
$username,
$public,
$submit));

$this->addDisplayGroup(array('accountName','account','public'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}