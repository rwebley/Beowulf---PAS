<?php

class TokenJettonForm extends Zend_Form
{
public function __construct($options = null)
{


$rulers = new Rulers();
$ro = $rulers->getJettonRulers();
$dies = new Dieaxes;
$die_options = $dies->getAxes();
$wears = new Weartypes;
$wear_options = $wears->getWears();
parent::__construct($options);

$this->setAttrib('accept-charset', 'UTF-8');
       
$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 
$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 


$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
					
$this->setName('jettontoken');
		
$denomination = new Zend_Form_Element_Select('denomination');
$denomination->setLabel('Denomination: ')
->setRequired(true)
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => NULL,'Choose denomination' => array(
'64' => 'Jetton')
))
->setRegisterInArrayValidator(true)
->setDecorators($decorators)
->addErrorMessage('You must enter a denomination');

$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
$denomination_qualifier->setLabel('Denomination qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->setValue(1)
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);


$ruler= new Zend_Form_Element_Select('ruler');
$ruler->setLabel('Ruler: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => NULL,'Choose a ruler' => $ro));
//->disabled =false;

$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
$ruler_qualifier->setLabel('Ruler qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);

$mint_id= new Zend_Form_Element_Select('mint_id');
$mint_id->setLabel('Issuing mint: ')
->setRegisterInArrayValidator(false)
->setDecorators($decorators)
->addMultiOptions(array(NULL => NULL,'Choose a mint' => array('286' => 'Nuremberg')));



$mint_qualifier = new Zend_Form_Element_Radio('mint_qualifier');
$mint_qualifier->setLabel('Mint qualifier: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);



$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
$degree_of_wear->setLabel('Degree of wear: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => NULL,'Choose coin status' => $wear_options))
->setDecorators($decorators);

$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
$obverse_inscription->setLabel('Obverse inscription: ')
->setAttrib('size',50)
->setDecorators($decorators);

$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
$reverse_inscription->setLabel('Reverse inscription: ')
->setAttrib('size',50)
->setDecorators($decorators);

$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
$obverse_description->setLabel('Obverse description: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',3)
->setAttrib('cols',80)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('class','expanding');

$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
$reverse_description->setLabel('Reverse description: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',3)
->setAttrib('cols',80)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('class','expanding');

$reverse_mintmark = new Zend_Form_Element_Textarea('reverse_mintmark');
$reverse_mintmark->setLabel('Reverse mintmark: ')
->addValidators(array('NotEmpty'))
->setAttrib('rows',3)
->setAttrib('cols',80)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('class','expanding');


$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
$die_axis_measurement->setLabel('Die axis measurement: ')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => NULL,'Choose die axis' => $die_options))
->setDecorators($decorators);

$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
$die_axis_certainty->setLabel('Die axis certainty: ')
->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
->setDecorators($decorators);



//Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class','large');
			  
$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
if($action == 'editcoin')
{
	$rulers = new Rulers();
	$ruler_options = $rulers->getRomanRulers();
	$ruler->addMultiOptions(array(NULL => NULL,'Choose ruler' => $ruler_options));
	$mints = new Mints();
	$mint_options = $mints->getRomanMints();
	$mint_id->addMultiOptions(array(NULL => NULL,'Choose Roman mint' => $mint_options));
	$reeces = new Reeces();
	$reece_options = $reeces->getReeces();
	$reeceID->addMultiOptions(array(NULL => NULL,'Choose Reece period' => $reece_options));
	
	
}


$this->addElements(array(
$ruler,
$denomination,
$degree_of_wear,
$obverse_description,
$obverse_inscription,
$reverse_description,
$reverse_inscription,
$die_axis_measurement,
$die_axis_certainty,
$mint_id,
$mint_qualifier,
$ruler_qualifier,
$denomination_qualifier,


$submit));

$this->addDisplayGroup(array('denomination','denomination_qualifier','ruler','ruler_qualifier',
'mint_id','mint_qualifier',
'status','status_qualifier','degree_of_wear','obverse_description',
'obverse_inscription','reverse_description','reverse_inscription',
'die_axis_measurement','die_axis_certainty'), 'details');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->addDisplayGroup(array('submit'),'submit');

}
}