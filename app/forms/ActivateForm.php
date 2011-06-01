<?php

include_once 'Zend/Form.php';

class ActivateForm extends Zend_Form
{


    public function init()
    {
       

        $this->clearDecorators();
        $this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');

        $decorators = array(
            array('ViewHelper'), 
            array('Errors'),
            array('Label', array('requiredSuffix' => ' *', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'li')),
        );

        $username = $this->addElement('text', 'username', 
            array('label' => 'Username'));
        $username = $this->getElement('username')
                  ->addValidator('alnum')
                  ->setRequired(true)
                  ->addFilter('StringTrim')
                  ->addValidator('Authorise');
        $username->getValidator('alnum')
                 ->setMessage('Your username should include letters and numbers only');
        $username->setDecorators($decorators);



        $submit = $this->addElement('submit', 'Login');
        $submit = $this->getElement('Login')
                       ->setDecorators(array(
                        array('ViewHelper'),
                        array('HtmlTag', array('tag' => 'li', 'class' => 'submit')),
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'ul')),
            array(array('DivTag' => 'HtmlTag'), 
                array('tag' => 'div', 'id' => 'loginDiv')),           
            'Form'
        ));
		
		$this->addDecorator('FormElements')
	 ->addDecorator('Form')
     ->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'ul'))
	 ->addDecorator('FieldSet');
		$this->setLegend('Activate your account on Beowulf: ');

    }
}