<?php
/** Form for linking medieval types to rulers
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MedTypeForm extends Pas_Form {
	
public function __construct($options = null) {
	
	$periods = new Periods();
	$period_options = $periods->getMedievalCoinsPeriodList();
	
	$cats = new CategoriesCoins();
	$cat_options = $cats->getCategoriesAll();

	$rulers = new Rulers();
	$ruler_options = $rulers->getAllMedRulers();

	parent::__construct($options);
	
	$this->setAttrib('accept-charset', 'UTF-8');
	
	$this->setName('medievaltype');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$type = new Zend_Form_Element_Text('type');
	$type->setLabel('Coin type: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('You must enter a type name.')
		->setDecorators($decorators);

	$periodID = new Zend_Form_Element_Select('periodID');
	$periodID->setLabel('Medieval period: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a period for this type')
		->addMultioptions(array(NULL => NULL,'Choose a period' => $period_options))
		->addValidator('InArray', false, array(array_keys($period_options)))
		->addValidator('Int')
		->setDecorators($decorators);

	$rulerID = new Zend_Form_Element_Select('rulerID');
	$rulerID->setLabel('Ruler assigned: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultioptions(array(NULL => NULL,'Choose a ruler' => $ruler_options))
		->addValidator('InArray', false, array(array_keys($ruler_options)))
		->addValidator('Int')
		->setDecorators($decorators);

	$datefrom = new Zend_Form_Element_Text('datefrom');
	$datefrom->setLabel('Date in use from: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->setDecorators($decorators);

	$dateto = new Zend_Form_Element_Text('dateto');
	$dateto->setLabel('Date in use until: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits')
		->setDecorators($decorators);
	
	$categoryID = new Zend_Form_Element_Select('categoryID');
	$categoryID->setLabel('Coin category: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => NULL, 'Choose a category' => $cat_options))
		->addValidator('InArray', false, array(array_keys($cat_options)))
		->addValidator('Int')
		->setDecorators($decorators);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');

	$this->addElements(array(
		$type, $rulerID, $periodID,
		$categoryID, $datefrom, $dateto,
		$submit));

	$this->addDisplayGroup(array(
		'periodID', 'type', 'categoryID',
		'rulerID', 'datefrom', 'dateto',
		'submit'), 'details')
		->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->setLegend('Mint details: ');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');

	}
}