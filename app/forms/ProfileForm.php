<?php


class ProfileForm extends Pas_Form
{
    protected $_actionUrl;
protected $_copyright = NULL;
    public function __construct($actionUrl = null, $options=null)
    {
        parent::__construct($options);
        $this->setActionUrl($actionUrl);
        $this->init();
    }

    public function setActionUrl($actionUrl) {
        $this->_actionUrl = $actionUrl;
        return $this;
    }


	

    public function init()
    {
        $required = true;
        $copyrights = new Copyrights();
		$copy = $copyrights->getStyles();
        $this->setAction($this->_actionUrl)
             ->setMethod('post')
             ->setAttrib('id', 'accountform');

        $this->clearDecorators();
        $this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
		$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
		
		
        $decorators = array(
	
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'li')),
			
        );
		$username = $this->addElement('text','username',array('label' => 'Username:'))->username;
        $username->setDecorators($decorators)
		->Disabled=true;


        $firstName = $this->addElement('text', 'first_name', 
            array('label' => 'First Name: ', 'size' => '30'))->first_name;
        $firstName->setRequired(true)
                  ->addFilter('stripTags')
				  ->addErrorMessage('You must enter a firstname');
				  $firstName->setDecorators($decorators);

        $lastName = $this->addElement('text', 'last_name', 
            array('label' => 'Last Name: ', 'size' => '30'))->last_name;
        $lastName->setRequired(true)
                 ->addFilter('stripTags')
				 ->addErrorMessage('You must enter a surname');
        $lastName->setDecorators($decorators);

        $fullname = $this->addElement('text', 'fullname', 
            array('label' => 'Preferred Name: ', 'size' => '30'))->fullname;
        $fullname->setRequired(true)
                      ->addFilter('stripTags')
					  ->addErrorMessage('You must enter your preferred name');
        $fullname->setDecorators($decorators);

        $email = $this->addElement('text', 'email',array('label' => 'Email Address', 'size' => '30'))->email;
        $email->addValidator('emailAddress')
			  ->setRequired(true)
			  ->addErrorMessage('Please enter a valid address!');
        $email->setDecorators($decorators);
		
		$password = $this->addElement('password', 'password',array('label' => 'Change password: ', 'size' => '30'))->password;
        $password->addValidator('emailAddress')
			  ->setRequired(false);
        $password->setDecorators($decorators);
        
        $copyright = $this->addElement('select','copyright',array('label' => 'Default copyright: '))->copyright;
        $copyright->setRequired(TRUE);
        $copyright->addMultiOptions(array(NULL => 'Select a licence holder','Valid copyrights' => $copy))
				->setDecorators($decorators);
		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Save details');
        $submit->clearDecorators();
        $submit->addDecorators(array(
            array('ViewHelper'),    // element's view helper
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit')),
        ));
		$submit->setAttrib('class','large');
        $this->addElement($submit);


$this->addDisplayGroup(array('username','first_name','last_name','fullname','email','password','copyright'), 'userdetails');
$this->addDecorator('FormElements')
	
     ->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'div'))
	 ->addDecorator('FieldSet') ->addDecorator('Form');
			 
$this->setLegend('Edit your account and profile details: ');
$this->userdetails->removeDecorator('DtDdWrapper');
$this->userdetails->removeDecorator('HtmlTag');
$this->userdetails->removeDecorator('FieldSet');

$this->userdetails->addDecorator(array('DtDdWrapper' => 'HtmlTag'),array('tag' => 'ul'));
$this->addDisplayGroup(array('submit'),'submit');
    }
}