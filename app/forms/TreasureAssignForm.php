<?php
/** Form for assignation by curator
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class TreasureAssignForm extends Pas_Form
{

public function __construct($options = null)
{
	$curators = new Peoples();
	$assigned = $curators->getCurators();
	
	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	
	$this->setName('actionsForTreasure');
	
	$curatorID = new Zend_Form_Element_Select('curatorID');
	$curatorID->setLabel('Curator assigned: ')
	->setRequired(true)
	->addValidator('InArray', false, array(array_keys($assigned)))
	->addMultiOptions($assigned)
	->setDecorators($decorators);
	
	$chaseDate = new ZendX_JQuery_Form_Element_DatePicker('chaseDate');
	$chaseDate->setLabel('Chase date assigned: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StringTrim','StripTags'))
		->addErrorMessage('You must enter a chase date')
		->setAttrib('size', 20)
		->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
		->removeDecorator('DtDdWrapper');
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
		
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->setAttrib('class', 'large')
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');
	
	$this->addElements(array(
	$curatorID, $chaseDate, $submit, $hash
	));
	
	$this->addDisplayGroup(array('curatorID','chaseDate'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	}
}