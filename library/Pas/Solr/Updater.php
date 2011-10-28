<?php
class Pas_Solr_Updater {
	
	
	protected $_solr;
	
	public function __construct(){
	$this->_solr = new Apache_Solr_Service('localhost', 8983, '/solr/beowulf');
	}

	public function getRecordData($id){
	$finds = new Finds();
	return $finds->getSolrData($id);
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
        $part->$key = $value;
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
	$this->_solr->commit();
	}
	
	/** Delete record by ID
	 * 
	 * @param $id
	 */
	public function delete($id){
	$this->_solr->deleteById($id);
	$this->_solr->commit();
	$this->_solr->optimize();
	}
	
	/** Optimise the index for solr
	 * 
	 */
	public function optimize(){
	$this->_solr->optimize();	
	}
}