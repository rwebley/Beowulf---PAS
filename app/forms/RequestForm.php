<?php
class RequestForm extends Pas_Form
{



public function __construct($options = null)
{
$countries = new Countries();
$countries_options = $countries->getOptions();

$counties = new Counties();
$counties_options = $counties->getCountyname2();
parent::__construct($options);

$decorators = array(
            array('ViewHelper'), 
    		array('Description', array('tag' => '','placement' => 'append')),
            array('Errors',array('placement' => 'append','tag' => 'li')),
            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
            array('HtmlTag', array('tag' => 'li')),
		    );
			

$this->setName('request');

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

$email = new Zend_Form_Element_Text('email');
$email->setLabel('Enter your email address: ')
->setDecorators($decorators)
->setAttrib('size',50)
->addFilter('StripTags')
->addFilter('StringTrim');

$title = new Zend_Form_Element_Select('title');
$title->setLabel('Title: ')
->setRequired(false)
->addFilter('StripTags')
->setValue('Mr')
->addErrorMessage('Choose title of person')
->addMultiOptions(array('Mr' => 'Mr','Mrs' => 'Mrs','Miss' => 'Miss','Ms' => 'Ms','Dr' => 'Dr.','Prof' => 'Prof.','Sir' => 'Sir','Lady' => 'Lady','Other' => 'Other','Captain' => 'Captain','Master' => 'Master','Dame' => 'Dame','Duke' => 'Duke'))
->setDecorators($decorators);

$fullname = new Zend_Form_Element_Text('fullname');
$fullname->setLabel('Enter your name: ')
->setRequired(true)
->addFilter('StripTags')
->addFilter('StringTrim')
->setAttrib('size',50)
->addValidator('NotEmpty')
->addErrorMessage('Please enter a valid name!')
->setDecorators($decorators);

$address = new Zend_Form_Element_Text('address');
$address->SetLabel('Address: ')
->setRequired(true)
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

$town_city = new Zend_Form_Element_Text('town_city');
$town_city->SetLabel('Town: ')
->setRequired(true)
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

$county = new Zend_Form_Element_Select('county');
$county->setLabel('County: ')
->setRequired(false)
->addFilter('StripTags')
->setRegisterInArrayValidator(false)
->addMultiOptions(array(NULL => 'Please choose a county','Valid counties' => $counties_options))
->setDecorators($decorators);

$postcode = new Zend_Form_Element_Text('postcode');
$postcode->SetLabel('Postcode: ')
->setRequired(true)
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

// Object specifics
$county = new Zend_Form_Element_Select('county');
$county->setLabel('County: ')
->addValidators(array('NotEmpty'))
->addMultiOptions(array(NULL => NULL,'Choose county' => $counties_options))
->setDecorators($decorators);

$country = new Zend_Form_Element_Select('country');
$country->SetLabel('Country: ')
->setRequired(true)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
//->addValidator('inArray', false, array(array_keys($countries_options)))
->addMultiOptions(array(NULL => 'Please choose a country of residence','Valid countries' => $countries_options))
->setValue('GB')
->setDecorators($decorators);

$tel = new Zend_Form_Element_Text('tel');
$tel->SetLabel('Contact number: ')
->setRequired(false)
->setAttrib('size',50)
->addFilter('stringTrim')
->addValidator('stringLength', false, array(1,200))
->setDecorators($decorators);

$leaflets = new Zend_Form_Element_MultiCheckbox('leaflets');
$leaflets->setLabel('Scheme leaflets: ')
->addMultiOptions(array('Advice for finders' => 'Advice for finders','Treasure Act' => 'Treasure Act'))->setOptions(array('separator' => ''))
->setDecorators($decorators);
;

$message = new Pas_Form_Element_RawText('message');
$message->setValue('<p>Some of our literature is now rather bulky, and therefore we have to charge postage or arrange collection from your local FLO. Please tick what you would like and we will contact you about delivery if needed.</p>')
->setAttrib('class','info');

$reports = new Zend_Form_Element_MultiCheckbox('reports');			
$reports->setLabel('Annual Reports: ')
->addMultiOptions(array('Report 2000' => 'Annual report 2000 - 2001','Annual report 2001/3' => 'Annual report 2001 - 2003','Report 2003/4' => 'Annual report 2003 - 2004','Annual report 2004/5' => 'Annual report 2004 - 2005','AR 2005/6' => 'Annual Report 2005 -2006'))->setOptions(array('separator' => ''))
->setDecorators($decorators);


$treasure = new Zend_Form_Element_MultiCheckbox('treasure');
$treasure->setLabel('Treasure Reports: ')
->addMultiOptions(array('T Report 2000' => 'Report 2000','T Report 2001' => 'Report 2001','T Report 2002' => 'Report 2002','T report 2003' => 'Report 2003'))->setOptions(array('separator' => ''))
->setDecorators($decorators);


$combined = new Zend_Form_Element_MultiCheckbox('combined');
$combined->setLabel('Combined Treasure & PAS Reports: ')
->addMultiOptions(array('Report 2007' => 'Annual report 2007'))->setOptions(array('separator' => ''))
->setDecorators($decorators);


$codes = new Zend_Form_Element_MultiCheckbox('codes');
$codes->setLabel('Codes of practice: ')
->addMultiOptions(array('Responsible metal detecting' => 'Responsible Metal Detecting','Treasure CofP' => 'Treasure Code of Practice'))->setOptions(array('separator' => ''))
->setDecorators($decorators);


$maillist = new Zend_Form_Element_Checkbox('maillist');
$maillist->setLabel('Mailing list opt in: ')
->setLabel('I would like to join your mailing list: ')
->setRequired(false)
->setDecorators($decorators)
->setUncheckedValue(NULL)
->setCheckedValue(true);


			
$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class','large')
			  ->setLabel('Submit your request');
			  
			  
$auth = Zend_Auth::getInstance();
if(!$auth->hasIdentity())
{

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


$this->addElements(array(
$title,
$fullname,
$address,
$town_city,
$county,
$postcode,
$country,
$tel,
$email,
$message,
$leaflets,
$reports,
$combined,
$treasure,
$codes,
$maillist,
$captcha,

$submit));

$this->addDisplayGroup(array('title','fullname','email','address','town_city','county','postcode','country','tel','message','leaflets','reports','combined','treasure','codes','maillist','captcha'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->removeDecorator('HtmlTag');
$this->details->setLegend('Enter your comments: ');

}
else {
$this->addElements(array(
$title,
$fullname,
$address,
$town_city,
$county,
$postcode,
$country,
$tel,
$message,
$email,
$leaflets,
$reports,
$combined,
$treasure,
$codes,
$maillist,
$submit));

$this->addDisplayGroup(array('title','fullname','email','address','town_city','county','postcode','country','tel','message','leaflets','reports','combined','treasure','codes','maillist'), 'details')
->removeDecorator('HtmlTag');
$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('DtDdWrapper');
$this->details->setLegend('Enter your comments: ');
}
$this->addDisplayGroup(array('submit'), 'submit');
$this->submit->removeDecorator('DtDdWrapper');
$this->submit->removeDecorator('HtmlTag');

}
}