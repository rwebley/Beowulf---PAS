<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author Katiebear
 */
class Pas_Controller_Action_Helper_SolrUpdater 
    extends Zend_Controller_Action_Helper_Abstract {
    
    protected $_cores = array('beowulf', 'beopeople');
  
    protected $_solr;
    
    protected $_config;
    
    public function __construct(){
    $this->_config = Zend_Registry::get('config')->solr->toArray();    
    }
    
    public function getSolrConfig($core){
    $solrAdapter = $this->_config;
    $solrAdapter['core'] = $core;
    $this->_solr = new Solarium_Client(array(
    'adapteroptions' => array(
    $solrAdapter
    )));
    return $this->_solr;
    }
    
    public function update($core, $id){
    $data = $this->getUpdateData($core, $id);
    $update = $this->_solr->createUpdate();
    $doc = $update->createDocument();
    foreach($data['0'] as $k => $v){
    $doc->$k = $v;
    }
    $update->addDocument($doc);
    $update->addCommit();
    return $this->_§solr->update($update);
    }
    
    public function deleteById($core,$id){
    $update = $this->_solr->createUpdate();
    $update->addDeleteById($this->_getIdentifier($core) . $id);
    $update->addCommit();
    return $this->_solr->update($update);    
    }
    
    protected function _getIdentifier($core){
        
        
    }
    
    public function getUpdateData($core, $id){
        switch($core){
            case 'beowulf':
                $model = new Finds();
                $data = $model->getRecordData($id);
                
                break;
            case 'beopeople':
                break;
            case 'beocontent':
                break;
            case 'beopublications':
                break;
            default:
                throw new Exception('Your core does not exist');
                break;
        }
        $data = $this->cleanData($data);
        return $data;
    }

    public function cleanData($data){
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

