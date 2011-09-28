<?php
/**
* Form for cross referencing finds liaison officers to rallies
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddFloRallyForm extends Pas_Form{

	
	public function __construct($options = null) {
		
	$staff = new Contacts();
	$flos = $staff->getAttending();
	
	parent::__construct($options);

	ZendX_JQuery::enableForm($this);
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	$this->setName('addFlo');


	$flo = new Zend_Form_Element_Select('staffID');
	$flo->setLabel('Finds officer present: ')
	->setRequired(true)
	->addFilter('HtmlBody')
	->addFilter('StringTrim')
	->addValidator('NotEmpty')
	->addErrorMessage('Please enter a valid interest!')
	->setDecorators($decorators)
	->addMultiOptions(array(NULL => 'Choose attending officer','Our staff members' => $flos));

	$dateFrom = new ZendX_JQuery_Form_Element_DatePicker('dateFrom');
	$dateFrom->setLabel('Attended from: ')
	->setRequired(false)
	->addValidator('Date')
	->addFilter('StripTags')
	->addFilter('StringTrim')
	->addValidator('NotEmpty')
	->addErrorMessage('Come on it\'s not that hard, enter a title!')
	->setAttrib('size', 20)
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
	->removeDecorator('DtDdWrapper');


	$dateTo = new ZendX_JQuery_Form_Element_DatePicker('dateTo');
	$dateTo->setLabel('Attended to: ')
	->setRequired(false)
	->addValidator('Date')
	->addFilter('StripTags')
	->addFilter('StringTrim')
	->addValidator('NotEmpty')
	->addErrorMessage('Come on it\'s not that hard, enter a title!')
	->setAttrib('size', 20)
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
	->removeDecorator('DtDdWrapper');

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');

	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	$this->addElements(array($flo,$dateFrom,$dateTo,$submit));

	$this->addDisplayGroup(array('staffID','dateFrom','dateTo'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Attending Finds Officers');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	}



}