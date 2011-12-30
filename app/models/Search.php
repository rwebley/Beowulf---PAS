<?php
/** Data model for accessing data for array based searching
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		this is to be replaced by SOLR and sucks the big dong
*/
class Search extends Pas_Db_Table_Abstract {

	protected $_name = 'finds';

	protected $_primary = 'id';

	protected $_higherlevel = array('admin','flos','fa');

	protected $_research = array('hero','research');

	protected $_restricted = array('public','member');

	protected $_edittest = array('flos','member');

	
	
	/** Get user's role for checking permissions
	* @return string $role
	*/
	public function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}
	
	/** Get user's institution
	* @return string $institution
	*/
	protected function getInstitution() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$institution = $user->institution;
	return $institution;
	}
	}

	/** Get search results
	* @param array $params
	* @param string $role 
	* @return Array $options
	*/

	public function getSearchResultsAdvanced($params,$role) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('id','old_findID','uniqueID' => 'secuid', 'objecttype',
				'classification','subclass','length','height','width','thickness','diameter',
				'quantity','other_ref','treasureID','broadperiod', 'numdate1','numdate2','culture',
				'description','notes','reuse','created' =>'finds.created','updated',
				'treasureID','secwfstage','secuid','findofnote', 'objecttypecert','datefound1',
				'datefound2','inscription','secuid','disccircum', 'museumAccession' => 'musaccno',
				'subsequentAction' => 'subs_action','objectCertainty' => 'objecttypecert',
				'dateFromCertainty' => 'numdate1qual','dateToCertainty' => 'numdate2qual',
				'dateFoundFromCertainty' => 'datefound1qual', 'dateFoundToCertainty' => 'datefound2qual',
				'subPeriodFrom' => 'objdate1subperiod','subPeriodTo' => 'objdate2subperiod'))
		->joinLeft('coins','finds.secuid = coins.findID', array('obverse_description','obverse_inscription',
		'reverse_description','reverse_inscription','denomination','degree_of_wear',
		'allen_type','va_type','mack' => 'mack_type','reeceID','die' => 'die_axis_measurement',
		'wearID'=> 'degree_of_wear','moneyer','revtypeID','categoryID','typeID','tribeID' => 'tribe',
		'status','rulerQualifier' => 'ruler_qualifier','denominationQualifier' => 'denomination_qualifier',
		'mintQualifier' => 'mint_qualifier','dieAxisCertainty' => 'die_axis_certainty','initialMark' => 'initial_mark',
		'reverseMintMark' => 'reverse_mintmark','statusQualifier' => 'status_qualifier'))	
		->joinLeft('findofnotereasons','finds.findofnotereason = findofnotereasons.id', array('reason' => 'term'))
		->joinLeft('users','users.id = finds.createdBy', array('username','fullname','institution'))
		->joinLeft(array('users2' => 'users'),'users2.id = finds.updatedBy', array('usernameUpdate' => 'username',
		'fullnameUpdate' => 'fullname'))
		->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id', array('primaryMaterial' =>'term'))
		->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id', array('secondaryMaterial' => 'term'))
		->joinLeft('decmethods','finds.decmethod = decmethods.id', array('decoration' => 'term'))
		->joinLeft('decstyles','finds.decstyle = decstyles.id', array('style' => 'term'))
		->joinLeft('manufactures','finds.manmethod = manufactures.id', array('manufacture' => 'term'))
		->joinLeft('surftreatments','finds.surftreat = surftreatments.id', array('surfaceTreatment' => 'term'))
		->joinLeft('completeness','finds.completeness = completeness.id', array('completeness' => 'term'))
		->joinLeft('preservations','finds.preservation = preservations.id', array('preservation' => 'term'))
		->joinLeft('certaintytypes','certaintytypes.id = finds.objecttypecert', array('cert' => 'term'))
		->joinLeft('periods','finds.objdate1period = periods.id', array('periodFrom' => 'term'))
		->joinLeft(array('p' => 'periods'),'finds.objdate2period = p.id', array('periodTo' => 'term'))
		->joinLeft('cultures','finds.culture = cultures.id', array('culture' => 'term'))
		->joinLeft('discmethods','discmethods.id = finds.discmethod', array('discmethod' => 'method'))
		->joinLeft('ironagetribes','coins.tribe = ironagetribes.id', array('tribe'))
		->joinLeft('geographyironage','geographyironage.id = coins.geographyID', array('region','area'))
		->joinLeft('denominations','denominations.id = coins.denomination', array('denomination'))
		->joinLeft('rulers','rulers.id = coins.ruler_id', array('ruler1' => 'issuer'))
		->joinLeft(array('rulers2' => 'rulers'),'rulers2.id = coins.ruler2_id', array('ruler2' => 'issuer'))
		->joinLeft('reeceperiods','coins.reeceID = reeceperiods.id', array('period_name','date_range'))
		->joinLeft('mints','mints.id = coins.mint_ID', array('mint_name'))
		->joinLeft('weartypes','coins.degree_of_wear = weartypes.id', array('wear' => 'term'))
		->joinLeft('medievalcategories','medievalcategories.id = coins.categoryID', array('category'))
		->joinLeft('medievaltypes','medievaltypes.id = coins.typeID', array('type'))
		->joinLeft('moneyers','moneyers.id = coins.moneyer', array('moneyer' => 'name'))
		->joinLeft('revtypes','coins.revtypeID = revtypes.id', array('reverseType' => 'type'))
		->joinLeft('statuses','coins.status = statuses.id', array('status' => 'term'))
		->limit(10000);
	$conSwitch = Zend_Controller_Action_HelperBroker::getExistingHelper('contextSwitch');
	$context = $conSwitch->getCurrentContext();
	//Set Up access to finds by workflow
	if(in_array($role,$this->_restricted)){
	if($context == 'kml') {
	$select->joinLeft('findspots','finds.secuid = findspots.findID', array('county',
	'district','knownas','lat' => 'declat','lon' => 'declong','fourFigure'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array())
		->joinLeft('slides','slides.secuid = finds_images.image_id', array( 'i' => 'imageID', 'f' => 'filename'))
		->joinLeft('people','finds.finderID = people.secuid', array());
	} elseif(in_array($role,$this->_research)) {
	$select->joinLeft('findspots','finds.secuid = findspots.findID', array('county','parish','district', 'knownas',
	'gridref', 'fourFigure','easting','northing','map25k','map10k', 'accuracy','gridlen','address','postcode',
	'findspotdescription' => 'description','lat' => 'declat','lon' => 'declong'))
		->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc', array('source' => 'term'))
		->where('finds.secwfstage > ?', (int)2);
	} else {
	$select->joinLeft('findspots','finds.secuid = findspots.findID', array('county','district', 'knownas'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array())
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID','f' => 'filename'));
	}
	$select->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc', array('gridRefSource' => 'term'))
		->where('finds.secwfstage > ?', (int)2);
	} else {
	$select	->joinLeft('people','finds.finderID = people.secuid', array( 'finder' => 'CONCAT(people.title," ",people.forename," ",people.surname)'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid', array('identifier' => 'CONCAT(ident1.title," ",ident1.forename," ",ident1.surname)'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid', array('secondaryIdentifier' => 'CONCAT(ident2.title," ",ident2.forename," ",ident2.surname)'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid', array('recorder' => 'CONCAT(record.title," ",record.forename," ",record.surname)'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county','parish','district','gridref','fourFigure','easting','northing','map25k','map10k',
		'gridlength' => 'gridlen','accuracy', 'address','postcode','findspotdescription' => 'description','lat' => 'declat','lon' => 'declong'))
		->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc', array('source' => 'term'));
	}
	if(isset($params['regionID']) && ($params['regionID'] != "")) {
	$select->joinLeft('regions','findspots.regionID = regions.id',array('region'));
	}
	if(isset($params['typeID']) && ($params['typeID'] != "")) {
	$typeID = $params['typeID'];
	$select->where('coins.typeID = ?', $typeID);
	}
	if(isset($params['activity']) && !s_null($params['activity']) ){
	$activity = $params['activity'];	
	$select->where('people.primary_activity = ?',$activity);	
	}
	if(isset($params['complete']) && ($params['complete'] != ""))  {
	$complete = $params['complete'];
	$select->where('finds.completeness = ?', $complete);
	}

	## Find specific query formation	
	//Old_findID
	if(isset($params['old_findID']) && ($params['old_findID'] != "")) {
	$old_findID = $params['old_findID'];
	$select->where('old_findID = ?', $old_findID);
	}
	//Objecttype
	if(isset($params['objecttype']) && ($params['objecttype'] != "")) {
	$objecttype = $params['objecttype'];
	$select->where('objecttype = ?', $objecttype);
	}
	//wear for coins
	if(isset($params['wear']) && ($params['wear'] != ""))  {
	$wear = $params['wear'];
	$select->where('degree_of_wear = ?', $wear);
	}
	//Description
	if(isset($params['description']) && ($params['description'] != "")) {
	$description = $params['description'];
	$select->where('finds.description LIKE ?', '%'.$description.'%');
	}
	//Notes
	if(isset($params['notes']) && ($params['notes'] != ""))  {
	$notes = $params['notes'];
	$select->where('finds.notes LIKE ?', '%'.$notes.'%');
	}
	//Broadperiod
	if(isset($params['broadperiod']) && ($params['broadperiod'] != "")) {
	$broadperiod = $params['broadperiod'];
	$select->where('broadperiod = ?', (string)$broadperiod);
	}
	//Period From date
	if(isset($params['periodfrom']) && ($params['periodfrom'] != "")) {
	$periodfrom = $params['periodfrom'];
	$select->where('finds.objdate1period = ?', (int)$periodfrom);
	}
	//culture
	if(isset($params['culture']) && ($params['culture'] != "")) {
	$culture = $params['culture'];
	$select->where('finds.culture = ?', (int)$culture);
	}
	if(isset($params['woeid']) && ($params['woeid'] != '')) {
	$select	->where('findspots.woeid = ?',$params['woeid']);
	}
	//From date
	if(isset($params['from']) && ($params['from'] != "")) {
	$from = $params['from'];
	$select->where('finds.numdate1 >= ?', $from)
	->where('finds.numdate1 IS NOT NULL');
	}
	if(isset($params['fromend']) && ($params['fromend'] != "")) {
	$fromend = $params['fromend'];
	$select->where('finds.numdate1 <= ?', $fromend)
	->where('finds.numdate1 IS NOT NULL');
	}
	//Early mid late
	if(isset($params['tosubperiod']) && ($params['tosubperiod'] != "")) {
	$tosubperiod = $params['tosubperiod'];
	$select->where('finds.objdate2subperiod = ?', $tosubperiod);
	}
	//Period to date
	if(isset($params['periodto']) && ($params['periodto'] != "")) {
	$periodto = $params['periodto'];
	$select->where('finds.objdate2period = ?', $periodto);
	}
	//Early Mid/late
	if(isset($params['fromsubperiod']) && ($params['fromsubperiod'] != "")) {
	$fromsubperiod = $params['fromsubperiod'];
	$select->where('finds.objdate1subperiod = ?', $fromsubperiod);
	}
	//Discmethod
	if(isset($params['discmethod']) && ($params['discmethod'] != "")) {
	$discmethod = $params['discmethod'];
	$select->where('finds.discmethod = ?', $discmethod);
	}
	//To date
	if(isset($params['to']) && ($params['to'] != "")) {
	$to = $params['to'];
	$select->where('finds.numdate2 <= ?', $to);
	}
	//Primary material
	if(isset($params['material']) && ($params['material'] != ""))  {
	$material = $params['material'];
	$select->where('finds.material1 = ?', $material);
	}
	//Created by
	if(isset($params['createdby']) && ($params['createdby'] != ""))  {
	$createdby = $params['createdby'];
	$select->where('finds.createdBy = ?', $createdby);
	}
	//Finder
	if(isset($params['finderID']) && ($params['finderID'] != ""))  {
	$finder = $params['finderID'];
	$select->where('finds.finderID = ?', $finder);
	}
	//Identifier
	if(isset($params['idby']) && ($params['idby'] != "")) {
	$idby = $params['idby'];
	$select->where('finds.identifier1ID = ?', $idby);
	}
	if(isset($params['idby2']) && ($params['idby2'] != "")) {
	$idby2 = $params['idby2'];
	$select->where('finds.identifier2ID = ?', $idby2);
	}
	//Recorded by
	if(isset($params['recorderID']) && ($params['recorderID'] != ""))  {
	$recordby = $params['recorderID'];
	$select->where('finds.recorderID = ?', $recordby);
	}
	//Created on exactly
	if(isset($params['created']) && ($params['created'] != ""))  {
	$created = $params['created'];
	$select->where('DATE(finds.created) = ?', $created);
	}
	
	//Created on
	if(isset($params['createdAfter']) && ($params['createdAfter'] != ""))  {
	$createdAfter = $params['createdAfter'];
	$select->where('finds.created >=?', $createdAfter . ' 00:00:00');
	}
	//Created before
	if(isset($params['createdBefore']) && ($params['createdBefore'] != ""))  {
	$createdBefore = $params['createdBefore'];
	$select->where('finds.created <= ?', $createdBefore);
	}
	//Workflow
	if(isset($params['workflow']) && ($params['workflow'] != ""))  {
	$workflow = $params['workflow'];
	$select->where('finds.secwfstage = ?', $workflow);
	}
	//Decoration method
	if(isset($params['decoration']) && ($params['decoration'] != "")) {
	$decoration = $params['decoration'];
	$select->where('finds.decmethod = ?', $decoration);
	}
	//Decoration style
	if(isset($params['decstyle']) && ($params['decstyle'] != ""))  {
	$decstyle = $params['decstyle'];
	$select->where('finds.decstyle = ?', $decstyle);
	}
	//Manufacture method
	if(isset($params['manufacture']) && ($params['manufacture'] != ""))  {
	$manufacture = $params['manufacture'];
	$select->where('finds.manmethod = ?', $manufacture);
	}
	//Surface treatment
	if(isset($params['surface']) && ($params['surface'] != ""))  {
	$surface = $params['surface'];
	$select->where('finds.surftreat = ?', $surface);
	}
	//Classification
	if(isset($params['class']) && ($params['class'] != "")) {
	$class = $params['class'];
	$select->where('finds.classification LIKE ?', '%'.$class.'%');
	}
	//Subclassification
	if(isset($params['subclass']) && ($params['subclass'] != ""))  {
	$subclass = $params['subclass'];
	$select->where('finds.subclass LIKE ?', '%'.$subclass.'%');
	}
	//Treasure
	if(isset($params['treasure']) && ($params['treasure'] != ""))  {
	$treasure = $params['treasure'];
	$select->where('finds.treasure = ?', $treasure);
	}
	//Treasure number
	if(isset($params['TID']) && ($params['TID'] != ""))  {
	$treasureID = $params['TID'];
	$select->where('finds.treasureID = ?', $treasureID);
	}
	//Hoard
	if(isset($params['hoard']) && ($params['hoard'] != "")) {
	$hoard = $params['hoard'];
	$select->where('finds.hoard = ?', $hoard);
	}
	//Hoard name
	if(isset($params['hID']) && ($params['hID'] != ""))  {
	$hoard = $params['hID'];
	$select->where('finds.hoardID = ?', $hoard);
	}
	//Rally
	if(isset($params['rally']) && ($params['rally'] != ""))  {
	$rally = $params['rally'];
	$select->where('finds.rally = ?', $rally);
	}
	//Rally name
	if(isset($params['rallyID']) && ($params['rallyID'] != "")) {
	$rallyID = $params['rallyID'];
	$select->joinLeft('rallies','finds.rallyID = rallies.id',array('rally_name'))
	->where('finds.rallyID = ?', $rallyID);
	}
	//find of note
	if(isset($params['note']) && ($params['note'] != ""))  {
	$note = $params['note'];
	$select->where('finds.findofnote = ?', $note);
	}
	//find of note reason
	if(isset($params['reason']) && ($params['reason'] != ""))  {
	$reason = $params['reason'];
	$select
	->where('finds.findofnotereason = ?', $reason);
	}
	//Other reference
	if(isset($params['otherref']) && ($params['otherref'] != ""))  {
	$otherref = $params['otherref'];
	$select->where('finds.other_ref = ?', $otherref);
	}
	##Coin specific query formation
	//Primary ruler
	if(isset($params['ruler']) && ($params['ruler'] != "")) 
	{
	$ruler = $params['ruler'];
	$select
	->where('coins.ruler_id = ?', $ruler);
	}
	//Secondary ruler
	if(isset($params['ruler2']) && ($params['ruler2'] != "")) 
	{
	$ruler2 = $params['ruler2'];
	$select
	->where('coins.ruler2_id = ?', $ruler2);
	}
	//Denomination
	if(isset($params['denomination']) && ($params['denomination'] != "")) 
	{
	$denomname = $params['denomination'];
	$select->where('coins.denomination = ?', $denomname);
	}
	//Mint
	if(isset($params['mint']) && ($params['mint'] != "")) 
	{
	$mint = $params['mint'];
	$select->where('coins.mint_id = ?', $mint);
	}
	//Die axis
	if(isset($params['axis']) && ($params['axis'] != "")) 
	{
	$axis = $params['axis'];
	$select->where('coins.die_axis_measurement = ?', $axis);
	}
	//Moneyer
	if(isset($params['moneyer']) && ($params['moneyer'] != "")) 
	{
	$moneyer = $params['moneyer'];
	$select->where('coins.moneyer = ?', $moneyer);
	}
	//Obverse inscription
	if(isset($params['obinsc']) && ($params['obinsc'] != "")) 
	{
	$obinsc = $params['obinsc'];
	$select->where('coins.obverse_inscription LIKE ?', '%'.$obinsc.'%');
	}
	//Obverse description
	if(isset($params['obdesc']) && ($params['obdesc'] != "")) 
	{
	$obdesc = $params['obdesc'];
	$select->where('coins.obverse_description LIKE ?', '%'.$obdesc.'%');
	}
	//Reverse inscription
	if(isset($params['revinsc']) && ($params['revinsc'] != "")) 
	{
	$revinsc = $params['revinsc'];
	$select->where('coins.reverse_inscription LIKE ?', '%'.$revinsc.'%');
	}
	//Reverse description
	if(isset($params['revdesc']) && ($params['revdesc'] != "")) 
	{
	$revdesc = $params['revdesc'];
	$select->where('coins.reverse_description LIKE ?', '%'.$revdesc.'%');
	}
	##Iron age specific
	//Mack type
	if(isset($params['mack']) && ($params['mack'] != "")) 
	{
	$mack = $params['mack'];
	$select->where('coins.mack_type = ?', $mack);
	}
	//Allen type
	if(isset($params['allen']) && ($params['allen'] != "")) 
	{
	$allen = $params['allen'];
	$select->where('coins.allen_type = ?', $allen);
	}
	//Rudd type
	if(isset($params['rudd']) && ($params['rudd'] != "")) 
	{
	$rudd = $params['rudd'];
	$select->where('coins.rudd_type = ?', $rudd);
	}
	//Van Arsdell type
	if(isset($params['va']) && ($params['va'] != "")) 
	{
	$va = $params['va'];
	$select->where('coins.va_type = ?', $va);
	}
	//Geographical region
	if(isset($params['geoIA']) && ($params['geoIA'] != "")) 
	{
	$geography = $params['geoIA'];
	$select->where('coins.geographyID = ?', $geography);
	}
	//Tribe
	if(isset($params['tribe']) && ($params['tribe'] != "")) 
	{
	$tribe = $params['tribe'];
	$select->where('coins.tribe = ?', $tribe);
	}
	#####
	##Roman specific
	#####
	//ReeceID
	if(isset($params['reeceID']) && ($params['reeceID'] != "")) 
	{
	$reeceID = $params['reeceID'];
	$select->where('coins.reeceID = ?', $reeceID);
	}
	//Reverse type
	if(isset($params['reverse']) && ($params['reverse'] != "")) 
	{
	$reverse = $params['reverse'];
	$select->where('coins.revtypeID = ?', $reverse);
	}
	####
	##Medieval specific
	####
	//Medieval type
	if(isset($params['medtype']) && ($params['medtype'] != "")) 
	{
	$typeID = $params['medtype'];
	$select->where('coins.typeID = ?', $typeID);
	}
	//Medieval category
	if(isset($params['category']) && ($params['category'] != "")) 
	{
	$categoryID = $params['category'];
	$select->where('coins.categoryID = ?', $categoryID);
	}
	####
	##Greek and roman prov specific
	####
	//Greek state ID
	if(isset($params['greekID']) && ($params['greekID'] != "")) 
	{
	$greekstateID = $params['greekID'];
	$select->where('coins.greekstateID = ?', $greekstateID);
	}
	##Spatial specific query formation
	//County
	if(isset($params['county']) && ($params['county'] != "")) 
	{
	$county = $params['county'];
	$select->where('findspots.county = ?', $county);
	}
	//District
	if(isset($params['district']) && ($params['district'] != "")) 
	{
	$district = $params['district'];
	$select->where('findspots.district = ?', $district);
	}
	//Parish
	if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->restricted) ) 
	{
	$parish = $params['parish'];
	$select->where('findspots.parish = ?', $parish)
	->where('findspots.knownas IS NULL');
	}
	else if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->higherlevel) )
	{
	$parish = $params['parish'];
	$select->where('findspots.parish = ?', $parish);
	}
	
	//Region
	if(isset($params['regionID']) && ($params['regionID'] != "")) 
	{
	$region = $params['regionID'];
	$select->where('findspots.regionID = ?', $region);
	}
	//Landuse
	if(isset($params['landuse']) && ($params['landuse'] != "")) 
	{
	$landuse = $params['landuse'];
	$select->where('findspots.landusecode = ?', $landuse);
	}
	//Secondary landuse
	if(isset($params['value']) && ($params['value'] != "")) 
	{
	$value = $params['value'];
	$select->where('findspots.landusevalue = ?', $value);
	}
	//Comments
	if(isset($params['fourfigure']) && ($params['fourfigure'] != "")) 
	{
	$fourfigure = $params['fourfigure'];
	$select->where('findspots.fourFigure = ?', $fourfigure);
	}
	//Known as
	if(isset($params['knownas']) && ($params['knownas'] != "")) 
	{
	$knownas = $params['knownas'];
	$select->where('findspots.knownas = ?', $knownas);
	}
	if(isset($params['preservation']) && ($params['preservation'] != "")){
	$preservation = $params['preservation'];
	$select->where('finds.preservation = ?', $preservation);	
	}
	
	//Known as
	if(isset($params['discovered']) && ($params['discovered'] != ""))  {
	$discovered = $params['discovered'];
	$select->where('finds.datefound1 >= ?',$discovered.'-01-01')
		->where('finds.datefound1 <= ?',$discovered.'-12-31')
		->where('finds.datefound1 IS NOT NULL');
	};
	return $finds = $finds->fetchAll($select);
	}

	public function getSearchResultsAdvancedPaginator($params,$role) {
	$finds = $this->getAdapter();
	$select = $finds->select()
	->from($this->_name, array( 'id', 'old_findID', 'uniqueID' => 'secuid', 'objecttype',
	'classification', 'subclass', 'length', 'height', 'width', 'thickness', 'diameter',
	'quantity', 'other_ref', 'treasureID', 'broadperiod', 'numdate1', 'numdate2',
	'culture','description','notes','reuse','created' =>'finds.created','updated',
	'treasureID','secwfstage','secuid','findofnote','objecttypecert','datefound1',
	'datefound2','inscription','secuid','disccircum','museumAccession' => 'musaccno',
	'subsequentAction' => 'subs_action','objectCertainty' => 'objecttypecert','dateFromCertainty' => 'numdate1qual',
	'dateToCertainty' => 'numdate2qual','dateFoundFromCertainty' => 'datefound1qual', 
	'dateFoundToCertainty' => 'datefound2qual','subPeriodFrom' => 'objdate1subperiod',
	'subPeriodTo' => 'objdate2subperiod'))
		->joinLeft('findofnotereasons','finds.findofnotereason = findofnotereasons.id', array('reason' => 'term'))
		->joinLeft('users','users.id = finds.createdBy',array('username','fullname','institution'))
		->joinLeft(array('users2' => 'users'),'users2.id = finds.updatedBy', array('usernameUpdate' => 'username',
		'fullnameUpdate' => 'fullname'))
		->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id', array('primaryMaterial' =>'term'))
		->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id', array('secondaryMaterial' => 'term'))
		->joinLeft('decmethods','finds.decmethod = decmethods.id', array('decoration' => 'term'))
		->joinLeft('decstyles','finds.decstyle = decstyles.id', array('style' => 'term'))
		->joinLeft('manufactures','finds.manmethod = manufactures.id', array('manufacture' => 'term'))
		->joinLeft('surftreatments','finds.surftreat = surftreatments.id',array('surfaceTreatment' => 'term'))
		->joinLeft('completeness','finds.completeness = completeness.id',array('completeness' => 'term'))
		->joinLeft('preservations','finds.preservation = preservations.id',array('preservation' => 'term'))
		->joinLeft('certaintytypes','certaintytypes.id = finds.objecttypecert',array('cert' => 'term'))
		->joinLeft('periods','finds.objdate1period = periods.id',array('periodFrom' => 'term'))
		->joinLeft(array('p' => 'periods'),'finds.objdate2period = p.id',array('periodTo' => 'term'))
		->joinLeft('cultures','finds.culture = cultures.id',array('culture' => 'term'))
		->joinLeft('discmethods','discmethods.id = finds.discmethod',array('discmethod' => 'method'))
		->joinLeft('coins','finds.secuid = coins.findID',array('id','obverse_description','obverse_inscription','reverse_description','reverse_inscription','denomination','degree_of_wear','allen_type','va_type','mack' => 'mack_type','reeceID','die' => 'die_axis_measurement','wearID'=> 'degree_of_wear','moneyer','revtypeID','categoryID','typeID','tribeID' => 'tribe','status','rulerQualifier' => 'ruler_qualifier','denominationQualifier' => 'denomination_qualifier','mintQualifier' => 'mint_qualifier','dieAxisCertainty' => 'die_axis_certainty','initialMark' => 'initial_mark','reverseMintMark' => 'reverse_mintmark','statusQualifier' => 'status_qualifier'))
		->joinLeft('ironagetribes','coins.tribe = ironagetribes.id',array('tribe'))
		->joinLeft('geographyironage','geographyironage.id = coins.geographyID',array('region','area'))
		->joinLeft('denominations','denominations.id = coins.denomination',array('denomination'))
		->joinLeft('rulers','rulers.id = coins.ruler_id',array('ruler1' => 'issuer'))
		->joinLeft(array('rulers2' =>'rulers'),'rulers2.id = coins.ruler2_id',array('ruler2' => 'issuer'))
		->joinLeft('reeceperiods','coins.reeceID = reeceperiods.id',array('period_name','date_range'))
		->joinLeft('mints','mints.id = coins.mint_ID',array('mint_name'))
		->joinLeft('weartypes','coins.degree_of_wear = weartypes.id',array('wear' => 'term'))
		->joinLeft('medievalcategories','medievalcategories.id = coins.categoryID',array('category'))
		->joinLeft('medievaltypes','medievaltypes.id = coins.typeID',array('type'))
		->joinLeft('moneyers','moneyers.id = coins.moneyer',array('moneyer' => 'name'))
		->joinLeft('revtypes','coins.revtypeID = revtypes.id',array('reverseType' => 'type'))
		->joinLeft('statuses','coins.status = statuses.id',array('status' => 'term'))
		->group('finds.id')
		->order('finds.id');
	if(isset($params['regionID']) && ($params['regionID'] != ""))  
	{
	$select->joinLeft('regions','findspots.regionID = regions.id',array('region'));
	}
	//Set Up access to finds by workflow
	if(in_array($role,$this->restricted))
	{
	$select->joinLeft('findspots','finds.secuid = findspots.findID',array('county','district','knownas',))
	->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc',array('gridRefSource' => 'term'))
	->where('finds.secwfstage > ?',2);
	} elseif(in_array($role,$this->_research)) {
	$select->joinLeft('findspots','finds.secuid = findspots.findID',array('county','parish','district','gridref','fourFigure','easting','northing','map25k','map10k','address','postcode','findspotdescription' => 'description','lat' => 'declat','lon' => 'declong'))->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc',array('source' => 'term'))
	->where('finds.secwfstage > ?',2);
	} else {
	$select	->joinLeft('people','finds.finderID = people.secuid',array('finder' => 'CONCAT(people.title," ",people.forename," ",people.surname)'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid',array('identifier' => 'CONCAT(ident1.title," ",ident1.forename," ",ident1.surname)'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid',array('secondaryIdentifier' => 'CONCAT(ident2.title," ",ident2.forename," ",ident2.surname)'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid',array('recorder' => 'CONCAT(record.title," ",record.forename," ",record.surname)'))
	->joinLeft('findspots','finds.secuid = findspots.findID',array('county','parish','district','gridref','fourFigure','easting','northing','map25k','map10k','address','postcode','findspotdescription' => 'description','lat' => 'declat','lon' => 'declong'))->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc',array('source' => 'term'));
	}
	## Find specific query formation	
	//Old_findID
	if(isset($params['old_findID']) && ($params['old_findID'] != ""))  
	{
	$old_findID = $params['old_findID'];
	$select->where('old_findID = ?', $old_findID);
	}
	//Objecttype
	if(isset($params['objecttype']) && ($params['objecttype'] != ""))  
	{
	$objecttype = $params['objecttype'];
	$select->where('objecttype = ?', $objecttype);
	}
	if(isset($params['woeid']) && ($params['woeid'] != '')) {
	$select	->where('findspots.woeid = ?',$params['woeid']);
	}
	//wear for coins
	
	if(isset($params['wear']) && ($params['wear'] != ""))  
	{
	$wear = $params['wear'];
	$select->where('degree_of_wear = ?', $wear);
	}
	//Description
	if(isset($params['description']) && ($params['description'] != ""))  
	{
	$description = $params['description'];
	$select->where('finds.description LIKE ?', '%'.$description.'%');
	}
	//Notes
	if(isset($params['notes']) && ($params['notes'] != ""))  
	{
	$notes = $params['notes'];
	$select->where('finds.notes LIKE ?', '%'.$notes.'%');
	}
	//Broadperiod
	if(isset($params['broadperiod']) && ($params['broadperiod'] != ""))  
	{
	$broadperiod = $params['broadperiod'];
	$select->where('broadperiod = ?', (string)$broadperiod);
	}
	//Period From date
	if(isset($params['periodfrom']) && ($params['periodfrom'] != ""))  
	{
	$periodfrom = $params['periodfrom'];
	$select->where('finds.objdate1period = ?', (int)$periodfrom);
	}
	//culture
	if(isset($params['culture']) && ($params['culture'] != ""))  
	{
	$culture = $params['culture'];
	$select->where('finds.culture = ?', (int)$culture);
	}
	
	//From date
	if(isset($params['from']) && ($params['from'] != ""))  
	{
	$from = $params['from'];
	$select->where('finds.numdate1 >= ?', $from)
	->where('finds.numdate1 IS NOT NULL');
	}
	if(isset($params['fromend']) && ($params['fromend'] != ""))  
	{
	$fromend = $params['fromend'];
	$select->where('finds.numdate1 <= ?', $fromend)
	->where('finds.numdate1 IS NOT NULL');
	}
	//Early mid late
	if(isset($params['tosubperiod']) && ($params['tosubperiod'] != ""))  
	{
	$tosubperiod = $params['tosubperiod'];
	$select->where('finds.objdate2subperiod = ?', $tosubperiod);
	}
	//Period to date
	if(isset($params['periodto']) && ($params['periodto'] != ""))  
	{
	$periodto = $params['periodto'];
	$select->where('finds.objdate2period = ?', $periodto);
	}
	//Early Mid/late
	if(isset($params['fromsubperiod']) && ($params['fromsubperiod'] != ""))  
	{
	$fromsubperiod = $params['fromsubperiod'];
	$select->where('finds.objdate1subperiod = ?', $fromsubperiod);
	}
	
	//Discmethod
	if(isset($params['discmethod']) && ($params['discmethod'] != ""))  
	{
	$discmethod = $params['discmethod'];
	$select->where('finds.discmethod = ?', $discmethod);
	}
	
	//To date
	if(isset($params['to']) && ($params['to'] != ""))  
	{
	$to = $params['to'];
	$select->where('finds.numdate2 <= ?', $to);
	}
	//Primary material
	if(isset($params['material']) && ($params['material'] != "")) 
	{
	$material = $params['material'];
	$select->where('finds.material1 = ?', $material);
	}
	//Created by
	if(isset($params['createdby']) && ($params['createdby'] != "")) 
	{
	$createdby = $params['createdby'];
	$select->where('finds.createdBy = ?', $createdby);
	}
	//Finder
	if(isset($params['finder']) && ($params['finder'] != "")) 
	{
	$finder = $params['finder'];
	$select->joinLeft('people','finds.finderID = people.secuid',array())
	->where('finds.finderID = ?', $finder);
	}
	//Identifier
	if(isset($params['idby']) && ($params['idby'] != "")) 
	{
	$idby = $params['idby'];
	$select->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid',array())
	->where('finds.identifier1ID = ?', $idby);
	}
	if(isset($params['idby2']) && ($params['idby2'] != "")) 
	{
	$id2by = $params['idby2'];
	$select->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid',array())
	->where('finds.identifier2ID = ?', $id2by);
	}
	//Recorded by
	if(isset($params['recorderID']) && ($params['recorderID'] != "")) 
	{
	$recordby = $params['recorderID'];
	$select->where('finds.recorderID = ?', $recordby);
	}
	//Created on exactly
	if(isset($params['created']) && ($params['created'] != "")) 
	{
	$created = $params['created'];
	$select->where('finds.created = ?', $created . ' 00:00:00');
	}
	
	//Created on
	if(isset($params['createdAfter']) && ($params['createdAfter'] != "")) 
	{
	$createdAfter = $params['createdAfter'];
	$select->where('finds.created >=?', $createdAfter . ' 00:00:00');
	}
	//Created before
	if(isset($params['createdBefore']) && ($params['createdBefore'] != "")) 
	{
	$createdBefore = $params['createdBefore'];
	$select->where('finds.created <= ?', $createdBefore);
	}
	//Workflow
	if(isset($params['workflow']) && ($params['workflow'] != "")) 
	{
	$workflow = $params['workflow'];
	$select->where('finds.secwfstage = ?', $workflow);
	}
	//Decoration method
	if(isset($params['decoration']) && ($params['decoration'] != "")) 
	{
	$decoration = $params['decoration'];
	$select->where('finds.decmethod = ?', $decoration);
	}
	//Decoration style
	if(isset($params['decstyle']) && ($params['decstyle'] != "")) 
	{
	$decstyle = $params['decstyle'];
	$select->where('finds.decstyle = ?', $decstyle);
	}
	
	//Manufacture method
	if(isset($params['manufacture']) && ($params['manufacture'] != "")) 
	{
	$manufacture = $params['manufacture'];
	$select->where('finds.manmethod = ?', $manufacture);
	}
	//Surface treatment
	if(isset($params['surface']) && ($params['surface'] != "")) 
	{
	$surface = $params['surface'];
	$select->where('finds.surftreat = ?', $surface);
	}
	//Classification
	if(isset($params['class']) && ($params['class'] != "")) 
	{
	$class = $params['class'];
	$select->where('finds.classification LIKE ?', '%'.$class.'%');
	}
	//Subclassification
	if(isset($params['subclass']) && ($params['subclass'] != "")) 
	{
	$subclass = $params['subclass'];
	$select->where('finds.subclass LIKE ?', '%'.$subclass.'%');
	}
	//Treasure
	if(isset($params['treasure']) && ($params['treasure'] != "")) 
	{
	$treasure = $params['treasure'];
	$select->where('finds.treasure = ?', $treasure);
	}
	//Treasure number
	if(isset($params['TID']) && ($params['TID'] != "")) 
	{
	$treasureID = $params['TID'];
	$select->where('finds.treasureID = ?', $treasureID);
	}
	//Hoard
	if(isset($params['hoard']) && ($params['hoard'] != "")) 
	{
	$hoard = $params['hoard'];
	$select->where('finds.hoard = ?', $hoard);
	}
	//Hoard name
	if(isset($params['hID']) && ($params['hID'] != "")) 
	{
	$hoard = $params['hID'];
	$select->where('finds.hoardID = ?', $hoard);
	}
	//Rally
	if(isset($params['rally']) && ($params['rally'] != "")) 
	{
	$rally = $params['rally'];
	$select->where('finds.rally = ?', $rally);
	}
	//Rally name
	if(isset($params['rallyID']) && ($params['rallyID'] != "")) 
	{
	$rallyID = $params['rallyID'];
	$select->joinLeft('rallies','finds.rallyID = rallies.id',array('rally_name'))
	->where('finds.rallyID = ?', $rallyID);
	}
	//find of note
	if(isset($params['note']) && ($params['note'] != "")) 
	{
	$note = $params['note'];
	$select->where('finds.findofnote = ?', $note);
	}
	//find of note reason
	if(isset($params['reason']) && ($params['reason'] != "")) 
	{
	$reason = $params['reason'];
	$select
	->where('finds.findofnotereason = ?', $reason);
	}
	//Other reference
	if(isset($params['otherref']) && ($params['otherref'] != "")) 
	{
	$otherref = $params['otherref'];
	$select->where('finds.other_ref = ?', $otherref);
	}
	##Coin specific query formation
	//Primary ruler
	if(isset($params['ruler']) && ($params['ruler'] != "")) 
	{
	$ruler = $params['ruler'];
	$select->where('coins.ruler_id = ?', $ruler);
	}
	//Secondary ruler
	if(isset($params['ruler2']) && ($params['ruler2'] != "")) 
	{
	$ruler2 = $params['ruler2'];
	$select->where('coins.ruler2_id = ?', $ruler2);
	}
	//Denomination
	if(isset($params['denomination']) && ($params['denomination'] != "")) 
	{
	$denomname = $params['denomination'];
	$select->where('coins.denomination = ?', $denomname);
	}
	//Mint
	if(isset($params['mint']) && ($params['mint'] != "")) 
	{
	$mint = $params['mint'];
	$select->where('coins.mint_id = ?', $mint);
	}
	//Die axis
	if(isset($params['axis']) && ($params['axis'] != "")) 
	{
	$axis = $params['axis'];
	$select->joinLeft('dieaxes','dieaxes.id = coins.die_axis_measurement',array('die_axis_name'))
	->where('coins.die_axis_measurement = ?', $axis);
	}
	//Moneyer
	if(isset($params['moneyer']) && ($params['moneyer'] != "")) 
	{
	$moneyer = $params['moneyer'];
	$select->where('coins.moneyer = ?', $moneyer);
	}
	//Obverse inscription
	if(isset($params['obinsc']) && ($params['obinsc'] != "")) 
	{
	$obinsc = $params['obinsc'];
	$select->where('coins.obverse_inscription LIKE ?', '%'.$obinsc.'%');
	}
	//Obverse description
	if(isset($params['obdesc']) && ($params['obdesc'] != "")) 
	{
	$obdesc = $params['obdesc'];
	$select->where('coins.obverse_description LIKE ?', '%'.$obdesc.'%');
	}
	//Reverse inscription
	if(isset($params['revinsc']) && ($params['revinsc'] != "")) 
	{
	$revinsc = $params['revinsc'];
	$select->where('coins.reverse_inscription LIKE ?', '%'.$revinsc.'%');
	}
	//Reverse description
	if(isset($params['revdesc']) && ($params['revdesc'] != "")) 
	{
	$revdesc = $params['revdesc'];
	$select->where('coins.reverse_description LIKE ?', '%'.$revdesc.'%');
	}
	##Iron age specific
	//Mack type
	if(isset($params['mack']) && ($params['mack'] != "")) 
	{
	$mack = $params['mack'];
	$select->where('coins.mack_type = ?', $mack);
	}
	//Allen type
	if(isset($params['allen']) && ($params['allen'] != "")) 
	{
	$allen = $params['allen'];
	$select->where('coins.allen_type = ?', $allen);
	}
	//Rudd type
	if(isset($params['rudd']) && ($params['rudd'] != "")) 
	{
	$rudd = $params['rudd'];
	$select->where('coins.rudd_type = ?', $rudd);
	}
	//Van Arsdell type
	if(isset($params['va']) && ($params['va'] != "")) 
	{
	$va = $params['va'];
	$select->where('coins.va_type = ?', $va);
	}
	//Geographical region
	if(isset($params['geoIA']) && ($params['geoIA'] != "")) 
	{
	$geography = $params['geoIA'];
	$select->where('coins.geographyID = ?', $geography);
	}
	//Tribe
	if(isset($params['tribe']) && ($params['tribe'] != "")) 
	{
	$tribe = $params['tribe'];
	$select->where('coins.tribe = ?', $tribe);
	}
	#####
	##Roman specific
	#####
	//ReeceID
	if(isset($params['reeceID']) && ($params['reeceID'] != "")) 
	{
	$reeceID = $params['reeceID'];
	$select->where('coins.reeceID = ?', $reeceID);
	}
	//Reverse type
	if(isset($params['reverse']) && ($params['reverse'] != "")) 
	{
	$reverse = $params['reverse'];
	$select->where('coins.revtypeID = ?', $reverse);
	}
	####
	##Medieval specific
	####
	//Medieval type
	if(isset($params['typeID']) && ($params['typeID'] != "")) 
	{
	$typeID = $params['typeID'];
	$select->where('coins.typeID = ?', $typeID);
	}
	
	if(isset($params['medtype']) && ($params['medtype'] != "")) 
	{
	$typeID = $params['medtype'];
	$select->where('coins.typeID = ?', $typeID);
	}
	//Medieval category
	if(isset($params['category']) && ($params['category'] != "")) 
	{
	$categoryID = $params['category'];
	$select->where('coins.categoryID = ?', $categoryID);
	}
	####
	##Greek and roman prov specific
	####
	//Greek state ID
	if(isset($params['greekID']) && ($params['greekID'] != "")) 
	{
	$greekstateID = $params['greekID'];
	$select->where('coins.greekstateID = ?', $greekstateID);
	}
	##Spatial specific query formation
	//County
	if(isset($params['county']) && ($params['county'] != "")) 
	{
	$county = $params['county'];
	$select->where('findspots.county = ?', $county);
	}
	//District
	if(isset($params['district']) && ($params['district'] != "")) 
	{
	$district = $params['district'];
	$select->where('findspots.district = ?', $district);
	}
	//Parish
	if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->restricted) ) 
	{
	$parish = $params['parish'];
	$select->where('findspots.parish = ?', $parish)
	->where('findspots.knownas IS NULL');
	}
	else if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->higherlevel) )
	{
	$parish = $params['parish'];
	$select->where('findspots.parish = ?', $parish);
	}
	
	//Region
	if(isset($params['regionID']) && ($params['regionID'] != "")) 
	{
	$region = $params['regionID'];
	$select->where('findspots.regionID = ?', $region);
	}
	//Landuse
	if(isset($params['landuse']) && ($params['landuse'] != "")) 
	{
	$landuse = $params['landuse'];
	$select->where('findspots.landusecode = ?', $landuse);
	}
	//Secondary landuse
	if(isset($params['value']) && ($params['value'] != "")) 
	{
	$value = $params['value'];
	$select->where('findspots.landusevalue = ?', $value);
	}
	//Comments
	if(isset($params['fourfigure']) && ($params['fourfigure'] != "")) 
	{
	$fourfigure = $params['fourfigure'];
	$select->where('findspots.fourFigure = ?', $fourfigure);
	}
	//Known as
	if(isset($params['knownas']) && ($params['knownas'] != "")) 
	{
	$knownas = $params['knownas'];
	$select->where('findspots.knownas = ?', $knownas);
	}
	//Known as
	if(isset($params['discovered']) && ($params['discovered'] != "")) 
	{
	$discovered = $params['discovered'];
	$select->where('finds.datefound1 >= ?',$discovered.'-01-01')
		->where('finds.datefound1 <= ?',$discovered.'-12-31')
		->where('finds.datefound1 IS NOT NULL');
	};
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30) 
		->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != ""))  {
	$paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}

	public function getSearchResultsAdvancedHero($params,$role) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('id','old_findID','uniqueID' => 'secuid','objecttype','classification','subclass','length','height','width','thickness','diameter','quantity','other_ref','treasureID','broadperiod','numdate1','numdate2','culture','description','notes','reuse','created' =>'finds.created','updated','treasureID','secwfstage','secuid','findofnote','objecttypecert','datefound1','datefound2','inscription','secuid','disccircum','museumAccession' => 'musaccno','subsequentAction' => 'subs_action','objectCertainty' => 'objecttypecert','dateFromCertainty' => 'numdate1qual','dateToCertainty' => 'numdate2qual','dateFoundFromCertainty' => 'datefound1qual', 'dateFoundToCertainty' => 'datefound2qual','subPeriodFrom' => 'objdate1subperiod','subPeriodTo' => 'objdate2subperiod'))
		->joinLeft('findofnotereasons','finds.findofnotereason = findofnotereasons.id',array('reason' => 'term'))
		->joinLeft('users','users.id = finds.createdBy',array('username','fullname','institution'))
		->joinLeft(array('users2' => 'users'),'users2.id = finds.updatedBy',array('usernameUpdate' => 'username','fullnameUpdate' => 'fullname'))
		->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id',array('primaryMaterial' =>'term'))
		->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id',array('secondaryMaterial' => 'term'))
		->joinLeft('decmethods','finds.decmethod = decmethods.id',array('decoration' => 'term'))
		->joinLeft('decstyles','finds.decstyle = decstyles.id',array('style' => 'term'))
		->joinLeft('manufactures','finds.manmethod = manufactures.id',array('manufacture' => 'term'))
		->joinLeft('surftreatments','finds.surftreat = surftreatments.id',array('surfaceTreatment' => 'term'))
		->joinLeft('completeness','finds.completeness = completeness.id',array('completeness' => 'term'))
		->joinLeft('preservations','finds.preservation = preservations.id',array('preservation' => 'term'))
		->joinLeft('certaintytypes','certaintytypes.id = finds.objecttypecert',array('cert' => 'term'))
		->joinLeft('periods','finds.objdate1period = periods.id',array('periodFrom' => 'term'))
		->joinLeft(array('p' => 'periods'),'finds.objdate2period = p.id',array('periodTo' => 'term'))
		->joinLeft('cultures','finds.culture = cultures.id',array('culture' => 'term'))
		->joinLeft('discmethods','discmethods.id = finds.discmethod',array('discmethod' => 'method'))
		->joinLeft('coins','finds.secuid = coins.findID',array('obverse_description','obverse_inscription','reverse_description','reverse_inscription','denomination','degree_of_wear','allen_type','va_type','mack' => 'mack_type','reeceID','die' => 'die_axis_measurement','wearID'=> 'degree_of_wear','moneyer','revtypeID','categoryID','typeID','tribeID' => 'tribe','status','rulerQualifier' => 'ruler_qualifier','denominationQualifier' => 'denomination_qualifier','mintQualifier' => 'mint_qualifier','dieAxisCertainty' => 'die_axis_certainty','initialMark' => 'initial_mark','reverseMintMark' => 'reverse_mintmark','statusQualifier' => 'status_qualifier'))
		->joinLeft('ironagetribes','coins.tribe = ironagetribes.id',array('tribe'))
		->joinLeft('geographyironage','geographyironage.id = coins.geographyID',array('region','area'))
		->joinLeft('denominations','denominations.id = coins.denomination',array('denomination'))
		->joinLeft('rulers','rulers.id = coins.ruler_id',array('ruler1' => 'issuer'))
		->joinLeft('rulers','rulers.id = coins.ruler2_id',array('ruler2' => 'issuer'))
		->joinLeft('reeceperiods','coins.reeceID = reeceperiods.id',array('period_name','date_range'))
		->joinLeft('mints','mints.id = coins.mint_ID',array('mint_name'))
		->joinLeft('weartypes','coins.degree_of_wear = weartypes.id',array('wear' => 'term'))
		->joinLeft('medievalcategories','medievalcategories.id = coins.categoryID',array('category'))
		->joinLeft('medievaltypes','medievaltypes.id = coins.typeID',array('type'))
		->joinLeft('moneyers','moneyers.id = coins.moneyer',array('moneyer' => 'name'))
		->joinLeft('revtypes','coins.revtypeID = revtypes.id',array('reverseType' => 'type'))
		->joinLeft('statuses','coins.status = statuses.id',array('status' => 'term'))
		->joinLeft('people','finds.finderID = people.secuid',array('finder' => 'CONCAT(people.title," ",people.forename," ",people.surname)'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid',array('identifier' => 'CONCAT(ident1.title," ",ident1.forename," ",ident1.surname)'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid',array('secondaryIdentifier' => 'CONCAT(ident2.title," ",ident2.forename," ",ident2.surname)'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid',array('recorder' => 'CONCAT(record.title," ",record.forename," ",record.surname)'))
		->joinLeft('findspots','finds.secuid = findspots.findID',array('county','parish','district','gridref','fourFigure','easting','northing','map25k','map10k','address','postcode','findspotdescription' => 'description','lat' => 'declat','lon' => 'declong'))->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc',array('source' => 'term'));
	if(isset($params['regionID']) && ($params['regionID'] != ""))  
	{
	$select->joinLeft('regions','findspots.regionID = regions.id',array('region'));
	}
	## Find specific query formation	
	//Old_findID
	if(isset($params['old_findID']) && ($params['old_findID'] != ""))  
	{
	$old_findID = $params['old_findID'];
	$select->where('old_findID = ?', $old_findID);
	}
	//Objecttype
	if(isset($params['objecttype']) && ($params['objecttype'] != ""))  
	{
	$objecttype = $params['objecttype'];
	$select->where('objecttype = ?', $objecttype);
	}
	if(isset($params['woeid']) && ($params['woeid'] != '')) {
	$select	->where('findspots.woeid = ?',$params['woeid']);
	}
	//wear for coins
	
	if(isset($params['wear']) && ($params['wear'] != ""))  
	{
	$wear = $params['wear'];
	$select->where('degree_of_wear = ?', $wear);
	}
	//Description
	if(isset($params['description']) && ($params['description'] != ""))  
	{
	$description = $params['description'];
	$select->where('finds.description LIKE ?', '%'.$description.'%');
	}
	//Notes
	if(isset($params['notes']) && ($params['notes'] != ""))  
	{
	$notes = $params['notes'];
	$select->where('finds.notes LIKE ?', '%'.$notes.'%');
	}
	//Broadperiod
	if(isset($params['broadperiod']) && ($params['broadperiod'] != ""))  
	{
	$broadperiod = $params['broadperiod'];
	$select->where('broadperiod = ?', (string)$broadperiod);
	}
	//Period From date
	if(isset($params['periodfrom']) && ($params['periodfrom'] != ""))  
	{
	$periodfrom = $params['periodfrom'];
	$select->where('finds.objdate1period = ?', (int)$periodfrom);
	}
	//culture
	if(isset($params['culture']) && ($params['culture'] != ""))  
	{
	$culture = $params['culture'];
	$select->where('finds.culture = ?', (int)$culture);
	}
	
	//From date
	if(isset($params['from']) && ($params['from'] != ""))  
	{
	$from = $params['from'];
	$select->where('finds.numdate1 >= ?', $from)
	->where('finds.numdate1 IS NOT NULL');
	}
	if(isset($params['fromend']) && ($params['fromend'] != ""))  
	{
	$fromend = $params['fromend'];
	$select->where('finds.numdate1 <= ?', $fromend)
	->where('finds.numdate1 IS NOT NULL');
	}
	//Early mid late
	if(isset($params['tosubperiod']) && ($params['tosubperiod'] != ""))  
	{
	$tosubperiod = $params['tosubperiod'];
	$select->where('finds.objdate2subperiod = ?', $tosubperiod);
	}
	//Period to date
	if(isset($params['periodto']) && ($params['periodto'] != ""))  
	{
	$periodto = $params['periodto'];
	$select->where('finds.objdate2period = ?', $periodto);
	}
	//Early Mid/late
	if(isset($params['fromsubperiod']) && ($params['fromsubperiod'] != ""))  
	{
	$fromsubperiod = $params['fromsubperiod'];
	$select->where('finds.objdate1subperiod = ?', $fromsubperiod);
	}
	
	//Discmethod
	if(isset($params['discmethod']) && ($params['discmethod'] != ""))  
	{
	$discmethod = $params['discmethod'];
	$select->where('finds.discmethod = ?', $discmethod);
	}
	
	//To date
	if(isset($params['to']) && ($params['to'] != ""))  
	{
	$to = $params['to'];
	$select->where('finds.numdate2 <= ?', $to);
	}
	//Primary material
	if(isset($params['material']) && ($params['material'] != "")) 
	{
	$material = $params['material'];
	$select->where('finds.material1 = ?', $material);
	}
	//Created by
	if(isset($params['createdby']) && ($params['createdby'] != "")) 
	{
	$createdby = $params['createdby'];
	$select->where('finds.createdBy = ?', $createdby);
	}
	//Finder
	if(isset($params['finder']) && ($params['finder'] != "")) 
	{
	$finder = $params['finder'];
	$select->where('finds.finderID = ?', $finder);
	}
	//Identifier
	if(isset($params['idby']) && ($params['idby'] != "")) 
	{
	$idby = $params['idby'];
	$select->where('finds.identifier1ID = ?', $idby);
	}
	if(isset($params['idby2']) && ($params['idby2'] != "")) 
	{
	$id2by = $params['idby2'];
	$select->where('finds.identifier2ID = ?', $id2by);
	}
	if(isset($params['recorderID']) && ($params['recorderID'] != "")) 
	{
	$recordby = $params['recorderID'];
	$select->where('finds.recorderID = ?', $recordby);
	}
	//Created on exactly
	if(isset($params['created']) && ($params['created'] != "")) 
	{
	$created = $params['created'];
	$select->where('finds.created = ?', $created . ' 00:00:00');
	}
	
	//Created on
	if(isset($params['createdAfter']) && ($params['createdAfter'] != "")) 
	{
	$createdAfter = $params['createdAfter'];
	$select->where('finds.created >=?', $createdAfter . ' 00:00:00');
	}
	//Created before
	if(isset($params['createdBefore']) && ($params['createdBefore'] != "")) 
	{
	$createdBefore = $params['createdBefore'];
	$select->where('finds.created <= ?', $createdBefore);
	}
	//Workflow
	if(isset($params['workflow']) && ($params['workflow'] != "")) 
	{
	$workflow = $params['workflow'];
	$select->where('finds.secwfstage = ?', $workflow);
	}
	//Decoration method
	if(isset($params['decoration']) && ($params['decoration'] != "")) 
	{
	$decoration = $params['decoration'];
	$select->where('finds.decmethod = ?', $decoration);
	}
	//Decoration style
	if(isset($params['decstyle']) && ($params['decstyle'] != "")) 
	{
	$decstyle = $params['decstyle'];
	$select->where('finds.decstyle = ?', $decstyle);
	}
	
	//Manufacture method
	if(isset($params['manufacture']) && ($params['manufacture'] != "")) 
	{
	$manufacture = $params['manufacture'];
	$select->where('finds.manmethod = ?', $manufacture);
	}
	//Surface treatment
	if(isset($params['surface']) && ($params['surface'] != "")) 
	{
	$surface = $params['surface'];
	$select->where('finds.surftreat = ?', $surface);
	}
	//Classification
	if(isset($params['class']) && ($params['class'] != "")) 
	{
	$class = $params['class'];
	$select->where('finds.classification LIKE ?', '%'.$class.'%');
	}
	//Subclassification
	if(isset($params['subclass']) && ($params['subclass'] != "")) 
	{
	$subclass = $params['subclass'];
	$select->where('finds.subclass LIKE ?', '%'.$subclass.'%');
	}
	//Treasure
	if(isset($params['treasure']) && ($params['treasure'] != "")) 
	{
	$treasure = $params['treasure'];
	$select->where('finds.treasure = ?', $treasure);
	}
	//Treasure number
	if(isset($params['TID']) && ($params['TID'] != "")) 
	{
	$treasureID = $params['TID'];
	$select->where('finds.treasureID = ?', $treasureID);
	}
	//Hoard
	if(isset($params['hoard']) && ($params['hoard'] != "")) 
	{
	$hoard = $params['hoard'];
	$select->where('finds.hoard = ?', $hoard);
	}
	//Hoard name
	if(isset($params['hID']) && ($params['hID'] != "")) 
	{
	$hoard = $params['hID'];
	$select->where('finds.hoardID = ?', $hoard);
	}
	//Rally
	if(isset($params['rally']) && ($params['rally'] != "")) 
	{
	$rally = $params['rally'];
	$select->where('finds.rally = ?', $rally);
	}
	//Rally name
	if(isset($params['rallyID']) && ($params['rallyID'] != "")) 
	{
	$rallyID = $params['rallyID'];
	$select->joinLeft('rallies','finds.rallyID = rallies.id',array('rally_name'))
	->where('finds.rallyID = ?', $rallyID);
	}
	//find of note
	if(isset($params['note']) && ($params['note'] != "")) 
	{
	$note = $params['note'];
	$select->where('finds.findofnote = ?', $note);
	}
	//find of note reason
	if(isset($params['reason']) && ($params['reason'] != "")) 
	{
	$reason = $params['reason'];
	$select
	->where('finds.findofnotereason = ?', $reason);
	}
	//Other reference
	if(isset($params['otherref']) && ($params['otherref'] != "")) 
	{
	$otherref = $params['otherref'];
	$select->where('finds.other_ref = ?', $otherref);
	}
	##Coin specific query formation
	//Primary ruler
	if(isset($params['ruler']) && ($params['ruler'] != "")) 
	{
	$ruler = $params['ruler'];
	$select
	->where('coins.ruler_id = ?', $ruler);
	}
	//Secondary ruler
	if(isset($params['ruler2']) && ($params['ruler2'] != "")) 
	{
	$ruler2 = $params['ruler2'];
	$select
	->where('coins.ruler2_id = ?', $ruler2);
	}
	//Denomination
	if(isset($params['denomination']) && ($params['denomination'] != "")) 
	{
	$denomname = $params['denomination'];
	$select->where('coins.denomination = ?', $denomname);
	}
	//Mint
	if(isset($params['mint']) && ($params['mint'] != "")) 
	{
	$mint = $params['mint'];
	$select->where('coins.mint_id = ?', $mint);
	}
	//Die axis
	if(isset($params['axis']) && ($params['axis'] != "")) 
	{
	$axis = $params['axis'];
	$select->where('coins.die_axis_measurement = ?', $axis);
	}
	//Moneyer
	if(isset($params['moneyer']) && ($params['moneyer'] != "")) 
	{
	$moneyer = $params['moneyer'];
	$select->where('coins.moneyer = ?', $moneyer);
	}
	//Obverse inscription
	if(isset($params['obinsc']) && ($params['obinsc'] != "")) 
	{
	$obinsc = $params['obinsc'];
	$select->where('coins.obverse_inscription LIKE ?', '%'.$obinsc.'%');
	}
	//Obverse description
	if(isset($params['obdesc']) && ($params['obdesc'] != "")) 
	{
	$obdesc = $params['obdesc'];
	$select->where('coins.obverse_description LIKE ?', '%'.$obdesc.'%');
	}
	//Reverse inscription
	if(isset($params['revinsc']) && ($params['revinsc'] != "")) 
	{
	$revinsc = $params['revinsc'];
	$select->where('coins.reverse_inscription LIKE ?', '%'.$revinsc.'%');
	}
	//Reverse description
	if(isset($params['revdesc']) && ($params['revdesc'] != "")) 
	{
	$revdesc = $params['revdesc'];
	$select->where('coins.reverse_description LIKE ?', '%'.$revdesc.'%');
	}
	##Iron age specific
	//Mack type
	if(isset($params['mack']) && ($params['mack'] != "")) 
	{
	$mack = $params['mack'];
	$select->where('coins.mack_type = ?', $mack);
	}
	//Allen type
	if(isset($params['allen']) && ($params['allen'] != "")) 
	{
	$allen = $params['allen'];
	$select->where('coins.allen_type = ?', $allen);
	}
	//Rudd type
	if(isset($params['rudd']) && ($params['rudd'] != "")) 
	{
	$rudd = $params['rudd'];
	$select->where('coins.rudd_type = ?', $rudd);
	}
	//Van Arsdell type
	if(isset($params['va']) && ($params['va'] != "")) 
	{
	$va = $params['va'];
	$select->where('coins.va_type = ?', $va);
	}
	//Geographical region
	if(isset($params['geoIA']) && ($params['geoIA'] != "")) 
	{
	$geography = $params['geoIA'];
	$select->where('coins.geographyID = ?', $geography);
	}
	//Tribe
	if(isset($params['tribe']) && ($params['tribe'] != "")) 
	{
	$tribe = $params['tribe'];
	$select->where('coins.tribe = ?', $tribe);
	}
	#####
	##Roman specific
	#####
	//ReeceID
	if(isset($params['reeceID']) && ($params['reeceID'] != "")) 
	{
	$reeceID = $params['reeceID'];
	$select->where('coins.reeceID = ?', $reeceID);
	}
	//Reverse type
	if(isset($params['reverse']) && ($params['reverse'] != "")) 
	{
	$reverse = $params['reverse'];
	$select->where('coins.revtypeID = ?', $reverse);
	}
	####
	##Medieval specific
	####
	//Medieval type
	if(isset($params['medtype']) && ($params['medtype'] != "")) 
	{
	$typeID = $params['medtype'];
	$select->where('coins.typeID = ?', $typeID);
	}
	//Medieval category
	if(isset($params['category']) && ($params['category'] != "")) 
	{
	$categoryID = $params['category'];
	$select->where('coins.categoryID = ?', $categoryID);
	}
	####
	##Greek and roman prov specific
	####
	//Greek state ID
	if(isset($params['greekID']) && ($params['greekID'] != "")) 
	{
	$greekstateID = $params['greekID'];
	$select->where('coins.greekstateID = ?', $greekstateID);
	}
	##Spatial specific query formation
	//County
	if(isset($params['county']) && ($params['county'] != "")) 
	{
	$county = $params['county'];
	$select->where('findspots.county = ?', $county);
	}
	//District
	if(isset($params['district']) && ($params['district'] != "")) 
	{
	$district = $params['district'];
	$select->where('findspots.district = ?', $district);
	}
	//Parish
	if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->restricted) ) 
	{
	$parish = $params['parish'];
	$select->where('findspots.parish = ?', $parish)
	->where('findspots.knownas IS NULL');
	}
	if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->higherlevel) )
	{
	$parish = $params['parish'];
	$select->where('findspots.parish = ?', $parish);
	}
	
	if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->_research) )
	{
	$parish = $params['parish'];
	$select->where('findspots.parish = ?', $parish);
	}
	
	//Region
	if(isset($params['regionID']) && ($params['regionID'] != "")) 
	{
	$region = $params['regionID'];
	$select->where('findspots.regionID = ?', $region);
	}
	//Landuse
	if(isset($params['landuse']) && ($params['landuse'] != "")) 
	{
	$landuse = $params['landuse'];
	$select->where('findspots.landusecode = ?', $landuse);
	}
	//Secondary landuse
	if(isset($params['value']) && ($params['value'] != "")) 
	{
	//$value = $params['value'];
	//$select->where('findspots.landusevalue = ?', $value);
	throw new Exception('Currently disabled',500);
	}
	//Comments
	if(isset($params['fourfigure']) && ($params['fourfigure'] != "")) 
	{
	$fourfigure = $params['fourfigure'];
	$select->where('findspots.fourFigure = ?', $fourfigure);
	}
	//Known as
	if(isset($params['knownas']) && ($params['knownas'] != "")) 
	{
	$knownas = $params['knownas'];
	$select->where('findspots.knownas = ?', $knownas);
	}
	//Known as
	if(isset($params['discovered']) && ($params['discovered'] != "")) 
	{
	$discovered = $params['discovered'];
	$select->where('finds.datefound1 >= ?',$discovered.'-01-01')
		->where('finds.datefound1 <= ?',$discovered.'-12-31')
		->where('finds.datefound1 IS NOT NULL');
	};
	return $finds = $finds->fetchAll($select);
}

	public function goGetTheHero($params, $role) {
	
	$sql = 'SELECT finds.secuid AS SecUID, finds.old_findID AS FindID, finds.objecttype AS ObjectType, cert1.term AS ObjectTypeCertainty, finds.description AS ObjectDescription, finds.classification AS ObjectClassification, finds.subclass AS ObjectSubClassification, finds.inscription AS ObjectInscription, finds.notes AS Notes, cert2.term AS ObjectDate1Certainty, numdate1 AS DateFrom, period1.term AS PeriodFrom, dq1.term AS CalendarDate1Qualifier, cert3.term AS ObjectDate2Certainty, period2.term AS PeriodTo, dq2.term AS CalendarDate2Qualifier, finds.numdate2 AS DateTo, period3.term AS AscribedCulture, mat1.term AS PrimaryMaterial, mat2.term AS AdditionalMaterial, manufactures.term AS MethodOfManufacture, surfaces.term AS SurfaceTreatment, finds.length, finds.width, finds.thickness, finds.diameter, finds.weight, finds.quantity, wear.term AS Wear, p.term AS Preservation, c.term AS Completeness, reuse AS EvidenceOfReuse, findspots.gridref AS OSRef, findspots.easting AS Easting, findspots.northing AS Northing, people.fullname AS Finder, dq3.term AS DateFound1Qualifier, finds.datefound1 AS DateFound1, dq4.term AS DateFound2Qualifier, datefound2 AS DateFound2, disco.method AS MethodsOfDiscovery, finds.disccircum AS CircumstancesofDiscovery, pep2.fullname AS RecordedBy, pep3.fullname AS PrimaryIdentifier, pep4.fullname AS SecondaryIdentifier, finds.curr_loc AS CurrentLocation, finds.musaccno AS MuseumAccNo, sub.action AS SubsequentAction, finds.other_ref AS OtherReference, 
sp.term AS SubperiodFrom,  sp2.term AS SubperiodTo, period4.term AS PeriodOfReuse, dm.term AS DecmethodObsolete, ds.term AS Decstyle, finds.findofnote AS CoolFind, findspots. old_findspotid AS FindspotCode, finds.old_finderID  AS FormerFinderID, finds.old_candidate AS FormerCandidateTerm, finds.smrrefno AS FormerPhotoReference, finds.smrrefno AS FormerDrawingReference, finds.smrrefno AS ExportedToWeb, finds. smr_ref AS SMRReference, finds.broadperiod AS BroadPeriod, finds.secwfstage as WorkflowStage,finds.createdBy AS FindOfficer, findspots.county AS county, findspots.district AS district, findspots.parish as parish, findspots.address, findspots.postcode, findspots.description, findspots.knownas AS KnownAs, findspots.comments, pep5.fullname AS LandOwner, smrrefno AS Occupier, lu1.term AS SpecificLanduse,lu2.term AS GeneralLanduse, finds.id AS IDOfFind, findspots.createdBy AS FindOfficerFindspot, rulers.issuer AS Ruler, cert4.term AS RulerQualifier, denoms.denomination AS Denomination,cert6.term AS DenominationQualifier, mints.mint_name AS Mint, cert5.term AS MintQualifier, 
smrrefno AS CoinType, sta.term AS STATUS, cert8.term AS StatusQualifier, coins.moneyer as Moneyer, coins.obverse_description AS Obverse_description, coins.obverse_inscription AS Obverse_inscription, coins.initial_mark AS Initial_mark, coins.reverse_description AS Reverse_description,coins.reverse_inscription AS Reverse_inscription, coins.reverse_mintmark AS Reverse_mintmark, coins.degree_of_wear AS Degree_of_wear, coins.die_axis_measurement AS Die_axis_measurement,  cert7.term AS Die_axis_certainty, coins.reeceID';
//JOIN tables
	$sql .= '
	FROM finds
	LEFT JOIN findspots ON finds.secuid = findspots.findID
	LEFT JOIN coins ON finds.secuid = coins.findID
	LEFT JOIN certaintytypes AS cert1 ON cert1.id = finds.objecttypecert
	LEFT JOIN certaintytypes AS cert2 ON cert2.id = finds.objdate1cert
	LEFT JOIN certaintytypes AS cert3 ON cert3.id = finds.objdate2cert
	LEFT JOIN certaintytypes AS cert4 ON cert4.id = coins.ruler_qualifier
	LEFT JOIN certaintytypes AS cert5 ON cert5.id = coins.mint_qualifier
	LEFT JOIN certaintytypes AS cert6 ON cert6.id = coins.denomination_qualifier
	LEFT JOIN certaintytypes AS cert7 ON cert7.id = coins.die_axis_certainty
	LEFT JOIN certaintytypes AS cert8 ON cert8.id = coins. status_qualifier 
	LEFT JOIN periods AS period1 ON finds.objdate1period = period1.id
	LEFT JOIN periods AS period2 ON finds.objdate2period = period2.id
	LEFT JOIN periods AS period3 ON finds.culture = period3.id
	LEFT JOIN periods AS period4 ON finds.reuse_period  = period4.id
	LEFT JOIN datequalifiers AS dq1 ON finds.objdate1cert = dq1.id
	LEFT JOIN datequalifiers AS dq2 ON finds.objdate2cert = dq2.id
	LEFT JOIN materials AS mat1 ON finds.material1 = mat1.id
	LEFT JOIN materials AS mat2 ON finds.material2 = mat2.id
	LEFT JOIN manufactures ON finds.manmethod = manufactures.id
	LEFT JOIN surftreatments AS surfaces ON finds.surftreat = surfaces.id
	LEFT JOIN weartypes AS wear ON finds.wear = wear.id
	LEFT JOIN preservations AS p ON finds.preservation = p.id
	LEFT JOIN completeness AS c ON finds.completeness = c.id
	LEFT JOIN people ON finds.finderID = people.secuid
	LEFT JOIN datequalifiers AS dq3 ON finds.datefound1qual = dq3.id
	LEFT JOIN datequalifiers AS dq4 ON finds.datefound2qual  = dq4.id
	LEFT JOIN discmethods AS disco ON finds.discmethod = disco.id
	LEFT JOIN people AS pep2 ON finds.recorderID = pep2.secuid
	LEFT JOIN people AS pep3 ON finds.identifier1ID = pep3.secuid
	LEFT JOIN people AS pep4 ON finds.identifier2ID = pep4.secuid
	LEFT JOIN people AS pep5 ON findspots.landowner = pep5.secuid
	LEFT JOIN subsequentActions AS sub ON finds.subs_action = sub.id
	LEFT JOIN subperiods AS sp  ON finds.objdate1period = sp.id
	LEFT JOIN subperiods AS sp2 ON finds.objdate2period = sp2.id
	LEFT JOIN decmethods AS dm ON finds.decmethod = dm.id
	LEFT JOIN decstyles AS ds ON finds.decstyle = ds.id
	LEFT JOIN denominations AS denoms ON coins.denomination = denoms.id
	LEFT JOIN mints ON coins.mint_id = mints.id
	LEFT JOIN statuses AS sta ON coins.status = sta.id
	LEFT JOIN rulers ON coins.ruler_id = rulers.id
	LEFT JOIN landuses AS lu1 ON findspots.landusecode = lu1.id
	LEFT JOIN landuses AS lu2 ON findspots.landusevalue = lu2.id
	WHERE 1';

	if(isset($params['regionID']) && ($params['regionID'] != ""))  
	{
	$regionID = $params['regionID'];
	$sql .= '  AND findspots.regionID = "' . $regionID . '"';
	}
		
	if(isset($params['old_findID']) && ($params['old_findID'] != ""))  
	{
	$old_findID = $params['old_findID'];
	$sql .= '  AND old_findID = "' . $old_findID . '" ';
	}
	
	if(isset($params['objecttype']) && ($params['objecttype'] != ""))  
	{
	$objecttype = $params['objecttype'];
	$sql .= '  AND finds.objecttype = "' . $objecttype . '"';
	}
	
	
	if(isset($params['wear']) && ($params['wear'] != ""))  
	{
	$wear = $params['wear'];
	$sql .= ' AND finds.degree_of_wear = "' . $wear . '"';
	}
	//Description
	if(isset($params['description']) && ($params['description'] != ""))  
	{
	$description = $params['description'];
	$sql .= ' AND finds.description LIKE "%' . $description . '%"';
	}
	//Notes
	if(isset($params['notes']) && ($params['notes'] != ""))  
	{
	$notes = $params['notes'];
	$sql .= ' AND finds.notes LIKE "%' . $notes . '%"';
	}
	//Broadperiod
	if(isset($params['broadperiod']) && ($params['broadperiod'] != ""))  
	{
	$broadperiod = $params['broadperiod'];
	$sql .= ' AND broadperiod = "' . (string)$broadperiod . '"';
	}
	//Period From date
	if(isset($params['periodfrom']) && ($params['periodfrom'] != ""))  
	{
	$periodfrom = $params['periodfrom'];
	$sql .= ' AND finds.objdate1period = "' . (int)$periodfrom . '"';
	}
	//culture
	if(isset($params['culture']) && ($params['culture'] != ""))  
	{
	$culture = $params['culture'];
	$sql .= ' AND finds.culture = "' . (int)$culture . '"';
	}
	
	//From date
	if(isset($params['from']) && ($params['from'] != ""))  
	{
	$from = $params['from'];
	$sql .= ' AND finds.numdate1 >= "' . (int)$from . '" AND finds.numdate1 IS NOT NULL';
	}
	if(isset($params['fromend']) && ($params['fromend'] != ""))  
	{
	$fromend = $params['fromend'];
	$sql .= ' AND finds.numdate1 <= "' . $fromend .'" AND finds.numdate1 IS NOT NULL';
	}
	//Early mid late
	if(isset($params['tosubperiod']) && ($params['tosubperiod'] != ""))  
	{
	$tosubperiod = $params['tosubperiod'];
	$sql .= ' AND finds.objdate2subperiod = "' . (int)$tosubperiod . '"';
	}
	//Period to date
	if(isset($params['periodto']) && ($params['periodto'] != ""))  
	{
	$periodto = $params['periodto'];
	$sql .= ' AND finds.objdate2period = "' . $periodto . '"';
	}
	//Early Mid/late
	if(isset($params['fromsubperiod']) && ($params['fromsubperiod'] != ""))  
	{
	$fromsubperiod = $params['fromsubperiod'];
	$sql .= ' AND finds.objdate1subperiod = "' . $fromsubperiod . '"';
	}
	
	//Discmethod
	if(isset($params['discmethod']) && ($params['discmethod'] != ""))  
	{
	$discmethod = $params['discmethod'];
	$sql .= ' AND finds.discmethod = "' . $discmethod . '"';
	}
	
	//To date
	if(isset($params['to']) && ($params['to'] != ""))  
	{
	$to = $params['to'];
	$sql .= ' AND finds.numdate2 <= "' . $to . '"';
	}
	//Primary material
	if(isset($params['material']) && ($params['material'] != "")) 
	{
	$material = $params['material'];
	$sql .= ' AND finds.material1 = "' . $material . '"';
	}
	//Created by
	if(isset($params['createdby']) && ($params['createdby'] != "")) 
	{
	$createdby = $params['createdby'];
	$sql .= ' AND finds.createdBy = "' . $createdby . '"';
	}
	//Finder
	if(isset($params['finderID']) && ($params['finderID'] != "")) 
	{
	$finder = $params['finderID'];
	$sql .= ' AND finds.finderID = "' . $finder . '"';
	}
	//Identifier
	if(isset($params['idby']) && ($params['idby'] != "")) 
	{
	$idby = $params['idby'];
	$sql .= ' AND finds.identifier1ID = "' . $idby . '"';
	}
	if(isset($params['idby2']) && ($params['idby2'] != "")) 
	{
	$idby2 = $params['idby2'];
	$sql .= ' AND finds.identifier2ID = "' . $idby2 . '"';
	}
	//Recorded by
	if(isset($params['recorderID']) && ($params['recorderID'] != "")) 
	{
	$recordby = $params['recorderID'];
	$sql .= ' AND finds.recorderID = "' . $recordby . '"';
	}
	//Created on exactly
	if(isset($params['created']) && ($params['created'] != "")) 
	{
	$created = $params['created'];
	$sql .= ' AND DATE(finds.created) = "' . $created . '"';
	}
	
	//Created on
	if(isset($params['createdAfter']) && ($params['createdAfter'] != "")) 
	{
	$createdAfter = $params['createdAfter'];
	$sql .= ' AND DATE(finds.created) >= "' . $createdAfter . '"';
	}
	//Created before
	if(isset($params['createdBefore']) && ($params['createdBefore'] != "")) 
	{
	$createdBefore = $params['createdBefore'];
	$sql .= ' AND DATE(finds.created) <= "' . $createdBefore . '"';
	}
	//Workflow
	if(isset($params['workflow']) && ($params['workflow'] != "")) 
	{
	$workflow = $params['workflow'];
	$sql .= ' AND finds.secwfstage = "' . $workflow . '"';
	}
	//Decoration method
	if(isset($params['decoration']) && ($params['decoration'] != "")) 
	{
	$decoration = $params['decoration'];
	$sql .= ' AND finds.decmethod = "' . $decoration . '"';
	}
	//Decoration style
	if(isset($params['decstyle']) && ($params['decstyle'] != "")) 
	{
	$decstyle = $params['decstyle'];
	$sql .= ' AND finds.decstyle = "' . $decstyle . '"';
	}
	
	//Manufacture method
	if(isset($params['manufacture']) && ($params['manufacture'] != "")) 
	{
	$manufacture = $params['manufacture'];
	$sql .= ' AND finds.manmethod = "' . $manufacture . '"';
	}
	//Surface treatment
	if(isset($params['surface']) && ($params['surface'] != "")) 
	{
	$surface = $params['surface'];
	$sql .= ' AND finds.surftreat = "' .  $surface . '"';
	}
	//Classification
	if(isset($params['class']) && ($params['class'] != "")) 
	{
	$class = $params['class'];
	$sql .= ' AND finds.classification LIKE "%' . $class . '%"';
	}
	//Subclassification
	if(isset($params['subclass']) && ($params['subclass'] != "")) 
	{
	$subclass = $params['subclass'];
	$sql .= ' AND finds.subclass LIKE "%' . $subclass . '%"';
	}
	//Treasure
	if(isset($params['treasure']) && ($params['treasure'] != "")) 
	{
	$treasure = $params['treasure'];
	$sql .= ' AND finds.treasure = "' . $treasure . '"';
	}
	//Treasure number
	if(isset($params['TID']) && ($params['TID'] != "")) 
	{
	$treasureID = $params['TID'];
	$sql .= ' AND finds.treasureID = "' . $treasureID . '"';
	}
	//Hoard
	if(isset($params['hoard']) && ($params['hoard'] != "")) 
	{
	$hoard = $params['hoard'];
	$sql .= ' AND finds.hoard = "' . $hoard . '"';
	}
	//Hoard name
	if(isset($params['hID']) && ($params['hID'] != "")) 
	{
	$hoard = $params['hID'];
	$sql .= ' AND finds.hoardID = "' .  $hoard . '"';
	}
	//Rally
	if(isset($params['rally']) && ($params['rally'] != "")) 
	{
	$rally = $params['rally'];
	$sql .= ' AND finds.rally = "' . $rally . '"';
	}
	//Rally name
	if(isset($params['rallyID']) && ($params['rallyID'] != "")) 
	{
	$rallyID = $params['rallyID'];
	$sql .= ' AND finds.rallyID = "' .  $rallyID . '"';
	}
	//find of note
	if(isset($params['note']) && ($params['note'] != "")) 
	{
	$note = $params['note'];
	$sql .= ' AND finds.findofnote = "' . $note . '"';
	}
	//find of note reason
	if(isset($params['reason']) && ($params['reason'] != "")) 
	{
	$reason = $params['reason'];
	$sql .= ' AND finds.findofnotereason = "' . $reason . '"';
	}
	//Other reference
	if(isset($params['otherref']) && ($params['otherref'] != "")) 
	{
	$otherref = $params['otherref'];
	$sql .= ' AND finds.other_ref = "' .  $otherref . '"';
	}
	##Coin specific query formation
	//Primary ruler
	if(isset($params['ruler']) && ($params['ruler'] != "")) 
	{
	$ruler = $params['ruler'];
	$sql .= ' AND coins.ruler_id = "' . $ruler . '"';
	}
	//Secondary ruler
	if(isset($params['ruler2']) && ($params['ruler2'] != "")) 
	{
	$ruler2 = $params['ruler2'];
	$sql .= ' AND coins.ruler2_id = "' .  $ruler2 . '"';
	}
	//Denomination
	if(isset($params['denomination']) && ($params['denomination'] != "")) 
	{
	$denomname = $params['denomination'];
	$sql .= ' AND coins.denomination = "' . $denomname . '"';
	}
	//Mint
	if(isset($params['mint']) && ($params['mint'] != "")) 
	{
	$mint = $params['mint'];
	$sql .= ' AND coins.mint_id = "' . $mint . '"';
	}
	//Die axis
	if(isset($params['axis']) && ($params['axis'] != "")) 
	{
	$axis = $params['axis'];
	$sql .= ' AND coins.die_axis_measurement = "' . $axis . '"';
	}
	//Moneyer
	if(isset($params['moneyer']) && ($params['moneyer'] != "")) 
	{
	$moneyer = $params['moneyer'];
	$sql .= ' AND coins.moneyer = "' . $moneyer . '"';
	}
	//Obverse inscription
	if(isset($params['obinsc']) && ($params['obinsc'] != "")) 
	{
	$obinsc = $params['obinsc'];
	$sql .= ' AND coins.obverse_inscription LIKE "%' . $obinsc . '%"';
	}
	//Obverse description
	if(isset($params['obdesc']) && ($params['obdesc'] != "")) 
	{
	$obdesc = $params['obdesc'];
	$sql .= ' AND coins.obverse_description LIKE "%' . $obdesc . '%"';
	}
	//Reverse inscription
	if(isset($params['revinsc']) && ($params['revinsc'] != "")) 
	{
	$revinsc = $params['revinsc'];
	$sql .= ' AND coins.reverse_inscription LIKE "%' . $revinsc . '%"';
	}
	//Reverse description
	if(isset($params['revdesc']) && ($params['revdesc'] != "")) 
	{
	$revdesc = $params['revdesc'];
	$sql .= ' AND coins.reverse_description LIKE "%' . $revdesc . '%"';
	}
	##Iron age specific
	//Mack type
	if(isset($params['mack']) && ($params['mack'] != "")) 
	{
	$mack = $params['mack'];
	$sql .= ' AND coins.mack_type = "' . $mack . '"';
	}
	//Allen type
	if(isset($params['allen']) && ($params['allen'] != "")) 
	{
	$allen = $params['allen'];
	$sql .= ' AND coins.allen_type = "' . $allen . '"';
	}
	//Rudd type
	if(isset($params['rudd']) && ($params['rudd'] != "")) 
	{
	$rudd = $params['rudd'];
	$sql .= ' AND coins.rudd_type = "' . $rudd . '"';
	}
	//Van Arsdell type
	if(isset($params['va']) && ($params['va'] != "")) 
	{
	$va = $params['va'];
	$sql .= ' AND coins.va_type = "' . $va . '"';
	}
	//Geographical region
	if(isset($params['geoIA']) && ($params['geoIA'] != "")) 
	{
	$geography = $params['geoIA'];
	$sql .= ' AND coins.geographyID = "' . $geography . '"';
	}
	//Tribe
	if(isset($params['tribe']) && ($params['tribe'] != "")) 
	{
	$tribe = $params['tribe'];
	$sql .= ' AND coins.tribe = "' . $tribe . '"';
	}
	#####
	##Roman specific
	#####
	//ReeceID
	if(isset($params['reeceID']) && ($params['reeceID'] != "")) 
	{
	$reeceID = $params['reeceID'];
	$sql .= ' AND coins.reeceID = "' .  $reeceID . '"';
	}
	//Reverse type
	if(isset($params['reverse']) && ($params['reverse'] != "")) 
	{
	$reverse = $params['reverse'];
	$sql .= ' AND coins.revtypeID = "' . $reverse . '"';
	}
	####
	##Medieval specific
	####
	//Medieval type
	if(isset($params['medtype']) && ($params['medtype'] != "")) 
	{
	$typeID = $params['medtype'];
	$sql .= ' AND coins.typeID = "' . $typeID . '"';
	}
	//Medieval category
	if(isset($params['category']) && ($params['category'] != "")) 
	{
	$categoryID = $params['category'];
	$sql .= ' AND coins.categoryID = "' . $categoryID . '"';
	}
	####
	##Greek and roman prov specific
	####
	//Greek state ID
	if(isset($params['greekID']) && ($params['greekID'] != "")) 
	{
	$greekstateID = $params['greekID'];
	$sql .= ' AND coins.greekstateID = "' . $greekstateID . '"';
	}
	##Spatial specific query formation
	//County
	if(isset($params['county']) && ($params['county'] != "")) 
	{
	$county = $params['county'];
	$sql .= ' AND findspots.county = "' . $county . '"';
	}
	//District
	if(isset($params['district']) && ($params['district'] != "")) 
	{
	$district = $params['district'];
	$sql .= ' AND findspots.district = "' . $district . '"';
	}
	//Parish
	if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->restricted) ) 
	{
	$parish = $params['parish'];
	$sql .= ' AND findspots.parish = "' . $parish .'" AND findspots.knownas IS NULL';
	}
	else if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->higherlevel) )
	{
	$parish = $params['parish'];
	$sql .= ' AND findspots.parish = "' . $parish . '"';
	}
	else if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->_research) )
	{
	$parish = $params['parish'];
	$sql .= ' AND findspots.parish = "' . $parish . '"';
	}
	//Region
	if(isset($params['regionID']) && ($params['regionID'] != "")) 
	{
	$region = $params['regionID'];
	$sql .= ' AND findspots.regionID = "' . $region . '"';
	}
	//Landuse
	if(isset($params['landuse']) && ($params['landuse'] != "")) 
	{
	$landuse = $params['landuse'];
	$sql .= ' AND findspots.landusecode = "' . $landuse . '"';
	}
	//Secondary landuse
	if(isset($params['value']) && ($params['value'] != "")) 
	{
	$value = $params['value'];
	$sql .= ' AND findspots.landusevalue = "' . $value . '"';
	}
	//Comments
	if(isset($params['fourfigure']) && ($params['fourfigure'] != "")) 
	{
	$fourfigure = $params['fourfigure'];
	$sql .= ' AND findspots.fourFigure = "' .  $fourfigure . '"';
	}
	//Known as
	if(isset($params['knownas']) && ($params['knownas'] != "")) 
	{
	$knownas = $params['knownas'];
	$sql .= ' AND findspots.knownas = "'. $knownas.'"';
	}
	//Known as
	if(isset($params['discovered']) && ($params['discovered'] != "")) 
	{
	$discovered = $params['discovered'];
	$sql .= ' AND DATE)(finds.datefound1) >= "'.$discovered.'-01-01" AND DATE(finds.datefound1) <= "'.$discovered.'-12-31" AND DATE(finds.datefound1) IS NOT NULL';
	};
	$finds = $this->getAdapter();
	
	$data = $finds->fetchAll($sql);
	return $data;
	}
}