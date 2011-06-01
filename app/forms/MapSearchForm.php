<?php
class MapSearchForm extends Zend_Form
{
public function __construct($options = null)
{
$counties = new Counties();
$county_options = $counties->getCountyName2();

parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

$this->setName('mapsearch');

$latitude = new Zend_Form_Element_Text('declat');
$latitude->setLabel('Latitude: ')
->setDecorators($decorators);

$longitude = new Zend_Form_Element_Text('declong');
$longitude->setLabel('Longitude: ')
->setDecorators($decorators);


$county = new Zend_Form_Element_Select('county');
$county->setLabel('County: ')
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => NULL,'Choose county' => $county_options))
->setDecorators($decorators);

$district = new Zend_Form_Element_Select('district');
$district->setLabel('District: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators);

$parish = new Zend_Form_Element_Select('parish');
$parish->setLabel('Parish: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators);

$distance = new Zend_Form_Element_Select('distance');
$distance->setLabel('Distance from point: ')
->addMultiOptions(array(NULL => NULL, 'Choose distance' => array('0.05' => '50 metres','0.1' => '100 metres', '0.25' => '250 metres','0.5' => '500 metres','1' => '1 kilometre','2' => '2 kilometres','3' => '3 kilometres', '4' => '4 kilometres', '5' => '5 kilometres', '10' => '10 kilometres')))
->setDecorators($decorators);

$objecttype = new Zend_Form_Element_Text('objecttype');
$objecttype->setLabel('Object type: ')
->setRequired(false)
->setAttrib('size',20)
->addFilter('StripTags')
->addFilter('StringTrim')
->setDecorators($decorators)
->addErrorMessage('You must enter an object type and it must be valid');

$gridref = new Zend_Form_Element_Text('gridref');
$gridref->setLabel('Nat. Grid Reference: ')
->setRequired(false)
->setAttrib('size',16)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('maxlength',16)
->setDecorators($decorators);



$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper');

$this->addElements(array(
$objecttype,
$distance,
$county,
$district,
$parish,
$gridref,
$latitude,
$longitude,
$submit));

$this->addDisplayGroup(array('objecttype','county','district','parish','gridref','declat','declong','distance'), 'details');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('ListWrapper');
$this->removeDecorator('HtmlTag');

$this->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'div','id' => 'mapsearchcontainer'));

$this->details->setLegend('Spatial data: ');
$this->addDisplayGroup(array('submit'), 'submit');
  
  

       

}
}