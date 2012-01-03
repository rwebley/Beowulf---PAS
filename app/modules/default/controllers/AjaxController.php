<?php
class AjaxController extends Pas_Controller_Action_Ajax
{
	protected $_cache;
	protected $_auth;
	
	public function init(){		
	$this->_helper->acl->allow('public',null);
	$this->_helper->layout->disableLayout();    
	$this->_helper->viewRenderer->setNoRender(); 
	$this->_cache = Zend_Registry::get('rulercache');
	$this->_auth = Zend_Auth::getInstance();
   	}

	public function getAccount() {
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	return $user;
	} else {
	return false;
	}	
	}
	public function indexAction()
	{}
	
	public function countiesAction() {
	$counties = new Counties;
	$countiesjson = $counties->getCountyName2();
	echo Zend_Json::encode($countiesjson);
	}

	public function placesAction() {
	if($this->_getParam('term',false)){
	$districts = new Places;
	$response = $districts->getDistrict($this->_getParam('term'));
	} else {
	$response = array('id' => NULL,'term' => 'No county specified');
	}
	echo  Zend_Json::encode($response);
	}

	public function parishesAction() {
	if($this->_getParam('term',false)) {
	$parishes = new Places;
	$response = $parishes->getParish($this->_getParam('term'));
	} else {
	$response = array('id' => NULL,'term' => 'No district specified');
	}
	echo  Zend_Json::encode($response);
	}

	public function parishesbycountyAction() {
	if($this->_getParam('term',false)){
	$parishes = new Places;
	$response = $parishes->getParishByCounty($this->_getParam('term'));
	} else {
	$response = array('id' => NULL,'term' => 'No county specified');
	}
	echo  Zend_Json::encode($response);
	}

	public function districtbyparishAction() {
	if($this->_getParam('term',false)){
	$parishes = new Places;
	$response = $parishes->getDistrictByParish($this->_getParam('term'));
	} else {
	$response = array('id' => NULL,'term' => 'No parish specified');
	}
	echo  Zend_Json::encode($response);
	}
	

	public function regionsAction() {
	if($this->_getParam('term',false)){
	$regions = new Counties;
	$response = $regions->getRegions($this->_getParam('term'));
	} else {
	$response = array('id' => NULL,'term' => 'No county specified');
	}
	echo  Zend_Json::encode($response);
	}
	
	public function landusecodesAction(){
	if($this->_getParam('term',false)){
	$landcodes = new Landuses();
	$response = $landcodes->getLandusesChildAjax2($this->_getParam('term'));
	}  else {
	$response = array('id' => NULL,'term' => 'No Landuse specified');
	}
	echo  Zend_Json::encode($response);
	}

	public function objecttermAction(){
	$objectterms = new ObjectTerms;
	$objecttermsjson = $objectterms->getObjectterm($this->_getParam('q'));
	echo  Zend_Json::encode($objecttermsjson);
	}

	public function objectimagelinkAction(){
	$objectterms = new Finds;
	$objecttermsjson = $objectterms->getImageLinkData($this->_getParam('q'));
	echo  Zend_Json::encode($objecttermsjson);
	}


	public function publicationtitleAction() {
	$publications = new Publications();
	$pubjson = $publications->getTitles(urlencode($this->_getParam('q')));
	echo  Zend_Json::encode($pubjson);
	}

	public function macktypesAction(){
	$macktypes = new MackTypes;
	$macktypesjson = $macktypes->getTypes($this->_getParam('q'));
	echo  Zend_Json::encode($macktypesjson);
	}
	
	public function allentypesAction(){
	$allentypes = new AllenTypes;
	$allentypesjson = $allentypes->getTypes($this->_getParam('q'));
	echo  Zend_Json::encode($allentypesjson);
	}

	public function vatypesAction(){
	$vatypes = new VATypes;
	$vatypesjson = $vatypes->getTypes($this->_getParam('q'));
	echo  Zend_Json::encode($vatypesjson);
	}

	public function otherrefsAction(){
	$otherrefs = new Finds();
	$otherrefsjson = $otherrefs->getOtherRef($this->_getParam('q'));
	echo  Zend_Json::encode($otherrefsjson);
	}

	public function treasureidsAction(){
	$treasureids = new Finds();
	$treasureidsjson = $treasureids->getTreasureID($this->_getParam('q'));
	echo Zend_Json::encode($treasureidsjson);
	}

	public function peopleAction()
	{
	$peoples = new Peoples();
	$people_options = $peoples->getNames($this->_getParam('q'));
	echo  Zend_Json::encode($people_options);
	}

	public function peoplesearchAction()
	{
	$peoples = new Peoples();
	$people_options = $peoples->getNamesSearch($this->_getParam('q'));
	echo  Zend_Json::encode($people_options);
	}
	
	public function rulerdenomAction() {
	if($this->_getParam('term',false)){
	$denominations = new Denominations();
	$data = $denominations->getRomanRulerDenom($this->_getParam('term'));
	if($data){
	$response = $data;
	} else {
	$response = array(array('id' => NULL, 'term' => 'No options available'));
	}	
	}  else {
	$response = array(array('id' => NULL, 'term' => 'No ruler specified'));	
	}
        $data = Zend_Json::encode($response);
	echo Zend_Json::prettyPrint($data, array("indent" => " ", 'format' => 'html'));
	}

	public function rulerdenomearlymedAction(){
	if($this->_getParam('term',false)){
	$denominations = new Denominations();
	$denom_options = $denominations->getEarlyMedRulerDenom($this->_getParam('term'));
	if ($denom_options) {
	echo  Zend_Json::encode($denom_options);
	} else {
	$data = array(array('id' => '', 'term' => 'No options available'));
	echo Zend_Json::encode($data);
	}
	} else {
	$response = array(array('id' => NULL, 'term' => 'No ruler specified'));	
	echo  Zend_Json::encode($response);	
	}
	}

	public function romandenomrulerAction(){
	if($this->_getParam('term',false)){
	$rulers = new Rulers();
	$ruler_options = $rulers->getRomanDenomRuler($this->_getParam('term'));
	if ($ruler_options) {
	echo  Zend_Json::encode($ruler_options);
	} else {
	$data = array(array('id' => NULL, 'term' => 'No options available.'));
	echo Zend_Json::encode($data);
	}
	} else {
	$response = array(array('id' => NULL, 'term' => 'No ruler specified'));	
	echo  Zend_Json::encode($response);	
	}
	}

	public function romanmintrulerAction(){
	if($this->_getParam('term',false)){
	$mints = new Mints();
	$mint_options = $mints->getRomanMintRuler($this->_getParam('term'));
	if ($mint_options) {
	echo  Zend_Json::encode($mint_options);
	} else {
	$data = array(array('id' => NULL, 'term' => 'No options available'));
	echo Zend_Json::encode($data);
	}
	} else {
	$data = array(array('id' => NULL, 'term' => 'No ruler specified'));
	echo Zend_Json::encode($data);	
	}
	}

	public function earlymedmintrulerAction() {
	$mints = new Mints();
	$mint_options = $mints->getEarlyMedMintRuler($this->_getParam('term'));
	if ($mint_options) {
	echo  Zend_Json::encode($mint_options);
	} else if($ruler == NULL) {
	$data = array(array('id' => NULL, 'term' => 'I donated my brain to Michael'));
	echo Zend_Json::encode($data);
	} else {
	$data = array(array('id' => NULL, 'term' => 'No options available'));
	echo Zend_Json::encode($data);
	}
	}
	
	public function medmintrulerAction(){
	if($this->_getParam('term',false)){
	$ruler = $this->_getParam('term');
	$mints = new Mints();
	$mint_options = $mints->getEarlyMedMintRuler($this->_getParam('term'));
	if ($mint_options) {
	echo  Zend_Json::encode($mint_options);
	} else if($ruler == NULL) {
	$data = array(array('id' => NULL, 'term' => 'No options available'));
	echo Zend_Json::encode($data);
	} else {
	$data = array(array('id' => NULL, 'term' => 'No options available'));
	echo Zend_Json::encode($data);
	}
	} else {
	$error = array(array('id' => '', 'term' => 'No ruler specified'));
	echo Zend_Json::encode($error);		
	}
	}

	public function earlymedtypecatAction(){
	if($this->_getParam('term',false)){
	$cats = new CategoriesCoins();
	$cat_options = $cats->getCategories($this->_getParam('term'));
	if ($cat_options) {
	echo  Zend_Json::encode($cat_options);
	} else {
	$data = array(array('id' => NULL, 'term' => 'No options available'));
	echo Zend_Json::encode($data);
	}
	} else {
	$error = array(array('id' => NULL, 'term' => 'No ruler specified'));
	echo Zend_Json::encode($error);	
	}
	}

	public function earlymedtyperulerAction(){
	if($this->_getParam('term',false)){
	$types = new MedievalTypes();
	$ruler_options = $types->getEarlyMedTypeRuler($this->_getParam('term'));
	if ($ruler_options) {
	echo  Zend_Json::encode($ruler_options);
	} else {
	$data = array(array('id' => NULL, 'term' => 'No options available'));
	echo Zend_Json::encode($data);
	}
	} else {
	$data = array(array('id' => NULL, 'term' => 'No ruler specified'));
	echo Zend_Json::encode($data);	
	}
	}


	public function reeceAction(){
	if($this->_getParam('term',false)){
	$reeces = new Reeces();
	$reece_options = $reeces->getRulerReece($this->_getParam('term'));
	$reeces2 = new Reeces();
	$reece2_options = $reeces->getReeceUnassigned();
	if ($reece_options) {
	echo  Zend_Json::encode($reece_options);
	} else {
	echo Zend_Json::encode($reece2_options);
	}
	} else {
	$error = array(array('id' => NULL, 'term' => 'No ruler specified'));
	echo Zend_Json::encode($error);
	}
	}

	public function iageographyAction(){
	if($this->_getParam('term',false)){
	$geographies= new Geography();
	$response = $geographies->getIronAgeGeography($this->_getParam('term'));
	} else {
	$response = array(array('id' => NULL, 'term' => 'No ruler specified'));	
	}
	echo  Zend_Json::encode($response);
	}
	
	public function iarulerregionAction(){
	if($this->_getParam('term',false)){
	$rulers = new Rulers();
	$response = $rulers->getIronAgeRulerRegion($this->_getParam('term'));
	} else {
	$response = array(array('id' => NULL, 'term' => 'No ruler specified'));	
	}
	echo  Zend_Json::encode($response);
	}


	public function catsperiodAction() {
	if($this->_getParam('term',false)){
	$cats = new CategoriesCoins();
	$response = $cats->getCategoriesPeriod($this->_getParam('term'));
	} else {
	$response = array(array('id' => NULL, 'term' => 'No period specified'));	
	}
	echo  Zend_Json::encode($response);
	}
	
	public function rulersperiodAction(){
	if($this->_getParam('term',false)){
	$rulers = new Rulers();
	$response = $rulers->getAllRulers($this->_getParam('term'));
	} else {
	$response = array(array('id' => NULL, 'term' => 'No period specified.'));	
	}
	echo  Zend_Json::encode($response);
	}


	public function iatriberegionAction(){
	if($this->_getParam('term',false)){
	$tribes = new Tribes();
	$response = $tribes->getIronAgeTribeRegion($this->_getParam('term'));
	} else {
	$response  = array(array('id' => NULL, 'term' => 'No region specified'));	
	}
	echo Zend_Json::encode($response);
	}

	public function revtypesAction(){
	if($this->_getParam('term',false)){
	$types = new Revtypes();
	$type_options = $types->getTypes($this->_getParam('term'));
	if ($type_options) {
	$response = $type_options;
	} else {
	$response = array(array('id' => NULL, 'term' => 'No options available'));
	}
	} else {
	$response = array(array('id' => NULL, 'term' => 'No ruler specified'));	
	}
	echo Zend_Json::encode($response);
	}

	public function earlymedcatrulerAction() {
	if($this->_getParam('term',false)){
	$rulers = new Rulers();
	$rulerOptions = $rulers->getEarlyMedievalRulersAjax($this->_getParam('term'));
	if ($rulerOptions) {
	$response = $rulerOptions;
	} else {
	$response = array(array('id' => '', 'term' => 'No options available'));
	}
	} else {
	$response = array(array('id' => NULL, 'term' => 'No ruler specified'));	
	}
	echo Zend_Json::encode($response);
	}
		
	public function postmedcatrulerAction() {
	if($this->_getParam('term',false)){
	$rulers = new Rulers();
	$rulerOptions = $rulers->getPostMedievalRulersAjax($this->_getParam('term'));
	if ($rulerOptions) {
	$response = $rulerOptions;
	} else {
	$response = array(array('id' => '', 'term' => 'No options available'));
	}
	} else {
	$response = array(array('id' => NULL, 'term' => 'No category specified'));		
	}
	echo Zend_Json::encode($response);
	}	

	public function medcatrulerAction() {
	if($this->_getParam('term',false)){
	$rulers = new Rulers();
	$rulerOptions = $rulers->getMedievalRulersAjax($this->_getParam('term'));
	if ($rulerOptions) {
	$response = $rulerOptions;
	} else {
	$response = array(array('id' => '', 'term' => 'No options available'));
	}
	} else {
	$response = array(array('id' => NULL, 'term' => 'No category specified'));		
	}
	echo Zend_Json::encode($response);
	}	


	public function moneyersAction() {
	if($this->_getParam('term',false)){
	$ruler = $this->_getParam('term');
	$moneyers = new Moneyers();
	$moneyerOptions = $moneyers->getMoneyers();
	if ($ruler == 242) {
	$response = $moneyerOptions;
	} else {
	$response = array(array('id' => '', 'term' => 'No options available'));
	}
	} else {
	$response = array(array('id' => NULL, 'term' => 'No options available'));		
	}
	echo Zend_Json::encode($response);
	}	

	public function relatedfindAction() {
	$finds = new Finds;
	$findsjson = $finds->getFindSecuid($this->_getParam('q'));
	echo  Zend_Json::encode($findsjson);
	}


	public function oldfindidAction() {
	$finds = new Finds;
	$findsjson = $finds->getOldFindID($this->_getParam('q'));
	echo  Zend_Json::encode($findsjson);
	}
	
	public function organisationAction() {
	$orgs = new Organisations;
	$orgsjson = $orgs->getOrgNames($this->_getParam('q'));
	echo  Zend_Json::encode($orgsjson);
	}
	
	public function usernameAction() {
	$users = new Users;
	$usersjson = $users->findUserAccountAjax($this->_getParam('q'));
	echo Zend_Json::encode($usersjson);
	}
	
	public function mappingdataAction()
	{
	$id = $this->_getParam('id');
	$limit = $this->_getParam('limit');
	$mapdata = new Findspots();
	$mapdatas = $mapdata->getFindspotDataMapping($id,$limit);
	$date = Zend_Date::now()->toString('yyyy-MM-dd');
	$time  = Zend_Date::now()->toString('HH:mm');
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node); 
	foreach($mapdatas as $mapdata)
	{
	  $node = $dom->createElement("marker");  
	  $newnode = $parnode->appendChild($node);  
	  $newnode->setAttribute("name", $mapdata['old_findID']);  
	  $newnode->setAttribute("broadperiod", $mapdata['broadperiod']);  
	  $newnode->setAttribute("lat", $mapdata['declat']);  
	  $newnode->setAttribute("lng", $mapdata['declong']); 
	  $newnode->setAttribute("type", $mapdata['objecttype']);  
	  $newnode->setAttribute("workflow",
	  str_replace(array('1','2','3','4'),
	  array('quarantine','review','published','validation'),
	  $mapdata['secwfstage']))
	  ;
	} 
	echo $dom->saveXML();
	}
	
	
		
	
	
	public function usermapAction()
	{
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node);
	if($this->getAccount() == true) {  
	$mapdata = new Finds();
	$mapdatas = $mapdata->getUserMap($this->getAccount()->peopleID,2000);
	if(count($mapdatas)) {
	foreach($mapdatas as $mapdata){
	$lat = $mapdata['declat'];
	$long = $mapdata['declong']; 
	$html = '';
	  if(isset($mapdata['i'])) {
	  $html .=  '<div id="tmb">'
	  .'<img src="http://'
	  . $_SERVER['SERVER_NAME']
	  . '/images/thumbnails/'
	  . $mapdata['i']
	  .'.jpg"/></div>';
	  }
	
	  $html .= '<div id="detailsmap"><p>'
	  . ucwords(strtolower($mapdata['county']))
	  . ' - <a href="http://'
	  . $_SERVER['SERVER_NAME']
	  . $this->view->url(array('module'=> 'database','controller' => 'artefacts','action' => 'record','id'=> $mapdata['id']),null,true)
	  . '" title="View record\'s details">'
	  . $mapdata['old_findID'] 
	  . '</a><br />' 
	  . $mapdata['objecttype']
	  .'<br />'
	  .$mapdata['broadperiod']
	  .'</p></div>';
	 
	  $node = $dom->createElement("marker");  
	  $newnode = $parnode->appendChild($node);  
	  $newnode->setAttribute("name", $html);
	  $newnode->setAttribute("broadperiod", $mapdata['broadperiod']);  
	  $newnode->setAttribute("lat", $lat);  
	  $newnode->setAttribute("lng", $long); 
	  $newnode->setAttribute("type", $mapdata['objecttype']); 
	  $newnode->setAttribute("workflow",
	  str_replace(array('1','2','3','4'),
	  array('quarantine','review','published','validation'),
	  $mapdata['secwfstage']))
	  ; 
	}
	} 
	}
	header('Content-Type: text/xml');
	echo $dom->saveXML();
	}
	
	public function mapdataAction()	{
	$params = $this->_getAllParams();
	$mapdata = new Finds();
	$mapdatas = $mapdata->getMapSearchResultsExport($params,2000);
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node); 
	foreach($mapdatas as $mapdata){
	$restricted = array('public','member');
	if(!is_null($mapdata['fourFigure'])) {
	if($this->_auth->hasIdentity())
	{
	$user = $this->_auth->getIdentity();
	{
	if(!in_array($user->role,$restricted)) 
	{
	$lat = $mapdata['declat'];
	$long = $mapdata['declong']; 
	} else {
	$results = $this->view->Gridcalculator($mapdata['fourFigure']);
	$lat = $results['Latitude'];
	$long = $results['Longitude']; 
	} 
	} 
	} else {
	$results = $this->view->Gridcalculator($mapdata['fourFigure']);
	
	$lat = $results['Latitude'];
	$long = $results['Longitude']; 
	}
	
	 $html = '';
	  if(isset($mapdata['i'])) {
	  $html .=  '<div id="tmb">'
	  .'<img src="http://'
	  . $_SERVER['SERVER_NAME']
	  . '/images/thumbnails/'
	  . $mapdata['i']
	  .'.jpg"/></div>';
	  }
	}
	  $html .= '<div id="detailsmap"><p>'
	  . ucwords(strtolower($mapdata['county']))
	  . ' - <a href="http://'
	  . $_SERVER['SERVER_NAME']
	  . $this->view->url(array('module'=> 'database','controller' => 'artefacts','action' => 'record','id'=> $mapdata['id']),null,true)
	  . '" title="View record\'s details">'
	  . $mapdata['old_findID'] 
	  . '</a><br />' 
	  . $mapdata['objecttype']
	  .'<br />'
	  .$mapdata['broadperiod']
	  .'</p></div>';
	 
	  $node = $dom->createElement("marker");  
	  $newnode = $parnode->appendChild($node);  
	  $newnode->setAttribute("name", $html);
	  $newnode->setAttribute("broadperiod", $mapdata['broadperiod']);  
	  $newnode->setAttribute("lat", $lat);  
	  $newnode->setAttribute("lng", $long); 
	  $newnode->setAttribute("type", $mapdata['objecttype']); 
	  $newnode->setAttribute("workflow",
	  str_replace(array('1','2','3','4'),
	  array('quarantine','review','published','validation'),
	  $mapdata['secwfstage']))
	  ; 
	 
	} 
	header('Content-Type: text/xml');
	echo $dom->saveXML();
	}



	public function findofnotemapdataAction()
	{
	$params = $this->_getAllParams();
	$this->_helper->layout->disableLayout();    
	$mapdata = new Findspots();
	$mapdatas = $mapdata->getFindsofNoteMap();
	
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node); 
	
	foreach($mapdatas as $mapdata){
	$allowed = array('fa','admin','hero','research','flos','treasure');
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	{
	if(in_array($user->role,$allowed)) {	
	$lat = $mapdata['declat'];
	$long = $mapdata['declong']; }
	}
	} else	 {
	$lat = Substr($mapdata['declat'],0,4);
	$long = SUBSTR($mapdata['declong'],0,4);
 	}
	 $html = '';
	  if(isset($mapdata['i'])) {
	  $html .=  '<div id="tmb">'
	  .'<img src="http://'
	  . $_SERVER['SERVER_NAME']
	  .'/images/'
	  .'thumbnails/'
	  . $mapdata['i']
	  .'.jpg"/></div>';
	  }
	
	  $html .= '<div id="detailsmap"><p>'
	  . ucwords(strtolower($mapdata['county']))
	  . ' <br /> <a href="http://'
	  . $_SERVER['SERVER_NAME']
	  . $this->view->url(array('module'=> 'database','controller' => 'artefacts','action' => 'record','id'=> $mapdata['id']),null,true)
	  . '" title="View record\'s details">'
	  . $mapdata['old_findID'] 
	  . '</a><br />' 
	  . $mapdata['objecttype']
	  .'<br />'
	  .$mapdata['broadperiod']
	  .'</p></div>';
	 
	  $node = $dom->createElement("marker");  
	  $newnode = $parnode->appendChild($node);  
	  $newnode->setAttribute("name",$html);
	  
	  $newnode->setAttribute("broadperiod", $mapdata['broadperiod']);  
	  $newnode->setAttribute("lat", $lat);  
	  $newnode->setAttribute("lng", $long); 
	  $newnode->setAttribute("type", $mapdata['objecttype']); 
	$newnode->setAttribute("workflow",
	  str_replace(array('1','2','3','4'),
	  array('quarantine','review','published','validation'),
	  $mapdata['secwfstage']))
	  ;  
	} 
	header('Content-Type: text/xml');
	echo $dom->saveXML();
	}



	public function objectmapAction()
	{
	$period = $this->_getParam('period');
	$objecttype = $this->_getParam('objecttype');
	$limit = $this->_getParam('limit');
	$mapdata = new Findspots();
	$mapdatas = $mapdata->getFindspotDataMappingObjects($period,$objecttype,$limit);
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node); 
	foreach($mapdatas as $mapdata) {
	  $node = $dom->createElement("marker");  
	  $newnode = $parnode->appendChild($node);  
	  $newnode->setAttribute("name", $mapdata['old_findID']);  
	  $newnode->setAttribute("broadperiod", $mapdata['broadperiod']);  
	  $newnode->setAttribute("lat", $mapdata['declat']);  
	  $newnode->setAttribute("lng", $mapdata['declong']); 
	    $newnode->setAttribute("type", $mapdata['objecttype']);  
	 
	} 
	echo $dom->saveXML();
	}

	public function workflowchangeAction(){
	$finds = new Finds();
	$updatedata = array('secwfstage' => $this->_getParam('wfstage'),'updated' => $this->getTimeForForms(),'updatedBy' => $this->getIdentityForForms());
	$where = array();
	$where[] = $finds->getAdapter()->quoteInto('id = ?', $this->_getParam('id'));
	$finds->update($updatedata,$where);
	}

	public function addmyresearchAction() {
	$research = new Find2research();
	$updatedata = array('findID' => $this->_getParam('findID'),'researchID' => $this->_getParam('researchtitle'),'created' => $this->getTimeForForms(),'createdBy' => $this->getIdentityForForms());
	$research->insert($updatedata);
	}

	public function deleteimagelinkAction() {
	if($this->_getParam('id',false)) {
	$links = new FindsImages();
	$where = $links->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$links->delete($where);
	}else {
	throw new Exception('No parameter on url string');
	}
	}

	public function staffdataAction(){
		$this->_helper->viewRenderer->setNoRender(false); 
	$contacts = new Contacts();
	$this->view->contacts = $contacts->getContactsForMap();
	}


	public function deleteprojectAction() {
	if($this->_getParam('id',false)){
	$projects = new ResearchProjects();
	$where = $projects->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$projects->delete($where);
	} else {
		throw new Exception ('There is no research project ID specified',500);
	}
	}
	
	public function deleteimagerulerAction(){
	$images = new RulerImages();
	$deletefiles = $images->getFilename($this->_getParam('id'));
	foreach($deletefiles as $files) {
	$filename = $files['filename'];
	}
	$where = $images->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$images->delete($where);
	unlink('./images/rulers/'.$filename);
	}

	public function deleteprofileimageAction() {
	$staff = new Contacts();
	$staffs = $staff->getImage($this->_getParam('id'));
	foreach($staffs as $staff){
	$filename = $staff['image'];
	}
	$updateData = array();
	$updateData['image'] = NULL;
	$updateData['updated'] = $this->getTimeForForms();
	$updateData['updatedBy'] = $this->getIdentityForForms();
	$stafflist = new Contacts();

	$where = $stafflist->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$stafflist->update($updateData,$where);
	$name = substr($filename, 0, strrpos($filename, '.')); 
	$ext = '.jpg';
	$converted = $name.$ext;
	unlink('./images/staffphotos/'.$filename);
	unlink('./images/staffphotos/resized/'.$converted);
	unlink('./images/staffphotos/thumbnails/'.$converted);
	}

	public function deletemintrulerAction(){
	$mints = new MintsRulers();
	$where = $mints->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$mints->delete($where);
	}
	
	public function deletedenomrulerAction() {
	$denoms = new DenomRulers();
	$where = $denoms->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$denoms->delete($where);
	}
	
	public function deletereverserulerAction(){
	$reverses = new RulerRevType();
	$where = $reverses->getAdapter()->quoteInto('id = ?', (int)$this->_getParam('id'));
	$reverses->delete($where);
	}


	public function linkimageAction() {
	if($this->_getParam('secuid',false)){
	$this->_helper->layout->disableLayout();  
	$form = new ImageLinkForm();
	$this->view->form = $form;
	$images = new Slides();
	$this->view->images = $images->getImageForLinks($this->_getParam('secuid'));
	} else {
	throw new Exception('No image id has been specified on the url string');
	}
	}
	
	public function samsAction(){
	$monuments = new ScheduledMonuments();
	$monjson = $monuments->samLookup($this->_getParam('q'));
	echo  Zend_Json::encode($monjson);
	}
	
	public function deletecommentAction(){
	if($this->_getParam('id',false)){
	$comments = new Comments();
	$where = $comments->getAdapter()->quoteInto('comment_id = ?', (int)$this->_getParam('id'));
	$comments->delete($where);
	} else {
	throw new Exception('No comment ID has been specified',500);
	}
	}
}