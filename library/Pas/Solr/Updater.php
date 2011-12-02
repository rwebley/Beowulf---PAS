<?php
class Pas_Solr_Updater {
	
	
	protected $_solrConfig;
	
	public function __construct(){
	$this->_solrConfig = Zend_Registry::get('config')->solr->toArray();
	}

	public function getRecordData($id){
	$finds = new Finds();
	$data = $finds->getSolrData($id);
	foreach ($data['0'] as $key => $value) {
		  if (is_null($value) || $value === "") {
			unset($data['0'][$key]);
		  }
	}
	if(array_key_exists('datefound1',$data['0'])){
		$df1 = $this->todatestamp($data['0']['datefound1']);
		$data['0']['datefound1'] = $df1;
	}
	if(array_key_exists('datefound2',$data['0'])){
		$df2 = $this->todatestamp($data['0']['datefound2']);
		$data['0']['datefound2'] = $df2;
	}
	if(array_key_exists('created',$data['0'])){
		$created = $this->todatestamp($data['0']['created']);
		$data['0']['created'] = $created;
	}
	if(array_key_exists('updated',$data['0'])){
		$updated = $this->todatestamp($data['0']['updated']);
		$data['0']['updated'] = $updated;
	}
	return $data;
	}
	
	
	/** Add to the index
	 * 
	 * @param int $id
	 */
	public function add($id,$core){
	$solr = new Solarium_Client(array(
    'adapteroptions' => array(
    'host' => '127.0.0.1',
    'port' => 8983,
    'path' => '/solr/',
	'core' => 'beowulf'
    )));
	
	$recordData = $this->getRecordData($id);
	$update = $solr->createUpdate();
	$doc = $update->createDocument();
	foreach($recordData['0'] as $k => $v){
	$doc->$k = $v;
	}
	$update->addDocument($doc);
    $update->addCommit();
    $result = $solr->update($update);
    Zend_Debug::dump($result->getStatus());
    exit;
    return $solr->update($update);
	}
	
	/** Delete record by ID
	 * 
	 * @param $id
	 */
	public function delete($id){
	$this->_solr->deleteById($id);
	$this->_commit();
	$this->_optimize();
	}
	
	protected function _commit(){
	$this->_solr->commit();	
	}
	/** Optimise the index for solr
	 * 
	 */
	protected function _optimize(){
	$this->_solr->optimize();	
	}
	
	public function fromString($date_string) {
	if (is_integer($date_string) || is_numeric($date_string)) {
	return intval($date_string);
	} else {
	return strtotime($date_string);
	}
	}

    /** Format the date and return as unix stamp
	* 
	* @param string $date_string
	*/
	public function todatestamp($date_string) {
	$date = $this->fromString($date_string);
	$ret = date('Y-m-d\TH:i:s\Z', $date);
	return $ret;
	}
}