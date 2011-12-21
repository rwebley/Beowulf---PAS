<?php
/** Form for setting up and editing publications data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PublicationForm extends Pas_Form {

public function __construct($options = null) {
	$types = new Publicationtypes();
	$type_options = $types->getTypes();
	
	parent::__construct($options);
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
			    
	$this->setName('publication');
	
	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Publication title: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',50)
		->addErrorMessage('Please enter a publication title.')
		->setDecorators($decorators);
	
	$authors = new Zend_Form_Element_Text('authors');
	$authors->setLabel('Author names: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',50)
		->addErrorMessage('You must enter either an author\'s or an editor\'s name.')
		->setDecorators($decorators);
	
	$editors = new Zend_Form_Element_Text('editors');
	$editors->setLabel('Editor names: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',50)
		->setDecorators($decorators);
	
	$publisher = new Zend_Form_Element_Text('publisher');
	$publisher->setLabel('Publisher: ')
		->addFilters(array('StripTags','StringTrim'))
		->setRequired(true)
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->setAttrib('size',50)
		->addErrorMessage('You must enter a publisher.')
		->setDecorators($decorators);
	
	$publication_place = new Zend_Form_Element_Text('publication_place');
	$publication_place->setLabel('Publication place: ')
		->addFilters(array('StripTags','StringTrim'))
		->setRequired(true)
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->setAttrib('size',70)
		->addErrorMessage('You must enter place of publication.')
		->setDecorators($decorators);
	
	$publication_year = new Zend_Form_Element_Text('publication_year');
	$publication_year->setLabel('Publication year: ')
		->setRequired(true)
		->addValidator('Digits')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',20)
		->addErrorMessage('You must enter year of publication.')
		->setDecorators($decorators);
	
	$vol_no = new Zend_Form_Element_Text('vol_no');
	$vol_no->setLabel('Volume number: ')
		->setRequired(false)
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->setAttrib('size',20)
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators);
	
	$edition = new Zend_Form_Element_Text('edition');
	$edition->setLabel('Edition: ')
		->setRequired(false)
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',20)
		->setDecorators($decorators);
	
	$in_publication = new Zend_Form_Element_Text('in_publication');
	$in_publication->setLabel('In publication: ')
		->addFilters(array('StripTags','StringTrim'))
		->setRequired(false)
		->setAttrib('size',50)
		->setDecorators($decorators);
	
	$publication_type = new Zend_Form_Element_Select('publication_type');
	$publication_type->setLabel('Publication type: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL,'Choose reason' => $type_options))
		->addValidator('InArray', false, array(array_keys($type_options)))
		->setDecorators($decorators);
	
	$ISBN = new Zend_Form_Element_Text('ISBN');
	$ISBN->setLabel('ISBN (allows people to look it up on Amazon): ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',40)
		->addValidator('Isbn')
		->setDecorators($decorators);
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
			->removeDecorator('label')
	        ->removeDecorator('HtmlTag')
			->removeDecorator('DtDdWrapper')
			->setAttrib('class','large');
	
	$this->addElements(array(
	$title, $authors, $publisher,
	$publication_place,	$publication_year, 	$vol_no,
	$edition,$in_publication, $editors,
	$publication_type, $ISBN, $submit)
	);
	
	$this->addDisplayGroup(array(
	'title','authors','publisher',
	'publication_place','publication_year','vol_no',
	'edition','in_publication','editors',
	'publication_type','ISBN'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->addDisplayGroup(array('submit'), 'submit')->removeDecorator('HtmlTag');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	$this->details->setLegend('Publication details: ');
	
	}
}