<?php
/** Form for editing and adding Heritage crime information
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class HeritageCrimeForm extends Pas_Form {
	
public function __construct($options = null) {

	$counties = new Counties();
	$county_options = $counties->getCountyName2();
	
	$crimes = new CrimeTypes();
	$crimeoptions = $crimes->getTypes();

	ZendX_JQuery::enableForm($this);

	parent::__construct($options);

	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'append','class'=>'error')),
	            array('Label'),
	            array('HtmlTag', array('tag' => 'li')),
			    );

	$this->setName('rally');

	$crimeType = new Zend_Form_Element_Select('crimeType');
	$crimeType->setLabel('Crime type: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('You must set crime type or no point entering it!')
	->addMultiOptions(array(NULL => 'Choose a crime type' ,'Valid types' => $crimeoptions))
	->addValidator('inArray', false, array(array_keys($crimeoptions)))
	->setDecorators($decorators);

	$subject = new Zend_Form_Element_Text('subject');
	$subject->setLabel('Title: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->setDecorators($decorators)
	->setAttrib('size',50)
	->addErrorMessage('You must enter a subject for this crime');

	$reporterID = new Zend_Form_Element_Hidden('reporterID');
	$reporterID->removeDecorator('Label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addErrorMessage('You do not appear to have chosen a reporter');

	$reporter = new Zend_Form_Element_Text('reporter');
	$reporter->setLabel('Source: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->setDecorators($decorators)
	->setDescription('Name and address of person providing intelligence.')
	->addErrorMessage('You must enter a reporter name');

	$reportingPerson = new Zend_Form_Element_Text('reportingPerson');
	$reportingPerson->setLabel('Person making the report: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->setDecorators($decorators)
	->setDescription('Name of person providing intelligence.')
	->addErrorMessage('You must enter a reporter name');

	$reportSubject = new Zend_Form_Element_Text('reportSubject');
	$reportSubject->setLabel('Subject of report: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->setDecorators($decorators)
	->setDescription('Name of suspect if known.')
	->addErrorMessage('You must enter a reporter name');

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a county' ,'Valid counties' => $county_options))
	->addValidator('inArray', false, array(array_keys($county_options)))
	->setDecorators($decorators);

	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
	->setDecorators($decorators)
	->addMultiOptions(array(NULL => 'Choose district after county'));

	$reliability = new Zend_Form_Element_Select('reliability');
	$reliability->setLabel('Source Evaluation: ')
	->addMultiOptions(array('1' => 'Always reliable','2' => 'Mostly reliable','3' => 'Sometimes reliable',
	'4' => 'Unreliable', '5' => 'Don\'t know'))
	->setValue(1)
	->addFilters(array('StripTags', 'StringTrim'))
	->setOptions(array('separator' => ''))
	->setDecorators($decorators);

	$intellEvaluation = new Zend_Form_Element_Select('intellEvaluation');
	$intellEvaluation->setLabel('Intelligence Evaluation: ')
	->addMultiOptions(array('1' => 'Known to be true','2' => 'Known to be true by the source, but not by the person making the report',
	'3' => 'Not known, but is corroborated',
	'4' => 'Cannot be judged', 
	'5' => 'Suspected to be false!'))
	->setValue(1)
	->addFilters(array('StripTags', 'StringTrim'))
	->setDescription('What you know about the intelligence itself.')
	->setOptions(array('separator' => ''))
	->setDecorators($decorators);
	
	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
	->setDecorators($decorators)
	->addMultiOptions(array(NULL => 'Choose parish after district'))
	->addFilters(array('StripTags', 'StringTrim'));
	
	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Associated NGR: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidators(array('NotEmpty','ValidGridRef'))
	->setAttrib('maxlength',16)
	->setDecorators($decorators);
	
	
	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Description of crime - Main report: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	$subjectDetails = new Pas_Form_Element_RTE('subjectDetails');
	$subjectDetails->setLabel('Subject details: ')
	->setRequired(false)
	->setDescription('DOB,address,description,etc if known.')
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	
	$evaluation = new Pas_Form_Element_RTE('evaluation');
	$evaluation->setLabel('Offences: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	
	$incidentDate = new ZendX_JQuery_Form_Element_DatePicker('incidentDate');
	$incidentDate->setLabel('Date of incident: ')
	->setJQueryParam('dateFormat', 'yy-mm-dd')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidators(array('NotEmpty','Date'))
	->addErrorMessage('Come on it\'s not that hard, enter a title!')
	->setAttrib('size', 20)
	->addDecorator(array('ListWrapper' => 'HtmlTag'), array('tag' => 'li'))
	->removeDecorator('DtDdWrapper');
	
	$sam = new Zend_Form_Element_Text('sam');
	$sam->setLabel('Associated with scheduled monument: ')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->setDecorators($decorators)
	->setAttrib('size',35);
	
	$samID = new Zend_Form_Element_Hidden('samID');
	$samID->removeDecorator('Label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->setRequired(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('Int');
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DtDdWrapper')
	->setAttrib('class','large');
	
	$this->addElements(array(
	$crimeType, $incidentDate, $samID,
	$sam, $reporter, $reporterID,
	$county, $district, $parish,
	$gridref, $description, $reliability,
	$evaluation, $subject, $reliability,
	$intellEvaluation, $subjectDetails, $reportSubject,
	$reportingPerson, $submit));
	
	$this->addDisplayGroup(array(
	'subject', 'crimeType', 'description',
	'incidentDate', 'evaluation', 'reportingPerson',
	'reporter', 'reporterID', 'reliability',
	'intellEvaluation', 'reportSubject', 'subjectDetails',
	'sam', 'samID','gridref', 
	'county', 'district', 'parish'), 
	'details');
	
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	
	$this->details->removeDecorator('DtDdWrapper');
	
	$this->details->setLegend('Crime details: ');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	
	}
}