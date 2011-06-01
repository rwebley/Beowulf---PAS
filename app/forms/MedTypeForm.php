<?php
class MedTypeForm extends Pas_Form
{
public function __construct($options = null)
{
$periods = new Periods();
$period_options = $periods->getMedievalCoinsPeriodList();

$cats = new CategoriesCoins();
$cat_options = $cats->getCategoriesAll();

$rulers = new Rulers();
$ruler_options = $rulers->getAllMedRulers();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
$this->setName('medievaltype');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$type = new Zend_Form_Element_Text('type');
$type->setLabel('Coin type: ')
->setRequired(true)
->addFilter('StripTags')
->setAttrib('size',60)
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('You must enter a type name.')
->setDecorators($decorators);

$periodID = new Zend_Form_Element_Select('periodID');
$periodID->setLabel('Medieval period: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addErrorMessage('You must enter a period for this type')
->addMultioptions(array(NULL => NULL,'Choose a period' => $period_options))
->setDecorators($decorators);


$rulerID = new Zend_Form_Element_Select('rulerID');
$rulerID->setLabel('Ruler assigned: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultioptions(array(NULL => NULL,'Choose a ruler' => $ruler_options))
->setDecorators($decorators);


$datefrom = new Zend_Form_Element_Text('datefrom');
$datefrom->setLabel('Date in use from: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);

$dateto = new Zend_Form_Element_Text('dateto');
$dateto->setLabel('Date in use until: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);


$categoryID = new Zend_Form_Element_Select('categoryID');
$categoryID->setLabel('Coin category: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL, 'Choose a category' => $cat_options))
->setDecorators($decorators);


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper');

$this->addElements(array(
$type,
$rulerID,
$periodID,
$categoryID,
$datefrom,
$dateto,
$submit));

$this->addDisplayGroup(array('periodID','type','categoryID','rulerID','datefrom','dateto','submit'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));

$this->details->setLegend('Mint details: ');
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

       

}
}