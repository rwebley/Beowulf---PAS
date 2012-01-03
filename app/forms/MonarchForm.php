<?php
/** Form for creating monarch's data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MonarchForm extends Pas_Form {
	
public function __construct($options = null) {
	$rulers = new Rulers();
	$rulers_options = $rulers-> getAllMedRulers();
	
	$dynasties = new Dynasties();
	$dynasties_options = $dynasties->getOptions();


parent::__construct($options);
       
	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
		    
	$this->setName('MonarchDetails');

	$name = new Zend_Form_Element_Text('name');
	$name->setLabel('Monarch\'s name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->setAttrib('size','50')
		->addErrorMessage('You must enter a Monarch\'s name')
		->setDecorators($decorators);

	$styled = new Zend_Form_Element_Text('styled');
	$styled->setLabel('Styled as: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->setDecorators($decorators);

	$alias = new Zend_Form_Element_Text('alias');
	$alias->setLabel('Monarch\'s alias: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->setDecorators($decorators);

	$dbaseID = new Zend_Form_Element_Select('dbaseID');
	$dbaseID->setLabel('Database ID: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('InArray', false, array(array_keys($rulers_options)))
		->addMultiOptions(array(NULL => NULL, 'Choose Database ID' => $rulers_options))
		->setDecorators($decorators);

	$date_from = new Zend_Form_Element_Text('date_from');
	$date_from->setLabel('Issued coins from: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits')
		->setDecorators($decorators);

	$date_to = new Zend_Form_Element_Text('date_to');
	$date_to->setLabel('Issued coins until: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits')
		->setDecorators($decorators);

	$born = new Zend_Form_Element_Text('born');
	$born->setLabel('Born: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits')
		->setDecorators($decorators);

	$died = new Zend_Form_Element_Text('died');
	$died->setLabel('Died: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits')
		->setDecorators($decorators);

	$biography = new Pas_Form_Element_RTE('biography');
	$biography->setLabel('Biography: ')
		->setRequired(false)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$dynasty = new Zend_Form_Element_Select('dynasty');
	$dynasty->setLabel('Dynastic grouping: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits')
		->setDecorators($decorators);

	$publishState = new Zend_Form_Element_Select('publishState');
	$publishState->setLabel('Publication status: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Digits')
		->addMultiOptions(array(NULL => NULL, 'Set status' => array('1' => 'Draft','2' => 'Published')))
		->setValue(1)
		->setDecorators($decorators);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');

	$this->addElements(array(
	$name, $styled, $alias, 
	$dbaseID, $date_from, $date_to,
	$born, $died, $biography, $dynasty, 
	$publishState, $submit));
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array('name','styled','alias'), 'names');
	$this->names->setLegend('Nomenclature');
	$this->names->removeDecorator('DtDdWrapper');
	$this->names->removeDecorator('HtmlTag');
	
	$this->addDisplayGroup(array('dbaseID','date_from','date_to','born','died'),'periods');
	$this->periods->setLegend('Dates');
	$this->periods->removeDecorator('DtDdWrapper');
	$this->periods->removeDecorator('HtmlTag');
	
	$this->addDisplayGroup(array('biography','dynasty','publishState'),'details');
	$this->details->setLegend('Biographical details');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	  
	}
}