<?php
class EducationForm extends Pas_Form
{
public function __construct($options = null)
{

parent::__construct($options);

$levels = new ProjectTypes();
$levelsListed = $levels->getDegrees();      

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
$this->setName('education');
ZendX_JQuery::enableForm($this);


$school = new Zend_Form_Element_Text('school');
$school->setLabel('Institution name: ')
->setRequired(true)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',30)
->addErrorMessage('Please enter a valid institutional name!')
->setDecorators($decorators);

$schoolUrl = new Zend_Form_Element_Text('schoolUrl');
$schoolUrl->setLabel('Institution web address: ')
->setRequired(true)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',30)
->addErrorMessage('Please enter a valid url!')
->setDecorators($decorators);


$subject = new Zend_Form_Element_Text('subject');
$subject->setLabel('Subject studied: ')
->setRequired(true)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',30)
->addErrorMessage('Please enter a valid string!')
->setDecorators($decorators);


$level = new Zend_Form_Element_Select('level');
$level->setLabel('Adademic level of study: ')
->addMultiOptions(array( NULL => 'Choose an academic level', 'Valid levels' => $levelsListed))
->setDecorators($decorators);


$dateFrom = new ZendX_JQuery_Form_Element_DatePicker('dateFrom');
$dateFrom->setLabel('Commenced programme: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->setJQueryParams(array('yearRange'=> '-20:+10'));


$dateTo = new ZendX_JQuery_Form_Element_DatePicker('dateTo');
$dateTo->setLabel('Finished programme: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->removeDecorator('DtDdWrapper')
->setJQueryParams(array('yearRange'=> '-20:+10'));


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$school,
$schoolUrl,
$subject,
$level,
$dateFrom,
$dateTo,
$submit));

$this->addDisplayGroup(array('school','schoolUrl','subject','level','dateFrom','dateTo'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Educational background');
$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}