<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author Katiebear
 */
class Analytics_IndexController extends Pas_Controller_Action_Admin {
    
    protected $_googleID;
    
    protected $_googlePassword;
    

    
    public function init() {
 	$this->_helper->_acl->allow(null); 
        $this->_googleID = $this->_helper->config()->webservice->google->username;
        $this->_googlePassword = $this->_helper->config()->webservice->google->password;
        
    }
    
    public function indexAction() {
        
        $client = Zend_Gdata_ClientLogin::getHttpClient($this->_googleID, 
                $this->_googlePassword, Zend_Gdata_Analytics::AUTH_SERVICE_NAME);
        $service = new Zend_Gdata_Analytics($client);
        $dimensions = array(
          Zend_Gdata_Analytics_DataQuery::DIMENSION_MEDIUM,
          Zend_Gdata_Analytics_DataQuery::DIMENSION_SOURCE,
          Zend_Gdata_Analytics_DataQuery::DIMENSION_BROWSER_VERSION,
          Zend_Gdata_Analytics_DataQuery::DIMENSION_MONTH,
        );

        $query = $service->newDataQuery()->setProfileId('456871')
          ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES) 
          ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS) 
        //  ->addFilter("ga:browser==Firefox")
          ->setStartDate('2011-12-01') 
          ->setEndDate('2011-12-31') 
          ->addSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS, true)
          ->addSort(Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES, false)
          ->setMaxResults(50);

        foreach($dimensions as $dim){
          $query->addDimension($dim);
        }

        $result = $service->getDataFeed($query); 
        $results = array();
        foreach($result as $row){
        $data = array(
        'source' => $row->getDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_SOURCE),
        'medium' => $row->getDimension('ga:medium'),
        'visits' => $row->getMetric('ga:visits'),
        'bounce' => $row->getValue('ga:bounces')          
        );
        $results[] = $data;
        } 
     
        $this->view->result = $results;
    }
}

