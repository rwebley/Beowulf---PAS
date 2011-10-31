<?php
/** Controller for displaying information about coins
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_CoinsController extends Pas_Controller_Action_Admin {
	
	protected $_coins;
	/** Setup the contexts by action and the ACL.
	*/	
	public function init() {	
	$this->_helper->_acl->allow('member',array(
	'add', 'edit', 'delete',
	'coinref', 'editcoinref', 'deletecoinref'));
	$this->_helper->_acl->allow('flos',null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_coins = new Coins();
    }
    
	const REDIRECT = '/database/artefacts/';
	/** Redirect as no direct access to the coins index page
	*/
	public function indexAction() {
	$this->_flashMessenger->addMessage('There is not a root action for coins');
	$this->_redirect(Zend_Controller_Request_Http::getServer('referer'));
	}
	/** Add a coin's data
	*/
	public function addAction() {
	if( ($this->_getParam('broadperiod',false)) && ($this->_getParam('findID',false) )){
	$exist = $this->_coins->checkCoinData($this->_getParam('findID'));
	$broadperiod = (string)$this->_getParam('broadperiod');
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
	. '/js/JQuery/jQueryLinkedSelect.js',
	$type='text/javascript'); 
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
	. '/js/JQuery/coinslinkedselect.js',
	$type='text/javascript'); 
	
	switch ($broadperiod) {
		
		case 'ROMAN':
			$form = new RomanCoinForm();
			$form->details->setLegend('Add Roman numismatic data');
			$form->submit->setLabel('Add Roman data');
			$this->view->headTitle('Add a Roman coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl()
			. '/js/JQuery/coinslinkedinit.js',$type='text/javascript');

			break;
		case 'IRON AGE':
			$form = new IronAgeCoinForm();
			$form->details->setLegend('Add Iron Age numismatic data');
			$form->submit->setLabel('Add Iron Age data');
			$this->view->headTitle('Add an Iron Age coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/iacoinslinkedinit.js',$type='text/javascript');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/jquery.autocomplete.pack.js',$type='text/javascript');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl()
			. '/js/JQuery/autocompleteinit.js',$type='text/javascript'); 
			$this->view->headLink()->appendStylesheet($this->view->baseUrl() 
			. '/css/autocomplete.css');

			break;
		case 'EARLY MEDIEVAL':
			$form = new EarlyMedievalCoinForm();
			$form->details->setLegend('Add Early Medieval numismatic data');
			$form->submit->setLabel('Add Early Medieval data');
			$this->view->headTitle('Add an Early Medieval coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/coinslinkedinitearlymededit.js',$type='text/javascript');
			break; 
		
		case 'MEDIEVAL':
			$form = new MedievalCoinForm();
			$form->details->setLegend('Add Medieval numismatic data');
			$form->submit->setLabel('Add Medieval data');
			$this->view->headTitle('Add a Medieval coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/coinslinkedinitmededit.js',$type='text/javascript');
			break; 
		
		case 'POST MEDIEVAL':
			$form = new PostMedievalCoinForm();
			$form->details->setLegend('Add Post Medieval numismatic data');
			$form->submit->setLabel('Add Post Medieval data');
			$this->view->headTitle('Add a Post Medieval coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/coinslinkedinitpostmededit.js',$type='text/javascript');
			break; 
		
		case 'BYZANTINE':
			$form = new ByzantineCoinForm();
			$form->details->setLegend('Add Byzantine numismatic data');
			$form->submit->setLabel('Add Byzantine data');
			break; 
		
		case 'GREEK AND ROMAN PROVINCIAL':
			$form = new GreekAndRomanCoinForm();
			$form->details->setLegend('Add Greek & Roman numismatic data');
			$form->submit->setLabel('Add Greek & Roman data');
			break; 
		
		default:
			throw new Exception('You cannot have a coin for that period. Stand at the back of the class.');
			break;
	}		
	
	$last = $this->_getParam('copy');
	if($last == 'last') {
	$this->_flashMessenger->addMessage('Your last record data has been cloned');
	$coindata = $this->_coins->getLastRecord($this->getIdentityForForms());
	foreach($coindata as $coindataflat){
	$form->populate($coindataflat);
	switch ($broadperiod) {
		
		case 'IRON AGE':
		if(isset($coindataflat['denomination'])) {
		$geographies= new Geography();
		$geography_options = $geographies->getIronAgeGeographyMenu($coindataflat['denomination']);
		$form->geographyID->addMultiOptions(array(NULL => NULL,'Choose geographic region' => $geography_options));
		}
		break;
		
		case 'ROMAN':
		if(isset($coindataflat['ruler'])) {
		$reverses = new Revtypes();
		$reverse_options = $reverses->getRevTypesForm($coindataflat['ruler']);
		if($reverse_options)
		{
		$form->revtypeID->addMultiOptions(array(NULL => NULL,'Choose reverse type' => $reverse_options));
		} else {
		$form->revtypeID->addMultiOptions(array(NULL => 'No options available'));
		}
		} else {
		$form->revtypeID->addMultiOptions(array(NULL => 'No options available'));
		}
		if(isset($coindataflat['ruler']) && ($coindataflat['ruler'] == 242)){
		$moneyers = new Moneyers();
		$moneyer_options = $moneyers->getRepublicMoneyers();
		$form->moneyer->addMultiOptions(array(NULL => NULL,'Choose reverse type' => $moneyer_options));
		} else {
		$form->moneyer->addMultiOptions(array(NULL => 'No options available'));
		//$form->moneyer->disabled=true;
		}	
		break;

		case 'EARLY MEDIEVAL':
		$types = new MedievalTypes();
		$type_options = $types->getMedievalTypeToRulerMenu($coindataflat['ruler']);
		$form->typeID->addMultiOptions(array(NULL => NULL,'Choose Early Medieval type' => $type_options));
		break;
		
		case 'MEDIEVAL':
		$types = new MedievalTypes();
		$type_options = $types->getMedievalTypeToRulerMenu($coindataflat['ruler']);
		$form->typeID->addMultiOptions(array(NULL => NULL,'Choose Medieval type' => $type_options));
		break;
		
		case 'POST MEDIEVAL':
		$types = new MedievalTypes();
		$type_options = $types->getMedievalTypeToRulerMenu($coindataflat['ruler']);
		$form->typeID->addMultiOptions(array(NULL => NULL,'Choose Post Medieval type' => $type_options));
		break;	
	}
	}
	}
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$secuid = $this->secuid();
	
	$insertData = array(
			'findID' => (string)$this->_getParam('findID'),
			'ruler_id' => $form->getValue('ruler'),
			'ruler_qualifier' => $form->getValue('ruler_qualifier'),
			'denomination' => $form->getValue('denomination'),
			'denomination_qualifier' => $form->getValue('denomination_qualifier'),
			'mint_id' => $form->getValue('mint_id'),
			'mint_qualifier' => $form->getValue('mint_qualifier'),
			'status' => $form->getValue('status'),
			'status_qualifier' => $form->getValue('status_qualifier'),
			'obverse_description' => (string)$form->getValue('obverse_description'),
			'obverse_inscription' => (string)$form->getValue('obverse_inscription'),
			'reverse_description' => (string)$form->getValue('reverse_description'),
			'reverse_inscription' => (string)$form->getValue('reverse_inscription'),
			'reverse_mintmark' => (string)$form->getValue('reverse_mintmark'),
			'degree_of_wear' => $form->getValue('degree_of_wear'),
			'die_axis_measurement' => $form->getValue('die_axis_measurement'),
			'die_axis_certainty' => $form->getValue('die_axis_certainty'),
			'moneyer' => $form->getValue('moneyer'),
			'reverse_mintmark' => $form->getValue('reverse_mintmark'),
			'initial_mark' => $form->getValue('initial_mark'),
			'reeceID' => $form->getValue('reeceID'),
			'revtypeID' => $form->getValue('revtypeID'),
			'revTypeID_qualifier' => $form->getValue('revTypeID_qualifier'),
			'ruler2_id' => $form->getValue('ruler2_id'),
			'ruler2_qualifier' => $form->getValue('ruler2_qualifier'),
			'tribe' => $form->getValue('tribe'),
			'tribe_qualifier' => $form->getValue('tribe_qualifier'),
			'geographyID' => $form->getValue('geographyID'),
			'geography_qualifier' => $form->getValue('geography_qualifier'),
			'bmc_type' => $form->getValue('bmc_type'),
			'allen_type' => $form->getValue('allen_type'),
			'mack_type' => $form->getValue('mack_type'),
			'rudd_type' => $form->getValue('rudd_type'),
			'va_type' => $form->getValue('va_type'),
			'cciNumber' => $form->getValue('cciNumber'),
			'phase_date_1' => $form->getValue('phase_date_1'),
			'phase_date_2' => $form->getValue('phase_date_2'),
			'context' => $form->getValue('context'),
			'depositionDate' => $form->getValue('depositionDate'),
			'numChiab' => $form->getValue('numChiab'),
			'categoryID' => $form->getValue('categoryID'),
			'typeID' => $form->getValue('typeID'),
			'type' => $form->getValue('type'),
			'initial_mark' => $form->getValue('initial_mark'),
			'greekstateID' => $form->getValue('greekstateID'),
			'secuid' => (string)$secuid,
			'created' => $this->getTimeForForms(), 
			'createdBy' => $this->getIdentityForForms()
			);
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$this->_coins->insert($insertData);
	$solr = new Pas_Solr_Updater();
	$solr->add($this->_getParam('returnID'));
	$this->_flashMessenger->addMessage('Coin data saved for this record.');
	$this->_redirect(self::REDIRECT.'record/id/' . $this->_getParam('returnID'));
	} 
	else
	{
	$form->populate($formData);
	//Add menu data here
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
		
	/** Edit coin data
	 * @throws Pas_Exception_Param
	*/	
	public function editAction() {
	if($this->_getParam('id',false)){
 	$finds = new Finds();
	$this->view->finds = $finds->getFindNumbersEtc($this->_getParam('returnID'));
 	$broadperiod = (string)$this->_getParam('broadperiod');
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
	. '/js/JQuery/jQueryLinkedSelect.js',
	$type='text/javascript'); 
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
	. '/js/JQuery/coinslinkedselect.js',
	$type='text/javascript'); 
	switch ($broadperiod) {
		
		case 'ROMAN':
			$form = new RomanCoinForm();
			$form->details->setLegend('Edit Roman numismatic data');
			$form->submit->setLabel('Save Roman data');
			$this->view->headTitle('Edit a Roman coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/coinslinkedinit.js',$type='text/javascript');
			break;
		
		case 'IRON AGE':
			$form = new IronAgeCoinForm();
			$form->details->setLegend('Edit Iron Age numismatic data');
			$form->submit->setLabel('Save Iron Age data');
			$this->view->headTitle('Edit an Iron Age coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl()
			. '/js/JQuery/iacoinslinkedinit.js',$type='text/javascript');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/jquery.autocomplete.pack.js',$type='text/javascript');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/autocompleteinit.js',$type='text/javascript'); 
			$this->view->headLink()->appendStylesheet($this->view->baseUrl() 
			. '/css/autocomplete.css');
			break;
		case 'EARLY MEDIEVAL':
			$form = new EarlyMedievalCoinForm();
			$form->details->setLegend('Edit Early Medieval numismatic data');
			$form->submit->setLabel('Save Early Medieval data');
			$this->view->headTitle('Edit an Early Medieval coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/coinslinkedinitearlymededit.js',$type='text/javascript');
			break; 
		case 'MEDIEVAL':
			$form = new MedievalCoinForm();
			$form->details->setLegend('Edit Medieval numismatic data');
			$form->submit->setLabel('Save Medieval data');
			$this->view->headTitle('Edit a Medieval coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/coinslinkedinitmededit.js',$type='text/javascript');
			break; 
		case 'POST MEDIEVAL':
			$form = new PostMedievalCoinForm();
			$form->details->setLegend('Edit Post Medieval numismatic data');
			$form->submit->setLabel('Save Post Medieval data');
			$this->view->headTitle('Edit a Post Medieval coin\'s details');
			$this->view->jQuery()->addJavascriptFile($this->view->baseUrl() 
			. '/js/JQuery/coinslinkedinitpostmededit.js',$type='text/javascript');
			break; 
		case 'BYZANTINE':
			$form = new ByzantineCoinForm();
			$form->details->setLegend('Edit Byzantine numismatic data');
			$form->submit->setLabel('Save Byzantine data');
			$this->view->headTitle('Edit a Byzantine coin\'s details');
			break; 
		case 'GREEK AND ROMAN PROVINCIAL':
			$form = new GreekAndRomanCoinForm();
			$form->details->setLegend('Edit Greek & Roman numismatic data');
			$form->submit->setLabel('Save Greek & Roman data');
			$this->view->headTitle('Edit a Greek & Roman provincial coin\'s details');
			break; 
		default:
			throw new Exception('You cannot have a coin for that period. 
			Stand at the back of the class.');
			break;
	}		
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$updateData = array(
			'ruler_id' => $form->getValue('ruler'),
			'ruler_qualifier' => $form->getValue('ruler_qualifier'),
			'denomination' => $form->getValue('denomination'),
			'denomination_qualifier' => $form->getValue('denomination_qualifier'),
			'mint_id' => $form->getValue('mint_id'),
			'mint_qualifier' => $form->getValue('mint_qualifier'),
			'status' => $form->getValue('status'),
			'status_qualifier' => $form->getValue('status_qualifier'),
			'obverse_description' => (string)$form->getValue('obverse_description'),
			'obverse_inscription' => (string)$form->getValue('obverse_inscription'),
			'reverse_description' => (string)$form->getValue('reverse_description'),
			'reverse_inscription' => (string)$form->getValue('reverse_inscription'),
			'reverse_mintmark' => (string)$form->getValue('reverse_mintmark'),
			'degree_of_wear' => $form->getValue('degree_of_wear'),
			'die_axis_measurement' => $form->getValue('die_axis_measurement'),
			'die_axis_certainty' => $form->getValue('die_axis_certainty'),
			'moneyer' => $form->getValue('moneyer'),
			'reverse_mintmark' => $form->getValue('reverse_mintmark'),
			'initial_mark' => $form->getValue('initial_mark'),
			'reeceID' => $form->getValue('reeceID'),
			'revtypeID' => $form->getValue('revtypeID'),
			'revTypeID_qualifier' => $form->getValue('revTypeID_qualifier'),
			'ruler2_id' => $form->getValue('ruler2_id'),
			'ruler2_qualifier' => $form->getValue('ruler2_qualifier'),
			'tribe' => $form->getValue('tribe'),
			'tribe_qualifier' => $form->getValue('tribe_qualifier'),
			'geographyID' => $form->getValue('geographyID'),
			'geography_qualifier' => $form->getValue('geography_qualifier'),
			'bmc_type' => $form->getValue('bmc_type'),
			'allen_type' => $form->getValue('allen_type'),
			'mack_type' => $form->getValue('mack_type'),
			'rudd_type' => $form->getValue('rudd_type'),
			'va_type' => $form->getValue('va_type'),
			'cciNumber' => $form->getValue('cciNumber'),
			'phase_date_1' => $form->getValue('phase_date_1'),
			'phase_date_2' => $form->getValue('phase_date_2'),
			'context' => $form->getValue('context'),
			'depositionDate' => $form->getValue('depositionDate'),
			'numChiab' => $form->getValue('numChiab'),
			'categoryID' => $form->getValue('categoryID'),
			'typeID' => $form->getValue('typeID'),
			'type' => $form->getValue('type'),
			'initial_mark' => $form->getValue('initial_mark'),
			'greekstateID' => $form->getValue('greekstateID'),
			'updated' => $this->getTimeForForms(), 
			'updatedBy' => $this->getIdentityForForms()
			);
	foreach ($updateData as $key => $value) {
      if (is_null($value) || $value=="") {
       $updateData[$key] = NULL;
      }
    }
	
	
	$auditData = $updateData;
	$audit = $this->_coins->fetchRow('id='.$this->_getParam('id'));
	$oldarray = $audit->toArray();
	$where =  $this->_coins->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$update = $this->_coins->update($updateData,$where);
	$solr = new Pas_Solr_Updater();
	$solr->add($this->_getParam('returnID'));
	if (!empty($auditData)) {
        // look for new fields with empty/null values
        foreach ($auditData as $item => $value) {
            if (empty($value)) {
                if (!array_key_exists($item, $oldarray)) {
                    // value does not exist in $oldarray, so remove from $newarray
                    unset ($updateData[$item]);
                } // if
            } else {
                // remove slashes (escape characters) from $newarray
                $auditData[$item] = stripslashes($auditData[$item]);
            } // if
        } // foreach 
        // remove entry from $oldarray which does not exist in $newarray
        foreach ($oldarray as $item => $value) {
            if (!array_key_exists($item, $auditData)) {
                unset ($oldarray[$item]);
            } // if
        } // foreach
    } //

	$fieldarray   = array();
    $ix           = 0;
	$editID = md5($this->getTimeForForms());
    foreach ($oldarray as $field_id => $old_value) {
        $ix++;
        $fieldarray[$ix]['coinID']     = $this->_getParam('id');
		$fieldarray[$ix]['findID']     = $this->_getParam('returnID');
		$fieldarray[$ix]['editID']     = $editID;
        $fieldarray[$ix]['created']     = $this->getTimeForForms();
		$fieldarray[$ix]['createdBy']     = $this->getIdentityForForms();
        $fieldarray[$ix]['fieldName']     = $field_id;
        $fieldarray[$ix]['beforeValue']    = $old_value;
        if (isset($auditData[$field_id])) {
            $fieldarray[$ix]['afterValue'] = $auditData[$field_id];
            // remove matched entry from $newarray
            unset($auditData[$field_id]);
        } else {
            $fieldarray[$ix]['afterValue'] = '';
        } // if
    } // foreach
    
    // process any unmatched details remaining in $newarray
    foreach ($auditData as $field_id => $new_value) {
        $ix++;
        $fieldarray[$ix]['coinID']     = $this->_getParam('id');
		$fieldarray[$ix]['findID']     = $returnID;
		$fieldarray[$ix]['editID']     = $editID;
        $fieldarray[$ix]['created']     = $this->getTimeForForms();
		$fieldarray[$ix]['createdBy']     = $this->getIdentityForForms();
        $fieldarray[$ix]['fieldName']     = $field_id;
        $fieldarray[$ix]['afterValue']    = $new_value;
		
    } 
	function filteraudit($fieldarray)
	{
	if ($fieldarray['afterValue'] != $fieldarray['beforeValue'])
	  {
	return true;
	  }
	}
	
	$fieldarray = array_filter($fieldarray,'filteraudit');
	
	foreach($fieldarray as $f){
	foreach ($f as $key => $value) {
      if (is_null($value) || $value=="") {
       $f[$key] = NULL;
      }
    }

	$audit = new CoinsAudit();
	$auditBaby = $audit->insert($f);
	}
	
	$this->_flashMessenger->addMessage('Numismatic details updated.');
	$this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
	} else {
	$this->_flashMessenger->addMessage('Please check your form for errors');

	$form->populate($formData);
	}
	} else {
	// find id is expected in $params['id']
	$id = (int)$this->_getParam('id', 0);
	if ($id > 0) {
	$coin = $this->_coins->getCoinToEdit($id);
	$form->populate($coin['0']);
	switch ($broadperiod) {
		case 'IRON AGE':
		if(isset($coin['0']['denomination'])) {
		$geographies= new Geography();
		$geography_options = $geographies->getIronAgeGeographyMenu($coin['0']['denomination']);
		$form->geographyID->addMultiOptions(array(NULL => NULL, 
		'Choose geographic region' => $geography_options));
		}
		break;
		
		case 'ROMAN':
		if(isset($coin['0']['ruler'])) {
		$reverses = new Revtypes();
		$reverse_options = $reverses->getRevTypesForm($coin['0']['ruler']);
		if($reverse_options)
		{
		$form->revtypeID->addMultiOptions(array(NULL => NULL,
		'Choose reverse type' => $reverse_options));
		} else {
		$form->revtypeID->addMultiOptions(array(NULL => 'No options available'));
		}
		} else {
		$form->revtypeID->addMultiOptions(array(NULL => 'No options available'));
		}
		if(isset($coin['0']['ruler']) && ($coin['0']['ruler'] == 242)){
		$moneyers = new Moneyers();
		$moneyer_options = $moneyers->getRepublicMoneyers();
		$form->moneyer->addMultiOptions(array(NULL => NULL,
		'Choose reverse type' => $moneyer_options));
		} else {
		$form->moneyer->addMultiOptions(array(NULL => 'No options available'));
		//$form->moneyer->disabled=true;
		}	
		break;
		
		case 'EARLY MEDIEVAL':
		if(isset($coin['0']['ruler'])) {
		$types = new MedievalTypes();
		$type_options = $types->getMedievalTypeToRulerMenu($coin['0']['ruler']);
		$form->typeID->addMultiOptions(array(NULL => NULL,
		'Choose Early Medieval type' => $type_options));
		$form->mint_id->clearMultiOptions();
		$form->denomination->clearMultiOptions();
		$mints = new Mints();
		$mints_options = $mints->getEarlyMedMintRulerPairs($coin['0']['ruler']);
		$form->mint_id->addMultiOptions(array(NULL => NULL,
		'Choose a valid mint' => $mints_options));
		$denoms = new Denominations();
		$denom_options = $denoms->getEarlyMedRulerToDenominationPairs($coin['0']['ruler']);
		$form->denomination->addMultiOptions(array(NULL => NULL,
		'Choose a valid denomination' => $denom_options));
		}
		break;
		
		case 'MEDIEVAL':
		if(isset($coin['0']['ruler'])) {
		$form->mint_id->clearMultiOptions();
		$form->denomination->clearMultiOptions();
		$types = new MedievalTypes();
		$type_options = $types->getMedievalTypeToRulerMenu($coin['0']['ruler']);
		$form->typeID->addMultiOptions(array(NULL => NULL,'Choose Medieval type' => $type_options));
		$mints = new Mints();
		$mints_options = $mints->getEarlyMedMintRulerPairs($coin['0']['ruler']);
		$form->mint_id->addMultiOptions(array(NULL => NULL,'Choose a valid mint' => $mints_options));
		$denoms = new Denominations();
		$denom_options = $denoms->getEarlyMedRulerToDenominationPairs($coin['0']['ruler']);
		$form->denomination->addMultiOptions(array(NULL => NULL,'Choose a valid denomination' => $denom_options));
		}
		break;
		case 'POST MEDIEVAL':
		if(isset($coin['0']['ruler'])) {
		$form->mint_id->clearMultiOptions();
		$form->denomination->clearMultiOptions();
		$types = new MedievalTypes();
		$type_options = $types->getMedievalTypeToRulerMenu($coin['0']['ruler']);
		$form->typeID->addMultiOptions(array(NULL => NULL,'Choose Post Medieval type' => $type_options));
		$denoms = new Denominations();
		$denom_options = $denoms->getEarlyMedRulerToDenominationPairs($coin['0']['ruler']);
		$form->denomination->addMultiOptions(array(NULL => NULL,'Choose a valid denomination' => $denom_options));
		$mints = new Mints();
		$mints_options = $mints->getEarlyMedMintRulerPairs($coin['0']['ruler']);
		$form->mint_id->addMultiOptions(array(NULL => NULL,'Choose a valid mint' => $mints_options));
		}
		break;	
	}
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	/** Delete coin data
	*/
	public function deleteAction() {
	if($this->_getParam('id',false)){
	$this->view->headTitle('Delete coin data');
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$returnID = (int)$this->_request->getPost('returnID');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$where = 'id = ' . $id;
	$this->_coins->delete($where);
	$this->_flashMessenger->addMessage('Numismatic data deleted!');
	$this->_redirect(self::REDIRECT . 'record/id/' . $returnID);
	}
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->coins = $this->_coins->getFindToCoinDelete($id);
	}
	}
	} else {
		throw new Pas_Exception_Param($this->_missingParameter);
	}
	}
	
	/** Link coin reference to object
	*/
	public function coinrefAction() {
	$params = $this->_getAllParams();
	if(!isset($params['returnID']) && !isset($params['findID'])) {
	throw new Pas_Exception_Param('Find ID and return ID missing');
	}
	if(!isset($params['returnID'])) {
	throw new Pas_Exception_Param('The return ID parameter is missing.');
	}
	if(!isset($params['findID'])) {
	throw new Pas_Exception_Param('The find ID parameter is missing.');
	}
	$form = new ReferenceCoinForm();
	$form->submit->setLabel('Add reference');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$coins = new CoinXClass();
	$secuid = $this->secuid();
	$insertData = array(
			'findID' => (string)$this->_getParam('findID'),
			'classID' => $form->getValue('classID'),
			'vol_no' => $form->getValue('vol_no'),
			'reference' => $form->getValue('reference'),
			'created' => $this->getTimeForForms(), 
			'createdBy' => $this->getIdentityForForms()
			);
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }
	$coins->insert($insertData);
	$this->_flashMessenger->addMessage('Coin reference data saved for this record.');
	$this->_redirect(self::REDIRECT.'record/id/' . $this->_getParam('returnID'));
	} else {
	$form->populate($formData);
	}
	}
	}
	
	/** Edit a coin reference to object
	*/	
	public function editcoinrefAction()	{
	$form = new ReferenceCoinForm();
	$form->submit->setLabel('Edit reference');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$coins = new CoinXClass();
	$updateData = array(
	'findID' => (string)$this->_getParam('findID'),
	'classID' => $form->getValue('classID'),
	'vol_no' => $form->getValue('vol_no'),
	'reference' => $form->getValue('reference'),
	'updated' => $this->getTimeForForms(),
	'updatedBy' => $this->getIdentityForForms()
	);
	foreach ($updateData as $key => $value) {
    if (is_null($value) || $value=="") {
    unset($updateData[$key]);
    }
    }
	$where = array();
	$where[] = $coins->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$coins->update($updateData,$where);
	$this->_flashMessenger->addMessage('Coin reference information updated!');
	$this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
	}  else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$coins = new CoinXClass();
	$coins = $coins->fetchRow('id=' . $id);
	$form->populate($coins->toArray());
	}
	}
	}
	/** Delete a coin reference to object
	*/
	public function deletecoinrefAction() {
	$returnID = $this->_getParam('returnID');
	$this->view->returnID = $returnID;
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$coins = new CoinXClass();
	$where = $coins->getAdapter()->quoteInto('id = ?', $id);
	$this->_flashMessenger->addMessage('Record deleted!');
	$coins->delete($where);	
	$this->_redirect(self::REDIRECT . 'record/id/' . $returnID);
	}
	$this->_flashMessenger->addMessage('No changes made!');
	$this->_redirect('database/artefacts/record/id/' . $returnID);
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$coins = new CoinXClass();
	$this->view->coin = $coins->fetchRow('id=' . $id);
	}
	}
	}

}