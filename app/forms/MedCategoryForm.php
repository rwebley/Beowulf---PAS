<?php
class MedCategoryForm extends Pas_Form
{
public function __construct($options = null)
{
$periods = new Periods();
$period_options = $periods->getMedievalCoinsPeriodList();


parent::__construct($options);
$this->setName('medievaltype');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$category = new Zend_Form_Element_Text('category');
$category->setLabel('Medieval coin category: ')
->setRequired(true)
->addFilter('StripTags')
->setAttrib('size',60)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter a category name.')
->setDecorators($decorators);

$periodID = new Zend_Form_Element_Select('periodID');
$periodID->setLabel('Medieval period: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addErrorMessage('You must enter a period for this type')
->addMultioptions(array(NULL => NULL,'Choose a period' => $period_options))
->setDecorators($decorators);

$description = new Zend_Form_Element_Textarea('description');
$description->setLabel('Description: ')
->setRequired(true)
->addFilter('StripTags')
->setAttrib('rows',15)
->setAttrib('cols',70)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addFilter('HtmlBody')
->addErrorMessage('You must enter a category description.');


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper');

$this->addElements(array(
$category,
$description,
$periodID,
$submit));

$this->addDisplayGroup(array('category','periodID','description','submit'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));

$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

  
  

       

}
}