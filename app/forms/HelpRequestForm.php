<?php
/** Form for contacting the Scheme for help
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class HelpRequestForm extends Pas_Form
{

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
			

	$this->setName('comments');

	$user_ip = new Zend_Form_Element_Hidden('user_ip');
	$user_ip->removeDecorator('HtmlTag')
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label')
	->setValue($_SERVER['REMOTE_ADDR'])
	->addValidator('Ip');

	$user_agent = new Zend_Form_Element_Hidden('user_agent');
	$user_agent->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label')
	->setValue($_SERVER['HTTP_USER_AGENT'])
	->setRequired(false);

	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('NotEmpty')
	->addErrorMessage('Please enter a valid name!')
	->setDecorators($decorators)
	->setDescription('If you are offering us SEO services, you will be added to the akismet spam list.');

	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
	->setDecorators($decorators)
	->setRequired(true)
	->addValidator('EmailAddress')   
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addErrorMessage('Please enter a valid email address!')
	->setDescription('* This will not be displayed to the public.');



	$comment_content = new Pas_Form_Element_RTE('comment_content');
	$comment_content->setLabel('Enter your comment: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'))
	->addErrorMessage('Please enter something in the comments box!');

	$privateKey = $this->_config->webservice->recaptcha->privatekey;
	$pubKey = $this->_config->webservice->recaptcha->pubkey;

	$captcha = new Zend_Form_Element_Captcha('captcha', array(  
                        		'captcha' => 'ReCaptcha',
								'label' => 'Please fill in this reCaptcha to show you are not a spammer!',
                                'captchaOptions' => array(  
                                'captcha' => 'ReCaptcha',								  
                                'privKey' => $privateKey,
                                'pubKey' => $pubKey,
								'theme'=> 'clean')
                        ));
                        
                        
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
				
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->setAttrib('class','large')
	->setLabel('Submit your query');
			  
			  
	$auth = Zend_Auth::getInstance();
	if(!$auth->hasIdentity()) {
	$this->addElements(array(
	$user_ip, $user_agent, $comment_author,
	$comment_author_email, $comment_content,
	$comment_author_url, $captcha, $submit));
	
	$this->addDisplayGroup(array(
	'comment_author', 'comment_author_email', 'comment_content', 'captcha'), 'details')
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
	$user_ip,	$user_agent, $comment_author,
	$comment_author_email, $comment_content,$comment_author_url,
	$submit));
	
	$this->addDisplayGroup(array(
	'comment_author', 'comment_author_email', 'comment_content'), 'details')
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