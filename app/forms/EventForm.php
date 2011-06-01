<?php

class EventForm extends Pas_Form
{

public function __construct($options = null)
{
$staffregions = new StaffRegions();
$staffregions_options = $staffregions->getOptions();

$eventtypes = new EventTypes();
$event_options = $eventtypes->getTypes();
ZendX_JQuery::enableForm($this);
parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
$this->setName('event');


$eventTitle = new Zend_Form_Element_Text('eventTitle');
$eventTitle->setLabel('Event title: ')
->setRequired(true)
->addFilter('StripTags')
->setAttrib('size',70)
->setDecorators($decorators);

$eventDescription = new Pas_Form_Element_RTE('eventDescription');
$eventDescription->setLabel('Event description: ')
->setRequired(true)
->setAttrib('rows',10)
->setAttrib('cols',70)
->addFilter('StringTrim')
->addFilter('HtmlBody')
->addFilter('EmptyParagraph');

$address = new Zend_Form_Element_Text('eventLocation');
$address->setLabel('Address: ')
->setRequired(true)
->setAttrib('size',70)
->addFilter('StripTags')
->setDecorators($decorators);

$eventStartTime = new Zend_Form_Element_Text('eventStartTime');
$eventStartTime->setLabel('Event start time: ')
->setRequired(true)
->addFilter('StripTags')
->setDecorators($decorators);


$eventEndTime = new Zend_Form_Element_Text('eventEndTime');
$eventEndTime->setLabel('Event end time: ')
->setRequired(true)
->addFilter('StripTags')
->setDecorators($decorators);


$eventStartDate = new ZendX_JQuery_Form_Element_DatePicker('eventStartDate');
$eventStartDate->setLabel('Event start date: ')
->setRequired(false)
->addFilter('StripTags')
->setJQueryParam('dateFormat', 'yy-mm-dd')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->removeDecorator('DtDdWrapper');


$eventEndDate = new ZendX_JQuery_Form_Element_DatePicker('eventEndDate');
$eventEndDate->setLabel('Event end date: ')
->setRequired(false)
->addFilter('StripTags')
->setJQueryParam('dateFormat', 'yy-mm-dd')

->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->removeDecorator('DtDdWrapper');


$eventRegion = new Zend_Form_Element_Select('eventRegion');
$eventRegion->setLabel('Organising section: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,10))
->addValidator('NotEmpty')
->addValidator('inArray', false, array(array_keys($staffregions_options)))
->addMultiOptions($staffregions_options)
->setDecorators($decorators);

$eventType = new Zend_Form_Element_Select('eventType');
$eventType->setLabel('Type of event: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,10))
->addValidator('NotEmpty')
->addValidator('inArray', false, array(array_keys($event_options)))
->addMultiOptions($event_options)
->setDecorators($decorators);


$adultsAttend = new Zend_Form_Element_Text('adultsAttend');
$adultsAttend->setLabel('Adults attending: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')->setDecorators($decorators);



$childrenAttend = new Zend_Form_Element_Text('childrenAttend');
$childrenAttend->setLabel('Children attending: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);


$organisation = new Zend_Form_Element_Select('organisation');
$organisation->setLabel('Organised by: ')
->setRequired(false)
->setValue('PAS')
->addMultioptions(array(NULL => 'Choose an organisation', 
'Available institutions' => array(
'PAS' => 'The Portable Antiquities Scheme',
 'BM' => 'The British Museum',
 'MLA' => 'MLA',
 'HLF' => 'Heritage Lottery Fund',
 'IFA' => 'Institute of Archaeology',
 'CBA' => 'Council for British Archaeology',
 'ARCH' => 'Current Archaeology',
 'AF' => 'The Art Fund',
 'LOC' => 'Local museum',
 'NADFAS' => 'NADFAS',
 'CASPAR' => 'CASPAR'
)))
->setDecorators($decorators);



$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');


$this->addElements(array(
$eventTitle,$eventDescription,$eventStartTime,$eventEndTime,$eventStartDate,$eventEndDate,$organisation,$childrenAttend,$eventRegion,$adultsAttend,$address,$eventType,
$submit
));

$this->addDisplayGroup(array('eventTitle','eventDescription','eventLocation','eventStartTime','eventEndTime','eventStartDate','eventEndDate','eventRegion','organisation','childrenAttend','adultsAttend','eventType'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}