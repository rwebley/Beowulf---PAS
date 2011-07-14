<?php
/** Controller for managing jettons etc
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_JettonsController extends Pas_Controller_ActionAdmin {
	
	protected $_coins;
	/** Setup the contexts by action and the ACL.
	*/	
	public function init()  {	
	$this->_helper->_acl->allow('member',array('add','edit','delete'));
	$this->_helper->_acl->allow('flos',null);
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    $this->_coins = new Coins();
    }
	const REDIRECT = '/database/artefacts/';
	/** redirect of the user due to no action existing.
	*/
	public function indexAction() {
	$this->_flashMessenger->addMessage('There is not a root action for jettons');
	$this->_redirect(Zend_Controller_Request_Http::getServer('referer'));
	}
	/** Add jetton data
	 * @todo rewrite for audit etc
	*/
	public function addAction() {
	if( ($this->_getParam('broadperiod',false)) && ($this->_getParam('findID',false) )){
	$exist = $this->_coins->checkCoinData($this->_getParam('findID'));
	$broadperiod = (string)$this->_getParam('broadperiod');
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl().'/js/JQuery/jQueryLinkedSelect.js',$type='text/javascript'); 
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl().'/js/JQuery/coinslinkedselect.js',$type='text/javascript'); 
	
	switch ($broadperiod) {
		case 'MEDIEVAL':
			$form = new TokenJettonForm();
			$form->details->setLegend('Add Medieval jetton data');
			$form->submit->setLabel('Add jetton data');
			$this->view->headTitle('Add a Medieval jetton\'s details');
			break; 
		case 'POST MEDIEVAL':
			$form = new TokenJettonForm();
			$form->details->setLegend('Add Post Medieval jetton data');
			$form->submit->setLabel('Add jetton data');
			$this->view->headTitle('Add a Post Medieval jetton\'s details');
			break; 
		default:
			throw new Exception('You cannot have a token for that period. Stand at the back of the class.');
			break;
	}		
	
	$last = $this->_getParam('copy');
	if($last == 'last') {
	$this->_flashMessenger->addMessage('Your last record data has been cloned');
	$coindata = $this->_coins->getLastRecord($this->getIdentityForForms());
	foreach($coindata as $coindataflat){
	$form->populate($coindataflat);
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
	$this->_flashMessenger->addMessage('Jetton data saved for this record.');
	$this->_redirect(self::REDIRECT . 'record/id/' . $this->_getParam('returnID'));
	}  else {
	$form->populate($formData);
	//Add menu data here
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
		
	/** Edit jetton data
	 * @todo rewrite for audit etc
	*/		
	public function editAction() {
	if($this->_getParam('id',false)){
 	$finds = new Finds();
	$this->view->finds = $finds->getFindNumbersEtc($this->_getParam('returnID'));
 	$broadperiod = (string)$this->_getParam('broadperiod');
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl().'/js/JQuery/jQueryLinkedSelect.js',$type='text/javascript'); 
	$this->view->jQuery()->addJavascriptFile($this->view->baseUrl().'/js/JQuery/coinslinkedselect.js',$type='text/javascript'); 
	switch ($broadperiod) {
		case 'MEDIEVAL':
			$form = new TokenJettonForm();
			$form->details->setLegend('Edit Medieval jetton data');
			$form->submit->setLabel('Save data');
			$this->view->headTitle('Edit a Medieval jetton\'s details');
			break; 
		case 'POST MEDIEVAL':
			$form = new TokenJettonForm();
			$form->details->setLegend('Edit Post Medieval jetton data');
			$form->submit->setLabel('Save data');
			$this->view->headTitle('Edit a Post Medieval jetton\'s details');
			break; 
		default:
			throw new Exception('You cannot have a jetton for that period. Stand at the back of the class.');
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
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Delete jetton data
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
	$this->_redirect(self::REDIRECT.'record/id/'.$returnID);
	}
	} else {
	$id = (int)$this->_request->getParam('id');
	if ($id > 0) {
	$this->view->coins = $this->_coins->getFindToCoinDelete($id);
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}

}