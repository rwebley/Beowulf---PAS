<?php
class Pas_Solr_Updater {
	
	
	protected $_solr;
	
	public function __construct(){
	$this->_solr = new Apache_Solr_Service('localhost', 8983, '/solr/beowulf');
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
	Zend_Debug::dump($data);
	exit;
	return $data;
	}
	
	public function createXml($data){
	$docs = array();
	foreach($data as $k => $v){
	$docs[$k] = $v;	
	}
  	$documents = array();
	foreach ( $docs as $item => $fields ) {
	$part = new Apache_Solr_Document();
	foreach ( $fields as $key => $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $data ) {
				$part->setMultiValue( $key, $data );
			}
	} else {
        $part->$key = strip_tags($value);
	}
	}
	$documents[] = $part;
	}
  	return $documents;
	}
	
	/** Add to the index
	 * 
	 * @param int $id
	 */
	public function add($id){
	$recordData = $this->getRecordData($id);
	$updateXml = $this->createXml($recordData);
	$this->_solr->addDocuments( $updateXml );
	$this->_commit();
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