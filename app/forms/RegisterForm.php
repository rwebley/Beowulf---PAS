<?php


class RegisterForm extends Pas_Form
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
             ->setAttrib('id', 'registerform');

        $this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
		$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
		
		
        $decorators = array(
	
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'li')),
			
        );

 $this->addElement('rawText', 'text1', array(
        'value' => '<p class="info">By registering you agree to these <a href="#toc" rel="facebox">terms and conditions</a> of the database.</p>',
    )); 

        $username = $this->addElement('Text', 'username', 
            array('label' => 'Username'))->username;
        $username = $this->getElement('username')
                ->addValidator('usernameUnique', true, 
                array('id','username','id','Users'))
				 ->addValidator('stringLength', true, array(4))
                 ->addValidator('alnum')
                 ->setRequired($required)
                 ->addFilter('StringToLower')
				 ->addValidator('Db_NoRecordExists', false, array('table' => 'users',
                                                               'field' => 'username'))
				 ->setDescription('Username must be more than 3 characters and include only letters and numbers');
        $username->getValidator('alnum')->setMessage('Your username must be letters and digits only');
        $username->setDecorators($decorators);

        $password = $this->addElement('Password', 'password', 
            array('label' => 'Password'))->password;
        $password->setDescription('Password must be longer than 6 characters and must include letters and numbers i.e. p4ssw0rd')
                 ->addValidator('stringLength', true, array(6))
                 ->addValidator('regex', true, array('/^(?=.*\d)(?=.*[a-zA-Z]).{6,}$/'))
                 ->setRequired($required)
				 ->addErrorMessage('Please enter a valid password!');
        $password->getValidator('stringLength')->setMessage('Password is too short');
        $password->getValidator('regex')->setMessage('Password does not contain letters and numbers');
        $password->setDecorators($decorators);

        $firstName = $this->addElement('Text', 'first_name', 
            array('label' => 'First Name', 'size' => '30'))->first_name;
        $firstName->setRequired(true)
                  ->addFilter('stripTags')
				  ->addErrorMessage('You must enter a firstname');
				  $firstName->setDecorators($decorators);

        $lastName = $this->addElement('Text', 'last_name', 
            array('label' => 'Last Name', 'size' => '30'))->last_name;
        $lastName->setRequired(true)
                 ->addFilter('stripTags')
				 ->addErrorMessage('You must enter a surname');
        $lastName->setDecorators($decorators);

        $preferredName = $this->addElement('Text', 'preferred_name', 
            array('label' => 'Preferred Name', 'size' => '30'))->preferred_name;
        $preferredName->setDescription('e.g. Joe Brown rather than Joseph Brown')
                      ->setRequired(true)
                      ->addFilter('stripTags')
					  ->addErrorMessage('You must enter your preferred name');
        $preferredName->setDecorators($decorators);

        $email = $this->addElement('Text', 'email', 
            array('label' => 'Email Address', 'size' => '30'))->email;
        $email->addValidator('emailAddress')
			  ->setRequired(true)
			  ->addValidator('Db_NoRecordExists', false, array('table' => 'users',
                                                               'field' => 'email'));
        $email->setDecorators($decorators);
	/* 	
		$higherLevel = $this->addElement('Checkbox','higher_level', 
				array('label' => 'Do you want higher level access?'))->higher_level;
		$higherLevel->setRequired(false)
                 ->addFilter('stripTags')->setDecorators($decorators)->setCheckedValue(1);
		
		
		$researchOutline = $this->addElement('Textarea','research_outline', 
				array('validators' => array(new Pas_Validate_FieldDepends('higher_level')),'allowEmpty' => false,'label' => 'Outline your research', 'rows' => 10, 'cols' => 70, 'description' => 'If you would like a research account, please fill in this box. We can only give research access to people with valid proposals. The terms under which you can gain access are available online and subject to change.
Valid reasons for asking for research accounts are laid out below.'))->research_outline;
		$researchOutline->setRequired(false)
		
						->addFilter('BasicHtml');
		
		
		
		$reference = $this->addElement('Text','reference',
				array('validators' => array(new Pas_Validate_FieldDepends('higher_level')),'allowEmpty' => false,'label' => 'Please provide a referee:', 'size' => '40','description' => 'We ask you to provide a referee who can substantiate your request for higher level access. Ideally they will be an archaeologist of good standing.'))->reference;
		$reference->setRequired(false)
				  ->addFilter('stripTags');
		$reference->setDecorators($decorators);
		
		$referenceEmail = $this->addElement('Text','reference_email',
				array('label' => 'Please provide an email address for your referee:','validators' => array(new Pas_Validate_FieldDepends('higher_level')),'allowEmpty' => false,'size' => '40'))->reference_email;
		$referenceEmail->setRequired(false)
				  ->addFilter('stripTags')
				  ->addValidator('emailAddress');
		$referenceEmail->setDecorators($decorators);		
		 */
		$config = new Zend_Config_Ini('app/config/config.ini','general');
		$privateKey = $config->recaptcha->privatekey;
		$pubKey = $config->recaptcha->pubkey;

		$captcha = new Zend_Form_Element_Captcha('captcha', array(  
                        		'captcha' => 'ReCaptcha',
								'label' => 'Prove you are not a robot you varmint!',
                                'captchaOptions' => array(  
                                'captcha' => 'ReCaptcha',								  
                                'privKey' => $privateKey,
                                'pubKey' => $pubKey,
								'theme'=> 'clean')
                        ));
		$captcha->setDescription('Due to the surge in robotic activity, we have had to introduce this software. However, by filling in this captcha, you help Carnegie Mellon University digitise old books.');
		$captcha->setDecorators(array(array('Description', array('placement' => 'append','class' => 'info')),            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li'))));	
		$captcha->addErrorMessage('You have not solved the captcha');			
		$this->addElement($captcha);


       //Submit button 
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')
->setAttrib('class', 'large')
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag')
->setLabel('Register your account...');


       
        $this->addElement($submit);

$this->addDisplayGroup(array('username','password','first_name','last_name','preferred_name','email','text1','captcha'), 'details');
$this->details->setLegend('Register with the Scheme: ');
$this->removeDecorator('DtDdWrapper');
$this->removeDecorator('HtmlTag');
$this->details->removeDecorator('DtDdWrapper');


$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');


    }
}