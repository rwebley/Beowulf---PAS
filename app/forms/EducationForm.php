<?php
/** Form for entering educational data
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class EducationForm extends Pas_Form
{
public function __construct($options = null)
{

parent::__construct($options);

	$levels = new ProjectTypes();
	$levelsListed = $levels->getDegrees();      
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'apppend','class'=>'error','tag' => 'li')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );
	$this->setName('education');
	ZendX_JQuery::enableForm($this);
	
	
	$school = new Zend_Form_Element_Text('school');
	$school->setLabel('Institution name: ')
	->setRequired(true)
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',30)
	->addErrorMessage('Please enter a valid institutional name!')
	->setDecorators($decorators);
	
	$schoolUrl = new Zend_Form_Element_Text('schoolUrl');
	$schoolUrl->setLabel('Institution web address: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim','StringToLower'))
	->addValidator('Uri')
	->setAttrib('size',30)
	->addErrorMessage('Please enter a valid url!')
	->setDecorators($decorators);
	
	
	$subject = new Zend_Form_Element_Text('subject');
	$subject->setLabel('Subject studied: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->setAttrib('size',30)
	->addErrorMessage('Please enter a valid string!')
	->setDecorators($decorators);
	
	
	$level = new Zend_Form_Element_Select('level');
	$level->setLabel('Adademic level of study: ')
	->addMultiOptions(array( NULL => 'Choose an academic level', 'Valid levels' => $levelsListed))
	->setDecorators($decorators);
	
	
	$dateFrom = new ZendX_JQuery_Form_Element_DatePicker('dateFrom');
	$dateFrom->setLabel('Commenced programme: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Date')
	->addValidator('NotEmpty')
	->setAttrib('size', 20)
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
	->setJQueryParams(array('yearRange'=> '-20:+10'));
	
	
	$dateTo = new ZendX_JQuery_Form_Element_DatePicker('dateTo');
	$dateTo->setLabel('Finished programme: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Date')
	->setAttrib('size', 20)
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
	->removeDecorator('DtDdWrapper')
	->setJQueryParams(array('yearRange'=> '-20:+10'));
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(60);
	$this->addElement($hash);
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submit')
	->setAttrib('class', 'large')
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag');
	
	$this->addElements(array(
	$school,
	$schoolUrl,
	$subject,
	$level,
	$dateFrom,
	$dateTo,
	$submit));
	
	$this->addDisplayGroup(array('school','schoolUrl','subject','level','dateFrom','dateTo'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	$this->details->removeDecorator('HtmlTag');
	$this->details->setLegend('Educational background');
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->submit->removeDecorator('DtDdWrapper');
	$this->submit->removeDecorator('HtmlTag');
	
	}
}