<?php
/** Form for searching for Roman numismatics
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanNumismaticSearchForm extends Pas_Form
{

	protected function getRole(){
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()){
	$user = $auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}
	
	protected $_higherlevel = array('admin','flos','fa','heros'); 
	
	protected $_restricted = array('public','member','research');
	
	
	public function __construct($options = null)
	{
	
	//Get data to form select menu for primary and secondary material
	$primaries = new Materials();
	$primary_options = $primaries->getPrimaries();
	
	//Get Rally data
	$rallies = new Rallies();
	$rally_options = $rallies->getRallies();
	
	//Get Hoard data
	$hoards = new Hoards();
	$hoard_options = $hoards->getHoards();
	
	$counties = new Counties();
	$county_options = $counties->getCountyName2();
	
	$denominations = new Denominations();
	$denom_options = $denominations->getOptionsRoman();
	
	$rulers = new Rulers();
	$ruler_options = $rulers->getRomanRulers();
	
	$mints = new Mints();
	$mint_options = $mints->getRomanMints();
	
	$axis = new Dieaxes();
	$axis_options = $axis->getAxes();
	
	$reece = new Reeces();
	$reece_options = $reece->getReeces();
	
	$regions = new Regions();
	$region_options = $regions->getRegionName();
	
	$moneyers = new Moneyers();
	$money = $moneyers->getRepublicMoneyers();

	
	$institutions = new Institutions();
	$inst_options = $institutions->getInsts();
	
	parent::__construct($options);
	
	
	$decorator =  array('SimpleInput');
	$decoratorSelect =  array('SelectInput');
	
	$decorators = array(
	            array('ViewHelper'), 
	            array('Description', array('placement' => 'append','class' => 'info')),
	            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
	            array('Label', array('separator'=>' ', 'requiredSuffix' => ' *')),
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
	
				
	$this->setName('search-roman-coins');
	
	$old_findID = new Zend_Form_Element_Text('old_findID');
	$old_findID->setLabel('Find number: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid number!')
		->setDecorators($decorators);
	
	$description = new Zend_Form_Element_Text('description');
	$description->setLabel('Object description contains: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);
	
	
	$workflow = new Zend_Form_Element_Select('workflow');
	$workflow->setLabel('Workflow stage: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setDecorators($decorators);
	if(in_array($this->getRole(),$this->_higherlevel)) {
	$workflow->addMultiOptions(array(NULL => NULL ,'Available worklow stage' => array('1'=> 'Quarantine','2' => 'On review', '4' => 'Awaiting validation', '3' => 'Published')));
	}
	if(in_array($this->getRole(),$this->_restricted)) {
	$workflow->addMultiOptions(array(NULL => 'Choose a workflow stage' ,'Available worklow stage' => array('4' => 'Awaiting validation', '3' => 'Published')));
	}
        
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')->removeDecorator('label')
		->setTimeout(4800);
	$this->addElement($hash);
	
	//Rally details
	$rally = new Zend_Form_Element_Checkbox('rally');
	$rally->setLabel('Rally find: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setUncheckedValue(NULL)
		->setDecorators($decorators);	
	
	$rallyID =  new Zend_Form_Element_Select('rallyID');
	$rallyID->setLabel('Found at this rally: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose rally name','Available rallies' => $rally_options))
		->addValidator('InArray', false, array(array_keys($rally_options)))
		->setDecorators($decorators);
	
	
	$hoard = new Zend_Form_Element_Checkbox('hoard');
	$hoard->setLabel('Hoard find: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setUncheckedValue(NULL)
		->setDecorators($decorators);
	
	$hoardID =  new Zend_Form_Element_Select('hID');
	$hoardID->setLabel('Part of this hoard: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose hoard name','Available hoards' => $hoard_options))
		->addValidator('InArray', false, array(array_keys($hoard_options)))
		->setDecorators($decorators);
	
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('County: ')
		->addValidators(array('NotEmpty'))
		->addMultiOptions(array(NULL => 'Choose county first','Available counties' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)))
		->setDecorators($decorators);
	
	$district = new Zend_Form_Element_Select('district');
	$district->setLabel('District: ')
	->addMultiOptions(array(NULL => 'Choose district after county'))
	->setRegisterInArrayValidator(false)
	->setDecorators($decorators)
	->disabled = true;
	
	$parish = new Zend_Form_Element_Select('parish');
	$parish->setLabel('Parish: ')
		->addMultiOptions(array(NULL => 'Choose parish after county'))
		->setDecorators($decorators)
		->disabled = true;
	
	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
		->setDecorators($decorators)
		->addMultiOptions(array(NULL => 'Choose a region for a wide result','Choose region' => $region_options))
		->addValidator('InArray', false, array(array_keys($region_options)))
		->addFilters(array('StripTags', 'StringTrim'));
	
	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
		->addValidators(array('ValidGridRef'))
		->addFilters(array('StripTags', 'StringTrim'))
		->setDecorators($decorators);
	
	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
		->addValidators(array('ValidGridRef'))
		->addFilters(array('StripTags', 'StringTrim'))
		->setDecorators($decorators);
	###
	##Numismatic data
	###
	//Denomination
	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose denomination type' ,'Available denominations' => $denom_options))
		->addValidator('InArray', false, array(array_keys($denom_options)))
		->setDecorators($decorators);
	
	//Primary ruler
	$ruler = new Zend_Form_Element_Select('ruler');
	$ruler->setLabel('Ruler / issuer: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose primary ruler', 'Available rulers'=> $ruler_options))
		->addValidator('InArray', false, array(array_keys($ruler_options)))
		->setDecorators($decorators);
	//Mint
	$mint = new Zend_Form_Element_Select('mint');
	$mint->setLabel('Issuing mint: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose issuing mint', 'Available mints' => $mint_options))
		->addValidator('InArray', false, array(array_keys($mint_options)))
		->setDecorators($decorators);
		
	//Reece
	$reece = new Zend_Form_Element_Select('reeceID');
	$reece->setLabel('Reece period: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => 'Choose Reece period','Available Reece periods' => $reece_options))
		->addValidator('InArray', false, array(array_keys($reece_options)))
		->setDecorators($decorators);
	
	//Reverse type
	$reverse = new Zend_Form_Element_Select('revtypeID');
	$reverse->setLabel('Fourth Century reverse type: ')
		->setDescription('This field is only applicable for fourth century AD coins.')
		->addFilters(array('StripTags', 'StringTrim'))
		->setDecorators($decorators)
		->addMultiOptions(array(NULL => 'Only available after choosing a 4th century issuer'));
	//Moneyer
	$moneyer = new Zend_Form_Element_Select('moneyer');
	$moneyer->setLabel('Republican moneyers: ')
		->setDescription('This field is only applicable for Republican coins.')
		->addFilters(array('StripTags', 'StringTrim'))
		->setDecorators($decorators)
		->addMultiOptions(array(NULL => 'Only available after choosing a Republican issuer'))
                ->addValidator('InArray', false, array(array_keys($money)));
	
	//Obverse inscription
	$obverseinsc = new Zend_Form_Element_Text('obverseLegend');
	$obverseinsc->setLabel('Obverse inscription contains: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);
	
	//Obverse description
	$obversedesc = new Zend_Form_Element_Text('obverseDescription');
	$obversedesc->setLabel('Obverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid term')
		->setDecorators($decorators);
	
	//reverse inscription
	$reverseinsc = new Zend_Form_Element_Text('reverseLegend');
	$reverseinsc->setLabel('Reverse inscription contains: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setAttrib('size',60)
		->setDecorators($decorators);
	
	//reverse description
	$reversedesc = new Zend_Form_Element_Text('reverseDescription');
	$reversedesc->setLabel('Reverse description contains: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('Please enter a valid term')
		->setAttrib('size',60)
		->setDecorators($decorators);
	
	//Die axis
	$axis = new Zend_Form_Element_Select('axis');
	$axis->setLabel('Die axis measurement: ')
		->setRequired(false)
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(NULL => NULL,'Choose measurement' => $axis_options))
		->addValidator('InArray', false, array(array_keys($axis_options)))
		->setDecorators($decorators);
	
	$objecttype = new Zend_Form_Element_Hidden('objecttype');
	$objecttype->setValue('coin')
		->setAttrib('class', 'none')->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper');
	
	$broadperiod = new Zend_Form_Element_Hidden('broadperiod');
	$broadperiod->setValue('Roman')
		->setAttrib('class', 'none')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->addFilters(array('StringToUpper', 'StripTags', 'StringTrim'))
		->addValidator('Alpha');
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setAttrib('id', 'submitbutton')
		->removeDecorator('label')
		->removeDecorator('HtmlTag')
		->removeDecorator('DtDdWrapper')
		->setLabel('Submit your search ..')
		->setAttrib('class', 'large');
		
	$institution = new Zend_Form_Element_Select('institution');
	$institution->setLabel('Recording institution: ')
	->setRequired(false)
	->addFilters(array('StringTrim','StripTags'))
	->addMultiOptions(array(NULL => NULL,'Choose institution' => $inst_options))
	->setDecorators($decorators); 
	
	$this->addElements(array(
	$old_findID,$description,
	$workflow,$rally,$rallyID,
	$hoard,$hoardID,$county,
	$regionID,$district,$parish,
	$fourFigure,$gridref,$denomination,
	$ruler,$mint,$axis,
	$reece,$reverse,$obverseinsc,
	$obversedesc,$reverseinsc,
	$reversedesc,$moneyer,$objecttype,
	$broadperiod, $submit, $hash,
	$institution));
	
	$this->addDisplayGroup(array(
	'denomination','ruler','mint',
	'moneyer','axis','reeceID',
	'revtypeID','obverseLegend','obverseDescription',
	'reverseLegend','reverseDescription'), 'numismatics')->removeDecorator('HtmlTag');
	$this->numismatics->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->numismatics->removeDecorator('DtDdWrapper');
	
	
	$this->addDisplayGroup(array('old_findID','description','rally','rallyID','hoard','hID','workflow'), 'details')
	->removeDecorator('HtmlTag');
	$this->details->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->details->removeDecorator('DtDdWrapper');
	
	$this->addDisplayGroup(array('county','regionID','district','parish','gridref','fourFigure','institution'), 
	'spatial')->removeDecorator('HtmlTag');
	$this->spatial->addDecorators(array('FormElements',array('HtmlTag', array('tag' => 'ul'))));
	$this->spatial->removeDecorator('DtDdWrapper');
	
	$this->addDisplayGroup(array('submit'), 'submit');
	$this->numismatics->setLegend('Numismatic details');
	$this->details->setLegend('Artefact details');
	$this->spatial->setLegend('Spatial details');
	
	
	}
}