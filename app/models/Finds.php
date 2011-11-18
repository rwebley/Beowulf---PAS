<?php
/** Access, manipulate and delete finds data. I wrote this when I was 
* a naive new php programmer (still am really!). It sucks in a massive way.  
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo needs a complete overhaul. Lots of duplication.
*/
class Finds extends Pas_Db_Table_Abstract {
	
	protected $_name = 'finds';

	protected $_primary = 'id';
	
	protected $_higherlevel = array('admin','flos','fa','hero','treasure'); 
	
	protected $_parishStop = array('admin','flos','fa','hero','treasure','research'); 
	
	protected $_restricted = array('public','member','research');
	
	protected $_edittest = array('flos','member');
	
	protected $config;

	/** Construct the config object
	* @return object
	*/
	public function init(){
		$this->_config = Zend_Registry::get('config');
	}
	/** Get identity for any form that is used
	* @return integer the user id
	*/
	protected function getIdentityForForms() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else  {
	$id = '3';
	return $id;
	}
	}
	/** Get role of user
	* @return string user role
	*/
	protected function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}
	/** Get institution of user
	* @return string user role
	*/
	protected function getInstitution() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$institution = $user->institution;
	} else {
	$institution = 'public';	
	}
	return $institution;
	}
	/** Get a find id for the jquery autocomplete
	* @param string $q the query for the find number
	* @return array
	*/
	public function getOldFindID($q) {
	$select = $this->select()
		->from($this->_name, array('id' => 'old_findID', 'term' => 'old_findID'))
		->order($this->_primary)
		->where('old_findID LIKE ?', (string)'%' . $q . '%')
		->limit(10);
	return $this->getAdapter()->fetchAll($select);
	}
	/** Get a find's secure unique id for the jquery autocomplete
	* @param string $q the query for the find number
	* @return array
	*/
	public function getFindSecuid($q) {
	$select = $this->select()
		->from($this->_name, array('id' => 'secuid', 'term' => 'CONCAT(old_findID," - ",objecttype," ","(",broadperiod,")")'))
		->order($this->_primary)
		->where('old_findID LIKE ?', (string) $q . '%')
		->limit(10);
	return $this->getAdapter()->fetchAll($select);
	}

	/** Get a count of all the finds and records on the database
	* @return array
	*/
	public function getCountAllFinds() {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('q' => 'SUM(quantity)','c' => 'COUNT(*)'));
	return $finds->fetchAll($select);
	}

	/** Get a count of all the objects and records on the database
	* @return array
	*/
	public function getObjectTotals() {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('q' => 'SUM(quantity)','b' => 'broadperiod'))
		->where('broadperiod IS NOT NULL')
		->where('broadperiod != ?', '')
		->order('quantity')
		->group('broadperiod');
        return $finds->fetchAll($select);
	}
	/** Get a count of all the finds and records on the database by year
	* @return array
	*/
	public function getFindsByYear() {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('q' => 'SUM(quantity)' , 'y' => 'EXTRACT(YEAR FROM created)')) 
		->where('created IS NOT NULL')
		->where('created != ?', '0000-00-00')
		->order('y')
		->group('y');
	return $finds->fetchAll($select);
	}
	
	/** Get a count of all the finds on the database 
	* @return array
	*/
	public function getCount($id) {
	$counts = $this->getAdapter();
	$select = $counts->select()
		->from($this->_name, array('c' => 'SUM(quantity)'))
		->joinLeft('coins','coins.findID = finds.secuid', array())
		->joinLeft('rulers','rulers.id = coins.ruler_id', array())
		->where('coins.ruler_id = ?', (int)$id);
        return $counts->fetchAll($select);
    }
	/** Get a count of all the coins by emperor on the database
	* @param integer $empID the emperor ID
	* @return array
	*/
	public function getCountEmperor($empID){
	$counts = $this->getAdapter();
	$select = $counts->select()
		->from($this->_name, array('c' => 'SUM(quantity)'))
		->joinLeft('coins','coins.findID = finds.secuid', array())
		->joinLeft('emperors','coins.ruler_id = emperors.pasID', array())
		->where('emperors.id = ?',(int)$empID);
	return $counts->fetchAll($select);
    }
	/** Get a count of all the coins by mint on the database for roman period
	* @param integer $mintID the mint's ID
	* @return array
	*/
	public function getCountMint($mintID) {
	$counts = $this->getAdapter();
	$select = $counts->select()
		->from($this->_name, array('c' => 'SUM(quantity)'))
		->joinLeft('coins','coins.findID = finds.secuid', array())
		->joinLeft('romanmints','coins.mint_id = romanmints.pasID', array())
		->where('romanmints.id = ?', (int)$mintID);
	return $counts->fetchAll($select);
    }
	/** Get a count of all the coins by mint on the database
	* @param integer $mintID the mint's ID
	* @return array
	*/
	public function getCountMedMint($mintID)  {
	$counts = $this->getAdapter();
	$select = $counts->select()
		->from($this->_name, array('c' => 'SUM(quantity)'))
		->joinLeft('coins','coins.findID = finds.secuid', array())
		->where('coins.mint_id = ?', (int)$mintID);
	return $counts->fetchAll($select);
    }

    /** Get a count of all the coins by medieval category on the database
	* @param integer $categoryID the category ID number
	* @return array
	*/
	public function getCategoryTotals($categoryID) {
	$counts = $this->getAdapter();
	$select = $counts->select()
		->from($this->_name, array('c' => 'SUM(quantity)'))
		->joinLeft('coins','coins.findID = finds.secuid',array())
		->where('coins.categoryID = ?',(int)$categoryID);
	return $counts->fetchAll($select);
    }

 	/** Get a count of all the coins by denomination on the database
	* @param integer $denominationID the denomination ID number
	* @return array
	*/
	public function getDenominationTotals($denominationID) {
	$counts = $this->getAdapter();
	$select = $counts->select()
		->from($this->_name, array('c' => 'SUM(quantity)'))
		->joinLeft('coins','coins.findID = finds.secuid', array())
		->where('coins.denomination = ?', (int)$denominationID);
	return $counts->fetchAll($select);
	}

	/** Get a count of all the finds by specific workflow stage
	* @param integer $wfStageID the workflow stage number
	* @return array
	* @param maybe needs deprecation?
	*/
	public function getWorkflowstatus($wfStageID) {
	$workflowstages = $this->getAdapter();
	$select = $workflowstages->select()
		->from($this->_name, array('secwfstage'))
		->joinLeft('workflowstages','finds.secwfstage = workflowstages.id', array('workflowstage'))
		->where('finds.id = ?', (int)$wfStageID);
	return $workflowstages->fetchAll($select);
	}

	/** Get all related finds one way only
	* @param integer $findID the find number
	* @return array
	* @param maybe needs deprecation?
	*/
	public function getRelatedFinds($findID) {
	$relatedfinds = $this->getAdapter();
	$select = $relatedfinds->select()
		->from($this->_name, array('i' => 'id' , 'b' => 'broadperiod', 'otype' => 'objecttype', 
		'oldfind' => 'old_findID')) 
		->joinLeft('findxfind','finds.secuid = findxfind.find2_id',array())
		->where('finds.id = ?', (int)$findID);
	return $relatedfinds->fetchAll($select);
	}

	/** Get next ten objects
	* @param integer $findID the find number
	* @return array
	*/
	public function getNextObject($findID) {
	$records = $this->getAdapter();
	$select = $records->select()
		->from($this->_name, array('id', 'old_findID', 'objecttype', 'broadperiod'))
		->where('finds.id > ?', (int)$findID)
		->order($this->_primary)
		->limit(10) ;
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?', (int)2);
	}
	return $records->fetchAll($select);
	}
	/** Get previous ten objects
	* @param integer $findID the find number
	* @return array
	*/
	public function getPreviousObject($findID) {
	$recordsprior = $this->getAdapter();
	$select = $recordsprior->select()
		->from($this->_name, array('id', 'old_findID', 'objecttype', 'broadperiod'))
		->where('finds.id < ?', (int)$findID)
		->order('finds.id DESC')
		->limit(10);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?',(int)2);
	}
	return $recordsprior->fetchAll($select);
	}

	/** Get massive data for a single record, loads of joins
	* @param integer $findID the find number
	* @return array
	* @todo cache the output
	*/
	public function getAllData($findID) {
	$findsdata = $this->getAdapter();
	$select = $findsdata->select()
		->from($this->_name, array('id', 'old_findID', 'uniqueID' => 'secuid',
		'objecttype', 'classification', 'subclass', 
		'length', 'height', 'width', 
		'thickness', 'diameter', 'quantity',
		'other_ref', 'treasureID', 'broadperiod',
		'numdate1', 'numdate2', 'description',
		'notes', 'reuse', 'created' =>'finds.created',
		'broadperiod', 'updated', 'treasureID',
		'secwfstage', 'findofnote', 'objecttypecert',
		'datefound1', 'datefound2', 'inscription',
		'disccircum', 'museumAccession' => 'musaccno', 'subsequentAction' => 'subs_action',
		'objectCertainty' => 'objecttypecert', 'dateFromCertainty' => 'numdate1qual', 'dateToCertainty' => 'numdate2qual',
		'dateFoundFromCertainty' => 'datefound1qual', 'dateFoundToCertainty' => 'datefound2qual', 
		'subPeriodFrom' => 'objdate1subperiod', 'subPeriodTo' => 'objdate2subperiod'))
		->joinLeft('findofnotereasons','finds.findofnotereason = findofnotereasons.id', array('reason' => 'term'))
		->joinLeft('users','users.id = finds.createdBy', array('username','fullname','institution'))
		->joinLeft(array('users2' => 'users'),'users2.id = finds.updatedBy', 
		array('usernameUpdate' => 'username','fullnameUpdate' => 'fullname'))
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
		->joinLeft('people','finds.finderID = people.secuid', array('finder' => 'CONCAT(people.title," ",people.forename," ",people.surname)'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid', 
		array('identifier' => 'CONCAT(ident1.title," ",ident1.forename," ",ident1.surname)'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid', 
		array('secondaryIdentifier' => 'CONCAT(ident2.title," ",ident2.forename," ",ident2.surname)'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid', 
		array('recorder' => 'CONCAT(record.title," ",record.forename," ",record.surname)'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county', 'parish', 'district',
		'easting', 'northing', 'gridref', 
		'fourFigure', 'map25k', 'map10k', 
		'address', 'postcode', 'findspotdescription' => 'description',
		'lat' => 'declat', 'lon' => 'declong', 'knownas'))
		->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc',array('source' => 'term'))
		->joinLeft('coins','finds.secuid = coins.findID',array('obverse_description', 'obverse_inscription', 
		'reverse_description', 'reverse_inscription', 'denomination',
		'degree_of_wear', 'allen_type', 'va_type',
		'mack' => 'mack_type', 'reeceID', 'die' => 'die_axis_measurement',
		'wearID'=> 'degree_of_wear', 'moneyer', 'revtypeID',
		'categoryID', 'typeID', 'tribeID' => 'tribe',
		'status', 'rulerQualifier' => 'ruler_qualifier','denominationQualifier' => 'denomination_qualifier',
		'mintQualifier' => 'mint_qualifier', 'dieAxisCertainty' => 'die_axis_certainty', 'initialMark' => 'initial_mark',
		'reverseMintMark' => 'reverse_mintmark', 'statusQualifier' => 'status_qualifier'))
		->joinLeft('ironagetribes','coins.tribe = ironagetribes.id', array('tribe'))
		->joinLeft('geographyironage','geographyironage.id = coins.geographyID', array('region','area'))
		->joinLeft('denominations','denominations.id = coins.denomination', array('denomination'))
		->joinLeft('rulers','rulers.id = coins.ruler_id', array('ruler1' => 'issuer'))
		->joinLeft(array('rulers_2' => 'rulers'),'rulers_2.id = coins.ruler2_id', array('ruler2' => 'issuer'))
		->joinLeft('reeceperiods','coins.reeceID = reeceperiods.id', array('period_name','date_range'))
		->joinLeft('mints','mints.id = coins.mint_ID', array('mint_name'))
		->joinLeft('weartypes','coins.degree_of_wear = weartypes.id', array('wear' => 'term'))
		->joinLeft('dieaxes','coins.die_axis_measurement = dieaxes.id', array('die_axis_name'))
		->joinLeft('medievalcategories','medievalcategories.id = coins.categoryID', array('category'))
		->joinLeft('medievaltypes','medievaltypes.id = coins.typeID', array('type'))
		->joinLeft('moneyers','moneyers.id = coins.moneyer', array('moneyer' => 'name'))
		->joinLeft('emperors','emperors.pasID = rulers.id', array('emperorID' => 'id'))
		->joinLeft('romanmints','romanmints.pasID = mints.id', array('mintid' => 'id'))
		->joinLeft('revtypes','coins.revtypeID = revtypes.id', array('reverseType' => 'type'))
		->joinLeft('statuses','coins.status = statuses.id', array('status' => 'term'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array())
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID','f' => 'filename')) 
		->joinLeft(array('u' => 'users'),'slides.createdBy = u.id', array('imagedir'))
		->where('finds.id = ?', (int)$findID)
		->group('finds.id')
		->limit(1);
	return $findsdata->fetchAll($select);
	}

	/** Get dimensional data for a find
	* @param integer $findID the find number
	* @return array
	* @todo cache the output
	*/
	public function getFindData($findID){
	$findsdata = $this->getAdapter();
	$select = $findsdata->select()
		->from($this->_name, array('length', 'height', 'width', 
		'thickness', 'diameter', 'quantity', 
		'weight'))
		->where('finds.id = ?', (int)$findID);
	return $findsdata->fetchAll($select);
	}

	/** Get reference data for a find
	* @param integer $findID the find number
	* @return array
	* @todo cache the output
	*/
	public function getFindOtherRefs($findID) {
	$findsdata = $this->getAdapter();
	$select = $findsdata->select()
		->from($this->_name, array('other_ref','treasureID','smr_ref','musaccno'))
		->where('finds.id = ?', (int)$findID);
	return $findsdata->fetchAll($select);
	}

	/** Get materials and manufacturing data for a find
	* @param integer $findID the find number
	* @return array
	* @todo cache the output
	*/
	public function getFindMaterials($findID) {
	$findsmaterial = $this->getAdapter();
	$select = $findsmaterial->select()
		->from($this->_name, array('material1', 'manmethod', 'decmethod' ,'decstyle', 'completeness', 'surftreat'))
		->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id', array('mat1' =>'term'))
		->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id', array('mat2' => 'term'))
		->joinLeft('decmethods','finds.decmethod = decmethods.id', array('decoration' => 'term'))
		->joinLeft('decstyles','finds.decstyle = decstyles.id', array('style' => 'term'))
		->joinLeft('manufactures','finds.manmethod = manufactures.id', array('manufacture' => 'term'))
		->joinLeft('surftreatments','finds.surftreat = surftreatments.id', array('surface' => 'term'))
		->joinLeft('completeness','finds.completeness = completeness.id', array('complete' => 'term'))
		->joinLeft('certaintytypes','certaintytypes.id = finds.objecttypecert', array('cert' => 'term'))
		->where('finds.id = ?', (int)$findID)
		->group('finds.id')
		->limit(1);
	return $findsmaterial->fetchAll($select);
	}

	/** Get temporal data for a find
	* @param integer $findID the find number
	* @return array
	* @todo cache the output
	*/
	public function getFindTemporalData($findID) {
	$temporals = $this->getAdapter();
	$select = $temporals->select()
		->from($this->_name, array('broadperiod','numdate1','numdate2','period1' => 'objdate1period', 
		'period2' => 'objdate2period','culture', 'subPeriodFrom' => 'objdate1subperiod', 
		'subPeriodTo' => 'objdate2subperiod'))
		->joinLeft('periods','finds.objdate1period = periods.id', array('term'))
		->joinLeft(array('p' => 'periods'),'finds.objdate2period = p.id', array('t2' => 'term'))
		->joinLeft('cultures','finds.culture = cultures.id', array('cult' => 'term'))
		->joinLeft(array('sub1' => 'subperiods'),$this->_name . '.objdate1subperiod = sub1.id', 
		array('subPeriodFrom' => 'term'))
		->joinLeft(array('sub2' => 'subperiods'),$this->_name . '.objdate2subperiod = sub2.id',
		array('subPeriodTo' => 'term'))
		->joinLeft(array('circa1' => 'datequalifiers'),$this->_name . '.numdate1qual = circa1.id',
		array('fromcirca' => 'term'))
		->joinLeft(array('circa2' => 'datequalifiers'),$this->_name . '.numdate2qual = circa2.id',
		array('tocirca' => 'term'))
		->where('finds.id = ?', (int)$findID)
		->group('finds.id')
		->limit(1);
	return $temporals->fetchAll($select);
	}
	/** Get personal data for a find
	* @param integer $findID the find number
	* @return array
	* @todo cache the output
	*/
	public function getPersonalData($findID) {
	$personals = $this->getAdapter();
	$select = $personals->select()
		->from($this->_name, array('finderID', 'recorderID','identifier1ID','identifier2ID'))
		->joinLeft('people','finds.finderID = people.secuid', array('tit1' => 'title', 'fore' => 'forename',
		'sur' => 'surname','secuid'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid', array('tit2' => 'title', 
		'fore2' => 'forename','sur2' => 'surname'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid', array('tit5' => 'title', 
		'fore5' => 'forename','sur5' => 'surname'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid', array('tit3' => 'title', 
		'fore3' => 'forename','sur3' => 'surname'))
		->where('finds.id = ?',$findID)
		->group('finds.id')
		->limit(1);
	return $personals->fetchAll($select);
	}
	/** Get other finds by a specific finder
	* @param string $finderID the finder number
	* @return array
	* @todo add a limit or make a better pagination type query
	*/
	public function getOtherFinds($finderID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('n' => 'id','broadperiod','objecttype','old_findID','description'))
		->joinLeft('findspots','finds.secuid = findspots.findID',array('county'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id',array())
		->joinLeft('slides','slides.secuid = finds_images.image_id',array('i' => 'imageID'))
		->where('finds.finderid = ?', (string)$finderID)
		->group('finds.old_findID');
	return $finds->fetchAll($select);
	}
	/** Get count of finds by finder
	* @param string $finderID the finder number
	* @return array
	* @todo add a limit or make a better pagination type query
	*/
	public function getOtherFindsTotals($id)  {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name, array('number' => 'SUM(quantity)'))
			->where('finds.finderid = ?', (string)$id);
       return $finds->fetchAll($select);
	}
	/** Get finds nearby to this specific point
	* @param double $declong decimal degrees longintude
	* @param double $declat decimal degrees latitude
	* @return array
	*/

	public function getFindsNearby($declong,$declat) {
	$nearbys = $this->getAdapter();
	$select = $nearbys->select()
		->from($this->_name,array('oldfindID','objecttype','broadperiod'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('distance' => 'acos(SIN( PI()* 40.7383040 /180 )*SIN( PI()*' 
		. $declat . '/180))+(cos(PI()* 40.7383040 /180)*COS( PI()*' . $declat . '/180) *COS(PI()*' . $declong 
		. '/180-PI()* -73.99319 /180))* 3963.191'))
		->where('1=1')
		->where('3963.191 * ACOS( (SIN(PI()* 40.7383040 /180)*SIN(PI() * ' . $declat 
		. '/180)) +(COS(PI()* 40.7383040 /180)*cos(PI()*' . $declat . '/180)*COS(PI() *' 
		. $declong . '/180-PI()* -73.99319 /180))) <= 2')
		->order('distance')
		->order('3963.191 * ACOS((SIN(PI()* 40.7383040 /180)*SIN(PI()*' . $declat 
		. '/180)) +(COS(PI()* 40.7383040 /180)*cos(PI()*' . $declat . '/180)*COS(PI() *' 
		. $declong . '/180-PI()* -73.99319 /180))'); 
	return $nearbys->fetchAll($select);
	}

	/** Get finds for a specific search
	* @param array $params The search string
	* @param string $role The user's role to apply
	* @return array
	* @todo move to search class and ultimately replace with SOLR
	*/

	public function getSearchResultsAdvanced($params,$role) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('old_findID', 'id', 'uniqueID' => 'secuid', 
		'objecttype', 'classification', 'subclass', 
		'length', 'height', 'width',
		'thickness', 'diameter', 'quantity',
		'other_ref', 'treasureID', 'broadperiod',
		'numdate1', 'numdate2', 'culture',
		'description', 'notes', 'reuse',
		'created' =>'finds.created', 'updated', 'treasureID',
		'secwfstage', 'findofnote', 'objecttypecert',
		'datefound1', 'datefound2', 'inscription',
		'disccircum', 'museumAccession' => 'musaccno', 'subsequentAction' => 'subs_action',
		'objectCertainty' => 'objecttypecert', 'dateFromCertainty' => 'numdate1qual', 
		'dateToCertainty' => 'numdate2qual', 'dateFoundFromCertainty' => 'datefound1qual', 
		'dateFoundToCertainty' => 'datefound2qual', 'subPeriodFrom' => 'objdate1subperiod',
		'subPeriodTo' => 'objdate2subperiod', 'createdBy'))
		->joinLeft('coins','finds.secuid = coins.findID', array('obverse_description','obverse_inscription',
		'reverse_description', 'reverse_inscription', 'denomination',
		'degree_of_wear', 'allen_type', 'va_type',
		'mack' => 'mack_type', 'reeceID', 'die' => 'die_axis_measurement',
		'wearID'=> 'degree_of_wear', 'moneyer', 'revtypeID',
		'categoryID', 'typeID', 'tribeID' => 'tribe', 
		'status', 'rulerQualifier' => 'ruler_qualifier', 'denominationQualifier' => 'denomination_qualifier',
		'mintQualifier' => 'mint_qualifier', 'dieAxisCertainty' => 'die_axis_certainty', 'initialMark' => 'initial_mark',
		'reverseMintMark' => 'reverse_mintmark', 'statusQualifier' => 'status_qualifier'))
		->joinLeft('findofnotereasons','finds.findofnotereason = findofnotereasons.id', array('reason' => 'term'))
		->joinLeft('users','users.id = finds.createdBy', array('username','fullname','institution'))
		->joinLeft(array('users2' => 'users'),'users2.id = finds.updatedBy',
		array('usernameUpdate' => 'username','fullnameUpdate' => 'fullname'))
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
		->joinLeft('ironagetribes','coins.tribe = ironagetribes.id',array('tribe'))
		->joinLeft('geographyironage','geographyironage.id = coins.geographyID',array('region','area'))
		->joinLeft('denominations','denominations.id = coins.denomination',array('denomination'))
		->joinLeft('rulers','rulers.id = coins.ruler_id',array('ruler1' => 'issuer'))
		->joinLeft(array('rulers2' => 'rulers'),'rulers2.id = coins.ruler2_id',array('ruler2' => 'issuer'))
		->joinLeft('reeceperiods','coins.reeceID = reeceperiods.id',array('period_name','date_range'))
		->joinLeft('mints','mints.id = coins.mint_ID',array('mint_name'))
		->joinLeft('weartypes','coins.degree_of_wear = weartypes.id',array('wear' => 'term'))
		->joinLeft('medievalcategories','medievalcategories.id = coins.categoryID',array('category'))
		->joinLeft('medievaltypes','medievaltypes.id = coins.typeID',array('type'))
		->joinLeft('moneyers','moneyers.id = coins.moneyer',array('moneyer' => 'name'))
		->joinLeft('revtypes','coins.revtypeID = revtypes.id',array('reverseType' => 'type'))
		->joinLeft('statuses','coins.status = statuses.id',array('status' => 'term')) 
		->order('finds.id DESC');
	##Rowcount for pagination query		
	$rowCount = $finds->select()->from( 'finds' )
		->joinLeft('findspots','finds.secuid = findspots.findID',array())
		->joinLeft('coins','finds.secuid = coins.findID',array());
	$rowCount->reset( Zend_Db_Select::COLUMNS )
		->columns( new Zend_Db_Expr( 'COUNT(finds.id) AS ' . Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN ));
	//Set Up access to finds by workflow
	if(!in_array($role,$this->_parishStop)) {
	$select->joinLeft('findspots','finds.secuid = findspots.findID',array('county','district','knownas'))
		->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc',array('gridRefSource' => 'term'));
	$select->where('finds.secwfstage > 2');
	$rowCount->where('finds.secwfstage > 2');
	if(isset($params['parish']) || isset($params['fourfigure'])) {
	$select->where('knownas IS NULL');
	$rowCount->where('knownas IS NULL');
	}
	} else{
	$select->joinLeft('findspots','finds.secuid = findspots.findID',array('county','district','knownas','gridref','lat' => 'declat','lon' => 'declong','map25k','map10k','easting','northing'))
	->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc',array('gridRefSource' => 'term'));
	}
	if(isset($params['woeid']) && ($params['woeid'] != '')) {
	$select	->where('findspots.woeid = ?',$params['woeid']);
	$rowCount->where('findspots.woeid = ?',$params['woeid']);
	}
	if(isset($params['elevation']) && ($params['elevation'] != '')) {
	$select	->where('findspots.elevation= ?',$params['elevation']);
	$rowCount->where('findspots.elevation = ?',$params['elevation']);
	}
	if(isset($params['regionID']) && ($params['regionID'] != "")) {
	$select->joinLeft('regions','findspots.regionID  = regions.id',array())
		->where('findspots.regionID = ?',$params['regionID']);
	$rowCount->joinLeft('regions','findspots.regionID = regions.id',array())
		->where('findspots.regionID = ?',$params['regionID']);
	}
	## Find specific query formation	
	//Old_findID
	if(isset($params['old_findID']) && ($params['old_findID'] != ""))  {
	$old_findID = $params['old_findID'];
	$select->where('old_findID = ?', $old_findID);
	$rowCount->where('old_findID = ?', $old_findID);
	}
	//Objecttype
	if(isset($params['objecttype']) && ($params['objecttype'] != "")) {
	$objecttype = $params['objecttype'];
	$select->where('objecttype = ?', $objecttype);
	$rowCount->where('objecttype = ?', $objecttype);
	}
	//wear for coins
	if(isset($params['wear']) && ($params['wear'] != ""))  {
	$wear = $params['wear'];
	$select->where('degree_of_wear = ?', $wear);
	$rowCount->where('degree_of_wear = ?', $wear);
	}
	//Description
	if(isset($params['description']) && ($params['description'] != "")) {
	$description = $params['description'];
	$select->where('finds.description LIKE ?', '%'.$description.'%');
	$rowCount->where('finds.description LIKE ?', '%'.$description.'%');
	}
	//Notes
	if(isset($params['notes']) && ($params['notes'] != "")) {
	$notes = $params['notes'];
	$select->where('finds.notes LIKE ?', '%'.$notes.'%');
	$rowCount->where('finds.notes LIKE ?', '%'.$notes.'%');
	}
	//Broadperiod
	if(isset($params['broadperiod']) && ($params['broadperiod'] != "")) {
	$broadperiod = $params['broadperiod'];
	$select->where('broadperiod = ?', (string)$broadperiod);
	$rowCount->where('broadperiod = ?', (string)$broadperiod);
	}
	//Period From date
	if(isset($params['periodfrom']) && ($params['periodfrom'] != "")) {
	$periodfrom = $params['periodfrom'];
	$select->where('finds.objdate1period = ?', (int)$periodfrom);
	$rowCount->where('finds.objdate1period = ?', (int)$periodfrom);
	}
	//culture
	if(isset($params['culture']) && ($params['culture'] != "")) {
	$culture = $params['culture'];
	$select->where('finds.culture = ?', (int)$culture);
	$rowCount->where('finds.culture = ?', (int)$culture);
	}
	//From date
	if(isset($params['from']) && ($params['from'] != ""))  {
	$from = $params['from'];
	$select->where('finds.numdate1 >= ?', $from)
		->where('finds.numdate1 IS NOT NULL');
	$rowCount->where('finds.numdate1 >= ?', $from)
		->where('finds.numdate1 IS NOT NULL');
	}
	if(isset($params['fromend']) && ($params['fromend'] != "")) {
	$fromend = $params['fromend'];
	$select->where('finds.numdate1 <= ?', $fromend)
		->where('finds.numdate1 IS NOT NULL');
	$rowCount->where('finds.numdate1 <= ?', $fromend)
		->where('finds.numdate1 IS NOT NULL');
	}
	//Early mid late
	if(isset($params['tosubperiod']) && ($params['tosubperiod'] != ""))  {
	$tosubperiod = $params['tosubperiod'];
	$select->where('finds.objdate2subperiod = ?', $tosubperiod);
	$rowCount->where('finds.objdate2subperiod = ?', $tosubperiod);
	}
	//Period to date
	if(isset($params['periodto']) && ($params['periodto'] != ""))  {
	$periodto = $params['periodto'];
	$select->where('finds.objdate2period = ?', $periodto);
	$rowCount->where('finds.objdate2period = ?', $periodto);
	}
	//Early Mid/late
	if(isset($params['fromsubperiod']) && ($params['fromsubperiod'] != "")) {
	$fromsubperiod = $params['fromsubperiod'];
	$select->where('finds.objdate1subperiod = ?', $fromsubperiod);
	$rowCount->where('finds.objdate1subperiod = ?', $fromsubperiod);
	}
	//Discmethod
	if(isset($params['discmethod']) && ($params['discmethod'] != ""))  {
	throw new Exception('Currently disabled',500);
//	$discmethod = $params['discmethod'];
//	$select->where('finds.discmethod = ?', $discmethod);
//	$rowCount->where('finds.discmethod = ?', $discmethod);
	}
	//To date
	if(isset($params['to']) && ($params['to'] != ""))  {
	$to = $params['to'];
	$select->where('finds.numdate2 <= ?', $to);
	$rowCount->where('finds.numdate2 <= ?', $to);
	}
	//Primary material
	if(isset($params['material']) && ($params['material'] != "")) {
	$material = $params['material'];
	$select->where('finds.material1 = ?', $material);
	$rowCount->where('finds.material1 = ?', $material);
	}
	//Created by
	if(isset($params['createdby']) && ($params['createdby'] != "")) {
	$createdby = $params['createdby'];
	$select->where('finds.createdBy = ?', $createdby);
	$rowCount->where('finds.createdBy = ?',$createdby);
	}
	//Finder
	if(isset($params['finderID']) && ($params['finderID'] != "")) {
	$finder = $params['finderID'];
	$select->joinLeft('people','finds.finderID = people.secuid',array())
		->where('finds.finderID = ?', $finder);
	$rowCount->joinLeft('people','finds.finderID = people.secuid',array())
		->where('finds.finderID = ?', $finder);
	}
	if(isset($params['activity']) && $params['activity'] != NULL ){
	$activity = $params['activity'];	
	//$select->joinLeft('people','finds.finderID = people.secuid',array())
	//	->where('people.primary_activity = ?',$activity);
	//	$rowCount->joinLeft('people','finds.finderID = people.secuid',array())
	//			->where('people.primary_activity = ?',$activity);	
	throw new Exception('Currently disabled',500);
	}
	//Identifier
	if(isset($params['idby']) && ($params['idby'] != "")) {
	$idby = $params['idby'];
	$select->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid',array())
		->where('finds.identifier1ID = ?', $idby);
	$rowCount->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid',array())
		->where('finds.identifier1ID = ?', $idby);	
	}
	//Identifier
	if(isset($params['idby2']) && ($params['idby2'] != ""))  {
	$idby2 = $params['idby2'];
	$select->joinLeft(array('ident2' => 'people'),'finds.identifier1ID = ident2.secuid',array())
		->where('finds.identifier2ID = ?', $idby2);
	$rowCount->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid',array())
		->where('finds.identifier2ID = ?', $idby2);	
	}
	//Recorded by
	if(isset($params['recorderID']) && ($params['recorderID'] != ""))  {
	$recordby = $params['recorderID'];
	$select->where('finds.recorderID = ?', $recordby);
	$rowCount->where('finds.recorderID = ?', $recordby);
	}
//	if(isset($params['recordby']) && $params['recordby'] != NULL ) {
//	throw new Exception('Disabled due to load',500);
//	}
	//Created on exactly
	if(isset($params['created']) && ($params['created'] != "")) {
	$created = $params['created'];
	$select->where('DATE(finds.created) = ?', $created);
	$rowCount->where('DATE(finds.created) = ?', $created);
	}
	//Created on
	if(isset($params['createdAfter']) && ($params['createdAfter'] != "")) {
	$createdAfter = $params['createdAfter'];
	$select->where('DATE(finds.created) >= ?', $createdAfter );
	$rowCount->where('DATE(finds.created) >= ?', $createdAfter);		
	}
	//Created before
	if(isset($params['createdBefore']) && ($params['createdBefore'] != ""))  {
	$createdBefore = $params['createdBefore'];
	$select->where('DATE(finds.created) <= ?', $createdBefore);
	$rowCount->where('DATE(finds.created) <= ?', $createdBefore);
	}
	//Workflow
	if(isset($params['workflow']) && ($params['workflow'] != ""))  {
	$workflow = $params['workflow'];
	$select->where('finds.secwfstage = ?', $workflow);
	$rowCount->where('finds.secwfstage = ?', $workflow);
	}
	//Decoration method
	if(isset($params['decoration']) && ($params['decoration'] != "")) {
	$decoration = $params['decoration'];
	$select->where('finds.decmethod = ?', $decoration);
	$rowCount->where('finds.decmethod = ?', $decoration);
	}
	//Decoration style
	if(isset($params['decstyle']) && ($params['decstyle'] != "")) {
	$decstyle = $params['decstyle'];
	$select->where('finds.decstyle = ?', $decstyle);
	$rowCount->where('finds.decstyle = ?', $decstyle);
	}
	//Manufacture method
	if(isset($params['manufacture']) && ($params['manufacture'] != "")) {
	$manufacture = $params['manufacture'];
	$select->where('finds.manmethod = ?', $manufacture);
	$rowCount->where('finds.manmethod = ?', $manufacture);
	}
	//	Surface treatment
	if(isset($params['surface']) && ($params['surface'] != "")) {
	$surface = $params['surface'];
	$select->where('finds.surftreat = ?', $surface);
	$rowCount->where('finds.surftreat = ?', $surface);
	}
	//Classification
	if(isset($params['class']) && ($params['class'] != ""))  {
	$class = $params['class'];
	$select->where('finds.classification LIKE ?', '%'.$class.'%');
	$rowCount->where('finds.classification LIKE ?', '%'.$class.'%');
	}
	//Subclassification
	if(isset($params['subclass']) && ($params['subclass'] != "")) {
	$subclass = $params['subclass'];
	$select->where('finds.subclass LIKE ?', '%'.$subclass.'%');
	$rowCount->where('finds.subclass LIKE ?', '%'.$subclass.'%');
	}
	//Treasure
	if(isset($params['treasure']) && ($params['treasure'] != ""))  {
	$treasure = $params['treasure'];
	$select->where('finds.treasure = ?', $treasure);
	$rowCount->where('finds.treasure = ?', $treasure);
	}
	//Treasure number
	if(isset($params['TID']) && ($params['TID'] != ""))  {
	$treasureID = $params['TID'];
	$select->where('finds.treasureID = ?', (string)$treasureID);
	$rowCount->where('finds.treasureID = ?', (string)$treasureID);
	}
	//Hoard
	if(isset($params['hoard']) && ($params['hoard'] != ""))  {
	$hoard = $params['hoard'];
	$select->where('finds.hoard = ?', $hoard);
	$rowCount->where('finds.hoard = ?', $hoard);
	}
	//Hoard name
	if(isset($params['hID']) && ($params['hID'] != ""))  {
	$hoard = $params['hID'];
	$select->where('finds.hoardID = ?', $hoard);
	$rowCount->where('finds.hoardID = ?', $hoard);
	}
	//Rally
	if(isset($params['rally']) && ($params['rally'] != ""))  {
	$rally = $params['rally'];
	$select->where('finds.rally = ?', $rally);
	$rowCount->where('finds.rally = ?', $rally);
	}
	//Rally name
	if(isset($params['rallyID']) && ($params['rallyID'] != "")) {
	$rallyID = $params['rallyID'];
	$select->joinLeft('rallies','finds.rallyID = rallies.id',array('rally_name'))
		->where('finds.rallyID = ?', $rallyID);
	$rowCount->joinLeft('rallies','finds.rallyID = rallies.id',array())
		->where('finds.rallyID = ?', $rallyID);
	}
	//find of note
	if(isset($params['note']) && ($params['note'] != "")) {
	$note = $params['note'];
	$select->where('finds.findofnote = ?', (int)$note);
	$rowCount->where('finds.findofnote = ?', (int)$note);
	}
	//find of note reason
	if(isset($params['reason']) && ($params['reason'] != "")) {
	$reason = $params['reason'];
	$select->where('finds.findofnotereason = ?', $reason);
	$rowCount->joinLeft('findofnotereasons','finds.findofnotereason = findofnotereasons.id',array())
		->where('finds.findofnotereason = ?', $reason);
	}
	//Other reference
	if(isset($params['otherref']) && ($params['otherref'] != "")) {
	$otherref = $params['otherref'];
	$select->where('finds.other_ref LIKE ?', '%'.$otherref.'%');
	$rowCount->where('finds.other_ref LIKE ?', '%'.$otherref.'%');
	}
	#Coin specific query formation
	//	Primary ruler
	if(isset($params['ruler']) && ($params['ruler'] != ""))  {
	$ruler = $params['ruler'];
	$select->where('coins.ruler_id = ?', $ruler);
	$rowCount->joinLeft('rulers','rulers.id = coins.ruler_id',array())
		->where('coins.ruler_id = ?', $ruler);
	}
//	Secondary ruler
	if(isset($params['ruler2']) && ($params['ruler2'] != "")) {
	$ruler2 = $params['ruler2'];
	$select->where('coins.ruler2_id = ?', $ruler2);
	$rowCount->joinLeft('rulers','rulers.id = coins.ruler2_id',array())
		->where('coins.ruler2_id = ?', $ruler2);
	}
	//Denomination
	if(isset($params['denomination']) && ($params['denomination'] != "")) {
	$denomname = $params['denomination'];
	$select->where('coins.denomination = ?', $denomname);
	$rowCount->joinLeft('denominations','denominations.id = coins.denomination',array())->where('coins.denomination = ?', $denomname);
	}
//	Mint
	if(isset($params['mint']) && ($params['mint'] != "")) {
	$mint = $params['mint'];
	$select->where('coins.mint_id = ?', $mint);
	$rowCount->joinLeft('mints','mints.id = coins.mint_id',array())->where('coins.mint_id = ?', $mint);
	}
	//Die axis
	if(isset($params['axis']) && ($params['axis'] != "")) {
	$axis = $params['axis'];
	$select->joinLeft('dieaxes','dieaxes.id = coins.die_axis_measurement',array('die_axis_name'))
		->where('coins.die_axis_measurement = ?', $axis);
	$rowCount->joinLeft('dieaxes','dieaxes.id = coins.die_axis_measurement',array())
		->where('coins.die_axis_measurement = ?', $axis);
	}
	//Moneyer
	if(isset($params['moneyer']) && ($params['moneyer'] != "")) {
	$moneyer = $params['moneyer'];
	$select->where('coins.moneyer = ?', $moneyer);
	$rowCount->where('coins.moneyer = ?', $moneyer);
	}
	if(isset($params['complete']) && ($params['complete'] != "")) {
	$complete = $params['complete'];
	$select->where('finds.completeness = ?', $complete);
	$rowCount->where('finds.completeness = ?', $complete);
	}
	//Obverse inscription
	if(isset($params['obinsc']) && ($params['obinsc'] != "")) {
	$obinsc = $params['obinsc'];
	$select->where('coins.obverse_inscription LIKE ?', '%' . $obinsc . '%');
	$rowCount->where('coins.obverse_inscription LIKE ?', '%' . $obinsc . '%');
	}
	//Obverse description
	if(isset($params['obdesc']) && ($params['obdesc'] != "")) {
	$obdesc = $params['obdesc'];
	$select->where('coins.obverse_description LIKE ?', '%' . $obdesc . '%');
	$rowCount->where('coins.obverse_description LIKE ?', '%' . $obdesc . '%');
	}
	//Reverse inscription
	if(isset($params['revinsc']) && ($params['revinsc'] != "")){
	$revinsc = $params['revinsc'];
	$select->where('coins.reverse_inscription LIKE ?', '%' . $revinsc . '%');
	$rowCount->where('coins.reverse_inscription LIKE ?', '%' . $revinsc . '%');
	}
	//Reverse description
	if(isset($params['revdesc']) && ($params['revdesc'] != "")){
	$revdesc = $params['revdesc'];
	$select->where('coins.reverse_description LIKE ?', '%' . $revdesc . '%');
	$rowCount->where('coins.reverse_description LIKE ?', '%' . $revdesc . '%');
	}
	##Iron age specific
	//Mack type
	if(isset($params['mack']) && ($params['mack'] != "")) {
	$mack = $params['mack'];
	$select->where('coins.mack_type = ?', $mack);
	$rowCount->where('coins.mack_type = ?', $mack);
	}
	//Allen type
	if(isset($params['allen']) && ($params['allen'] != "")) {
	$allen = $params['allen'];
	$select->where('coins.allen_type = ?', $allen);
	$rowCount->where('coins.allen_type = ?', $allen);
	}
	//Rudd type
	if(isset($params['rudd']) && ($params['rudd'] != "")) {
	$rudd = $params['rudd'];
	$select->where('coins.rudd_type = ?', $rudd);
	$rowCount->where('coins.rudd_type = ?', $rudd);
	}
	//Van Arsdell type
	if(isset($params['va']) && ($params['va'] != ""))  {
	$va = $params['va'];
	$select->where('coins.va_type = ?', $va);
	$rowCount->where('coins.va_type = ?', $va);
	}
	//Geographical region
	if(isset($params['geoIA']) && ($params['geoIA'] != "")) {
	$geography = $params['geoIA'];
	$select->where('coins.geographyID = ?', $geography);
	$rowCount->where('coins.geographyID = ?', $geography);
	}
	//Tribe
	if(isset($params['tribe']) && ($params['tribe'] != "")) {
	$tribe = $params['tribe'];
	$select->where('coins.tribe = ?', $tribe);
	$rowCount->where('coins.tribe = ?', $tribe);
	}
	##Roman specific
//	ReeceID
	if(isset($params['reeceID']) && ($params['reeceID'] != "")) {
	$reeceID = $params['reeceID'];
	$select->where('coins.reeceID = ?', $reeceID);
	$rowCount->where('coins.reeceID = ?', $reeceID);
	}
	//Reverse type
	if(isset($params['reverse']) && ($params['reverse'] != "")) {
	$reverse = $params['reverse'];
	$select->where('coins.revtypeID = ?', $reverse);
	$rowCount->where('coins.revtypeID = ?', $reverse);
	}
	##Medieval specific
	//Medieval type
	if(isset($params['typeID']) && ($params['typeID'] != "")) {
	$typeID = $params['typeID'];
	$select->where('coins.typeID = ?', $typeID);
	$rowCount->where('coins.typeID = ?', $typeID);
	}
	//Medieval category
	if(isset($params['category']) && ($params['category'] != "")){
	$categoryID = $params['category'];
	$select->where('coins.categoryID = ?', $categoryID);
	$rowCount->where('coins.categoryID = ?', $categoryID);
	}
	##Greek and roman prov specific
	//Greek state ID
	if(isset($params['greekID']) && ($params['greekID'] != "")) {
	$greekstateID = $params['greekID'];
	$select->where('coins.greekstateID = ?', $greekstateID);
	$rowCount->where('coins.greekstateID = ?', $greekstateID);
	}
	##Spatial specific query formation
	//County
	if(isset($params['county']) && ($params['county'] != "")) {
	$county = $params['county'];
	$select->where('findspots.county = ?', $county);
	$rowCount->where('findspots.county = ?', $county);
	}
	//District
	if(isset($params['district']) && ($params['district'] != ""))  {
	$district = $params['district'];
	$select->where('findspots.district = ?', $district);
	$rowCount->where('findspots.district = ?', $district);
	}
	//Parish
	if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->_restricted) ) {
	$parish = $params['parish'];
	$select->where('findspots.parish = ?', $parish);
	$rowCount->where('findspots.parish = ?', $parish);
	} else if( isset($params['parish']) && ($params['parish'] != "") && in_array($role,$this->higherlevel) ) {
	$parish = $params['parish'];
	$select->where('findspots.parish = ?', $parish);
	$rowCount->where('findspots.parish = ?', $parish);
	}
	//Landuse
	if(isset($params['landuse']) && ($params['landuse'] != "")) {
	$landuse = $params['landuse'];
//	$select->where('findspots.landusecode = ?', $landuse);
//	$rowCount->where('findspots.landusecode = ?', $landuse);
	throw new Exception('Currently disabled',500);
	}
	//Secondary landuse
	if(isset($params['value']) && ($params['value'] != "")) {
//	$value = $params['value'];
//	$select->where('findspots.landusevalue = ?', $value);
//	$rowCount->where('findspots.landusevalue = ?', $value);
	throw new Exception('Currently disabled',500);
	}
	//Comments
	if(isset($params['fourfigure']) && ($params['fourfigure'] != "")) {
	$fourfigure = $params['fourfigure'];
	$select->where('findspots.fourFigure = ?', $fourfigure);
	$rowCount->where('findspots.fourFigure = ?', $fourfigure);
	}
	//Known as
	if(isset($params['knownas']) && ($params['knownas'] != ""))  {
	$knownas = $params['knownas'];
	$select->where('findspots.knownas = ?', $knownas);
	$rowCount->where('findspots.knownas = ?', $knownas);
	}
	//Known as
	if(isset($params['discovered']) && ($params['discovered'] != "")) {
	$discovered = $params['discovered'];
	$select->where('finds.datefound1 >= ?',$discovered.'-01-01')
		->where('finds.datefound1 <= ?',$discovered.'-12-31')
		->where('finds.datefound1 IS NOT NULL');
	$rowCount->where('finds.datefound1 >= ?',$discovered.'-01-01')
		->where('finds.datefound1 <= ?',$discovered.'-12-31')
		->where('finds.datefound1 IS NOT NULL');
	}
	if(isset($params['preservation']) && ($params['preservation'] != "")){
	$preservation = $params['preservation'];
	$select->where('finds.preservation = ?', $preservation);	
	$rowCount->where('finds.preservation = ?', $preservation);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->getAdapter()->setRowCount($rowCount);
	$paginator->setItemCountPerPage(30) 
		->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")){
	$paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}


	public function getMapSearchResultsExport($params,$limit) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array('id', 'old_findID', 'objecttype', 'broadperiod',
		'dateFrom' => 'numdate1', 'dateTo' => 'numdate2', 
		'created', 'description' => 'IFNULL(finds.description,"No description recorded")', 'findofnote',
		'secwfstage','updated'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county', 'declat', 'declong', 
		'easting', 'northing', 'fourFigure'))
		->joinLeft('coins','finds.secuid = coins.findID',array())
//		->joinLeft('regions','findspots.regionID = regions.id',array('region'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id',array())
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID','filename'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir'))
		->where('findspots.declat IS NOT NULL')
		->where('findspots.declong IS NOT NULL');
		## Find specific query formation
	if(isset($params['createdby']) && ($params['createdby'] != "")) {
	$created = $params['createdby'];
	$select->where('finds.createdBy = ?', $created);
	}
	//Old_findID
	if(isset($params['old_findID']) && ($params['old_findID'] != ""))  {
		$old_findID = $params['old_findID'];
		$select->where('old_findID = ?', $old_findID);
		}
		//Objecttype
		if(isset($params['objecttype']) && ($params['objecttype'] != ""))  
		{
		$objecttype = $params['objecttype'];
		$select->where('objecttype = ?', $objecttype);
		}
		//wear for coins
		//Created on exactly
		//Created on exactly
		if(isset($params['created']) && ($params['created'] != "")) 
		{
		$created = $params['created'];
		$select->where('DATE(finds.created) = ?', $created);
		}
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
		$select->where('broadperiod = ?', $broadperiod);
		}
		//Period From date
		if(isset($params['periodfrom']) && ($params['periodfrom'] != ""))  
		{
		$periodfrom = $params['periodfrom'];
		$select->where('finds.objdate1period = ?', $periodfrom);
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
		//Finder
		if(isset($params['finderID']) && ($params['finderID'] != "")) 
		{
		$finder = $params['finderID'];
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
		//Recorded by
		if(isset($params['recorderID']) && ($params['recorderID'] != "")) 
		{
		$recordby = $params['recorderID'];
		$select->where('finds.recorderID = ?', $recordby);
		}
		//Created on
		if(isset($params['createdAfter']) && ($params['createdAfter'] != "")) 
		{
		$createdAfter = $params['createdAfter'];
		$select->where('finds.created >= ?', $createdAfter);
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
		$select->joinLeft('decmethods','finds.decmethod = decmethods.id',array('decoration' => 'term'))
		->where('finds.decmethod = ?', $decoration);
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
		$select->joinLeft('findofnotereasons','finds.findofnotereason = findofnotereasons.id',array('term'))
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
	if(isset($params['ruler']) && ($params['ruler'] != "")) {
	$ruler = $params['ruler'];
	$select->joinLeft('rulers','rulers.id = coins.ruler_id',array('issuer'))
		->where('coins.ruler_id = ?', $ruler);
	}
		//Secondary ruler
		if(isset($params['ruler2']) && ($params['ruler2'] != "")) 
		{
		$ruler2 = $params['ruler2'];
		$select->joinLeft('rulers','rulers.id = coins.ruler2_id',array('issuer'))
->where('coins.ruler2_id = ?', $ruler2);
		}
		//Denomination
		if(isset($params['denomination']) && ($params['denomination'] != "")) 
		{
		$denomname = $params['denomination'];
		$select->joinLeft('denominations','denominations.id = coins.denomination',array('denomination'))->where('coins.denomination = ?', $denomname);
		}
		//Mint
		if(isset($params['mint']) && ($params['mint'] != "")) 
		{
		$mint = $params['mint'];
		$select->joinLeft('mints','mints.id = coins.mint_id',array('mint_name'))->where('coins.mint_id = ?', $mint);
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
		if(isset($params['reeceID']) && ($params['reeceID'] != "")) {
		$reeceID = $params['reeceID'];
		$select->where('coins.reeceID = ?', $reeceID);
		}
		//Reverse type
		if(isset($params['reverse']) && ($params['reverse'] != "")) 
		{
		$reverse = $params['reverse'];
		$select->where('coins.revtypeID = ?', $reverse);
		}
		if(isset($params['woeid']) && ($params['woeid'] != ""))  {
		$woeid = $params['woeid'];
		$select->where('findspots.woeid = ?', $woeid);
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
		$select->where('findspots.county = ?', $county)
		->where('findspots.declong IS NOT NULL');
		}
		//District
		if(isset($params['district']) && ($params['district'] != "")) 
		{
		$district = $params['district'];
		$select->where('findspots.district = ?', $district);
		}
		//Parish
		if(isset($params['parish']) && ($params['parish'] != "")) 
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
		if(isset($params['discovered']) && ($params['discovered'] != "")) 
		{
		$discovered = $params['discovered'];
		$select->where('finds.datefound1 >= ?',$discovered.'-01-01')
		->where('finds.datefound1 <= ?',$discovered.'-12-31')
		->where('finds.datefound1 IS NOT NULL');
		}
		$select->order('finds.id DESC')
			//->where('secwfstage NOT IN ( 1, 2 )')
			->order('finds.created DESC')
			->group('finds.id')
			->limit($limit);
			
			
		if(in_array($this->getRole(),$this->restricted))
		{
		$select->where('finds.secwfstage > 2 ')
				->where('findspots.knownas IS NULL');
		}
       return $finds->fetchAll($select);
		  
}


		  
	/** Get finds entered by user per quarter as a count and sum
	* @param integer $staffID The user's ID
	* @return array
	*/	  
	public function getFindsFloQuarter($staffID){
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('year' => 'EXTRACT(YEAR FROM finds.created)',
		'artefacts' => 'SUM(quantity)', 'records' => 'COUNT(*)', 'quarter' => 'QUARTER(finds.created)'))
		->joinLeft('staff','staff.dbaseID = finds.createdBy', array())
		->where('staff.id = ?',(int)$staffID)
		->order(array('year','quarter'))
		->group('quarter')
		->group('year');
	return $finds->fetchAll($select); 
	}

	/** Get finds entered by user per broadperiod as a count and sum
	* @param integer $staffID The user's ID
	* @return array
	*/	  
	public function getFindsFloPeriod($staffID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('artefacts' => 'SUM(quantity)', 'records' => 'COUNT(*)', 'broadperiod'))
		->joinLeft('staff','staff.dbaseID = finds.createdBy',array('id' => 'dbaseID' ))
		->where('broadperiod IS NOT NULL')
		->where('staff.id = ?',(int)$staffID)
		->group('broadperiod');
	return $finds->fetchAll($select);
	}
	
	/** Get finds entered by user where broadperiod is set
	* @param integer $staffID The user's ID
	* @return array
	*/	  
	public function getFindsRecorded($userID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('artefacts' => 'SUM(quantity)', 'records' => 'COUNT(*)', 'broadperiod'))
		->where('broadperiod IS NOT NULL')
		->where('createdBy = ?',(int)$userID)
		->group('broadperiod');
	return $finds->fetchAll($select);
	}

	/** Get finds entered by user 
	* @param integer $userID The user's ID
	* @return array
	*/	
	public function getTotalFindsRecorded($userID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('artefacts' => 'SUM(quantity)', 'records' => 'COUNT(*)'))
		->where('broadperiod IS NOT NULL')
		->where('createdBy = ?',(int)$userID);
	return $finds->fetchAll($select);
	}
	
	/** Get all cases where treasure status is set 
	* @return array
	*/	
	public function getTreasureCases() {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('createdOn' => 'DATE_FORMAT(finds.created,"%Y-%m-%d")'))
		->where('finds.treasure = ?',(int)1)
		->group('createdOn');
	return $finds->fetchAll($select);
	}

	/** Get all finds by a day 
	* @return array
	* @todo this will die soon!
	*/	
	public function getFindsByDay() {
 	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('createdOn' => 'DATE_FORMAT(finds.created,"%Y-%m-%d")'))
		->group('createdOn');
	return $finds->fetchAll($select);
	}

	/** Get total for reports by date
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	* @todo this could be refactored for a cleaner query
	*/	
	public function getReportTotals($datefrom, $dateto){
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)'))
		->where('created >= ?', (string)$datefrom)
		->where('created <= ?', (string)$dateto);
       return $finds->fetchAll($select);
	}

	/** Get finds officer totals by fullname for reports by date
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	* @todo this could be refactored for a cleaner query
	*/	
	public function getOfficerTotals($datefrom, $dateto){
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)'))
		->joinLeft('users','users.id = finds.createdBy',array('fullname','institution','id'))
		->where($this->_name . '.created >= ?', (string)$datefrom)
		->where($this->_name . '.created <= ?',(string) $dateto)
		->order('institution','fullname')
		->group('fullname','institution');
	return $finds->fetchAll($select);
	}

	/** Get institutional totals for reports by date
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	* @todo this could be refactored for a cleaner query
	*/	
	public function getInstitutionTotals($datefrom, $dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)'))
		->joinLeft('users','users.id = finds.createdBy', array('institution'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('institution')
		->group('institution');
	return $finds->fetchAll($select);
	}
	
	/** Get broadperiod totals for reports by date
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	* @todo this could be refactored for a cleaner query
	*/	
	public function getPeriodTotals($datefrom, $dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)','broadperiod'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->group('broadperiod');
	return $finds->fetchAll($select);
	}

	/** Get distinct finder totals for reports by date
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	* @todo this could be refactored for a cleaner query
	*/	
	public function getFindersTotals($datefrom,$dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array())
		->joinLeft('people','people.secuid = finds.finderID',array('finders' => 'COUNT(DISTINCT(finderID))'))
		->joinLeft('users','users.id = finds.createdBy',array('institution'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('institution')
		->group('institution');
	return $finds->fetchAll($select);
	}
	
	/** Get monthly count of finds found
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	* @todo this could be refactored for a cleaner query
	*/	
	public function getAverageMonth($datefrom,$dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)','broadperiod',
		'month' => 'EXTRACT(MONTH FROM created)'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('month ASC')
		->group('month');
	return $finds->fetchAll($select);
	}

	/** Get discovery year for finds found between certain dates
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	* @todo this could be refactored for a cleaner query
	*/	
	public function getYearFound($datefrom,$dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)','broadperiod',
		'year' => 'EXTRACT(YEAR FROM datefound1)'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('year ASC')
		->group('year');
	return $finds->fetchAll($select);
	}

	/** Get discovery method counts for finds found between certain dates
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	* @todo this could be refactored for a cleaner query
	*/	
	public function getDiscoveryMethod($datefrom,$dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)'))
		->joinLeft('discmethods','discmethods.id = finds.discmethod', array('discmethod' => 'method','id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('discmethod')
		->group('discmethod');
	return $finds->fetchAll($select);
	}
	
	/** Get landuse counts for finds found between certain dates
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	* @todo this could be refactored for a cleaner query
	*/	

	public function getLandUse($datefrom,$dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array())
		->joinLeft('landuses','landuses.id = findspots.landusevalue',array('landuse' => 'term'))
		->where($this->_name.'.created >= ?', $datefrom)
		->where($this->_name.'.created <= ?', $dateto)
		->order('landuse')
		->group('landuse');
	return $finds->fetchAll($select);
	}

	/** Get landuse counts for finds found between certain dates
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	*/	
	public function getPrecision($datefrom,$dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array('precision' => 'gridlen'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('precision')
		->group('precision');
	return $finds->fetchAll($select);
	}
	
	/** Get landuse counts for finds found between certain dates
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	*/	

	public function getCounties($datefrom, $dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name.'.secuid = findspots.findID',array('county'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('county')
		->group('county');
	return $finds->fetchAll($select);
	}

	/** Get counts for finds found between certain dates for counties
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $county The specific county to query
	* @return array
	*/
	public function getCountyStat($datefrom,$dateto,$county) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array('county'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('findspots.county = ?',(string)$county)
		->order('county')
		->group('county');
	return $finds->fetchAll($select);
	}

	/** Get counts for finds found between certain dates for counties by specific user
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $county The specific county to query
	* @return array
	*/
	public function getUsersStat($datefrom,$dateto,$county) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array())
		->joinLeft('users',$this->_name . '.createdBy = users.id',array('fullname','username','institution','id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('findspots.county = ?',(string)$county)
		->order('institution')
		->group('fullname');
	return $finds->fetchAll($select);
	}

	/** Get counts for finds found between certain dates for periods and county
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $county The specific county to query
	* @return array
	*/
	public function getPeriodTotalsCounty($datefrom, $dateto,$county) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)','broadperiod'))
		->joinLeft('findspots',$this->_name.'.secuid = findspots.findID',array())
		->where($this->_name.'.created >= ?', $datefrom)
		->where($this->_name.'.created <= ?', $dateto)
		->where('findspots.county = ?',(string)$county)
		->group('broadperiod');
	return $finds->fetchAll($select);
	}

	/** Get counts for finders by county
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $county The specific county to query
	* @return array
	*/
	public function getFinderTotalsCounty($datefrom, $dateto,$county) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array())
		->joinLeft('people','people.secuid = finds.finderID',array('finders' => 'COUNT(DISTINCT(finderID))'))
		->joinLeft('users','users.id = finds.createdBy',array('institution'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array())
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('findspots.county = ?',(string)$county)
		->order('institution')
		->group('institution');
	return $finds->fetchAll($select);
	}
	
	/** Get finds per month by county
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $county The specific county to query
	* @return array
	*/
	public function getAverageMonthCounty($datefrom,$dateto,$county) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)','broadperiod', 
		'month' => 'EXTRACT(MONTH FROM '.$this->_name.'.created)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array())
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('findspots.county = ?',(string)$county)
		->order('month ASC')
		->group('month');
	return $finds->fetchAll($select);
	}

	/** Get finds per year by county
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $county The specific county to query
	* @return array
	*/
	public function getYearFoundCounty($datefrom,$dateto,$county) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)','broadperiod', 'year' => 'EXTRACT(YEAR FROM datefound1)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array())
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('findspots.county = ?',(string)$county)
		->order('year ASC')
		->group('year');
	return $finds->fetchAll($select);
	}
	
	/** Get discovery method totals by county
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $county The specific county to query
	* @return array
	*/
	public function getDiscoveryMethodCounty($datefrom,$dateto,$county) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array())
		->joinLeft('discmethods','discmethods.id = finds.discmethod',array('discmethod' => 'method','id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('findspots.county = ?',(string)$county)
		->order('discmethod')
		->group('discmethod');
	return $finds->fetchAll($select);
	}
	
	/** Get landuse totals by county
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $county The specific county to query
	* @return array
	*/
	public function getLandUseCounty($datefrom,$dateto,$county) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array())
		->joinLeft('landuses','landuses.id = findspots.landusevalue',array('landuse' => 'term','id'))
		->where('findspots.county = ?',(string)$county)
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('landuse')
		->group('landuse');
	return $finds->fetchAll($select);
	}

	/** Get precision of findspot by county
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $county The specific county to query
	* @return array
	*/
	public function getPrecisionCounty($datefrom,$dateto,$county) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name.'.secuid = findspots.findID',array('precision' => 'gridlen'))
		->where('findspots.county = ?',(string)$county)
		->where($this->_name.'.created >= ?', $datefrom)
		->where($this->_name.'.created <= ?', $dateto)
		->order('precision')
		->group('precision');
	return $finds->fetchAll($select);
	}

	/** Get recording institutions between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	*/
	public function getInstitutions($datefrom,$dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('users',$this->_name . '.createdBy = users.id', array('institution'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('institution')
		->group('institution');
	return $finds->fetchAll($select);
	}
	
	/** Get  institution's recording stats between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $institution The specific institution
	* @return array
	*/
	public function getInstStat($datefrom,$dateto,$institution) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('users',$this->_name . '.createdBy = users.id', array('institution'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('users.institution = ?', (string)$institution)
		->order('institution')
		->group('institution');
	return $finds->fetchAll($select);
	}
	
	
	/** Get institution's recording user stats between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $institution The specific institution
	* @return array
	*/
	public function getUsersInstStat($datefrom,$dateto,$institution){
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('users',$this->_name . '.createdBy = users.id',array('fullname','username','institution','id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('users.institution = ?',(string)$institution)
		->order('institution')
		->group('fullname');
	return $finds->fetchAll($select);
	}
	
	/** Get institution's recording period totals between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $institution The specific institution
	* @return array
	*/
	public function getPeriodTotalsInst($datefrom,$dateto,$institution) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)','broadperiod'))
		->joinLeft('users',$this->_name . '.createdBy = users.id', array('fullname','username','institution','id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('users.institution = ?',(string)$institution)
		->order('broadperiod')
		->group('broadperiod');
	return $finds->fetchAll($select);
	}

	/** Get institution's number of finders between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $institution The specific institution
	* @return array
	*/
	public function getFinderTotalsInst($datefrom,$dateto,$institution) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array())
		->joinLeft('people','people.secuid = finds.finderID',array('finders' => 'COUNT(DISTINCT(finderID))'))
		->joinLeft('users','users.id = finds.createdBy',array('institution'))
		->where($this->_name.'.created >= ?', $datefrom)
		->where($this->_name.'.created <= ?', $dateto)
		->where('users.institution = ?',(string)$institution)
		->order('institution')
		->group('institution');
	return $finds->fetchAll($select);
	}
	
	/** Get institution's year of discovery range between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $institution The specific institution
	* @return array
	*/
	public function getYearFoundInst($datefrom,$dateto,$institution) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)','broadperiod', 
		'year' => 'EXTRACT(YEAR FROM datefound1)'))
		->joinLeft('users','users.id = finds.createdBy', array('institution'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('users.institution = ?',(string)$institution)
		->order('year ASC')
		->group('year');
       return $finds->fetchAll($select);
	}
	
	/** Get institution's method of discovery range between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $institution The specific institution
	* @return array
	*/
	public function getDiscoveryMethodInst($datefrom,$dateto,$institution) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('discmethods','discmethods.id = finds.discmethod',array('discmethod' => 'method'))
		->joinLeft('users','users.id = finds.createdBy',array('institution'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('users.institution = ?',(string)$institution)
		->order('discmethod')
		->group('discmethod');
	return $finds->fetchAll($select);
	}
	
	/** Get institution's land uses range between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $institution The specific institution
	* @return array
	*/
	public function getLandUseInst($datefrom,$dateto,$institution) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('users','users.id = finds.createdBy',array('institution'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID',array())
		->joinLeft('landuses','landuses.id = findspots.landusevalue',array('landuse' => 'term','id'))
		->where('users.institution = ?',(string)$institution)
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('landuse')
		->group('landuse');
	return $finds->fetchAll($select);
	}

	/** Get institution's land uses range between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $institution The specific institution
	* @return array
	*/
	public function getPrecisionInst($datefrom,$dateto,$institution) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name.'.secuid = findspots.findID', array('precision' => 'gridlen'))
		->joinLeft('users','users.id = finds.createdBy', array('institution'))
		->where('users.institution = ?', (string)$institution)
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('precision')
		->group('precision');
	return $finds->fetchAll($select);
	}

	/** Get institution's monthly records and sum recorded between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param string $institution The specific institution
	* @return array
	*/
	public function getAverageMonthInst($datefrom,$dateto,$institution) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)','broadperiod', 
		'month' => 'EXTRACT(MONTH FROM finds.created)'))
		->joinLeft('users','finds.createdBy = users.id', array('fullname'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('users.institution = ?', (string)$institution)
		->order('month')
		->group('month');
	return $finds->fetchAll($select);
	}

	/** Get recording regions between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @return array
	*/
	public function getRegions($datefrom,$dateto) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID', array())
		->joinLeft('regions','findspots.regionID = regions.id', array('region' , 'id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('region')
		->group('region');
	return $finds->fetchAll($select);
	}

	/** Get regions' figures between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param integer $regionID
	* @return array
	*/
	public function getRegionStat($datefrom,$dateto,$regionID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID', array('county'))
		->joinLeft('regions','findspots.regionID = regions.id', array('region', 'id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('regions.id = ?',(int)$regionID)
		->order('county')
		->group('county');
	return $finds->fetchAll($select);
	}
	
	/** Get users recording by a region between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param integer $regionID The recording region
	* @return array
	*/
	public function getUsersRegionStat($datefrom,$dateto,$regionID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID', array())
		->joinLeft('regions','findspots.regionID = regions.id', array('region'))
		->joinLeft('users',$this->_name . '.createdBy = users.id', array('fullname', 'username', 'institution', 'id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('regions.id = ?',(integer)$regionID)
		->order('institution')
		->group('fullname');
	return $finds->fetchAll($select);
	}

	/** Get broadperiods by a region between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param integer $regionID The recording region
	* @return array
	*/
	public function getPeriodTotalsRegion($datefrom,$dateto,$regionID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)', 'broadperiod'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID', array())
		->joinLeft('regions','findspots.regionID = regions.id', array('region','id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('regions.id = ?', (integer)$regionID)
		->order('broadperiod')
		->group('broadperiod');
	return $finds->fetchAll($select);
	}

	/** Get finder totals by a region between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param integer $regionID The recording region
	* @return array
	*/
	public function getFinderTotalsRegion($datefrom,$dateto,$regionID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array())
		->joinLeft('people','people.secuid = finds.finderID', array('finders' => 'COUNT(DISTINCT(finderID))'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID', array('county'))
		->joinLeft('regions','findspots.regionID = regions.id', array('region'))
		->joinLeft('users',$this->_name . '.createdBy = users.id',array('fullname', 'username', 'institution','id'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('regions.id = ?', (string)$regionID)
		->order('institution')
		->group('institution');
	return $finds->fetchAll($select);
	}
	
	/** Get year of discovery by a region between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param integer $regionID The recording region
	* @return array
	*/
	public function getYearFoundRegion($datefrom,$dateto,$regionID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)','broadperiod', 
		'year' => 'EXTRACT(YEAR FROM datefound1)'))
		->joinLeft('findspots',$this->_name.'.secuid = findspots.findID', array('county'))
		->joinLeft('regions','findspots.regionID = regions.id', array('region','id'))
		->where($this->_name.'.created >= ?', $datefrom)
		->where($this->_name.'.created <= ?', $dateto)
		->where('regions.id = ?',(integer)$regionID)
		->order('year ASC')
		->group('year');
	return $finds->fetchAll($select);
	}
	
	/** Get finder totals by a region between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param integer $regionID The recording region
	* @return array
	*/
	public function getDiscoveryMethodRegion($datefrom,$dateto,$regionID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('discmethods','discmethods.id = finds.discmethod', array('discmethod' => 'method','id'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID', array('county'))
		->joinLeft('regions','findspots.regionID = regions.id', array('region'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('regions.id = ?', (integer)$regionID)
		->order('discmethod')
		->group('discmethod');
	return $finds->fetchAll($select);
	}
	
	/** Get landuse totals by a region between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param integer $regionID The recording region
	* @return array
	*/
	public function getLandUseRegion($datefrom,$dateto,$regionID){
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID', array('county'))
		->joinLeft('regions','findspots.regionID = regions.id', array('region'))
		->joinLeft('landuses','landuses.id = findspots.landusevalue', array('landuse' => 'term'))
		->where('regions.id = ?',(integer)$regionID)
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('landuse')
		->group('landuse');
       return $finds->fetchAll($select);
	}

	/** Get findspot precision by a region between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param integer $regionID The recording region
	* @return array
	*/
	public function getPrecisionRegion($datefrom,$dateto,$regionID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
		->joinLeft('findspots',$this->_name.'.secuid = findspots.findID', array('precision' => 'gridlen'))
		->joinLeft('regions','findspots.regionID = regions.id', array('region'))
		->where('regions.id = ?', (integer)$regionID)
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->order('precision')
		->group('precision');
	return $finds->fetchAll($select);
	}

	/** Get monthly totals by region between dates	
	* @param string $datefrom The first date
	* @param string $dateto The second date 
	* @param integer $regionID The recording region
	* @return array
	*/
	public function getAverageMonthRegion($datefrom,$dateto,$regionID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)','broadperiod', 
		'month' => 'EXTRACT(MONTH FROM finds.created)'))
		->joinLeft('findspots',$this->_name . '.secuid = findspots.findID', array('county'))
		->joinLeft('regions','findspots.regionID = regions.id', array('region'))
		->where($this->_name . '.created >= ?', $datefrom)
		->where($this->_name . '.created <= ?', $dateto)
		->where('regions.id = ?', (integer)$regionID)
		->order('month')
		->group('month');
	return $finds->fetchAll($select);
	}

	/** Get all finds paginated for the lister
	* @param string $sort The sort method
	* @param array $params 
	* @param string $role The user's role
	* @return array
	*/
	public function getAllFinds($sort,$params,$role) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array( 'id', 'old_findID', 'uniqueID' => 'secuid',
		'objecttype', 'classification', 'subclass',
		'length', 'height', 'width',
		'thickness', 'diameter', 'quantity',
		'other_ref', 'treasureID', 'broadperiod',
		'numdate1', 'numdate2', 'culture',
		'description', 'notes', 'reuse',
		'created' =>'finds.created', 'updated',
		'treasureID', 'secwfstage', 'findofnote',
		'objecttypecert', 'datefound1', 'datefound2',
		'inscription', 'disccircum', 'museumAccession' => 'musaccno',
		'subsequentAction' => 'subs_action', 'objectCertainty' => 'objecttypecert',
		'dateFromCertainty' => 'numdate1qual', 'dateToCertainty' => 'numdate2qual',
		'dateFoundFromCertainty' => 'datefound1qual', 'dateFoundToCertainty' => 'datefound2qual',
		'subPeriodFrom' => 'objdate1subperiod', 'subPeriodTo' => 'objdate2subperiod',
		'createdBy'))
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
		->joinLeft('coins','finds.secuid = coins.findID', array('obverse_description', 'obverse_inscription', 
		'reverse_description', 'reverse_inscription', 'denomination',
		'degree_of_wear', 'allen_type', 'va_type',
		'mack' => 'mack_type', 'reeceID', 'die' => 'die_axis_measurement',
		'wearID'=> 'degree_of_wear', 'moneyer', 
		'revtypeID', 'categoryID', 'typeID',
		'tribeID' => 'tribe', 'status', 'rulerQualifier' => 'ruler_qualifier',
		'denominationQualifier' => 'denomination_qualifier', 'mintQualifier' => 'mint_qualifier',
		'dieAxisCertainty' => 'die_axis_certainty', 'initialMark' => 'initial_mark', 
		'reverseMintMark' => 'reverse_mintmark', 'statusQualifier' => 'status_qualifier'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array())
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID', 'f' => 'filename')) 
		->joinLeft('ironagetribes','coins.tribe = ironagetribes.id', array('tribe'))
		->joinLeft('geographyironage','geographyironage.id = coins.geographyID', array('region','area'))
		->joinLeft('denominations','denominations.id = coins.denomination', array('denomination'))
		->joinLeft('rulers','rulers.id = coins.ruler_id', array('ruler1' => 'issuer'))
		->joinLeft('revtypes','coins.revtypeID = revtypes.id', array('reverseType' => 'type'))
		->joinLeft('statuses','coins.status = statuses.id', array('status' => 'term'))
		->joinLeft('reeceperiods','coins.reeceID = reeceperiods.id', array('period_name','date_range'))
		->joinLeft('mints','mints.id = coins.mint_ID', array('mint_name'))
		->joinLeft('weartypes','coins.degree_of_wear = weartypes.id', array('wear' => 'term'))
		->joinLeft('medievalcategories','medievalcategories.id = coins.categoryID', array('category'))
		->joinLeft('medievaltypes','medievaltypes.id = coins.typeID', array('type'))
		->group('finds.id')
		->order('finds.id DESC');
	$paginator = Zend_Paginator::factory($select);
	##Rowcount for pagination query
	$rowCount = $finds->select()->from($this->_name);		
	$rowCount->reset( Zend_Db_Select::COLUMNS )
		->columns( new Zend_Db_Expr( 'COUNT(*) AS ' . Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN ));
	if(in_array($role,$this->_restricted)) {
	$select->joinLeft('findspots','finds.secuid = findspots.findID', array('county','district','knownas',))
		->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc', array('gridRefSource' => 'term'));
	} else {
	$select	->joinLeft('people','finds.finderID = people.secuid', array('finder' => 'CONCAT(people.title," ",people.forename," ",people.surname)'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid', array('identifier' => 'CONCAT(ident1.title," ",ident1.forename," ",ident1.surname)'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid', array('secondaryIdentifier' => 'CONCAT(ident2.title," ",ident2.forename," ",ident2.surname)'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid', array('recorder' => 'CONCAT(record.title," ",record.forename," ",record.surname)'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county','parish','district','gridref','fourFigure','easting','northing','map25k','map10k','address','postcode','findspotdescription' => 'description','lat' => 'declat','lon' => 'declong'))->joinLeft('gridrefsources','gridrefsources.ID = findspots.gridrefsrc',array('source' => 'term'));
	}
	if(in_array($role,$this->_restricted)){
	$select->where(new Zend_Db_Expr('finds.secwfstage > 2 OR finds.createdBy = ' . (int)$this->getIdentityForForms()));
	$rowCount->where(new Zend_Db_Expr('finds.secwfstage > 2 OR finds.createdBy = ' . (int)$this->getIdentityForForms()));
	}
	if(isset($params['old_findID']) && ($params['old_findID'] != "")) {
	$findID = strip_tags($params['old_findID']);
	$select->where('old_findID LIKE ?', '%' . (string)$findID . '%');
	$rowCount->where('old_findID LIKE ?', (string)'%' . $findID . '%');
	}
	if(isset($params['broadperiod']) && ($params['broadperiod'] != "")) {
	$broadperiod = strip_tags($params['broadperiod']);
	$select->where('broadperiod = ?', (string)$broadperiod);
	$rowCount->where('broadperiod = ?', (string)$broadperiod);
	}
	if(isset($params['objecttype']) && ($params['objecttype'] != "")) {
//	Why am I stripping tags here? Input filtered already elsewhere.
	$objecttype = strip_tags($params['objecttype']);
	$select->where('objecttype = ?', (string)$objecttype);
	$rowCount->where('objecttype = ?', (string)$objecttype);
	}
	if(isset($params['county']) && ($params['county'] != "")) {
	$county = strip_tags($params['county']);
	$select->where('findspots.county = ?', (string)$county);
	$rowCount->joinLeft('findspots','finds.secuid = findspots.findID',array())
		->where('findspots.county = ?', (string)$county);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->getAdapter()->setRowCount($rowCount);
	$paginator->setItemCountPerPage(30) 
		->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']); 
	}
	return $paginator;
	}

	/** Get all finds within a distance of a point
	* @param double $lat the decimal degree latitude
	* @param double $long The decimal degree longitude
	* @param integer $distance The radial distance from the point
	* @return array
	*/
	public function getByLatLong($lat,$long,$distance) {		
	$pi = '3.141592653589793';
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('broadperiod', 'i' => 'id', 'objecttype', 'old_findID'))
		->joinLeft('findspots','finds.secuid = findspots.findID',array('id', 'declat', 'declong', 
		'distance' => 'acos((SIN(' . $pi . '*' . $lat . '/180 ) * SIN(' . $pi 
		. '* declat /180)) + (cos(' 
		. $pi . '*' . $lat . '/180) * COS(' . $pi . '* declat/180) * COS(' . $pi . '* declong/180 - ' 
		. $pi . '* (' . $long . ') /180))) *6378.137'))
		->where('6378.137 * ACOS((SIN(' . $pi .'*' . $lat . '/180) * SIN('
		. $pi . '* declat/180)) + (COS(' . $pi . '*' . $lat . '/180) * cos('
		. $pi . '* declat /180 ) * COS(' . $pi . '* declong /180 - ' . $pi
		. '* ( ' . $long . ')/180))) <=' . $distance)
		->where('1=1')
//		->order('distance');
		->order('6378.137 * ACOS((SIN(' . $pi . '*' . $lat . '/180 ) * SIN(' 
		. $pi . '* declat/180)) + (COS(' . $pi . '*' . $lat . '/180) * cos(' 
		. $pi . ' * declat /180 ) * COS(' . $pi . '* declong /180 - ' . $pi 
		. '*  (' . $long . ' )/180))) ASC');
	return $finds->fetchAll($select);
	}

	/** Get a paginated list of user's finds
	* @param integer $id The user's id
	* @param array $params 
	* @return array
	*/
	public function getMyFindsUser($id,$params) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from('finds',array('id', 'objecttype', 'broadperiod', 'old_findID', 
		'description', 'createdBy', 'secwfstage',
		'uniqueID' => 'secuid'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array()) 
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID', 'f' => 'filename')) 
		->joinLeft('users','users.id = finds.createdBy', array('username'))
		->where('finds.createdBy = ?', (int)$id)
		->order('finds.id DESC')
		->group('finds.id');
	$rowCount = $finds->select()->from( 'finds' )
		->where('finds.createdBy = ?', (int)$id)
		->reset( Zend_Db_Select::COLUMNS )
		->columns( new Zend_Db_Expr( 'COUNT(*) AS '. Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN ));
	if(isset($params['old_findID']) && ($params['old_findID'] != "")) {
	$findID = strip_tags($params['old_findID']);
	$select->where('old_findID LIKE ?', (string)'%' . $findID . '%');
	$rowCount->where('old_findID LIKE ?', (string)'%' . $findID . '%');
	}
	if(isset($params['broadperiod']) && ($params['broadperiod'] != "")) {
	$broadperiod = strip_tags($params['broadperiod']);
	$select->where('broadperiod = ?', (string)$broadperiod);
	$rowCount->where('broadperiod = ?', (string)$broadperiod);
	}
	if(isset($params['objecttype']) && ($params['objecttype'] != "")) {
	$objecttype = strip_tags($params['objecttype']);
	$select->where('objecttype = ?', (string)$objecttype);
	$rowCount->where('objecttype = ?', (string)$objecttype);
	}
	if(isset($params['county']) && ($params['county'] != "")) {
	$county = strip_tags($params['county']);
	$select->where('findspots.county = ?', (string)$county);
	$rowCount->joinLeft('findspots','finds.secuid = findspots.findID',array())
		->where('findspots.county = ?', (string)$county)
		->group('finds.id');
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->getAdapter()->setRowCount($rowCount);
	$paginator->setItemCountPerPage(30) 
		->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}

	/** Get a paginated list of institution's finds
	* @param string $inst The institution chosen
	* @param array $params 
	* @return array
	*/
	public function getMyFindsInstitution($inst,$params) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from('finds',array('id', 'objecttype', 'broadperiod',
		'old_findID', 'description', 'createdBy',
		'uniqueID' => 'secuid', 'secwfstage'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array()) 
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID','f' => 'filename')) 
		->joinLeft('users','users.id = finds.createdBy', array('username'))
		->where('finds.old_findID LIKE ?', (string)$inst . '%')
		->order('finds.id DESC')
		->group('finds.id');
	$rowCount = $finds->select()->from( 'finds' )
		->where('finds.old_findID LIKE ?', (string)$inst . '%')
		->reset( Zend_Db_Select::COLUMNS )
		->columns( new Zend_Db_Expr( 'COUNT(*) AS ' . Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN ));
	if(in_array($this->getRole(),$this->restricted)) {
	$select->where(new Zend_Db_Expr('finds.secwfstage IN ( 3, 4) OR finds.createdBy = '
	. (int)$this->getIdentityForForms()));
	$rowCount->where(new Zend_Db_Expr('finds.secwfstage IN ( 3, 4) OR finds.createdBy = '
	. (int)$this->getIdentityForForms()));
	}			
	if(isset($params['old_findID']) && ($params['old_findID'] != ""))  {
	$findID = strip_tags($params['old_findID']);
	$select->where('old_findID LIKE ?', (string)'%' . $findID . '%');
	$rowCount->where('old_findID LIKE ?', (string)'%' . $findID . '%');
	}
	if(isset($params['broadperiod']) && ($params['broadperiod'] != "")) {
	$broadperiod = strip_tags($params['broadperiod']);
	$select->where('broadperiod = ?', (string)$broadperiod);
	$rowCount->where('broadperiod = ?', (string)$broadperiod);
	}
	if(isset($params['objecttype']) && ($params['objecttype'] != "")) {
	$objecttype = strip_tags($params['objecttype']);
	$select->where('objecttype = ?', (string)$objecttype);
	$rowCount->where('objecttype = ?', (string)$objecttype);
	}
	if(isset($params['county']) && ($params['county'] != "")){
	$county = strip_tags($params['county']);
	$select->where('findspots.county = ?', (string)$county);
	$rowCount->joinLeft('findspots','finds.secuid = findspots.findID',array())
		->where('findspots.county = ?', (string)$county)
		->group('finds.id');
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->getAdapter()->setRowCount($rowCount);
	$paginator->setItemCountPerPage(30) 
		->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}

	/** Get a treasure ID
	* @param string $q
	* @return array
	*/
	public function getTreasureID($q) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('id' => 'treasureID','term' => 'treasureID'))
		->where('treasureID LIKE ?', (string)'%' . $q . '%')
		->order('treasureID ASC')
		->group('treasureID')
		->limit(10);
	if(in_array($this->getRole(),$this->_restricted)){
	$select->where('finds.secwfstage > ?', (int)2);
	}
	return $finds->fetchAll($select);
	}

	/** Get other references
	* @param string $q
	* @return array
	*/
	public function getOtherRef($q) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('id' => 'other_ref','term' => 'other_ref'))
		->where('other_ref LIKE ?', (string)$q . '%')
		->limit(10);
	if(in_array($this->getRole(),$this->_restricted)){
	$select->where('finds.secwfstage > ?', (int)2);
	}
	return $finds->fetchAll($select);
	}

	/** Get a person's finds 
	* @param array $params
	* @return array
	*/
	public function getFindsToPerson($params) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array('uniqueID' => 'secuid', 'objecttype', 'broadperiod',
		'id', 'old_findID', 'description', 'secwfstage'))
		->joinLeft('people','finds.finderID = people.secuid', array())
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array()) 
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID','f' => 'filename')) 
		->joinLeft('users','users.id = finds.createdBy', array('username'))	
		->joinLeft('findspots','findspots.findID = finds.secuid', array('county'))
		->where('people.id = ?', (int)$params['id'])
		->group('finds.id');
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?', (int)2);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(10) 
		->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']); 
	}
	return $paginator;		 
	}
	
	/** Check if a findspot exists
	* @param integer $findspotID The findspot ID
	* @return array
	*/
	public function getFindtoFindspots($findspotID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name)
		->joinLeft('findspots','finds.secuid = findspots.findID',array())
		->where('findspots.id = ?' ,(int)$findspotID);
	return $finds->fetchAll($select);
	}


	/** get attached publications for a findspot
	* @param integer $findID The find ID
	* @return array
	*/
	public function getFindtoPublication($findID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array('f' => 'old_findID', 'objecttype','broadperiod','id'))
		->joinLeft('bibliography','finds.secuid = bibliography.findID', array())
		->joinLeft('publications','publications.secuid = bibliography.pubID', array())
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county'))
		->where('publications.id = ?',(int)$findID);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?', (int)2);
	}
	return $finds->fetchAll($select);
	}

	/** get data for embedding a find
	* @param integer $findID The find ID
	* @return array
	*/
	public function getEmbedFind($findID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('broadperiod','id','objecttype','old_findID'))
		->joinLeft('periods','finds.objdate1period = periods.id', array('t' => 'term'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('gridref', 'easting', 'northing',
		'parish', 'county', 'regionID', 
		'district', 'declat', 'declong',
		'smrref', 'map25k', 'map10k',
		'knownas'))
		->where('finds.id= ?',(int)$findID);
	return $finds->fetchAll($select);
	}

	/** get data for citation of a find
	* @param integer $findID The find ID
	* @return array
	*/
	public function getWebCiteFind($findID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array('broadperiod','id','objecttype','old_findID', 'c' => 'DATE_FORMAT(finds.created,"%Y")'))
		->joinLeft('periods','finds.objdate1period = periods.id', array('t' => 'term'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid', array('tit3' => 'title', 
		'fore3' => 'forename','sur3' => 'surname'))
		->where('finds.id= ?',(int)$findID);
	return $finds->fetchAll($select);
	}

	/** get a list of treasure finds
	* @param array $params
	* @return array
	*/
	public function getTreasureFindsList($params){
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from('finds',array('id', 'objecttype', 'broadperiod', 
		'old_findID', 'snippet' =>'LEFT(finds.description,400)', 'treasureID'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array())
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('thumbnail'  => 'slides.imageID',
		'f' => 'filename')) 
		->joinLeft('users','users.id = finds.createdBy', array('username'))
		->where('treasure = ?', (int)1) 
		->order('finds.treasureID DESC');
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > ?', (int)2);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30) 
		->setPageRange(20);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber((int)$params['page']); 
	}
	return $paginator;		 
	}

	/** Retrieve edit data for a find
	* @param integer $findID The find's ID number
	* @return array
	*/
	public function getEditData($findID)  {
	$personals = $this->getAdapter();
	$select = $personals->select()
		->from($this->_name)
		->joinLeft('people','finds.finderID = people.secuid',array('finder'  => 'fullname'))
		->joinLeft(array('people2' => 'people'),'finds.finder2ID = people2.secuid',array('secondfinder' => 'fullname'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid',array('idBy' => 'fullname'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid',array('id2by' => 'fullname'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid',array('recordername' => 'fullname'))
		->where('finds.id = ?', (int)$findID)
		->group('finds.id')
		->limit(1);
	$data = $personals->fetchAll($select);
	return $data;
	}

	/** Retrieve find's numbers and broadperiod
	* @param integer $findID The find's ID number
	* @return array
	*/
	public function getFindNumbersEtc($findID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array('objecttype','id','broadperiod','old_findID'))
		->where('finds.id = ?', (int)$findID)
		->limit(1);
	return $finds->fetchAll($select);
	}

	/** Retrieve finds adviser responsible
	* @param integer $findID The find's ID number
	* @return array
	*/
	public function getRelevantAdviserFind($findID){
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name,array('objecttype','id','broadperiod','old_findID','secuid'))
			->joinLeft('findspots','finds.secuid = findspots.findID', array('county'))
			->where('finds.id = ?',$findID)
			->limit(1);
	return $finds->fetchAll($select);
	}
		
	/** Retrieve find data if allowed access
	* @param integer $findID The find's ID number
	* @param string $role The user's role
	* @return array
	*/
	public function getIndividualFind($findID,$role) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('created2' => 'DATE_FORMAT(finds.created,"%Y %m %d")', 'description',
		'notes', 'old_findID', 'id',
		'objecttype', 'classification', 'subclass',
		'reuse', 'created' =>'finds.created', 'broadperiod',
		'updated', 'treasureID', 'secwfstage',
		'secuid', 'findofnote', 'objecttypecert',
		'datefound1', 'datefound2', 'createdBy',
		'curr_loc', 'inscription'))
		->joinLeft('findofnotereasons','finds.findofnotereason = findofnotereasons.id', array('reason' => 'term'))
		->joinLeft('subsequentActions','finds.subs_action = subsequentActions.id', array('subsequentAction' => 'action'))
		->where('finds.id= ?',(int)$findID);
	if(in_array($role,$this->_restricted)) {
	$select->where(new Zend_Db_Expr('finds.secwfstage IN ( 3, 4) OR finds.createdBy = ' 
	. (int)$this->getIdentityForForms()));
	}
	return  $finds->fetchAll($select);
	}
	
	/** Get attached images
	* @param integer $id The find's ID number
	* @return array
	*/
	public function getImageToFind($id) {
	$cache = Zend_Registry::get('rulercache');
	if (!$data = $cache->load('findtoimage' . $id)) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('old_findID','broadperiod','objecttype'))
		->joinLeft('users','users.id = finds.createdBy', array('imagedir'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array())
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID','f' => 'filename')) 
		->where('finds.id= ?',(int)$id)
		->order('slides.imageID ASC')
		->limit(1);
	$data =  $finds->fetchAll($select);
	$cache->save($data, 'findtoimage'.$id);
	} 
	return $data; 
	}
	
	/** Get the last record created by a specific user
	* @param integer $userid The user's ID
	* @return array
	*/
	public function getLastRecord($userid) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array('description', 'finderID', 'other_ref',
		'datefound1', 'datefound2', 'culture',
		'discmethod', 'disccircum', 'notes',
		'objecttype', 'classification', 'subclass',
		'inscription', 'objdate1period', 'objdate2period',
		'broadperiod', 'numdate1', 'numdate2',
		'material1', 'material2', 'manmethod',
		'decmethod', 'surftreat', 'decstyle',
		'preservation', 'completeness', 'reuse',
		'reuse_period', 'length', 'width', 
		'thickness', 'diameter', 'weight',
		'height', 'quantity', 'curr_loc',
		'recorderID', 'finder2ID', 'identifier1ID',
		'identifier2ID', 'findofnotereason', 'findofnote',
		'numdate1qual', 'numdate2qual','objdate1cert', 
		'objdate2cert',	'treasure', 'treasureID',
		'subs_action', 'musaccno', 'smrrefno',
		'objdate1subperiod','objdate2subperiod' ))
		->joinLeft(array('finderOne' => 'people'),'finderOne.secuid = finds.finderID', 
		array('finder' => 'fullname'))
		->joinLeft(array('finderTwo' => 'people'),'finderTwo.secuid = finds.finder2ID', 
		array('secondfinder' => 'fullname')) 	
		->joinLeft(array('identifier' => 'people'),'identifier.secuid = finds.identifier1ID', 
		array('idby' => 'fullname')) 	
		->joinLeft(array('identifierTwo' => 'people'),'identifierTwo.secuid = finds.identifier2ID', 
		array('id2by' => 'fullname')) 	
		->joinLeft(array('recorder' => 'people'),'recorder.secuid = finds.finderID', 
		array('recordername' => 'fullname')) 	
		->where('finds.createdBy = ?',(int)$userid)
		->order('finds.id DESC')
		->limit(1);
	return $finds->fetchAll($select);
	}
	
	/** Get findID and secuid for linking images
	* @param string $q The query string for the old find ID
	* @return array
	*/
	public function getImageLinkData($q) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('term' => 'old_findID','id' => 'secuid'))
		->where('old_findID LIKE ?', (string)$q . '%')
		->limit(10);
	return $finds->fetchAll($select);
	}
	/** Get creator of a record by ID number
	* @param integer $findID The record ID
	* @return array
	*/
	public function getCreator($findID) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('old_findID','objecttype'))
		->joinLeft('users','users.id = finds.createdBy',array('email','fullname'))
		->where('finds.id = ?', (int)$findID);
	return $finds->fetchAll($select);
	}
	/** Get records for a specific user
	* @param integer $userid The database user account ID
	* @param integer $page The page to retrieve
	* @return array
	*/
	public function getRecordsByUserAcct($userid,$page) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('objecttype', 'broadperiod', 'id',
		'old_findID', 'description', 'secwfstage'))
		->joinLeft('people','finds.finderID = people.secuid', array())
		->joinLeft('findspots','findspots.findID = finds.secuid', array('county'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array()) 
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID','f' => 'filename')) 
		->joinLeft('users','users.id = finds.createdBy', array('username','imagedir'))			
		->where('people.dbaseID = ?' , $userid)
		->group('finds.id');
	if(in_array($this->getRole(),$this->_restricted)){
	$select->where('finds.secwfstage > ?', (int)2);
	}
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(10) 
		->setPageRange(20);
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber((int)$page); 
	}
	return $paginator;		 
	}
	
	/** Retrieve a finder's objects for mapping in KML feed
	* @param string $peopleID The finder unique ID
	* @param integer $limit The limit to return
	* @return array
	*/
	public function getUserMap($peopleID = NULL,$limit){
	if(!is_null($peopleID)) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('id', 'old_findID', 'objecttype',
		'broadperiod', 'dateFrom' => 'numdate1', 'dateTo' => 'numdate2',
		'created', 'description' => 'IFNULL(finds.description,"No description recorded")', 'findofnote',
		'secwfstage', 'updated'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county', 'declat', 'declong',
		 'easting', 'northing'))
		->joinLeft('coins','finds.secuid = coins.findID', array())
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array())
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('i' => 'imageID','filename'))
		->joinLeft('users','users.id = slides.createdBy', array('imagedir'))
		->where('findspots.declat IS NOT NULL')
		->where('findspots.declong IS NOT NULL')
		->where('finds.finderID = ?', (string)$peopleID)
		->limit((int)$limit);
	return  $finds->fetchAll($select);
	}
	}
	
	/** Curl function
	* @return string $output JSON encoded string
	*/
	public function get($url){
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$output = curl_exec($ch); 
	curl_close($ch);
	return $output;
	}

	/** Retrieve finds for a constituency
	* @param string $constituency
	* @return array
	*/
	public function getFindsConstituency($constituency) {
	ini_set("memory_limit","256M");
	$twfy = 'http://www.theyworkforyou.com/api/getGeometry?name=' . urlencode($constituency)
	. '&output=js&key=CzhqDaDMAgkMEcjdvuGZeRtR';
	$data = $this->get($twfy);
	$data = json_decode($data);
	if(array_key_exists('min_lat',$data)) {
	$latmin = $data->min_lat;
	$latmax = $data->max_lat;
	$longmin = $data->min_lon;
	$longmax = $data->max_lon;
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array('id', 'old_findID', 'objecttype',
		'broadperiod', 'dateFrom' => 'numdate1', 'dateTo' => 'numdate2',
		'created', 'description' => 'IFNULL(finds.description,"No description recorded")', 'findofnote',
		'secwfstage', 'updated'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county', 'knownas', 'fourFigure',
		'lat' => 'declat', 'lon' => 'declong', 'easting', 
		'northing'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id',array())
		->joinLeft('slides','slides.secuid = finds_images.image_id',array('i' => 'imageID','filename'))
		->where('declat > ?',$latmin)
		->where('declat < ?',$latmax)
		->where('declong > ?',$longmin)
		->where('declong < ?',$longmax)
		->order('finds.id DESC');
	if(in_array($this->getRole(),$this->_restricted)){
	$select->where('finds.secwfstage > 2');
	}
	return  $finds->fetchAll($select);
	} else {
	return NULL;
	}
	}	

	/** Retrieve finds for a constituency map
	* @param string $constituency
	* @return array
	*/
	public function getFindsConstituencyMap($constituency) {
	ini_set("memory_limit","256M");
	$twfy = 'http://www.theyworkforyou.com/api/getGeometry?name=' . urlencode($constituency)
	. '&output=js&key=CzhqDaDMAgkMEcjdvuGZeRtR';
	$data = $this->get($twfy);
	$data = json_decode($data);
	$latmin = $data->min_lat;
	$latmax = $data->max_lat;
	$longmin = $data->min_lon;
	$longmax = $data->max_lon;
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name, array('id', 'old_findID', 'objecttype',
		'broadperiod', 'dateFrom' => 'numdate1', 'dateTo' => 'numdate2',
		'created', 'description' => 'IFNULL(finds.description,"No description recorded")', 'findofnote',
		'secwfstage', 'updated'))
		->joinLeft('findspots','finds.secuid = findspots.findID',array('county', 'declat', 'declong', 
		'easting', 'northing', 'fourFigure'))
		->where('declat > ?',$latmin)
		->where('declat < ?',$latmax)
		->where('declong > ?',$longmin)
		->where('declong < ?',$longmax)
		->where('fourFigure IS NOT NULL')
		->limit(2000);
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > 2')
		->where('knownas IS NOT NULL');
	}
	return  $finds->fetchAll($select);
	}	

	/** Retrieve finds of note for a constituency
	* @param string $constituency
	* @return array
	*/
	public function getFindsConstituencyNote($constituency) {
	ini_set("memory_limit","256M");
	$twfy = 'http://www.theyworkforyou.com/api/getGeometry?name=' 
	. urlencode($constituency) . '&output=js&key=CzhqDaDMAgkMEcjdvuGZeRtR';
	$data = $this->get($twfy);
	$data = json_decode($data);
	if(array_key_exists('min_lat',$data)) {
	$latmin = $data->min_lat;
	$latmax = $data->max_lat;
	$longmin = $data->min_lon;
	$longmax = $data->max_lon;
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array('id','old_findID', 'objecttype',
		'broadperiod', 'dateFrom' => 'numdate1', 'dateTo' => 'numdate2',
		'created', 'description' => 'IFNULL(finds.description,"No description recorded")', 'findofnote',
		'secwfstage', 'updated'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array('county', 'knownas', 'fourFigure',
		'lat' => 'declat', 'lon' => 'declong', 'easting',
		'northing'))
		->where('declat > ?',$latmin)
		->where('declat < ?',$latmax)
		->where('declong > ?',$longmin)
		->where('declong < ?',$longmax)
		->where('finds.findofnote = ?',(int)1)
		->order('finds.id DESC');
	if(in_array($this->getRole(),$this->_restricted)) {
	$select->where('finds.secwfstage > 2');
	}
	return  $finds->fetchAll($select);
	} else {
	return NULL;
	}
	}	
	
	/** Retrieve record data in format for solr schema
	* @param int $findID
	* @return array
	*/
	public function getSolrData($findID) {
	$findsdata = $this->getAdapter();
	$select = $findsdata->select()
		->from($this->_name, array(
		'id',
		'old_findID', 
		'objectType' => 'objecttype', 
		'broadperiod', 
		'description', 
		'notes', 
		'inscription',
		'classification', 
		'fromdate' => 'numdate1',
		'todate' => 'numdate2',
		'treasure',
		'rally',
		'treasureID' => 'treasureID', 
		'workflow' => 'secwfstage',
		'institution',
		'datefound1',
		'datefound2',
		'subClassification' => 'subclass',
		'smrref' => 'smr_ref',
		'other_ref',
		'musaccno',
		'currentLocation' => 'curr_loc',
		'created',
		'updated'))
		->joinLeft('findspots','finds.secuid = findspots.findID', array(
		'county', 
		'district', 
		'parish', 
		'knownas',
		'fourFigure',
		'gridref',
		'latitude' => 'declat',
		'longitude' => 'declong',
		'elevation',
		'woeid'))
		->joinLeft('coins', 'finds.secuid = coins.findID',array(
		'obverseDescription' => 'obverse_description', 
		'obverseLegend' => 'obverse_inscription',
		'reverseDescription' => 'reverse_description',
		'reverseLegend' => 'reverse_inscription',
		'reeceperiod' => 'reeceID',
		'cciNumber',
		'mintmark' => 'reverse_mintmark',
		'allenType' => 'allen_type',
		'mackType' => 'mack_type',
		'abcType' => 'rudd_type',
		'vaType' => 'va_type'
		))
		->joinLeft('mints','mints.id = coins.mint_ID', array ('mint' => 'mint_name'))
		->joinLeft('denominations','coins.denomination = denominations.id', array('denomination'))
		->joinLeft('rulers','coins.ruler_id = rulers.id',array('ruler' => 'issuer'))
		->joinLeft('users','users.id = finds.createdBy', array('createdBy' => 'fullname'))
		->joinLeft(array('users2' => 'users'),'users2.id = finds.updatedBy', 
		array('updatedBy' => 'fullname'))
		->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id', array('material' =>'term'))
		->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id', array('secondaryMaterial' => 'term'))
		->joinLeft('decmethods','finds.decmethod = decmethods.id', array('decoration' => 'term'))
		->joinLeft('decstyles','finds.decstyle = decstyles.id', array('decstyle' => 'term'))
		->joinLeft('manufactures','finds.manmethod = manufactures.id', array('manufacture' => 'term'))
		->joinLeft('surftreatments','finds.surftreat = surftreatments.id', array('treatment' => 'term'))
		->joinLeft('completeness','finds.completeness = completeness.id', array('completeness' => 'term'))
		->joinLeft('preservations','finds.preservation = preservations.id', array('preservation' => 'term'))
		->joinLeft('periods','finds.objdate1period = periods.id', array('periodFrom' => 'term'))
		->joinLeft(array('p' => 'periods'),'finds.objdate2period = p.id', array('periodTo' => 'term'))
		->joinLeft('cultures','finds.culture = cultures.id', array('culture' => 'term'))
		->joinLeft('discmethods','discmethods.id = finds.discmethod', array('discmethod' => 'method'))
		->joinLeft('people','finds.finderID = people.secuid', array('finder' => 'CONCAT(people.title," ",people.forename," ",people.surname)'))
		->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid', 
		array('identifier' => 'CONCAT(ident1.forename," ",ident1.surname)'))
		->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid', 
		array('secondaryIdentifier' => 'CONCAT(ident2.forename," ",ident2.surname)'))
		->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid', 
		array('recorder' => 'CONCAT(record.title," ",record.forename," ",record.surname)'))
		->joinLeft('subsequentActions','finds.subs_action = subsequentActions.id', 
		array('subsequentAction' => 'action'))
		->joinLeft('finds_images','finds.secuid = finds_images.find_id', array())
		->joinLeft('slides','slides.secuid = finds_images.image_id', array('filename'))
		->joinLeft('rallies','finds.rallyID = rallies.id',array('rallyName' => 'rally_name')) 
		->joinLeft('ironagetribes','coins.tribe = ironagetribes.id', array('tribe'))
		->joinLeft('medievalcategories','medievalcategories.id = coins.categoryID', array('category'))
		->joinLeft('medievaltypes','medievaltypes.id = coins.typeID', array('type'))
		->joinLeft('geographyironage','geographyironage.id = coins.geographyID', array('geography' => 'CONCAT(region,","area)'))
		->where('finds.id = ?', (int)$findID)
		->group('finds.id')
		->limit(1);
	return $findsdata->fetchAll($select);
	}
	
	
}
