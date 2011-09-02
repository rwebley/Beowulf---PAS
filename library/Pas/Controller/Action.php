<?php

class Pas_Controller_Action extends Zend_Controller_Action {
    
    public function init()
	{
	
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');

    }
   public function postDispatch()
    {
	$this->view->messages = $this->_flashMessenger->getMessages();
    }

}
