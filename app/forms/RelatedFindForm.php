<?php
class RelatedFindForm extends Pas_Form
{
public function __construct($options = null)
{

parent::__construct($options);
$this->setName('relatedfindform');

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );


$old_findID = new Zend_Form_Element_Text('old_findID');
$old_findID->setLabel('Find number: ')
->setDecorators($decorators)
->setAttrib('size',25);




	$find1ID = new Zend_Form_Element_Hidden('find1ID');
	$find1ID->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label');
	$clause    = 'AND find1ID = '.$find1ID->getValue;
	$find2ID = new Zend_Form_Element_Hidden('find2ID');
	$find2ID->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label');

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->setAttrib('class','large');

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);

$this->addElements(array(
$old_findID, 
$find2ID,
$find1ID,
$submit));
$this->addDisplayGroup(array('old_findID','find1ID','find2ID'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Add a new reference');
$this->addDisplayGroup(array('submit'),'submit');

}
}