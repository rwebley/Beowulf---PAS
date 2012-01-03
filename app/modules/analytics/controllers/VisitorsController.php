<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VisitorsController
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Analytics_VisitorsController 
    extends Pas_Controller_Action_Admin {
   
    public function init(){
        $this->_helper->Acl->allow(null);
    }
    
    public function indexAction(){
        
    }
    
    public function bydayAction(){
        
    }
    
    public function byyearAction(){
        
    }
    
    public function bymonthAction(){
        
    }
}


?>
