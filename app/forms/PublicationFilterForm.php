<?php
/** Form for filtering publications
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PublicationFilterForm extends Pas_Form {
	
public function __construct($options = null) {

	parent::__construct($options);
 	$this->setMethod('get');  
	$this->setName('filterpubs');

	$decorator =  array('TableDecInput');

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Filter by title')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 30)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$authorEditor = new Zend_Form_Element_Text('authorEditor');
	$authorEditor->setLabel('Filter by author')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->setAttrib('size', 15)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$yearPub = new Zend_Form_Element_Text('pubYear');
	$yearPub->setLabel('Filter by pub. year')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->setAttrib('size', 15)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$place = new Zend_Form_Element_Text('place');
	$place->setLabel('Filter by place of pub.')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->setAttrib('size', 15)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setLabel('Filter')
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'td'))
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$this->addElements(array(
	$title, 
	$authorEditor,
	$yearPub,
	$place,
	$submit));
  
	}
}