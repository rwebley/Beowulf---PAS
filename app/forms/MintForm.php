<?php
/** Form for manipulating numismatic mint data 
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class MintForm extends Pas_Form {

public function __construct($options = null) {
	
	$periods = new Periods();
	$period_actives = $periods->getMintsActive();

	parent::__construct($options);

	$this->setAttrib('accept-charset', 'UTF-8');
	
	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
       
	$this->setName('people');

	$mint_name = new Zend_Form_Element_Text('mint_name');
	$mint_name->setLabel('Mint name: ')
		->setRequired(true)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->addFilters(array('StripTags','StringTrim'))
		->setDecorators($decorators)
		->setAttrib('size',70);


	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->SetLabel('Is this ruler or issuer currently valid: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int')
		->setDecorators($decorators);
	
	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addMultiOptions(array(NULL=> NULL,'Choose period:' => $period_actives))
		->addValidator('InArray', false, array(array_keys($period_actives)))
		->addErrorMessage('You must enter a period for this mint')
		->setDecorators($decorators);

		//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');
	
	$this->addElements(array(
	$mint_name, $valid, $period,
	$submit));
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($_formsalt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	$this->addDisplayGroup(array('mint_name', 'period', 'valid', 
	'submit'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	
	$this->details->setLegend('Mint details: ');
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	
	}
}