<?php
class CommentReplyFindForm extends Pas_Form
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
            array('Errors',array('placement' => 'append','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
		    );
			

$this->setAttrib('accept-charset', 'UTF-8');
       
	   $this->setDecorators(array(
            'FormElements',
         
            'Form',
			
        ));
$this->setName('comments');

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


/**

$comment_findID = new Zend_Form_Element_Hidden('comment_findID');
$comment_findID->setDecorators(array(
    array('ViewHelper'),
    array('Description', array('tag' => '')),
    array('Errors'),
    array('HtmlTag', array('tag' => 'p')),
    array('Label', array('tag' => ''))
));

**/

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
->setDescription('* This will not be displayed to the public y\'all!');

$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
$comment_author_url->setLabel('Enter your web address: ')
->setDecorators($decorators)
->setRequired(false)
->addFilter('StripTags')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->addFilter('StringToLower')
->addErrorMessage('Please enter a valid address!')
->setDescription('* Not compulsory');


$comment_content = new Pas_Form_Element_TinyMce('comment_content');
$comment_content->setLabel('Enter your comment: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttrib('rows',10)
->setAttrib('cols',80)
->setAttrib('class','expanding')
->addFilter('HtmlBody')
->addErrorMessage('Please enter something in the comments box!');

$config = new Zend_Config_Ini('../app/config/config.ini','general');
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
			  ->removeDecorator('DtDdWrapper');
			  
			  
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

$this->addDisplayGroup(array('comment_author','comment_author_email','comment_author_url','comment_content','captcha','submit'), 'details');
}
else {
$this->addElements(array(
$comment_author_IP,
$comment_agent,
$comment_author,
$comment_author_email,
$comment_content,
$comment_author_url,
$submit));

$this->addDisplayGroup(array('comment_author','comment_author_email','comment_author_url','comment_content','submit'), 'details');

}
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('HtmlTag');
$this->details->removeDecorator('DtDdWrapper');

$this->details->setLegend('Enter your comments: ');
}
}