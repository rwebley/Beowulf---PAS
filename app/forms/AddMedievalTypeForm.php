<?php
/**
* Form for adding and editing Medieval types.
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddMedievalTypeForm extends Pas_Form {
	
	public function __construct($options = null) {

	$cats = new CategoriesCoins();
	$cat_options = $cats->getCategoriesAll();
	
	$rulers = new Rulers();
	$ruler_options = $rulers->getAllMedRulers();
	
	parent::__construct($options);
	
	$this->setAttrib('accept-charset', 'UTF-8');
	$this->setDecorators(array('FormElements','Form'));
	$this->setName('MedievalType');

	$type = new Zend_Form_Element_Text('type');
	$type->setLabel('Medieval type: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttribs(array('class'=> 'textInput','size' => 60));

	$broadperiod = new Zend_Form_Element_Select('periodID');
	$broadperiod->setLabel('Broadperiod for type: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->setAttribs(array('class'=> 'textInput'))
	->addMultioptions(array(NULL => NULL, 'Choose broadperiod' => array('47' => 'Early Medieval','29' => 'Medieval','36' => 'Post Medieval')));

	$category = new Zend_Form_Element_Select('categoryID');
	$category->setLabel('Coin category: ')
	->setAttribs(array('class'=> 'textInput'))
	->addValidator('Int')
	->addFilter('StringTrim')
	->addMultioptions( array(NULL => NULL, 'Choose a category' =>$cat_options))
	->addValidator('InArray', true, array($cat_options));

	$ruler = new Zend_Form_Element_Select('rulerID');
	$ruler->setLabel('Ruler assigned to: ')
	->setAttribs(array('class'=> 'textInput'))
	->addValidator('Int')
	->addFilter('StringTrim')
	->addMultioptions(array(NULL => NULL, 'Choose a category' => $ruler_options))
	->addValidator('inArray', true, array($ruler_options));

	$datefrom = new Zend_Form_Element_Text('datefrom');
	$datefrom->setLabel('Date type in use from: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->setAttribs(array('class'=> 'textInput'));

	$dateto = new Zend_Form_Element_Text('dateto');
	$dateto->setLabel('Date type in use until: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->setAttribs(array('class'=> 'textInput'));

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Submit details for medieval coin type')
	->setAttribs(array('class'=> 'large'));

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);

	$this->addElements(array($type,$broadperiod,$category,$ruler,$datefrom,$dateto,$submit))
	->setLegend('Add an active type of Medieval coin')
	->setMethod('post')
	->addDecorators(array('Fieldset', 'form',array('HtmlTag', array('tag' => 'div'))));

	}
}