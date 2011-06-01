<?php
class PublishCommentFindForm extends Pas_Form
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



$comment_findID = new Zend_Form_Element_Hidden('comment_findID');
$comment_findID->setDecorators(array(
    array('ViewHelper'),
    array('Description', array('tag' => '')),
    array('Errors'),
    array('HtmlTag', array('tag' => 'p')),
    array('Label', array('tag' => ''))
));

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
->setDescription('* Not compulsory');


$comment_content = new Pas_Form_Element_RTE('comment_content');
$comment_content->setLabel('Enter your comment: ')
->setRequired(true)
->addFilter('StringTrim')
->setAttrib('rows',10)
->setAttrib('cols',80)
->addFilter('BasicHtml')
->addErrorMessage('Please enter something in the comments box.');

$submit = new Zend_Form_Element_Submit('submit');
$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
              ->removeDecorator('HtmlTag')
			  ->removeDecorator('DtDdWrapper')
			  ->setAttrib('class', 'large');
$approval = new Zend_Form_Element_Radio('approval');
$approval->setLabel('What would you like to do? ')
->addMultiOptions(array('spam' => 'Set as spam','ham' => 'Submit ham?','approved' => 'Publish it?','delete' => 'Delete it?'))
->setValue('approved')
->addFilter('StripTags')
->addFilter('StringTrim')
->setOptions(array('separator' => ''))
->setDecorators($decorators);			  

$this->addElements(array(
$comment_author_IP,
$comment_agent,
$comment_author,
$comment_author_email,
$comment_content,
$comment_author_url,
$comment_findID,
$approval,
$submit));

$this->addDisplayGroup(array('comment_author','comment_author_email','comment_author_url','comment_content','approval','comment_findID'), 'details');

$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
$this->details->removeDecorator('HtmlTag');
$this->details->removeDecorator('DtDdWrapper');
$this->details->setLegend('Enter your comments: ');

$this->addDisplayGroup(array('submit'), 'submit');
}
}