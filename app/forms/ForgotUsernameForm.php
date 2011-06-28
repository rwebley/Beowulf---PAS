<?php
/** Form for retrieval of username via email
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class ForgotUsernameForm extends Pas_Form {

	public function init() {

	$this->clearDecorators();
	
	$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 

	$decorators = array(
            array('ViewHelper'), 
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label', array('requiredSuffix' => ' *', 'class' => 'leftalign')),
            array('HtmlTag', array('tag' => 'li')),
        );

	$email = $this->addElement('Text', 'email', 
	array('label' => 'Email Address: ', 'size' => '30'))->email;
	$email->addValidator('emailAddress')
	->setRequired(true)
	->addErrorMessage('Please enter a valid address!')
	->addValidator('Db_RecordExists', false,
	array('table' => 'users', 'field' => 'email'))
	->addFilters(array('StringTrim','StripTags'));
	$email->setDecorators($decorators);
	
	$submit = $this->addElement('submit', 'submit');
	$submit = $this->getElement('submit')
	->setLabel('Retrieve my password');
	$submit->setAttrib('class','large');
	$this->addDisplayGroup(array('email'), 'details');
		
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');

	$this->setLegend('Reset my password: ');
	$this->addDisplayGroup(array('submit'),'submit');
	$this->submit->removeDecorator('Label');
	$this->submit->removeDecorator('DtDdWrapper');
    }
}