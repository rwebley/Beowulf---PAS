<?php
/** Form for submitting and editing content for static pages
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ContentForm extends Pas_Form
{
public function __construct($options = null)
{
	$authors = new Users();
	$authorOptions = $authors->getAuthors();
	
	parent::__construct($options);
	 $decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	
	$this->setAttrib('enctype', 'multipart/form-data');
	$this->setDecorators(array('FormElements','Form'));
	$this->setName('addcontent');
	
	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Content Title: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',60)
	->addValidator('NotEmpty')
	->addErrorMessage('You must enter a title')
	->setDecorators($decorators);
	
	$menuTitle = new Zend_Form_Element_Text('menuTitle');
	$menuTitle->setLabel('Menu Title: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',60)
	->addValidator('NotEmpty')
	->addErrorMessage('You must enter a title')
	->setDecorators($decorators);
	
	$author = new Zend_Form_Element_Select('author');
	$author->setLabel('Set the author of the article: ')
	->addMultiOptions(array('Choose an author' => $authorOptions))
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('NotEmpty')
	->addErrorMessage('You must choose an author')
	->setDecorators($decorators);
	
	$excerpt = new Zend_Form_Element_Textarea('excerpt');
	$excerpt->setLabel('Optional excerpt: ')
	->setRequired(false)
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->addFilters(array('StripTags','StringTrim'));
	
	$body = new Pas_Form_Element_RTE('body');
	$body->setLabel('Main body of text: ')
	->setRequired(true)
	->setAttrib('rows',30)
	->setAttrib('cols',60)
	->addErrorMessage('You must enter a main body of text')
	->addFilter('HtmlBody')
	->setAttrib('Height',400)
	->addFilter('EmptyParagraph')
	->addFilter('StringTrim')
	->addFilter('WordChars');
	
	$section = new Zend_Form_Element_Select('section');
	$section->setLabel('Set site section to appear under: ')
	->addMultiOptions(array(
	'index' => 'Home page',
	'info' => 'Site information',
	'staffs' => 'Staffordshire Hoard Symposium',
	'getinvolved' => 'Get involved',
	'frg' => 'Voluntary recording guide',
	'byzantinecoins' => 'Byzantine coin guide',
	'greekromancoins' => 'Greek and Roman coin guide',
	'conservation' => 'Conservation pages',
	'news' => 'News',
	'reviews' => 'Scheme reviews',
	'reports' => 'Annual reports',
	'treports' => 'Treasure annual reports',
	'romancoins' => 'Roman coin guide',
	'ironagecoins' => 'Iron Age coin guide',
	'earlymedievalcoins' => 'Early Medieval coin guide',
	'medievalcoins' => 'Medieval coin guide',
	'postmedievalcoins' => 'Post Medieval coin guide',
	'research' => 'Research',
	'api' => 'Applications Programming Interface',
	'databasehelp' => 'Database help',
	'events' => 'Events',
	'treasure' => 'Treasure',
	'help' => 'Help section',
	'publications' => 'Publications',
	'database' => 'Database front page',
	'oai' => 'OAI instructions',
	'bronzeage' => 'Bronze Age guide'))
	->setDecorators($decorators)
	->setRequired(true)
	->addErrorMessage('You must choose a section for this to be filed under');
	
	$parentcontent = new Zend_Form_Element_Select('parent');
	$parentcontent->setLabel('Does this have a parent?: ')
	->setDecorators($decorators)
	->setRequired(false);
	
	$metaKeywords = new Zend_Form_Element_Textarea('metaKeywords');
	$metaKeywords->setLabel('Meta keywords: ')
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->addFilters(array('StripTags','StringTrim'))
	->setRequired(true);
	
	$metaDescription = new Zend_Form_Element_Textarea('metaDescription');
	$metaDescription->setLabel('Meta description: ')
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->addFilters(array('StripTags','StringTrim'))
	->setRequired(true);
	
	$publishState = new Zend_Form_Element_Select('publishState');
	$publishState->setLabel('Publishing status: ')
	->addMultiOptions(array('Please choose publish state' => array('1' => 'Draft',
	'2' => 'Admin to review', '3' => 'Published')))
	->setValue(1)
	->setDecorators($decorators)
	->setRequired(true);
	
	$slug = new Zend_Form_Element_Text('slug');
	$slug->setLabel('Page slug: ')
	->setAttrib('size',50)
	->setDecorators($decorators)
	->addFilter('UrlSlug')
	->addFilters(array('StripTags','StringTrim'))
	->setRequired(true);
	
	
	$frontPage = new Zend_Form_Element_Checkbox('frontPage');
	$frontPage->setLabel('Appear on section\'s front page?: ')
	->setDecorators($decorators)
	->addValidator('NotEmpty','Integer')
	->setRequired(true);
	
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setAttrib('class','large');
	$submit->removeDecorator('DtDdWrapper');
	$submit->removeDecorator('HtmlTag');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	$this->addElements(array(
	$title, $author, $body,
	$section, $publishState, $excerpt,
	$metaKeywords, $metaDescription,
	$slug, $frontPage, $submit,
	$menuTitle ));
	
	$this->addDisplayGroup(array('title', 'menuTitle', 'author',
	'body', 'section', 'publishState',
	'excerpt', 'metaKeywords', 'metaDescription',
	'slug','frontPage'), 'details')->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	
	
	$this->addDisplayGroup(array('submit'), 'submit')->removeDecorator('HtmlTag');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	$this->details->setLegend('Add new site content');

}
}