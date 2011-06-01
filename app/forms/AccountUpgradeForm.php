<?php

class AccountUpgradeForm extends Pas_Form
{

public function __construct($options = null)
{

parent::__construct($options);
		
  
$this->setName('accountupgrades');
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
$researchOutline = new Zend_Form_Element_Textarea('researchOutline');
$researchOutline->setLabel('Research outline: ')
->setRequired(true)
->addFilter('StringTrim')
->addFilter('HtmlBody')
->setAttribs(array('rows' => 10))
->addErrorMessage('Outline must be present.')
->setDescription('Use this textarea to tell us whether you want to become a research level user and why. We would also like to know the probable length of time for this project so that we can inform our research board of progress. We need a good idea as we have to respect privacy of findspots and landowner/finder personal data');


$reference = $this->addElement('Text','reference',
				array('label' => 'Please provide a referee:', 'size' => '40','description' => 'We ask you to provide a referee who can substantiate your request for higher level access. Ideally they will be an archaeologist of good standing.'))->reference;
		$reference->setRequired(false)
				  ->addFilter('stripTags');
		$reference->setDecorators($decorators);
		
		$referenceEmail = $this->addElement('Text','referenceEmail',
				array('label' => 'Please provide an email address for your referee:', 'size' => '40'))->referenceEmail;
		$referenceEmail->setRequired(false)
				  ->addFilter('stripTags')
				  ->addValidator('emailAddress');
		$referenceEmail->setDecorators($decorators);	


$already = new Zend_Form_Element_Radio('already');
$already->setLabel('Is your topic already listed on our research register?: ')
->addMultiOptions(array( 1 => 'Yes it is',0 => 'No it isn\'t' ))
->setRequired(true)->setOptions(array('separator' => ''))
->setDecorators($decorators);


//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag')
->setLabel('Submit request');

$this->addElements(array(
$researchOutline,
$submit,
$already,
));

$this->addDisplayGroup(array('researchOutline','reference','referenceEmail','already'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Details: ');
$this->addDisplayGroup(array('submit'), 'submit');

}
}