<?php

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
			->addFilter('StripTags')
			->addFilter('BasicHtml')
			->addFilter('EmptyParagraph')
			->addFilter('StringTrim');
	
	$contents = new Pas_Form_Element_RTE('contents');
	$contents->setLabel('News story content: ')
			->setRequired(true)
			->setAttrib('rows',10)
			->setAttrib('cols',70)
			->setAttrib('Height',400)
			->addFilter('WordChars')
			->addFilter('HtmlBody')
			->addFilter('EmptyParagraph')
			->addFilter('StringTrim');
	
	$address = new Zend_Form_Element_Text('primaryNewsLocation');
	$address->setLabel('News address (puts it on map): ')
			->setRequired(true)
			->setAttrib('size',50)
			->addFilter('StripTags')
			->setDecorators($decorators);
	
	$author = new Zend_Form_Element_Text('author');
	$author->setLabel('Principal author: ')
			->setRequired(true)
			->setAttrib('size',60)
			->addErrorMessage('Please enter a title for this story.')
			->setDecorators($decorators);
	
	
	$contactEmail = new Zend_Form_Element_Text('contactEmail');
	$contactEmail->setLabel('Contact email address: ')
			->setRequired(false)
			->setAttrib('size',50)
			->addErrorMessage('Please enter a title for this story.')
			->setDecorators($decorators);
	
	$contactName = new Zend_Form_Element_Text('contactName');
	$contactName->setLabel('Contact name: ')
			->setRequired(false)->setAttrib('size',50)
			->addErrorMessage('Please enter a title for this story.')
			->setDecorators($decorators);
	
	$contactTel = new Zend_Form_Element_Text('contactTel');
	$contactTel->setLabel('Contact telephone number: ')
			->setRequired(false)
			->addErrorMessage('Please enter a valid telephone number.')
			->setDecorators($decorators);
	
	$keywords = new Zend_Form_Element_Text('keywords');
	$keywords->setLabel('Keywords for the story: ')
			->setRequired(false)
			->setAttrib('size',50)
			->addErrorMessage('Please enter a valid tags.')
			->setDecorators($decorators);
	
	$golive = new ZendX_JQuery_Form_Element_DatePicker('golive');
	$golive->setLabel('News story to go live: ')
			->setRequired(true)
			->setJQueryParam('dateFormat', 'yy-mm-dd')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty')
			->setAttrib('size', 20)
			->removeDecorator('DtDdWrapper')
			->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'));
	
	
	$publishstate = new Zend_Form_Element_Radio('publish_state');
	$publishstate->setLabel('Publication state: ')
			->addMultiOptions(array('0' => 'Draft','1' => 'Publish',))
			->setValue(1)
			->addFilter('StripTags')
			->addFilter('StringTrim')
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

	$this->addDisplayGroup(array('title', 'summary', 'contents', 
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