<?php
/** Form for manipulating find information!
* This is one of the most important forms of the entire site. 
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class FindForm extends Pas_Form {
	
public function __construct($options = null) {
	
	//Get data to form select menu for discovery methods
	$discs = new DiscoMethods();
	$disc_options = $discs->getOptions();
	//Get data to form select menu for manufacture methods
	$mans = new Manufactures();
	$man_options = $mans->getOptions();
	
	//Get data to form select menu for primary and secondary material
	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();
	
	//Get data to form select menu for periods
	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();
	
	//Get data to form select menu for cultures
	$cultures = new Cultures();
	$culture_options = $cultures->getCultures();
	
	//Get data to form Surface treatments menu
	$surfaces = new Surftreatments();
	$surface_options = $surfaces->getSurfaces();
	
	//Get data to form Decoration styles menu
	$decorations = new Decstyles();
	$decoration_options = $decorations->getStyles();
	
	//Get data to form Decoration methods menu
	$decmeths = new Decmethods();
	$decmeth_options = $decmeths->getDecmethods();
	
	//Get Find of note reason data
	$reasons = new Findofnotereasons();
	$reason_options = $reasons->getReasons();
	
	//Get Preservation data
	$preserves = new Preservations();
	$preserve_options = $preserves->getPreserves();
	
	//Get Rally data
	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();
	
	$periods = new Periods();
	$periodword_options = $periods->getPeriodFromWords();
	
	$circa = new DateQualifiers();
	$circa_o = $circa->getTerms();
	
	$actions = new SubsequentActions();
	$actionsDD = $actions->getSubActionsDD();
	//End of select options construction

	parent::__construct($options);

	$decorator =  array('SimpleInput');
	$decoratorButton =  array('NormalDecButton');

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );
		    
	$decoratorsHide = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'hideme')),
            array('HtmlTag', array('tag' => 'li')),
		    );
		    
	$decoratorsRally = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'hiderally')),
            array('HtmlTag', array('tag' => 'li')),
		    );
	
	$decoratorsHoard = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'hidehoard')),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$decoratorsNote = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label', array('separator'=>' ', 'class' => 'hidenote')),
            array('HtmlTag', array('tag' => 'li')),
		    );	

	$this->setName('finds');
		
   
	$secuid = new Zend_Form_Element_Hidden('secuid');
	$secuid->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Alnum');

	// Object specifics
	$old_findID = new Zend_Form_Element_Hidden('old_findID');
	$old_findID->removeDecorator('label')
	->removeDecorator('HtmlTag')
	->addFilters(array('StripTags','StringTrim'));
			  
			  
	//Objecttype - autocomplete from thesaurus
	$objecttype = new Zend_Form_Element_Text('objecttype');
	$objecttype->setLabel('Object type: ')
	->setRequired(true)
	->setAttrib('size',50)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('ValidObjectType')
	->setDecorators($decorators);

	$objecttypecert = new Zend_Form_Element_Radio('objecttypecert');
	$objecttypecert->setLabel('Object type certainty: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->setOptions(array('separator' => ''))
	->setDecorators($decorators);


	//Object description
	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Object description: ')
	->setRequired(false)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	//Object notes
	$notes = new Pas_Form_Element_RTE('notes');
	$notes->setLabel('Notes: ')
	->setRequired(false)
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->setAttrib('ToolbarSet','Finds')
	->setAttrib('Height',250)
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));


	//Find of note
	$findofnote = new Zend_Form_Element_Checkbox('findofnote');
	$findofnote->setLabel('Find of Note: ')
	->setRequired(false)
	->setCheckedValue('1')
	->setUncheckedValue(NULL)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators)
	->addValidator('NotEmpty','Int');

	//Reason for find of note
	$findofnotereason = new Zend_Form_Element_Select('findofnotereason');
	$findofnotereason->setLabel('Why this find is considered noteworthy: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a reasoning','Available reasons' => $reason_options))
	->addValidator('InArray', false, array(array_keys($reason_options)))
	->addValidator('Int')
	->setDecorators($decoratorsNote);
	
	//Find classification
	$class = new Zend_Form_Element_Text('classification');
	$class->setLabel('Classification: ')
	->setAttrib('size',60)
	->setRequired(false)
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);

	//Find subclassification
	$subclass = new Zend_Form_Element_Text('subclass');
	$subclass->setLabel('Sub-classification: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->setDecorators($decorators)
	->setAttrib('size',60);

	//Inscription: Only available if !=coin
	$inscription = new Zend_Form_Element_Text('inscription');
	$inscription->setLabel('Inscription: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',60)
	->addValidator('Alnum', false, array('allowWhiteSpace' => true))
	->setDecorators($decorators);

	//Treasure: enumerator 1/0
	$treasure = new Zend_Form_Element_Checkbox('treasure');
	$treasure->setLabel('Treasure: ')
	->setRequired(false)
	->setCheckedValue('1')
	->setUncheckedValue(NULL)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);


	//Treasure: enumerator 1/0
	$treasureID = new Zend_Form_Element_Text('treasureID');
	$treasureID->setLabel('Treasure number: ')
	->setRequired(false)
	->addValidator('Alnum', false, array('allowWhiteSpace' => false))
	->addFilters(array('StripTags','StringTrim', 'StringToUpper'))
	->setDecorators($decoratorsHide);


	// Temporal details section //
	//Broadperiod: 
	$broadperiod = new Zend_Form_Element_Select('broadperiod');
	$broadperiod->setLabel('Broad period: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose broadperiod' ,
	'Available periods' => $periodword_options))
	->setDecorators($decorators)
	->addErrorMessage('You must enter a broad period.')
	->addValidator('InArray', false, array(array_keys($periodword_options)));

	//Period from: Assigned via dropdown
	$objdate1subperiod = new Zend_Form_Element_Select('objdate1subperiod');
	$objdate1subperiod->setLabel('Sub period from: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a subperiod' ,
	'Valid sub periods' => array('1' => 'Early','2' => 'Middle', '3' => 'Late')))
	->setDecorators($decorators);


	//Period from: Assigned via dropdown
	$objdate1period = new Zend_Form_Element_Select('objdate1period');
	$objdate1period->setLabel('Period from: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a period from' ,
	'Available periods' => $period_options))
	->addValidator('InArray', false, array(array_keys($period_options)))
	->addValidator('Int')
	->setDecorators($decorators);

	$objdate1cert = new Zend_Form_Element_Radio('objdate1cert');
	$objdate1cert->setLabel('Period from certainty: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->setOptions(array('separator' => ''))
	->addValidator('Digits')
	->setDecorators($decorators);

	//Period from: Assigned via dropdown
	$objdate2subperiod = new Zend_Form_Element_Select('objdate2subperiod');
	$objdate2subperiod->setLabel('Sub period to: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose a subperiod' ,
	'Valid sub periods' => array('1' => 'Early','2' => 'Middle', '3' => 'Late')))
	->addValidator('Digits')
	->setDecorators($decorators);

	//Period to: Assigned via dropdown
	$objdate2period = new Zend_Form_Element_Select('objdate2period');
	$objdate2period->setLabel('Period to: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose period to',
	'Available periods' => $period_options))
	->addValidator('InArray', false, array(array_keys($period_options)))
	->addValidator('Int')
	->setDecorators($decorators);

	$objdate2cert = new Zend_Form_Element_Radio('objdate2cert');
	$objdate2cert->setLabel('Period to certainty: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->setOptions(array('separator' => ''))
	->setDecorators($decorators)
	->addValidator('Digits');

	$numdate1qual = new Zend_Form_Element_Radio('numdate1qual');
	$numdate1qual->setLabel('Date certainty: ')
	->addMultiOptions($circa_o)
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->setOptions(array('separator' => ''))
	->setDecorators($decorators)
	->addValidator('Digits');

	//Date from: Free text Integer +ve or -ve
	$numdate1 = new Zend_Form_Element_Text('numdate1');
	$numdate1->setLabel('Date from: ')
	->setAttrib('size',10)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Digits')
	->setDecorators($decorators);

	$numdate2qual = new Zend_Form_Element_Radio('numdate2qual');
	$numdate2qual->setLabel('Date certainty: ')
	->addMultiOptions($circa_o)
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->setOptions(array('separator' => ''))
	->setDecorators($decorators)
	->addValidator('Digits');

	//Date to: Free text Integer +ve or -ve
	$numdate2 = new Zend_Form_Element_Text('numdate2');
	$numdate2->setLabel('Date to: ')
	->setRequired(false)
	->setAttrib('size',10)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Digits')
	->setDecorators($decorators);
	
	//Ascribed culture: assigned via dropdown
	$culture = new Zend_Form_Element_Select('culture');
	$culture->setLabel('Ascribed culture: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose ascribed culture',
	'Available cultures' => $culture_options))
	->addValidator('InArray', false, array(array_keys($culture_options)))
	->addValidator('Int')
	->setDecorators($decorators);
	 
	//Period of reuse
	$reuse_period = new Zend_Form_Element_Select('reuse_period');
	$reuse_period->setLabel('Period of reuse: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose period of reuse',
	'Available periods' => $period_options))
	->addValidator('InArray', false, array(array_keys($period_options)))
	->addValidator('Int')
	->setDecorators($decorators);
	
	//Evidence of reuse
	$reuse = new Zend_Form_Element_Text('reuse');
	$reuse->setLabel('Evidence of reuse: ')
	->setAttrib('size',60)
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	//METRICS SECTION//
	//Weight: grammes
	$weight = new Zend_Form_Element_Text('weight');
	$weight->setLabel('Weight: ')
	->setAttrib('size',5)
	->setRequired(false)
	->addValidator('Float')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	//Height: millimetres
	$height = new Zend_Form_Element_Text('height');
	$height->setLabel('Height: ')
	->setRequired(false)
	->setAttrib('size',5)
	->addValidator('Float')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	//Length: millimetres
	$length = new Zend_Form_Element_Text('length');
	$length->setLabel('Length: ')
	->setRequired(false)
	->setAttrib('size',5)
	->addValidator('Float')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	//Diameter: millimetres
	$diameter = new Zend_Form_Element_Text('diameter');
	$diameter->setLabel('Diameter: ')
	->setRequired(false)
	->setAttrib('size',5)
	->addValidator('Float')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);

	//
	$width = new Zend_Form_Element_Text('width');
	$width->setLabel('Width: ')
	->setRequired(false)
	->setAttrib('size',5)
	->addValidator('Float')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);

	//Thickness: millimetres
	$thickness = new Zend_Form_Element_Text('thickness');
	$thickness->setLabel('Thickness: ')
	->setRequired(false)
	->setAttrib('size',5)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Float')
	->setDecorators($decorators);

	//Quantity: positive integers only
	$quantity = new Zend_Form_Element_Text('quantity');
	$quantity->setLabel('Quantity: ')
	->setRequired(true)
	->setValue('1')
	->setAttrib('size',3)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Digits')
	->setDecorators($decorators);

	$material1 = new Zend_Form_Element_Select('material1');
	$material1->setLabel('Primary material: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose primary material',
	'Available materials' => $primary_options))
	->addValidator('InArray', false, array(array_keys($primary_options)))
	->addValidator('Int')
	->setDecorators($decorators);


	//Secondary material
	$material2 = new Zend_Form_Element_Select('material2');
	$material2->setLabel('Secondary material: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose secondary material',
	'Available materials' => $primary_options))
	->addValidator('InArray', false, array(array_keys($primary_options)))
	->addValidator('Int')
	->setDecorators($decorators);
 

	//Manufacture method
	$manmethod = new Zend_Form_Element_Select('manmethod');
	$manmethod->setLabel('Manufacture method: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose method of manufacture',
	'Available methods' => $man_options))
	->addValidator('InArray', false, array(array_keys($man_options)))
	->addValidator('Int')	
	->setDecorators($decorators);

	//Decoration method
	$decmethod = new Zend_Form_Element_Select('decmethod');
	$decmethod->setLabel('Decoration method: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose decoration method',
	'Available methods' => $decmeth_options))
	->addValidator('InArray', false, array(array_keys($decmeth_options)))
	->addValidator('Int')
	->setDecorators($decorators);


	//Surface treatment
	$surftreat = new Zend_Form_Element_Select('surftreat');
	$surftreat->setLabel('Surface Treatment: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose surface treatment',
	'Available treatments' => $surface_options))
	->addValidator('InArray', false, array(array_keys($surface_options)))
	->addValidator('Int')
	->setDecorators($decorators);


	//decoration style
	$decstyle = new Zend_Form_Element_Select('decstyle');
	$decstyle->setLabel('Decorative style: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose decorative style',
	'Available styles' => $decoration_options))
	->addValidator('InArray', false, array(array_keys($decoration_options)))
	->addValidator('Int')
	->setDecorators($decorators);

	//Preservation of object
	$preservation = new Zend_Form_Element_Select('preservation');
	$preservation->setLabel('Preservation: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->addMultiOptions(array(NULL => 'Choose level of preservation',
	'Available states' => $preserve_options))
	->addValidator('InArray', false, array(array_keys($preserve_options)))
	->addValidator('Int')
	->setDecorators($decorators);


	//Completeness of object
	$completeness = new Zend_Form_Element_Radio('completeness');
	$completeness->setLabel('Completeness: ')
	->setRequired(false)
	->addMultiOptions(array('4' => 'Complete','2' => 'Incomplete','1' => 'Fragment','3' => 'Uncertain'))
	->setValue('4')
	->setOptions(array('separator' => ''))
	->addFilters(array('StripTags','StringTrim'))
	->addDecorator('HtmlTag', array('placement' => 'prepend','tag'=>'div','id'=>'radios'))
	->setDecorators($decorators);

	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
	->setRequired(false)
	->setCheckedValue('1')
	->setUncheckedValue(NULL)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->setDecorators($decorators);

	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->addMultiOptions(array(NULL => 'Choose rally name',
	'Available rallies' => $rally_options))
	->addValidator('InArray', false, array(array_keys($rally_options)))
	->addValidator('Int')
	->setDecorators($decoratorsRally); 


	## PERSONNEL INFORMATION (or personal depending on the way you see it!)
	// Identifier
	$finder = new Zend_Form_Element_Text('finder');
	$finder->setLabel('Found by: ')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators)
	->setDescription('To make a new finder/identifier appear, you first need to create them from the people menu on the left hand side');


	$finderID = new Zend_Form_Element_Hidden('finderID');
	$finderID->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->removeDecorator('Label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DdDtWrapper')
	->setDecorators($decorators);

	$secondfinder = new Zend_Form_Element_Text('secondfinder');
	$secondfinder->setLabel('Secondary finder: ')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);

	//Secondary finder
	$finder2ID = new Zend_Form_Element_Hidden('finder2ID');
	$finder2ID->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->removeDecorator('Label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DdDtWrapper')
	->setDecorators($decorators);

	$recordername = new Zend_Form_Element_Text('recordername');
	$recordername->setLabel('Recorded by: ')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	//recorder information
	$recorderID = new Zend_Form_Element_Hidden('recorderID');
	$recorderID->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->removeDecorator('Label')
	->removeDecorator('HtmlTag')
	->removeDecorator('DdDtWrapper')
	->setDecorators($decorators);

	$idBy = new Zend_Form_Element_Text('idBy');
	$idBy->setLabel('Primary identifier: ')
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);

	$identifier1ID = new Zend_Form_Element_Hidden('identifier1ID');
	$identifier1ID->removeDecorator('HtmlTag')
	->addFilters(array('StripTags','StringTrim'))
	->removeDecorator('DtDdWrapper')
	->removeDecorator('Label');

	$id2by = new Zend_Form_Element_Text('id2by');
	$id2by->setLabel('Secondary Identifier: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	//Secondary Identifier
	$identifier2ID = new Zend_Form_Element_Hidden('identifier2ID');
	$identifier2ID->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
		->removeDecorator('Label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DdDtWrapper');

	##DISCOVERY INFORMATION
	//Discovery method
	$discmethod = new Zend_Form_Element_Select('discmethod');
	$discmethod->setLabel('Discovery method: ')
	->setRequired(true)
	->setValue(1)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Int')
	->addValidator('inArray', true, array(array_keys($disc_options)))
	->addMultiOptions(array(NULL => 'Choose method of discovery','Available methods' => $disc_options))
	->setDecorators($decorators);

	//Discovery circumstances
	$disccircum = new Zend_Form_Element_Text('disccircum');
	$disccircum->setLabel('Discovery circumstances: ')
	->setRequired(false)
	->setAttrib('size',50)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	//Date found from
	$datefound1 = new Zend_Form_Element_Text('datefound1');
	$datefound1->setLabel('First discovery date: ')
	->setRequired(false)
	->setAttrib('size',10)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Datetime')
	->setDecorators($decorators);

	//Date found to
	$datefound2 = new Zend_Form_Element_Text('datefound2');
	$datefound2->setLabel('Second discovery date: ')
	->setRequired(false)
	->setAttrib('size',10)
	->addFilters(array('StripTags','StringTrim'))
	->addValidator('Datetime')
	->setDecorators($decorators);

	##OTHER REFERENCE NUMBERS
	//Other reference number
	$other_ref = new Zend_Form_Element_Text('other_ref');
	$other_ref->setLabel('Other reference: ')
	->setRequired(false)
	->setAttrib('size',50)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);
	
	//SMR reference number
	$smrrefno = new Zend_Form_Element_Text('smr_ref');
	$smrrefno->setLabel('Sites and Monuments record number: ')
	->setRequired(false)
	->setAttrib('size',30)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);

	//Museum accession number
	$musaccno = new Zend_Form_Element_Text('musaccno');
	$musaccno->setLabel('Museum accession number: ')
	->setRequired(false)
	->setAttrib('size',50)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);

	//Current location of object
	$curr_loc = new Zend_Form_Element_Text('curr_loc');
	$curr_loc->setLabel('Current location: ')
	->setRequired(false)
	->setAttrib('size',60)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators);

	//Current location of object
	$subs_action = new Zend_Form_Element_Select('subs_action');
	$subs_action->setLabel('Subsequent action: ')
	->setRequired(false)
	->addFilters(array('StripTags','StringTrim'))
	->setDecorators($decorators)
	->addMultiOptions(array(NULL => 'Choose a subsquent action', 
	'Available options' => $actionsDD))
	->setValue(1)
	->addValidator('InArray', false, array(array_keys($actionsDD)))
	->addValidator('Int');

 	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
	->removeDecorator('DtDdWrapper')
	->removeDecorator('HtmlTag')->removeDecorator('label')
	->setTimeout(4800);
	$this->addElement($hash);


	

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')->removeDecorator('label')
	              ->removeDecorator('HtmlTag')
				  ->setAttrib('class','large')
				  ->removeDecorator('DtDdWrapper');
			  
	$this->addElements(array(
	$secuid, $old_findID,
	$objecttype, $broadperiod, $objdate1period,
	$objdate1subperiod, $objdate2subperiod,	$objdate2period, 
	$numdate1, $numdate2, $culture,
	$inscription, $description, $notes,
	$findofnote, $class, $subclass,
	$weight, $length, $thickness,
	$diameter, $height, $quantity,
	$manmethod, $surftreat,//$decmethod,
	$treasure, $treasureID, $decstyle,
	$recordername, $recorderID, $idBy,
	$identifier1ID, $id2by, $identifier2ID,
	$finder, $finderID, $secondfinder,
	$finder2ID,	$discmethod, $disccircum,
	$datefound1, $datefound2, $reuse,
	$reuse_period, $material1, $material2,
	$curr_loc, $smrrefno, $musaccno,
	$other_ref, $width, $preservation,
	$completeness, $findofnotereason, $rally,
	$objecttypecert, $rallyID, $objdate1cert,
	$objdate2cert, $submit,	$subs_action,
	$numdate1qual, $numdate2qual));
		
	$this->removeDecorator('DtDdWrapper');
	$this->addDisplayGroup(array('objecttype','objecttypecert','classification',
	'subclass','description','notes',
	'inscription','findofnote','findofnotereason',
	'treasure','treasureID'), 'objectdetails');
	$this->objectdetails->removeDecorator('DtDdWrapper');
	$this->objectdetails->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->objectdetails->setLegend('Object details');
	
	$this->addDisplayGroup(array('broadperiod','objdate1period','objdate1cert',
	'objdate1subperiod', 'objdate2period','objdate2cert',
	'objdate2subperiod','numdate1qual','numdate1',
	'numdate2qual','numdate2','culture',
	'reuse_period','reuse'), 'date');
	$this->date->removeDecorator('DtDdWrapper');
	$this->date->setLegend('Temporal details');
	$this->date->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	
	
	$this->addDisplayGroup(array('length','width','thickness','height',
	'diameter','weight','quantity'), 'metrics');
	$this->metrics->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->metrics->removeDecorator('DtDdWrapper');
	$this->metrics->setLegend('Measurements');
	
	$this->addDisplayGroup(array('material1','material2','manmethod','surftreat',
	//'decmethod',
	'decstyle','preservation','completeness'), 'methods');
	$this->methods->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->methods->removeDecorator('DtDdWrapper');
	$this->methods->setLegend('Methods of production and decoration');
	
	$this->addDisplayGroup(array('recordername','recorderID','idBy',
	'identifier1ID','id2by','identifier2ID'), 'people');
	$this->people->removeDecorator('DtDdWrapper');
	$this->people->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->people->setLegend('Recording details');
	
	$this->addDisplayGroup(array('finder','finderID','secondfinder','finder2ID'), 'discoverers');
	$this->discoverers->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->discoverers->removeDecorator('DtDdWrapper');
	$this->discoverers->setLegend('Discoverer details');
	
	
	$this->addDisplayGroup(array('disccircum','discmethod','datefound1',
	'datefound2','rally','rallyID'), 'discovery');
	$this->discovery->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->discovery->removeDecorator('DtDdWrapper');
	$this->discovery->setLegend('Discovery details');
	
	$this->addDisplayGroup(array('other_ref','smr_ref','musaccno','curr_loc',
	'subs_action'), 'references');
	$this->references->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->references->removeDecorator('DtDdWrapper');
	$this->references->setLegend('Reference numbers');
	
	
	$this->addDisplayGroup(array('submit'), 'submit');
	
	}
}