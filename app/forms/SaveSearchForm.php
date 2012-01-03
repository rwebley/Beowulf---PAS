<?php
/** Form for saving your searches
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SaveSearchForm extends Pas_Form {
	
public function __construct($options = null) {
	parent::__construct($options);
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
			    
	$this->setName('savesearch'); 
	
	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Search title : ')
		->setRequired(true)
		->addFilters(array('StriptTags', 'StringTrim'))
		->setAttrib('size',30)
		->addErrorMessage('Please enter a valid title!')
		->setDecorators($decorators);
	
	$description = new Zend_Form_Element_Textarea('description');
	$description->setLabel('Description of search: ')
		->setRequired(true)
		->addFilters(array('BasicHtml', 'StringTrim', 'WordChars', 'EmptyParagraph'))
		->setAttribs(array('rows' => 10, 'cols' => 30))
		->addErrorMessage('Please enter a valid description!')
		->setDecorators($decorators);
	
	$public = new Zend_Form_Element_Checkbox('public');
	$public->setLabel('Show this to public users?: ')
		->setRequired(true)
		->setDecorators($decorators)
		->addErrorMessage('You must set the status of this search')
		->setDescription('By setting this option, you can show this search on your profile and allow others to make use of it');
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
		->setAttribs(array('class' => 'large', 'order' => 93))
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
		
	$this->addElements(array($title, $description, $public, $submit, $hash));
	
	$this->addDisplayGroup(array('title','description','public'), 'details')
			->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Save this search');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	}
}
