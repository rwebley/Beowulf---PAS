<?php
/** Form for submitting complaints about the Scheme
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ComplaintsForm extends Pas_Form {
	public function __construct($options = null)
	{

	parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
    		array('Description', array('tag' => '','placement' => 'append')),
            array('Errors',array('placement' => 'append','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
		    );
			

	$this->setName('complaints');

	$user_ip = new Zend_Form_Element_Hidden('user_ip');
	$user_ip->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label')
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->setValue($_SERVER['REMOTE_ADDR'])
	->addValidator('Ip')
	->setRequired(true);

	$user_agent = new Zend_Form_Element_Hidden('user_agent');
	$user_agent->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->addFilters(array('StripTags','StringTrim'))
	->removeDecorator('Label')
	->setValue($_SERVER['HTTP_USER_AGENT'])
	->setRequired(false);

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
	->addFilters(array('StripTags', 'StringTrim', 'StringToLower'))
	->addValidator('EmailAddress')   
	->addErrorMessage('Please enter a valid email address!')
	->setDescription('This will not be displayed to the public.');

	$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
	$comment_author_url->setLabel('Enter your web address: ')
	->setDecorators($decorators)
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim', 'StringToLower'))
	->addValidator('NotEmpty')
	->addErrorMessage('Please enter a valid address!')
	->setDescription('Not compulsory');

	$comment_content = new Pas_Form_Element_RTE('comment_content');
	$comment_content->setLabel('Enter your comment: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',80)
	->addFilters(array('HtmlBody','EmptyParagraph','WordChars'))
	->addErrorMessage('Please enter something in the comments box!');

	$privateKey = $this->_config->webservice->recaptcha->privatekey;
	$pubKey = $this->_config->webservice->recaptcha->pubkey;

	$captcha = new Zend_Form_Element_Captcha('captcha', array(  
                        		'captcha' => 'ReCaptcha',
								'label' => 'Please prove you are not a spammer',
                                'captchaOptions' => array(  
                                'captcha' => 'ReCaptcha',								  
                                'privKey' => $privateKey,
                                'pubKey' => $pubKey,
								'theme'=> 'clean')
                        ));
					
			
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
	              ->removeDecorator('HtmlTag')
				  ->removeDecorator('DtDdWrapper')
				  ->setAttrib('class','large')
				  ->setLabel('Submit your query');
				  

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	$auth = Zend_Auth::getInstance();
	if(!$auth->hasIdentity()){
	$this->addElements(array(
	$user_ip, $user_agent, $comment_author,
	$comment_author_email, $comment_content, $comment_author_url,
	$captcha, $submit));

	$this->addDisplayGroup(array('comment_author', 'comment_author_email', 'comment_author_url',
	'comment_content','captcha'), 'details')
	->removeDecorator('HtmlTag');

	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Enter your comments: ');
	} else {
	$user = $auth->getIdentity();
	$comment_author->setValue($user->fullname);
	$comment_author_email->setValue($user->email);
	$this->addElements(array(
	$comment_author_IP, $comment_agent, $comment_author,
	$comment_author_email, $comment_content, $comment_author_url,
	$submit));

	$this->addDisplayGroup(array('comment_author', 'comment_author_email', 'comment_author_url',
	'comment_content'), 'details')
	->removeDecorator('HtmlTag');

	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Enter your comments: ');
	}
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	}
}