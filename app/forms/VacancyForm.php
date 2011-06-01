<?php


class VacancyForm extends Pas_Form
{
public function __construct($options = null)
{
$staffregions = new StaffRegions();
$staffregions_options = $staffregions->getOptions();
ZendX_JQuery::enableForm($this);


parent::__construct($options);

$this->setAttrib('accept-charset', 'UTF-8');
$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 
$decorator =  array('SimpleInput');
$decoratorSelect =  array('SelectInput');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
$this->setName('vacancies');


$title = new Zend_Form_Element_Text('title');
$title->setLabel('Role title: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter a title for this vacancy.')
->setAttrib('size', 60)
->setDecorators($decorators);

$salary = new Zend_Form_Element_Text('salary');
$salary->setLabel('Salary: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size', 20)
->addErrorMessage('You must enter a salary.')
->setDecorators($decorators);

$specification = new Pas_Form_Element_RTE('specification');
$specification->setLabel('Job specification: ')
->setRequired(true)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttribs(array('cols' => 50, 'rows' => 10))
->addErrorMessage('You must enter a job description.');

$regionID = new Zend_Form_Element_Select('regionID');
$regionID->setLabel('Location of role: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('inArray', false, array(array_keys($staffregions_options)))
->addMultiOptions(array(NULL => NULL,'Choose region' => $staffregions_options))
->setDecorators($decorators)
->addErrorMessage('You must choose a region');

$live = new ZendX_JQuery_Form_Element_DatePicker('live');
$live->setLabel('Date for advert to go live: ')
->setRequired(true)
->setJQueryParam('dateFormat', 'yy-mm-dd')
->setJQueryParam('maxDate', '+1y')
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->removeDecorator('DtDdWrapper');

$expire = new ZendX_JQuery_Form_Element_DatePicker('expire');
$expire->setLabel('Date for advert to expire: ')
->setRequired(true)
->setJQueryParam('dateFormat', 'yy-mm-dd')
->setJQueryParam('maxDate', '+1y')
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->removeDecorator('DtDdWrapper');



$status = new Zend_Form_Element_Select('status');
$status->SetLabel('Publish status: ')
->setRequired(true)
->addMultiOptions(array(NULL => 'Choose a status','2' => 'Publish','1' => 'Draft'))
->setValue(2)
->addFilter('stringTrim')
->setDecorators($decorators)
->addErrorMessage('You must choose a status');

//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')
->setAttrib('class','large');

$this->addElements(array(

$title, 
$salary,
$specification,
$regionID,
$live,
$expire,
$status,
$submit));

$this->removeDecorator('DtDdWrapper');

$this->addDisplayGroup(array('title','salary','specification','regionID'), 'details');
$this->details->setLegend('Vacancy details');
$this->details->removeDecorator('DtDdWrapper');

$this->addDisplayGroup(array('live','expire','status'), 'dates');
$this->dates->setLegend('Publication details');
$this->dates->removeDecorator('DtDdWrapper');

$this->setLegend('Vacancy details');
$this->addDisplayGroup(array('submit'), 'submit');
}
}