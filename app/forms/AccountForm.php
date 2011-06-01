<?php


class AccountForm extends Pas_Form
{
    protected $_actionUrl;

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
		$username = $this->addElement('text','username',array('label' => 'Username: '))->username;
		$username->setDecorators($decorators)
				->setRequired(true);
				
        $password = $this->addElement('password', 'password', 
            array('label' => 'Password'))->password;
        $password->addValidator('stringLength', true, array(6))
                 ->addValidator('regex', true, array('/^(?=.*\d)(?=.*[a-zA-Z]).{6,}$/'))
                 ->setRequired(true)
				 ->addErrorMessage('Please enter a valid password!');
        $password->getValidator('stringLength')->setMessage('Password is too short');
        $password->getValidator('regex')->setMessage('Password does not contain letters and numbers');
        $password->setDecorators($decorators);

        $firstName = $this->addElement('text', 'first_name', 
            array('label' => 'First Name', 'size' => '30'))->first_name;
        $firstName->setRequired(true)
                  ->addFilter('stripTags')
				  ->addErrorMessage('You must enter a firstname');
				  $firstName->setDecorators($decorators);

        $lastName = $this->addElement('text', 'last_name', 
            array('label' => 'Last Name', 'size' => '30'))->last_name;
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
		
		$institution = $this->addElement('text', 'institution',array('label' => 'Recording institution: ', 'size' => '30'))->institution;
        $institution->setDecorators($decorators);
		
		
		$researchOutline = $this->addElement('textArea','research_outline', 
				array('label' => 'Outline your research', 'rows' => 10, 'cols' => 40))->research_outline;
		$researchOutline->setRequired(false)
						->addFilter('HtmlBody');
		
		
		
		$reference = $this->addElement('text','reference',
				array('label' => 'Please provide a referee:', 'size' => '40'))->reference;
		$reference->setRequired(false)
				  ->addFilter('stripTags');
		$reference->setDecorators($decorators);
		
		$referenceEmail = $this->addElement('text','reference_email',
				array('label' => 'Please provide an email address for your referee:', 'size' => '40'))->reference_email;
		$referenceEmail->setRequired(false)
				  ->addFilter('stripTags')
				  ->addValidator('emailAddress');
		$referenceEmail->setDecorators($decorators);		
		


        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Set my account up on Beowulf');
        $submit->clearDecorators();
        $submit->addDecorators(array(
            array('ViewHelper'),    // element's view helper
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit')),
        ));
		$submit->setAttrib('class','large');
        $this->addElement($submit);


$this->addDisplayGroup(array('username','password','first_name','last_name','fullname','email','institution','research_outline','reference','reference_email'), 'userdetails');
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