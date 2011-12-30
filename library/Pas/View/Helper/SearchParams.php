<?php
/**
 * This class is to display search params
 * Sucks monkey balls in extremis.
 * Load of rubbish, needs a rewrite
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2011
 * @todo change the class to use zend_navigation
*/
class Pas_View_Helper_SearchParams
	extends Zend_View_Helper_Abstract {
 
	public function SearchParams($params = NULL) {

	$html = '';
	if(!is_null($params)) {	
	$html .= '<p>You searched for: </p>'; 
	$html .= '<ul>';
	//Objecttype
	if(array_key_exists('objecttype',$params)) {
	if(!s_null($params['objecttype'])) {
	$html .= '<li>Object type: ' . $this->view->escape($params['objecttype']) 
                . '</li>';
	$this->view->headTitle(  ' > Object type: ' . 
                $this->view->escape($params['objecttype']));
	}
	}
	
	//	Broadperiod
	if(array_key_exists('broadperiod',$params)) {
	if(!is_null($params['broadperiod'])) {
	$html .= '<li>Broadperiod: '. $this->view->escape($params['broadperiod']) . '</li>';
	$this->view->headTitle(  ' > Broadperiod: ' . $this->view->escape($params['broadperiod']));
	}
	}
	//County
	if(array_key_exists('county',$params)) {
	if(!is_null($params['county'])) {
	$html .= '<li>County: '. $this->view->escape($params['county']) . '</li>';
	$this->view->headTitle(  ' > County: ' . $this->view->escape($params['county']));
	}
	}

	//region
	if(array_key_exists('regionID',$params)) {
	if(!is_null($params['regionID'])) {
	$region = $params['regionID'];
	$regions = new Regions();
	$regions = $regions->getRegion($region);
	$this->regions = $regions;
	foreach($this->regions as $region){
	$html .= '<li>Region: '. $this->view->escape($region['region']) . '</li>';
	$this->view->headTitle(  ' > Region: ' . $this->view->escape($region['region']));
	}
	}
	}

	if(array_key_exists('material',$params)) {
	if(!is_null($params['material'])) {
	$mat = $params['material'];
	$materials = new Materials();
	$materials = $materials->getMaterialName($mat);
	$this->materials = $materials;
	foreach($this->materials as $material){
	$html .= '<li>Primary material: '. $this->view->escape($material['term']) . '</li>';
	$this->view->headTitle(  ' > Primary material: ' . $this->view->escape($material['term']));
	}
	}
	}

	if(array_key_exists('parish',$params)) {
	if(!is_null($params['parish'])) {
	$html .= '<li>Parish: '. $this->view->escape($params['parish']) . '</li>';
	$this->view->headTitle(  ' > Parish: '. $this->view->escape($params['parish']));
	}
	}

	if(array_key_exists('district',$params)) {
	if(!is_null($params['district'])) {
	$html .= '<li>District: '. $this->view->escape($params['district']) . '</li>';
	$this->view->headTitle(  ' > District: '. $this->view->escape($params['district']));
	}
	}

	if(array_key_exists('denomination',$params)) {
	$denomname = $params['denomination'];
	$denoms = new Denominations();
	$denoms = $denoms->getDenomName($denomname);
	$this->denoms = $denoms;
	foreach($this->denoms as $denom) {
	$html .= '<li>Denomination type: ' . $this->view->escape($denom['denomination']). '</li>';
	$this->view->headTitle(  ' > Denomination: '. $this->view->escape($denom['denomination']));
	}
	}

	if(array_key_exists('description',$params)) {
	if(!is_null($params['description'])) {
	$html .= '<li>Description contained: '. $this->view->escape($params['description']) . '</li>';
	$this->view->headTitle(  ' > Description contained: '. $this->view->escape($params['description']));
	}
	}

	if(array_key_exists('fourFigure',$params)) {
	if(!is_null($params['fourFigure'])) {
	$html .= '<li>Four figure grid reference: '. $this->view->escape($params['fourFigure']) . '</li>';
	$this->view->headTitle(  ' > Four figure NGR: '. $this->view->escape($params['fourFigure']));
	}
	}

	if(array_key_exists('old_findID',$params)) {
	if(!is_null($params['old_findID'])) {
	$html .= '<li>Find reference number: '. $this->view->escape($params['old_findID']) . '</li>';
	$this->view->headTitle(  ' > Find ID: '. $this->view->escape($params['old_findID']));
	}
	}


	if(array_key_exists('fromsubperiod',$params)) {
	if (!is_null($params['fromsubperiod'])){
	$sub = $params['fromsubperiod'];
	if($sub == 1) { $html .= '<li>Subperiod: Early</li>';
	$this->view->headTitle(  ' > Subperiod: Early');
	}
	else if ($sub == 2) {
	$html .= '<li>Subperiod: Middle</li>';
	$this->view->headTitle(  ' > Subperiod: Middle');
	}
	else if ($sub == 3){
	$html .= '<li>Subperiod: Late</li>';
	$this->view->headTitle(  ' > Subperiod: Late');
	}
	}
	}

	if(array_key_exists('tosubperiod',$params)) {
	if (!is_null($params['tosubperiod'])){
	$sub = $params['tosubperiod'];
	if($sub == 1) { 
	$html .= '<li>Subperiod: Early</li>';
	$this->view->headTitle(  ' > Subperiod: Early');
	} else if ($sub == 2) {
	$html .= '<li>Subperiod: Middle</li>';
	$this->view->headTitle(  ' > Subperiod: Middle');
	} else if ($sub == 3) {
	$html .= '<li>Subperiod: Late</li>';
	$this->view->headTitle(  ' > Subperiod: Late');
	}
	}
	}

	if(array_key_exists('periodfrom',$params)) {
	if(!is_null($params['periodfrom'])) {
	$period = $params['periodfrom'];
	$periods = new Periods();
	$periods = $periods->getPeriodName($period);
	$this->periods = $periods;
	foreach($this->periods as $period) {
	$html .= '<li>Period from: ' . $this->view->escape($period['term']). '</li>';
	$this->view->headTitle(  ' > Period from: '. $this->view->escape($period['term']));
	}
	}
	}

	//Period to key
	if(array_key_exists('periodto',$params)) {
	if(!is_null($params['periodto'])) {
	$period = $params['periodto'];
	$periods = new Periods();
	$periods = $periods->getPeriodName($period);
	$this->periods = $periods;
	foreach($this->periods as $period) {
	$html .= '<li>Period to: ' . $this->view->escape($period['term']). '</li>';
	$this->view->headTitle(  ' > Period to: '. $this->view->escape($params['period']));
	
	}
	}
	}
	//
	if(array_key_exists('surface',$params)) {
	if(!is_null($params['surface'])) {
	$surfaceterm = $params['surface'];
	$surfaces = new Surftreatments();
	$surfaces = $surfaces->getSurfaceTerm($surfaceterm);
	$this->surfaces = $surfaces;
	foreach($this->surfaces as $surface) {
	$html .= '<li>Surface treatment: ' . $this->view->escape($surface['term']). '</li>';
	$this->view->headTitle(  ' > Surface treatment: '.$this->view->escape($surface['term']));
	}
	}
	}

	if(array_key_exists('class',$params)) {
	if(!is_null($params['class'])) {
	$html .= '<li>Classification term like: ' . $this->view->escape($params['class']). '</li>';
	$this->view->headTitle(  ' > Classification: '. $this->view->escape($params['class']));
	}
	}

	//Date from starts
	if(array_key_exists('from',$params)) {
	if(!is_null($params['from'])) {
	$from = $params['from'];
	$suffix="BC";
	$prefix="AD";
	if ($from < 0) {
	$date =  abs($from). ' ' .$suffix;
	} else if ($from > 0) {
	$date =  $prefix . ' ' .  abs($from);
	}
	$html .= '<li>Date from greater or equal to: ' . (int)$date. '</li>';
	$this->view->headTitle(  ' > Date from starts: '. (int)$date);
	}
	}

	//Date from ends
	if(array_key_exists('fromend',$params)) {
	if(!is_null($params['fromend'])) {
	$from = $params['fromend'];
	$suffix="BC";
	$prefix="AD";
	if ($from < 0) {
	$date =  abs($from) . ' ' . $suffix;
	} else if ($from > 0) {
	$date =  $prefix .' ' . abs($from);
	}
	$html .= '<li>Date from smaller or equal to: ' . $date. '</li>';
	$this->view->headTitle(  ' > Date from ends: ' . $date);
	}
	}


	//Date to starts
	
	//Date to ends
	
	//Year found
	if(array_key_exists('discovered',$params)) {
	if(!is_null($params['discovered'])) {
	$html .= '<li>Year of discovery where known: ' . $this->view->escape($params['discovered']) . '</li>';
	$this->view->headTitle(  ' > Discovery year: ' . $this->view->escape($params['discovered']));
	}
	}
	
	//Found by
	if(array_key_exists('finder',$params)) {
	if(!is_null($params['finder'])) {
	$finder = $params['finder'];
	$peoples = new Peoples();
	$peoples = $peoples->getName($finder);
	$this->peoples = $peoples;
	foreach($this->peoples as $people) {
	$html .= '<li>Item found by: ' . $this->view->escape($people['term']). '</li>';
	$this->view->headTitle(  ' > Finder: ' . $this->view->escape($people['term']));
	}
	}
	}
	
	//Identified by
	if(array_key_exists('idby',$params)) {
	if(!is_null($params['idby'])) {
	$finder = $params['idby'];
	$peoples = new Peoples();
	$peoples = $peoples->getName($finder);
	$this->peoples = $peoples;
	foreach($this->peoples as $people) {
	$html .= '<li>Identified by: ' . $this->view->escape($people['term']). '</li>';
	$this->view->headTitle(  ' > Identified by: ' . $this->view->escape($people['term']));
	}
	}
	}
	
	//Recorded by
	//Identified by
	if(array_key_exists('recordby',$params)) {
	if(!is_null($params['recordby'])) {
	$finder = $params['recordby'];
	$peoples = new Peoples();
	$peoples = $peoples->getName($finder);
	$this->peoples = $peoples;
	foreach($this->peoples as $people) {
	$html .= '<li>Recorded by: ' . $this->view->escape($people['term']). '</li>';
	$this->view->headTitle(  ' > Recorded by: ' . $this->view->escape($people['term']));
	}
	}
	}
	//Issuer
	if(array_key_exists('ruler',$params)) {
	if(!is_null($params['ruler'])) {
	$ruler = $params['ruler'];
	$rulers = new Rulers();
	$rulers = $rulers->getRulersName($ruler);
	$this->rulers = $rulers;
	foreach($this->rulers as $ruler){
	$html .= '<li>Coin issued by: ' . $this->view->escape($ruler['issuer']). '</li>';
	$this->view->headTitle(  ' > Coin issued by: ' .  $this->view->escape($ruler['issuer']));
	}
	}
	}

	if(array_key_exists('note',$params)) {
	if ($params['note'] == (int)1){
	$html .= '<li>Object is a find of note';
	$this->view->headTitle(  ' > Object is a find of note');
	}
	}

	if(array_key_exists('treasure',$params)) {
	if ($params['treasure'] == (int)1){
	$html .= '<li>Object is Treasure or potential Treasure';
	$this->view->headTitle(  ' > Object is Treasure');
	}
	}

	if(array_key_exists('TID',$params)) {
	if (!is_null($params['TID'])){
	$html .= '<li>Treasure case number: ' . $this->view->escape($params['TID']);
	$this->view->headTitle(  ' > Treasure case number: ' . $this->view->escape($params['TID']));
	}
	}

	if(array_key_exists('created',$params)) {
	if (!is_null($params['created'])){
	$html .= '<li>Finds entered on: '.$this->view->escape($params['created']);
	$this->view->headTitle(  ' > finds entered on: ' . $this->view->escape($params['created']));
	}
	}

	if(array_key_exists('createdBefore',$params)) {
	if (!is_null($params['createdBefore'])){
	$html .= '<li>Finds entered on or before: '
	. $this->view->niceShortDate($this->view->escape($params['createdBefore'])) . '</li>';
	$this->view->headTitle(  ' > finds entered on or before: ' 
	. $this->view->niceShortDate($this->view->escape($params['createdAfter'])));
	}
	}

	if(array_key_exists('createdAfter',$params)) {
	if (!is_null($params['createdAfter'])){
	$html .= '<li>Finds entered on or after: ' 
	. $this->view->niceShortDate($this->view->escape($params['createdAfter'])) .'</li>';
	$this->view->headTitle(  ' > finds entered on or after: '
	. $this->view->niceShortDate($this->view->escape($params['createdAfter'])));
	}
	}

	if(array_key_exists('hoard',$params)) {
	if ((int)$params['hoard'] == (int)1){
	$html .= '<li>Object is part of a hoard.</li>';
	$this->view->headTitle(  ' > Object is part of a hoard.');
	}
	}

	if(array_key_exists('hID',$params)) {
	if((int)$params['hID']) {
	$hID = $params['hID'];
	$hIDs = new Hoards();
	$hIDsList = $hIDs->getHoardDetails((int)$hID);
	$this->hids = $hIDsList;
	foreach($this->hids as $hid) {
	$html .= '<li>Part of the ' . $this->view->escape($hid['term']) 
                . ' hoard.</li>';
	$this->view->headTitle(  ' > Part of the '.  $this->view->escape(
                $hid['term']) . ' hoard.');
	}
	}
	}
	
	if(array_key_exists('otherref',$params)) {
	if (!is_null($params['otherref'])){
	$html .= '<li>Other reference: '.$this->view->escape($params['otherref']);
	$this->view->headTitle(  ' > Other reference: ' 
                . $this->view->escape($params['otherref']));
	}
	}

	//Workflow
	if(array_key_exists('workflow',$params)) {
	if(!is_null($params['workflow'])) {
	$stage = $params['workflow'];
	$stages = new Workflows();
	$stages = $stages->getStageName($stage);
	$this->stages = $stages;
	foreach($this->stages as $stage){
	$html .= '<li>Workflow stage: ' . $this->view->escape($stage['workflowstage']). '</li>';
	$this->view->headTitle(  ' > Workflow stage: '.  $this->view->escape($stage['workflowstage']));
	}
	}
	}

	if(array_key_exists('manufacture',$params)) {
	if(!is_null($params['manufacture'])) {
	$manufacture = $params['manufacture'];
	$manufactures = new Manufactures();
	$manufactures = $manufactures->getManufactureDetails((int)$manufacture);
	$this->manufactures = $manufactures;
	foreach($this->manufactures as $man) {
	$html .= '<li>Manufacture type: ' . $this->view->escape($man['term']). '</li>';
	$this->view->headTitle(  ' > Manufacture type: '.  $this->view->escape($man['term']));
	}
	}
	}
	
	if(array_key_exists('decoration',$params)) {
	if(!is_null($params['decoration'])) {
	$decoration = $params['decoration'];
	$decorations = new Decmethods();
	$decorations = $decorations->getDecorationDetails((int)$decoration);
	$this->decorations = $decorations;
	foreach($this->decorations as $dec){
	$html .= '<li>Decoration type: ' . $this->view->escape($dec['term']). '</li>';
	$this->view->headTitle(  ' > Decoration type: '.  $this->view->escape($dec['term']));
	}
	}
	}

	//Mint
	if(array_key_exists('mint',$params)) {
	if(!is_null($params['mint'])) {
	$id = $params['mint'];
	$mints = new Mints();
	$mints = $mints->getMintName($id);
	$this->mints = $mints;
	foreach($this->mints as $mint) {
	$html .= '<li>Mint issuing coins: ' . $this->view->escape($mint['mint_name']).' ('.$mint['term']. ')</li>';
	$this->view->headTitle(  ' > Mint issuing coins: '.  $this->view->escape($mint['mint_name']));
	}
	}
	}
	
	//Category
	if(array_key_exists('category',$params)) {
	if(!is_null($params['category'])) {
	$id = $params['category'];
	$cats = new CategoriesCoins();
	$cats = $cats->getCategory($id);
	$this->cats = $cats;
	foreach($this->cats as $cat){
	$html .= '<li>Coin category: ' . $this->view->escape($cat['term']).'</li>';
	$this->view->headTitle(  ' > Coin category: '.  $this->view->escape($cat['term']));
	}
	}
	}




	$html .= '</ul>';
	}
	return $html;
	}

	}