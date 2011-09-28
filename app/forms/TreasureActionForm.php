<?php
/** Form for assigning Treasure case actions
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class TreasureActionForm extends Pas_Form
{

public function __construct($options = null) {
	
	$actionTypes = new TreasureActionTypes();
	$actionlist = $actionTypes->getList();

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

	$actionDescription = new Pas_Form_Element_RTE('actionTaken');
	$actionDescription->setLabel('Action summary: ')
		->setRequired(true)
		->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400,
		'ToolbarSet' => 'Basic'))
		->addFilters(array('StringTrim', 'WordChars', 'BasicHtml', 'EmptyParagraph'));

	$action = new Zend_Form_Element_Select('actionID');
	$action->setLabel('Type of action taken: ')
		->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('InArray', false, array(array_keys($actionlist)))
		->addMultiOptions($actionlist)
		->setDecorators($decorators);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	
	$this->addElements(array(
	$action, $actionDescription, $submit, $hash
	));
	
	$this->addDisplayGroup(array('actionID','actionTaken',), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	}
}
