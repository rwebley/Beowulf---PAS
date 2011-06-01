<?php
class AddFloRallyForm extends Pas_Form
{

	
public function __construct($options = null)
{
$staff = new Contacts();
$flos = $staff->getAttending();
parent::__construct($options);
ZendX_JQuery::enableForm($this);

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
$this->setName('addFlo');


$flo = new Zend_Form_Element_Select('staffID');
$flo->setLabel('Finds officer present: ')
->setRequired(true)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid interest!')
->setDecorators($decorators)
->addMultiOptions(array(NULL => 'Choose attending officer','Our staff members' => $flos));

$dateFrom = new ZendX_JQuery_Form_Element_DatePicker('dateFrom');
$dateFrom->setLabel('Attended from: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->removeDecorator('DtDdWrapper');


$dateTo = new ZendX_JQuery_Form_Element_DatePicker('dateTo');
$dateTo->setLabel('Attended to: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setAttrib('size', 20)
->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
->removeDecorator('DtDdWrapper');

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$flo,$dateFrom,$dateTo,$submit));

$this->addDisplayGroup(array('staffID','dateFrom','dateTo'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Attending Finds Officers');
$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}



}