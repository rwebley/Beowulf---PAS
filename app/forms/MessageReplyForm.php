<?php
/** Form for replying to messages sent via public users
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MessageReplyForm extends Pas_Form {

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

	$comment_author = new Zend_Form_Element_Text('comment_author');
	$comment_author->setLabel('Enter your name: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid name!')
		->setDecorators($decorators);

	$comment_author_email = new Zend_Form_Element_Text('comment_author_email');
	$comment_author_email->setLabel('Enter your email address: ')
		->setDecorators($decorators)
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim', 'StringToLower'))
		->addValidator('EmailAddress')   
		->addErrorMessage('Please enter a valid email address!')
		->setDescription('* This will not be displayed to the public.');

	$comment_content = new Pas_Form_Element_RTE('comment_content');
	$comment_content->setLabel('Message submitted by user: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$messagetext = new Pas_Form_Element_RTE('messagetext');
	$messagetext->setLabel('Your reply: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setAttrib('class', 'large');

	$this->addElements(array(
		$comment_author, $comment_author_email,
		$comment_content, $messagetext, $submit
		));

	$this->addDisplayGroup(array(
	'comment_author','comment_author_email','comment_author_url',
	'comment_content','messagetext'), 
	'details');
	
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('HtmlTag');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->setLegend('Enter your comments: ');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	}
}