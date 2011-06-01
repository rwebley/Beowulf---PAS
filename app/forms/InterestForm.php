<?php
class InterestForm extends Pas_Form
{
public function getIdentityForForms()
	{
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity())
	{
	$user = $auth->getIdentity();
	$id = $user->id;
	return $id;
	}
	else 
	{
	$id = '3';
	return $id;
	}
	}
	
public function __construct($options = null)
{

parent::__construct($options);

$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
$this->setName('interests');

/* $id = new Zend_Form_Element_Hidden('id');
$id->setValue(1)->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag')->removeDecorator('Label');
 */

$interest = new Zend_Form_Element_Text('interest');
$interest->setLabel('Your interest: ')
->setRequired(true)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')

->setAttrib('size',30)
->addErrorMessage('Please enter a valid interest!')
->setDecorators($decorators);

$interest2 = new Zend_Form_Element_Text('interest2');
$interest2->setLabel('Your interest: ')
->setRequired(false)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',30)
->addErrorMessage('Please enter a valid interest!')
->setDecorators($decorators);
$interest3 = new Zend_Form_Element_Text('interest3');
$interest3->setLabel('Your interest: ')
->setRequired(false)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',30)
->addErrorMessage('Please enter a valid interest!')
->setDecorators($decorators);
$interest4 = new Zend_Form_Element_Text('interest4');
$interest4->setLabel('Your interest: ')
->setRequired(false)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',30)
->addErrorMessage('Please enter a valid interest!')
->setDecorators($decorators);
$interest5 = new Zend_Form_Element_Text('interest5');
$interest5->setLabel('Your interest: ')
->setRequired(false)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size',30)
->addErrorMessage('Please enter a valid interest!')
->setDecorators($decorators);


/* $addElement = new Zend_Form_Element_Button('addElement');
$addElement->setLabel('Add interest field')
->setAttrib('order',91);

$removeElement = new Zend_Form_Element_Button('removeElement');
$removeElement->setLabel('Remove interest field')
->setAttrib('order',92);
 */
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit')
->setAttrib('class', 'large')
->setAttrib('order',93)
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array(
/* $id,
 */$interest,$interest2,$interest3,$interest4,$interest5,
/* $addElement,
$removeElement,
 */$submit));

$this->addDisplayGroup(array('interest','interest2','interest3','interest4','interest5',/* 'addElement','removeElement' */), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Your interests');
$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}

 public function preValidation(array $data) {

    // array_filter callback
    function findFields($field) {
      // return field names that include 'newName'
      if (strpos($field, 'newName') !== false) {
        return $field;
      }
    }
    
    // Search $data for dynamically added fields using findFields callback
    $newFields = array_filter(array_keys($data), 'findFields');
    
    foreach ($newFields as $fieldName) {
      // strip the id number off of the field name and use it to set new order
      $order = ltrim($fieldName, 'newName') + 2;
      $this->addNewField($fieldName, $data[$fieldName], $order);
    }
  }
  
  /**
   * Adds new fields to form
   *
   * @param string $name
   * @param string $value
   * @param int    $order
   */
  public function addNewField($name, $value, $order) {
    
    $this->addElement('text', $name, array(
      'required'       => true,
      'label'          => 'Name',
      'value'          => $value,
      'order'          => $order
    ));
  }

}