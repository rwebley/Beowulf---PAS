<?php
/** A view helper for generating a list of the parameters and search results
 * @category Pas
 * @package  Pas_View_Helper
 * @author   Daniel Pett
 * @version  1
 * @since    19/12/2011
 * @license  GNU Public
 * @todo     Clean up the code and make it write as one html block remove echoes.
 */
class Pas_View_Helper_SearchParamsUsers 
	extends Zend_View_Helper_Abstract {

	public function SearchParamsUsers($params = NULL)
	{
	unset($params['submit']);
	unset($params['action']);
	unset($params['controller']);
	unset($params['module']);
	if(!is_null($params)) {	
	echo 'You searched for: '; 
	
	//Objecttype
	if(array_key_exists('objecttype',$params)) {
	if(!is_null($params['objecttype'])) {
	echo 'Object type: '. $this->view->escape($params['objecttype']) . ' &raquo; ';
	}
	}
	//Broadperiod
	if(array_key_exists('broadperiod',$params)) {
	if(!is_null($params['broadperiod'])) {
	echo 'Broadperiod: '. $this->view->escape($params['broadperiod']) . ' &raquo; ';
	}
	}

	//VA type
	if(array_key_exists('vaType',$params)) {
	if(!is_null($params['vaType'])) {
	$va = $params['vaType'];
	echo 'Van Arsdell Type: '.$va;
	}
	}


	if(array_key_exists('woeid',$params)) {
	if(!is_null($params['woeid'])) {
	$woeid = $params['woeid'];
	echo 'Where on Earth ID: '.$woeid;
	}
	}

	if(array_key_exists('recorderID',$params)) {
	if(!is_null($params['recorderID'])) {
	$rid = $params['recorderID'];
	$peoples = new Peoples();
	$people = $peoples->fetchRow($peoples->select()->where('secuid = ?', $rid));
	echo 'Recorded by: ' . $people->fullname;
	}
	}
	//County
	if(array_key_exists('county',$params)) {
	if(!is_null($params['county'])) {
	echo 'County: '. $this->view->escape($params['county']) . ' &raquo; ';
	}
	}
	//Tribe for IA coins
	if(array_key_exists('tribe',$params)) {
	if(!is_null($params['tribe'])) {
	$tribe = $params['tribe'];
	$tribes = new Tribes();
	$tribe = $tribes->fetchRow($tribes->select()->where('id = ?', (int)$tribe));
	echo 'Iron Age Tribe: ' . $tribe->tribe;
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
	echo 'Region: '. $this->view->escape($region['region']).' &raquo; ';
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
    echo 'Primary material: ' . $this->view->escape($material['term']) . ' &raquo; ';
    }
    }
    }


    if(array_key_exists('parish',$params)) {
    if(!is_null($params['parish'])) {
    echo 'Parish: '. $this->view->escape($params['parish']) . ' &raquo; ';
    }
    }

    if(array_key_exists('district',$params)) {
    if(!is_null($params['district'])) {
    echo 'District: '. $this->view->escape($params['district']) . ' &raquo; ';
    }
    }

    if(array_key_exists('denomination',$params)) {
    $denomname = $params['denomination'];
    $denoms = new Denominations();
    $denoms = $denoms->getDenomName($denomname);
    $this->denoms = $denoms;
    foreach($this->denoms as $denom)
    {
    echo 'Denomination type: ' . $this->view->escape($denom['denomination']). ' &raquo; ';
    }
    }

    if(array_key_exists('description',$params)) {
    if(!is_null($params['description'])) {
    echo 'Description contained: '. $this->view->escape($params['description']) . ' &raquo; ';
    }
    }

    if(array_key_exists('fourFigure',$params)) {
    if(!is_null($params['fourFigure'])) {
    echo 'Four figure grid reference: '. $this->view->escape($params['fourFigure']) . ' &raquo; ';
    }
    }

    if(array_key_exists('old_findID',$params)) {
    if(!is_null($params['old_findID'])) {
    echo 'Find reference number: '. $this->view->escape($params['old_findID']) . ' &raquo; ';
    }
    }


    if(array_key_exists('fromsubperiod',$params)) {

    if (!is_null($params['fromsubperiod'])){

    $sub = $params['fromsubperiod'];
    if($sub == 1)
    { echo 'Subperiod: Early'. ' &raquo; ';
    }
    else if ($sub == 2)
    {echo 'Subperiod: Middle'. ' &raquo; ';
    }
    else if ($sub == 3)
    {echo 'Subperiod: Late'. ' &raquo; ';
    }
    }
    }

    if(array_key_exists('tosubperiod',$params)) {

    if (!is_null($params['tosubperiod'])){

    $sub = $params['tosubperiod'];
    if($sub == 1)
    { echo 'Subperiod: Early'. ' &raquo; ';
    }
    else if ($sub == 2)
    {echo 'Subperiod: Middle'. ' &raquo; ';
    }
    else if ($sub == 3)
    {echo 'Subperiod: Late'. ' &raquo; ';
    }
    }
    }



    if(array_key_exists('periodfrom',$params)) {
    if(!is_null($params['periodfrom'])) {
    $period = $params['periodfrom'];
    $periods = new Periods();
    $periods = $periods->getPeriodName($period);
    $this->periods = $periods;
    foreach($this->periods as $period)
    {
    echo 'Period from: ' . $this->view->escape($period['term']). ' &raquo; ';

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
    foreach($this->periods as $period)
    {
    echo 'Period to: ' . $this->view->escape($period['term']). ' &raquo; ';

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
    foreach($this->surfaces as $surface)
    {
    echo 'Surface treatment: ' . $this->view->escape($surface['term']). ' &raquo; ';
    }
    }
    }

    if(array_key_exists('class',$params)) {
    if(!is_null($params['class'])) {
    echo 'Classification term like: ' . $this->view->escape($params['class']). ' &raquo; ';

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
            }
                     else if ($from > 0) {
            $date =  $prefix.' '. abs($from);
                     }
    echo 'Date from greater or equal to: ' . (int)$date. ' &raquo; ';

    }
    }

    //Date from ends
    if(array_key_exists('fromend',$params)) {
    if(!is_null($params['fromend'])) {
    $from = $params['fromend'];
    $suffix="BC";
    $prefix="AD";
    if ($from < 0) {
    $date =  abs($from). ' ' .$suffix;
            }
                     else if ($from > 0) {
            $date =  $prefix.' '. abs($from);
                     }
    echo 'Date from smaller or equal to: ' . $date. ' &raquo; ';

    }
    }


    //Date to starts

    //Date to ends

    //Year found
    if(array_key_exists('discovered',$params)) {
    if(!is_null($params['discovered'])) {
    echo 'Year of discovery where known: ' . $this->view->escape($params['discovered']). ' &raquo; ';

    }
    }
    //Found by
    if(array_key_exists('finder',$params)) {
    if(!is_null($params['finder'])) {

    $finder = $params['finder'];
    $peoples = new Peoples();
    $peoples = $peoples->getName($finder);

    $this->peoples = $peoples;
    foreach($this->peoples as $people)
    {
    echo 'Item found by: ' . $this->view->escape($people['term']). ' &raquo; ';

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
    foreach($this->peoples as $people)
    {
    echo 'Identified by: ' . $this->view->escape($people['term']). ' &raquo; ';

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
    echo 'Recorded by: ' . $this->view->escape($people['term']) . ' &raquo; ';
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
    echo 'Coin issued by: ' . $this->view->escape($ruler['issuer']) . ' &raquo; ';
    }
    }
    }

    if(array_key_exists('note',$params)) {
    if ($params['note'] == (int)1){
    echo 'Object is a find of note';
    }
    }

    if(array_key_exists('treasure',$params)) {
    if ($params['treasure'] == (int)1){
    echo 'Object is Treasure or potential Treasure';
    }
    }

    if(array_key_exists('TID',$params)) {
    if (!is_null($params['TID'])){
    echo 'Treasure case number: '.$this->view->escape($params['TID']);
    }
    }

    if(array_key_exists('created',$params)) {
    if (!is_null($params['created'])){
    echo 'Finds entered on: '.$this->view->escape($params['created']);
    }
    }
    if(array_key_exists('createdBefore',$params)) {
    if (!is_null($params['createdBefore'])){
    echo 'Finds entered on or before: '.$this->view->niceShortDate($this->view->escape($params['createdBefore'])).' &raquo; ';
    }
    }

    if(array_key_exists('createdAfter',$params)) {
    if (!is_null($params['createdAfter'])){
    echo 'Finds entered on or after: '.$this->view->niceShortDate($this->view->escape($params['createdAfter'])).' &raquo; ';
    }
    }

    if(array_key_exists('hoard',$params)) {
    if ((int)$params['hoard'] == (int)1){
    echo 'Object is part of a hoard.'. ' &raquo; ';
    }
    }

    if(array_key_exists('hID',$params)) {
    if((int)$params['hID']) {
    $hID = $params['hID'];
    $hIDs = new Hoards();
    $hIDsList = $hIDs->getHoardDetails((int)$hID);
    $this->hids = $hIDsList;
    foreach($this->hids as $hid)
    {
    echo 'Part of the ' . $this->view->escape($hid['term']). ' hoard.'. ' &raquo; ';
    }
    }
    }
    if(array_key_exists('otherref',$params)) {
    if (!is_null($params['otherref'])){
    echo 'Other reference: '.$this->view->escape($params['otherref']);
    }
    }

    //Workflow
    if(array_key_exists('workflow',$params)) {
    if(!is_null($params['workflow'])) {

    $stage = $params['workflow'];

    $stages = new Workflows();
    $stages = $stages->getStageName($stage);

    $this->stages = $stages;
    foreach($this->stages as $stage)
    {
    echo 'Workflow stage: ' . $this->view->escape($stage['workflowstage']). ' &raquo; ';

    }

    }
    }

    if(array_key_exists('manufacture',$params)) {
    if(!is_null($params['manufacture'])) {
    $manufacture = $params['manufacture'];
    $manufactures = new Manufactures();
    $manufactures = $manufactures->getManufactureDetails((int)$manufacture);
    $this->manufactures = $manufactures;
    foreach($this->manufactures as $man)
    {
    echo 'Manufacture type: ' . $this->view->escape($man['term']). ' &raquo; ';
    }
    }
    }
    if(array_key_exists('decoration',$params)) {
    if(!is_null($params['decoration'])) {
    $decoration = $params['decoration'];
    $decorations = new Decmethods();
    $decorations = $decorations->getDecorationDetails((int)$decoration);
    $this->decorations = $decorations;
    foreach($this->decorations as $dec)
    {
    echo 'Decoration type: ' . $this->view->escape($dec['term']). ' &raquo; ';
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
    foreach($this->mints as $mint)
    {
    echo 'Mint issuing coins: ' . $this->view->escape($mint['mint_name']).' ('.$mint['term']. ')'. ' &raquo; ';
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
    foreach($this->cats as $cat)
    {
    echo 'Coin category: ' . $this->view->escape($cat['term']).' &raquo; ';
    }

    }
    }

    if(array_key_exists('reeceID',$params)) {
    if(!is_null($params['reeceID'])) {
    $id = $params['reeceID'];

    $reeces = new Reeces();
    $rs = $reeces->getReecePeriodDetail($id);

    foreach($rs as $r)
    {
    echo 'Reece Period: ' . $this->view->escape($r['period_name']).' '.$r['date_range'].' &raquo; ';

    }

    }
    }


    //Workflow
    if(array_key_exists('createdby',$params)) {
    if(!is_null($params['createdby'])) {

    $createdby = $params['createdby'];

    $users = new Users();
    $names = $users->getCreatedBy($createdby);

    $this->names = $names;
    foreach($this->names as $name)
    {
    echo 'Record created by: <a href="'.$this->view->baseUrl() 
            . '/contacts/staff/profile/id/'.$name['i'].'" title="View profile for '.$name['fullname'].'">' . $name['fullname']. '</a>'. ' &raquo; ';

    }

    }
    }


            //End of function
    echo '</ul>';
            }
    }

}