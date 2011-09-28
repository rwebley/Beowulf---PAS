<?php
/** Form for creating and editing news stories for the Scheme website
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class NewsStoryForm extends Pas_Form {

	public function __construct($options = null) {
	
	parent::__construct($options);
	
	ZendX_JQuery::enableForm($this);
	
	$this->setName('newsstory');
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	
	$date = Zend_Date::now()->toString('yyyy-MM-dd');
	
	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('News story title: ')
		  ->setRequired(false)
		  ->setAttrib('size',60)
		  ->addErrorMessage('Please enter a title for this story.')
		  ->setDecorators($decorators);


	$summary = new Zend_Form_Element_Textarea('summary');
	$summary->setLabel('Short summary: ')
		->setRequired(true)
		->setAttrib('rows',5)
		->setAttrib('cols',70)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));
	
	$contents = new Pas_Form_Element_RTE('contents');
	$contents->setLabel('News story content: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	$address = new Zend_Form_Element_Text('primaryNewsLocation');
	$address->setLabel('News address (puts it on map): ')
		->setRequired(true)
		->setAttrib('size',50)
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));
	
	$author = new Zend_Form_Element_Text('author');
	$author->setLabel('Principal author: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addErrorMessage('Please enter a title for this story.')
		->setDecorators($decorators)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));

	$contactEmail = new Zend_Form_Element_Text('contactEmail');
	$contactEmail->setLabel('Contact email address: ')
		->setRequired(false)
		->setAttrib('size',50)
		->addErrorMessage('Please enter a valid email.')
		->addFilters(array('StripTags','StringTrim', 'StringToLower'))
		->addValidator('EmailAddress')
		->setDecorators($decorators);
	
	$contactName = new Zend_Form_Element_Text('contactName');
	$contactName->setLabel('Contact name: ')
		->setRequired(false)
		->setAttrib('size',50)
		->addErrorMessage('Please enter a title for this story.')
		->setDecorators($decorators)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));
	
	$contactTel = new Zend_Form_Element_Text('contactTel');
	$contactTel->setLabel('Contact telephone number: ')
		->addFilters(array('StripTags','StringTrim'))
		->setRequired(false)
		->addErrorMessage('Please enter a valid telephone number.')
		->setDecorators($decorators)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true));
	
	$keywords = new Zend_Form_Element_Text('keywords');
	$keywords->setLabel('Keywords for the story: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',50)
		->addErrorMessage('Please enter a valid tags.')
		->setDecorators($decorators);
	
	$golive = new ZendX_JQuery_Form_Element_DatePicker('golive');
	$golive->setLabel('News story to go live: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->removeDecorator('DtDdWrapper')
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'));
	
	$publishstate = new Zend_Form_Element_Radio('publish_state');
	$publishstate->setLabel('Publication state: ')
		->addMultiOptions(array('0' => 'Draft','1' => 'Publish',))
		->setValue(1)
		->addFilters(array('StripTags','StringTrim'))
		->setOptions(array('separator' => ''))
		->setDecorators($decorators);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$this->addElements(array( 
	$title, $summary, $contents,
	$author, $contactEmail, $contactTel,
	$contactName, $keywords, $address,
	$golive, $publishstate, $submit));

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array(
	'title', 'summary', 'contents', 
	'author', 'contactName', 'contactTel', 
	'contactEmail', 'primaryNewsLocation', 'keywords',
	'golive', 'publish_state'), 
	'details')->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->details->setLegend('Story details: ');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	}
}