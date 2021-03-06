<?php
/** Form for editing and creating landuses
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class LanduseForm extends Pas_Form {
	
public function __construct($options = null) {
	$landuses = new Landuses();
	$landuse_opts = $landuses->getUsesValid();
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	
	parent::__construct($options);
	
	$this->setName('Landuse');
	
	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Landuse term name: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a valid title for this landuse!')
	->setDecorators($decorators);
	
	$oldID = new Zend_Form_Element_Text('oldID');
	$oldID->setLabel('Old landuse type code: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('Please enter a valid title for this landuse!')
	->setDecorators($decorators);
	
	$termdesc = new Pas_Form_Element_RTE('termdesc');
	$termdesc->setLabel('Description of landuse type: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$belongsto = new Zend_Form_Element_Select('belongsto');
	$belongsto->setLabel('Belongs to landuse type: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL,'Choose period:' => $landuse_opts))
	->addValidator('InArray', false, array(array_keys($landuse_opts)))
	->setDecorators($decorators);

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Landuse type is currently in use: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Digits')
	->setDecorators($decorators);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');

	$this->addElements(array(
	$term, 	$termdesc,	$oldID,
	$valid,	$belongsto,	$submit)
	);

	$this->addDisplayGroup(array(
	'term', 'termdesc', 'oldID',
	'belongsto', 'valid'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');

	$this->addDisplayGroup(array('submit'), 'submit');
	
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
}
}