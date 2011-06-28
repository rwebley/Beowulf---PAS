<?php
/** Form for submitting an error
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class CommentOnErrorFindForm extends Pas_Form
{



public function __construct($options = null)
{

parent::__construct($options);

	$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
	$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element/', 'element'); 
	$this->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator/', 'decorator'); 
	$decorator =  array('SimpleInput');
	$decoratorSelect =  array('SelectInput');
	$decorators = array(
	            array('ViewHelper'), 
	    		array('Description', array('tag' => '','placement' => 'append')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
	            array('HtmlTag', array('tag' => 'li')),
			    );
			

	$this->setAttrib('accept-charset', 'UTF-8');
       

	$this->setName('comments');

	$comment_author_IP = new Zend_Form_Element_Hidden('comment_author_IP');
	$comment_author_IP->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label')
	->addFilters(array('StripTags','StringTrim'))
	->setRequired(true)
	->addValidator('Ip')
	->setValue($_SERVER['REMOTE_ADDR']);

	$comment_agent = new Zend_Form_Element_Hidden('comment_agent');
	$comment_agent->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label')
	->addFilters(array('StripTags','StringTrim'))
	->setValue($_SERVER['HTTP_USER_AGENT'])
	->setRequired(true);

	$comment_subject = new Zend_Form_Element_Hidden('comment_subject');
	$comment_subject->removeDecorator('HtmlTag')
	->addFilters(array('StripTags','StringTrim'))
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label')
	->setRequired(true);

	$comment_findID = new Zend_Form_Element_Hidden('comment_findID');
	$comment_findID->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidators(array('Int'))
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label');

	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addErrorMessage('Please enter a valid name!')
	->setDecorators($decorators);

	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
	->setDecorators($decorators)
	->setRequired(true)
	->setAttrib('size',40)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addValidator('EmailAddress')   
	->addErrorMessage('Please enter a valid email address!')
	->setDescription('* This will not be displayed to the public');

	$comment_type = new Zend_Form_Element_Select('comment_type');
	$comment_type->setLabel('Error type: ')
	->setRequired(true)
	->setDecorators($decorators)
	->addMultiOptions(array(NULL => NULL,'Choose error type' => array(
	'Incorrect ID' => 'Incorrect identification',
	'More info' => 'I have further information',
	'Incorrect image' => 'Incorrect image',
	'Incorrect parish' => 'Incorrect parish',
	'Grid reference issues' => 'Grid reference wrong',
	'Date found wrong' => 'Date of discovery wrong',
	'Spelling errors' => 'Spelling errors',
	'Duplicated record' => 'Duplicated record',
	'Data problems apparent' => 'Data problems',
	'Other' => 'Other reason')))
	->addErrorMessage('You must enter an error report type');

	$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
	$comment_author_url->setLabel('Enter your web address: ')
	->setDecorators($decorators)
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addErrorMessage('Please enter a valid address!')
	->setDescription('* Not compulsory');


	$comment_content = new Pas_Form_Element_RTE('comment_content');
	$comment_content->setLabel('Enter your comment: ')
	->setRequired(true)
	->addFilter('StringTrim')
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Basic')
	->addFilter('StringTrim')
	->addFilter('WordChars')
	->addFilter('HtmlBody')
	->addFilter('EmptyParagraph')
	->addErrorMessage('Please enter something in the comments box!');

	$config = Zend_registry::get('config');
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
						
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
		
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper');

	$auth = Zend_Auth::getInstance();

	if(!$auth->hasIdentity()) {
	$this->addElements(array(
	$comment_findID, $comment_author_IP, $comment_agent,
	$comment_subject, $comment_author, $comment_author_email,
	$comment_content, $comment_author_url, $comment_type,
	$captcha, $submit));


	$this->addDisplayGroup(array('comment_author','comment_author_email','comment_author_url',
	'comment_type','comment_content','captcha',
	'submit'), 'details');
	} else {
	$this->addElements(array(
	$comment_findID, $comment_subject, $comment_author_IP,
	$comment_agent, $comment_author, $comment_author_email,
	$comment_content, $comment_author_url, $comment_type,
	$submit));

	$this->addDisplayGroup(array('comment_author', 'comment_author_email', 'comment_author_url',
	'comment_type', 'comment_content', 'submit'), 'details');
	}
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('HtmlTag');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Enter your error report: ');
	}
}