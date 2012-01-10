<?php
/** Form for publishing comments on finds
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PublishCommentFindForm extends Pas_Form {

public function __construct($options = null) {

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
		->addFilters(array('StripTags','StringTrim'))
		->removeDecorator('DtDdWrapper')
		->removeDecorator('Label')
		->setValue($_SERVER['REMOTE_ADDR'])
		->addValue('Ip');

	$comment_agent = new Zend_Form_Element_Hidden('comment_agent');
	$comment_agent->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('Label')
		->setValue($_SERVER['HTTP_USER_AGENT'])
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'));

	$comment_findID = new Zend_Form_Element_Hidden('comment_findID');
	$comment_findID->addFilters(array('StripTags','StringTrim'))
		->setDecorators(array(
	    array('ViewHelper'),
	    array('Description', array('tag' => '')),
	    array('Errors'),
	    array('HtmlTag', array('tag' => 'p')),
	    array('Label', array('tag' => ''))
	));
	
	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->addErrorMessage('Please enter a valid name!')
		->setDecorators($decorators);

	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
		->setDecorators($decorators)
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->addValidator('EmailAddress',false,array('mx' => true))   
		->addErrorMessage('Please enter a valid email address!')
		->setDescription('* This will not be displayed to the public.');

	$comment_author_url = new Zend_Form_Element_Text('comment_author_url');
	$comment_author_url->setLabel('Enter your web address: ')
		->setDecorators($decorators)
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->addErrorMessage('Please enter a valid address!')
		->addValidator('Url')
		->setDescription('* Not compulsory');


	$comment_content = new Pas_Form_Element_RTE('comment_content');
	$comment_content->setLabel('Enter your comment: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setAttrib('class', 'large');
		
	$approval = new Zend_Form_Element_Radio('comment_approval');
	$approval->setLabel('What would you like to do? ')
		->addMultiOptions(array('spam' => 'Set as spam','ham' => 'Submit ham?','approved' => 'Publish it?','delete' => 'Delete it?'))
		->setValue('approved')
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->setOptions(array('separator' => ''))
		->setDecorators($decorators);			  

	$this->addElements(array(
	$comment_author_IP, $comment_agent, $comment_author,
	$comment_author_email, $comment_content, $comment_author_url,
	$comment_findID, $approval, $submit)
	);

	$this->addDisplayGroup(array(
	'comment_author','comment_author_email','comment_author_url',
	'comment_content','comment_approval','comment_findID'), 'details');
	
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('HtmlTag');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Enter your comments: ');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	}
}