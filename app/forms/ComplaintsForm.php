<?php
class ComplaintsForm extends Pas_Form
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
			

$this->setName('complaints');

$comment_author_IP = new Zend_Form_Element_Hidden('comment_author_IP');
$comment_author_IP->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->removeDecorator('Label');

$comment_author_IP->setValue($_SERVER['REMOTE_ADDR']);

$comment_agent = new Zend_Form_Element_Hidden('comment_agent');
$comment_agent->removeDecorator('HtmlTag')
->removeDecorator('DtDdWrapper')
->removeDecorator('Label');

$comment_agent->setValue($_SERVER['HTTP_USER_AGENT'])
->setRequired(false);



$comment_author = new Zend_Form_Element_Text('comment_author');
$comment_author->setLabel('Enter your name: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid name!')
->setDecorators($decorators);

$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
$comment_author_email->setLabel('Enter your email address: ')
->setDecorators($decorators)
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('EmailAddress')   
->addFilter('StringToLower')
->addErrorMessage('Please enter a valid email address!')
->setDescription('* This will not be displayed to the public.');

$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
$comment_author_url->setLabel('Enter your web address: ')
->setDecorators($decorators)
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addFilter('StringToLower')
->addErrorMessage('Please enter a valid address!')
->setDescription('Not compulsory');


$comment_content = new Zend_Form_Element_Textarea('comment_content');
$comment_content->setLabel('Enter your comment: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttrib('rows',10)
->setAttrib('cols',80)
->addFilter('HtmlBody')
->addErrorMessage('Please enter something in the comments box!');

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
					
			
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class','large')
			  ->setLabel('Submit your query');
			  
			  
$auth = Zend_Auth::getInstance();
if(!$auth->hasIdentity())
{
$this->addElements(array(
$comment_author_IP,
$comment_agent,
$comment_author,
$comment_author_email,
$comment_content,
$comment_author_url,
$captcha,
$submit));

$this->addDisplayGroup(array('comment_author','comment_author_email','comment_author_url','comment_content','captcha'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Enter your comments: ');

}
else {
$user = $auth->getIdentity();
$comment_author->setValue($user->fullname);
$comment_author_email->setValue($user->email);

$this->addElements(array(
$comment_author_IP,
$comment_agent,
$comment_author,
$comment_author_email,
$comment_content,
$comment_author_url,
$submit));

$this->addDisplayGroup(array('comment_author','comment_author_email','comment_author_url','comment_content'), 'details')
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