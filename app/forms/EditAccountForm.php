<?php
/** Form for editing a user's account details
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class EditAccountForm extends Pas_Form
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
        $roles = new Roles();
		$role_options = $roles->getRoles();
		$inst = new Institutions();
		$inst_options = $inst->getInsts();
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
		 ->addFilters(array('StripTags', 'StringTrim'))
				->setRequired(true);
				

        $firstName = $this->addElement('text', 'first_name', 
            array('label' => 'First Name', 'size' => '30'))->first_name;
        $firstName->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum', false, array('allowWhiteSpace' => true))
		->addErrorMessage('You must enter a firstname');
		$firstName->setDecorators($decorators);

        $lastName = $this->addElement('text', 'last_name', 
            array('label' => 'Last Name', 'size' => '30'))
		->last_name;
        $lastName->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum', false, array('allowWhiteSpace' => true))
		->addErrorMessage('You must enter a surname');
        $lastName->setDecorators($decorators);

        $fullname = $this->addElement('text', 'fullname', 
		array('label' => 'Preferred Name: ', 'size' => '30'))
		->fullname;
        $fullname->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Alnum', false, array('allowWhiteSpace' => true))
		->addErrorMessage('You must enter your preferred name');
        $fullname->setDecorators($decorators);

        $email = $this->addElement('text', 'email',array('label' => 'Email Address', 'size' => '30'))
        ->email;
        $email->addValidator('EmailAddress')
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->setRequired(true)
		->addErrorMessage('Please enter a valid address!');
        $email->setDecorators($decorators);
		
		$password = $this->addElement('password', 'password',array('label' => 'Change password: ', 
		'size' => '30'))
		->password;
        $password->setRequired(false);
        $password->setDecorators($decorators);
		
		$institution = $this->addElement('select', 'institution',array('label' => 'Recording institution: '))->institution;
        $institution->setDecorators($decorators);
		$institution->addMultiOptions(array(NULL => NULL, 'Choose institution' => $inst_options));
		
		$role = $this->addElement('select', 'role',array('label' => 'Site role: '))->role;
        $role->setDecorators($decorators);
		$role->addMultiOptions(array(NULL => NULL,'Choose role' => $role_options));

		$person = $this->addElement('text', 'person',array('label' => 'Personal details attached: '))->person;
        $person->setDecorators($decorators);
		
		$peopleID = $this->addElement('hidden', 'peopleID',array())->peopleID;
        $peopleID->setDecorators($decorators);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->clearDecorators();
        $submit->addDecorators(array(
            array('ViewHelper'),    // element's view helper
            array('HtmlTag', array('tag' => 'div', 'class' => 'submit')),
        ));
		$submit->setAttrib('class','large');
        $this->addElement($submit);

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array('username','first_name','last_name','fullname','email','institution','role','password','person','peopleID'), 'userdetails');
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