<?php
class TagForm extends Zend_Form
{

public function __construct($options = null)
{


parent::__construct($options);
$this->setAttrib('accept-charset', 'UTF-8');
       
$this->clearDecorators();
$decorators = array(
	
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'div')),
			
        );	  
$this->setName('tag');

$title = new Zend_Form_Element_Text('tag');
$title->setLabel('Tag record: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Come on it\'s not that hard, enter a title!')
->setDecorators($decorators);

$findID = new Zend_Form_Element_Hidden('findID');
$findID->removeDecorator('label')
   ->removeDecorator('HtmlTag');

//Submit button 

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submit');
$submit->clearDecorators();
        $submit->addDecorators(array(
            array('ViewHelper'),    // element's view helper
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit')),
        ));
$this->addElements(array($title,$findID,$submit));

$this->setLegend('Tag this record: ');
  $this->addDecorator('FormElements')
	 ->addDecorator('Form')
	 ->addDecorator('FieldSet');

  

       

}
}