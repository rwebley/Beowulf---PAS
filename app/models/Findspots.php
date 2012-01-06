<?php
/** A model for manipulating findspots
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/
class Findspots extends Pas_Db_Table_Abstract {

	protected $_name = 'findspots';

	protected $_primary = 'id';

	protected $_higherlevel = array('admin','flos','fa'); 

	protected $_restricted = array('public','member','research','hero');

	protected $_edittest = array('flos','member');
	
	/** The Yahoo! appid variable for placemaker
     * 
     * @var string $_appid;
     */
    protected $_appid;
    
    public function init(){
    	$this->_appid = $this->_config->webservice->ydnkeys->placemakerkey;
    }
	
	/** Determine role of the user
	* @return string $role
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
	
	/** Determine user institution
	* @return string $institution
	*/
	protected function getInstitution(){
		if($this->_auth->hasIdentity()) {
		$user = $this->_auth->getIdentity();
		$institution = $user->institution;
		return $institution;
		}
	}

	/** Retrieval of findspot row for editing
	* @return array $data
	*/
	public function getEditData($id) {
		$findspotdata = $this->getAdapter();
		$select = $findspotdata->select()
			->from($this->_name)
			->where('findspots.id = ?', (int)$id)
			->group($this->_primary)
			->limit('1');
       return $data = $findspotdata->fetchAll($select);
	}

	/** 
	 * 
	 */
	public function getFindNumber($id){
		$findspotdata = $this->getAdapter();
		$select = $findspotdata->select()
			->from($this->_name,array())
			->joinLeft('finds', 'finds.secuid = findspots.findID',array('id'))
			->where('findspots.id = ?', (int)$id)
			->limit('1');
       $data = $findspotdata->fetchAll($select);
       return $data[0]['id'];
	}
	/** Retrieval of findspot row for display (not all columns)
	* @param integer $id 
	* @return array $data
	* @todo add caching
	*/
	public function getFindSpotData($id)  {
		$findspotdata = $this->getAdapter();
		$select = $findspotdata->select()
			->from($this->_name, array('county', 'district', 'parish',
									   'easting', 'northing', 'gridref',
									   'declat', 'declong', 'fourFigure',
									   'knownas', 'smrref', 'map25k',
									   'map10k', 'landusecode', 'landusevalue',
									   'id', 'old_findspotid', 'createdBy',
									   'description', 'comments', 'address', 
									   'woeid', 'elevation'))
			->joinLeft('finds','finds.secuid = findspots.findID',array('discmethod'))
			->joinLeft(array('land1' => 'landuses'),'land1.id = findspots.landusecode',array('landuse' => 'term'))
			->joinLeft(array('land2' =>'landuses'),'land2.id = findspots.landusevalue',array('landvalue' => 'term'))
			->joinLeft('maporigins','maporigins.id = findspots.gridrefsrc',array('source' => 'term'))
			->joinLeft('regions','findspots.regionID = regions.id',array('region'))
			->joinLeft('people',$this->_name.'.landowner = people.secuid', array('fullname'))
			->joinLeft('discmethods','finds.discmethod = discmethods.id',array('method'))
			->where('finds.id = ?', (int)$id)
			->group('finds.id')
			->limit('1');
       return $findspotdata->fetchAll($select);
	}

	/** Retrieval of findspots for mapping on a user basis for staff profile map
	* @param integer $id 
	* @param integer $limit
	* @return array $data
	* @todo add caching
	*/
	public function getFindSpotDataMapping($id, $limit)  {
		$findspotdata = $this->getAdapter();
		$select = $findspotdata->select()
			->from($this->_name, array('declat','declong','county','id'))
			->joinLeft('finds','finds.secuid = findspots.findID', 
			array('old_findID', 'objecttype', 'broadperiod'))
			->joinLeft('staff','staff.dbaseID = finds.created_by', array())
			->where('staff.id = ?' , (int)$id)
			->where('declong IS NOT NULL')
			->where('declat IS NOT NULL') 
			->limit($limit);
       return $findspotdata->fetchAll($select);
	}

	/** Retrieval of findspots for mapping on a user basis for staff profile map
	* @param string $broadperiod
	* @param string $objecttype 
	* @param integer $limit
	* @return array $data
	* @todo add caching
	*/
	public function getFindSpotDataMappingObjects($broadperiod,$objecttype, $limit) {
		$findspotdata = $this->getAdapter();
		$select = $findspotdata->select()
			->from($this->_name, array('declat', 'declong', 'county', 'id'))
			->joinLeft('finds','finds.secuid = findspots.findID', array(
			'old_findID', 'objecttype', 'broadperiod'))
			->where('finds.broadperiod = ?' , (string)$broadperiod)
			->where('finds.objecttype = ?',(string)$objecttype)
			->where('declong IS NOT NULL')
			->where('declat IS NOT NULL') 
			->limit((int)$limit);
       return $findspotdata->fetchAll($select);
	}
	
	/** Retrieval of findspots data for finds and findspots
	* @param string $secuid
	* @param integer $id 
	* @return array $data
	* @todo add caching
	*/
	public function getFindtoFindspotsAdmin($id,$secuid) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name)
			->joinLeft('finds','finds.secuid = findspots.findID',array('id'))
			->where('finds.id = ?' ,(int)$id)
			->where('finds.secuid = ?',(string)$secuid);
       return $finds->fetchAll($select);
	}
	
	/** Retrieval of findspots data row for deletion
	* @param integer $id 
	* @return array $data
	* @todo add caching
	*/
	public function getFindtoFindspotDelete($id) {
		$finds = $this->getAdapter();
		$select = $finds->select()
			->from($this->_name)
			->joinLeft('finds','finds.secuid = findspots.findID', array('findID' => 'id'))
			->where('findspots.id = ?' ,(int)$id);
       return $finds->fetchAll($select);
	}

	/** Retrieval of findspots data row for cloning record
	* @param integer $userid 
	* @return array $data
	* @todo add caching
	*/
	public function getLastRecord($userid) {
	$finds = $this->getAdapter();
	$select = $finds->select()
		->from($this->_name,array(
		'county', 'district', 'parish',
		'knownas', 'regionID', 'knownas',
		'gridref', 'gridrefsrc', 'gridrefcert',
		'description', 'comments', 'landusecode',
		'landusevalue', 'depthdiscovery'))
		->where('findspots.createdBy = ?', (int)$userid)
		->order('findspots.id DESC')
		->limit(1);
	return $finds->fetchAll($select);
	}

	
	/** Retrieval of findspots with missing districts to harangue the crew
	* @return array $data
	*/
	public function getMissingDistrict() {
	$findspots = $this->getAdapter();
	$select = $findspots->select()
		->from($this->_name,array('id','county','parish'))
		->where('county IS NOT NULL')
		->where('parish IS NOT NULL')
		->where('district IS NULL')
		->limit(5000);
	return $findspots->fetchAll($select);
	}
	
	/** Function for adding and processing the findspot data
	 * @access public
	 * @param array $data
	 */
	public function addAndProcess($data){
	if(is_array($data)){
	foreach($data as $k => $v) {
	if ( $v == "") {
	$data[$k] = NULL;
	}
	}
	if(!is_null($data['gridref'])) {
	$data = $this->_processFindspot($data);
	}
	$findid = new Pas_Generator_FindID();
	$data['old_findspotid'] = $findid->generate();
	$secuid = new Pas_Generator_SecuID();
	$data['secuid'] = $secuid->secuid(); 
	if(array_key_exists('landownername', $data)){
		unset($data['landownername']);
	}
	if(array_key_exists('csrf', $data)){
 		unset($data['csrf']);
  	}
	if(empty($data['created'])){
		$data['created'] = $this->timeCreation();
	}
	if(empty($data['createdBy'])){
		$data['createdBy'] = $this->userNumber();
	
	return parent::insert($data);		
	} else {
		throw new Exception('The data submitted is not an array',500);
	}
	}
	}
	
	/** Function for updating findspots with processing of geodata
	 * @access public
	 * @param array $data
	 * @param array $where
	 */
	public function updateAndProcess($data){
	if(is_array($data)){
	foreach($data as $k => $v) {
	if ( $v == "") {
	$data[$k] = NULL;
	}
	}
	if(!is_null($data['gridref'])) {
	$data = $this->_processFindspot($data);
	}   
	}
	if(array_key_exists('csrf', $data)){
	unset($data['csrf']);
	}
	if(array_key_exists('landownername', $data)){
	unset($data['landownername']);
	}   

	return $data;
	}
	
	/** Function for processing findspot
	 * 
	 * @param array $data
	 */
	protected function _processFindspot($data){
	if(is_array($data)) {
	$conversion = new Pas_Geo_Gridcalc($data['gridref']);
	$results = $conversion->convert();
	$place = new Pas_Service_Geo_Geoplanet($this->_appid);
	$geoHash = new Pas_Geo_Hash();
	$hash = $geoHash->encode($results['decimalLatLon']['decimalLatitude'],
		$results['decimalLatLon']['decimalLongitude']);
	$data['declong'] = $results['decimalLatLon']['decimalLongitude'];
	$data['declat'] = $results['decimalLatLon']['decimalLatitude'];
	$data['easting'] = $results['easting'];
	$data['northing'] = $results['northing'];	  
	$data['map10k'] = $results['10kmap'];
	$data['map25k'] = $results['25kmap'];
	$data['fourFigure'] = $results['fourFigureGridRef'];
	$data['accuracy'] = $results['accuracy']['precision'];
	$data['gridlen'] = $results['gridrefLength'];
	$data['geohash'] = $hash;
	$yahoo = $place->reverseGeoCode($results['decimalLatLon']['decimalLatitude'],
		$results['decimalLatLon']['decimalLongitude']);	
        $data['woeid'] = $yahoo['woeid'];
    return $data;
	} else {
	return $data;
	}
	}
}
