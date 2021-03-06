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

class Pas_Controller_Action_Helper_Akismet 
    extends Zend_Controller_Action_Helper_Abstract {
    
    protected $_config;
    
    protected $_baseurl;
    
    protected $_akismetKey;
    
    public function __construct(){
        $this->_config = Zend_Registry::get('config');
        $this->_baseurl = Zend_Registry::get('siteurl');
        $this->_akismetKey = $this->_config->webservice->akismet->apikey;
    }
    
    public function direct(){
    $akismet = new Zend_Service_Akismet($this->_akismetKey, $this->_baseurl);    
    return $aksimet;
    }
}