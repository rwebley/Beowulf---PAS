<?php
/** Form for logging into the system
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class LoginForm extends Pas_Form {

public function init() {
       
//
//   $this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
//   $this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
//   $this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 
//		     

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
		    );
	$this->setName('login');
	
	$username = $this->addElement('text', 'username',array('label' => 'Username: '));
	$username = $this->getElement('username')
	->setRequired(true)
	->addFilters(array('StringTrim', 'StripTags'))
	->addValidator('Authorise')
	->setAttrib('size','20')
	->setDecorators($decorators);

	$password = $this->addElement('password', 'password',array('label' => 'Password: '));
	$password = $this->getElement('password')
	->addValidator('StringLength', true, array(3))
	->setRequired(true)
	->setAttrib('size','20')
	->addFilters(array('StringTrim', 'StripTags'))
	->setDecorators($decorators);
	$password->getValidator('StringLength')
	->setMessage('Your password is too short');
				
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);

	$submit = $this->addElement('submit', 'submit' , array('label' => 'Login...'));
	$submit = $this->getElement('submit');
	$submit->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')
	->setAttrib('class','large');

	$this->addDisplayGroup(array('username','password'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');

	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');

	$this->details->setLegend('Login: ');
    }
}