<?php

class MonarchForm extends Pas_Form
{
public function __construct($options = null)
{
$rulers = new Rulers();
$rulers_options = $rulers-> getAllMedRulers();

$dynasties = new Dynasties();
$dynasties_options = $dynasties->getOptions();


parent::__construct($options);
       
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
$this->setName('MonarchDetails');

$name = new Zend_Form_Element_Text('name');
$name->setLabel('Monarch\'s name: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size','50')
->addErrorMessage('You must enter a Monarch\'s name')
->setDecorators($decorators);

$styled = new Zend_Form_Element_Text('styled');
$styled->setLabel('Styled as: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setDecorators($decorators);

$alias = new Zend_Form_Element_Text('alias');
$alias->setLabel('Monarch\'s alias: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setDecorators($decorators);


$dbaseID = new Zend_Form_Element_Select('dbaseID');
$dbaseID->setLabel('Database ID: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('inArray', false, array(array_keys($rulers_options)))
->addMultiOptions(array(NULL => NULL, 'Choose Database ID' => $rulers_options))
->setDecorators($decorators);

$date_from = new Zend_Form_Element_Text('date_from');
$date_from->setLabel('Issued coins from: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('Int')
->setDecorators($decorators);

$date_to = new Zend_Form_Element_Text('date_to');
$date_to->setLabel('Issued coins until: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('Int')
->setDecorators($decorators);

$born = new Zend_Form_Element_Text('born');
$born->setLabel('Born: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('Int')
->setDecorators($decorators);

$died = new Zend_Form_Element_Text('died');
$died->setLabel('Died: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('Int')
->setDecorators($decorators);


$biography = new Pas_Form_Element_RTE('biography');
$biography->setLabel('Biography: ')
->setRequired(false)
->addFilter('StringTrim')
->setAttribs(array('cols' => 90, 'rows' => 10))
->addFilter('HtmlBody');

$dynasty = new Zend_Form_Element_Select('dynasty');
$dynasty->setLabel('Dynastic grouping: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators);

$publishState = new Zend_Form_Element_Select('publishState');
$publishState->setLabel('Publication status: ')
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addMultiOptions(array(NULL => NULL, 'Set status' => array('1' => 'Draft','2' => 'Published')))
->setValue(1)
->setDecorators($decorators);


$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
$name,
$styled,
$alias, 
$dbaseID,
$date_from,
$date_to,
$born,
$died,
$biography,
$dynasty,
$publishState,
$submit));

$this->addDisplayGroup(array('name','styled','alias'), 'names');
$this->names->setLegend('Nomenclature');
$this->names->removeDecorator('DtDdWrapper');
$this->names->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('dbaseID','date_from','date_to','born','died'),'periods');
$this->periods->setLegend('Dates');
$this->periods->removeDecorator('DtDdWrapper');
$this->periods->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('biography','dynasty','publishState'),'details');
$this->details->setLegend('Biographical details');
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');

$this->addDisplayGroup(array('submit'), 'submit');
  
  

       

}
}