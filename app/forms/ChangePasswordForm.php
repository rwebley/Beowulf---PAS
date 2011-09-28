<?php

/**
* Form for changing a user's password
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ChangePasswordForm extends Pas_Form
{

    public function __construct($actionUrl = null, $options=null) {
        parent::__construct($options);
        $this->init();
    }


    public function init() {
        
        $this->clearDecorators();
        $this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
		$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
		
        $decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'li')),
        );
		
		
		$oldpassword = new Zend_Form_Element_Password('oldpassword');
		$oldpassword->setLabel('Your old password: ');
		$oldpassword->setRequired(true)
       	->addValidator('RightPassword')
       	->addFilters(array('StripTags','StringTrim'));
        $oldpassword->setDecorators($decorators);
		
		
		$password = new Zend_Form_Element_Password("password");
    	$password->setLabel("New password:")
		->addValidator("NotEmpty")
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators)
		->addValidator('IdenticalField', false, array('password2', ' confirm password field'));

    // identical field validator with custom messages
   	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);

    $password2 = new Zend_Form_Element_Password("password2");
    $password2->setLabel("Confirm password:")
              ->addValidator("NotEmpty")
              ->addFilters(array('StripTags','StringTrim'))
			  ->setRequired(true)
			  ->setDecorators($decorators);


	$submit = new Zend_Form_Element_Submit('submit');
	$submit->clearDecorators();
	$submit->addDecorators(array(
            array('ViewHelper'),    // element's view helper
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit')),
        ));
        
	$submit->setAttrib('class','large')
		->setLabel('Change password');
		
    $this->addElement($submit);
	$this->addElements(array( $oldpassword, $password, $password2, $submit));


	$this->addDisplayGroup(array('oldpassword','password','password2'), 'userdetails');
	$this->addDecorator('FormElements')
	     ->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'div'))
		 ->addDecorator('FieldSet') ->addDecorator('Form');
	$this->userdetails->removeDecorator('DtDdWrapper');
	$this->userdetails->removeDecorator('FieldSet');
	
	$this->userdetails->addDecorator(array('DtDdWrapper' => 'HtmlTag'),array('tag' => 'ul'));
	$this->addDisplayGroup(array('submit'),'submit');
				 
	$this->setLegend('Edit account details: ');

    }
}