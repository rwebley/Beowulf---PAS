<?php
 /**
 * ACL integration
 *
 * Places_Controller_Action_Helper_Acl provides ACL support to a 
 * controller.
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @package    Controller
 * @subpackage Controller_Action
 * @copyright  Copyright (c) 2007,2008 Rob Allen
 * @license    http://framework.zend.com/license/new-bsd  New BSD License
 */
class Pas_Controller_Action_Helper_Mailer extends Zend_Controller_Action_Helper_Abstract {


	protected $_view;
	
        protected $_templates;
        
        protected $_mail;
        
        protected $_markdown;
        
	public function init(){
	$this->_view = new Zend_View();	
        $this->_mail = new Zend_Mail('utf-8');
        $this->_templates = APPLICATION_PATH . '/views/email/';
        $this->_markdown = new Pas_MarkDownify();
	}
	
	public function direct($assignData, $type, $to, $from, $bcc ){
	$script = $this->_getTemplate($type);
        $display = $this->_view->setScriptPath($this->_templates);
        $display->assign($assignData);
        $html = $display->render($script);
        $text = $this->_stripper($html);
        $this->_setUpSending($to, $from, $bcc);
        $this->_mail->addHeader('X-MailGenerator', 'Portable Antiquities Scheme');
        $this->_mail->setBodyHtml($html);
        $this->_mail->setBodyText($text);
        $this->_sendIt();
	}
        
        protected function _setUpTo($to, $from, $bcc){
            if(is_array($to)){
            foreach($to as $k => $v) {
                $this->_mail->addTo($k, $v);   
            }
            } else {
                throw new Exception('There must be an addressee', 500);
            }
            if(is_array($from)){
            foreach($from as $k => $v) {
                $this->_mail->addFrom($k, $v);   
            }   
            } else {
                $this->_mail->addFrom('info@finds.org.uk', 'The PAS head office');
            }
            if(is_array($bcc)){
                foreach($to as $k => $v) {
                $this->_mail->addBcc($k, $v);   
            }
            }
        }    
        
        
        protected function _stripper($string){
            return $this->_markdown->parseString($string);
        }
        
        protected function _sendIt(){
            return $this->_mail->send();
        }
        protected function _getTemplate($type){
            if(!is_null($type)){
                switch($type){
                    case 'newUser':
                        $script = 'newUser.phtml';
                        break;
                    case 'commentPublished':
                        $script = 'commentPublished';
                        break;
                    case 'workflowChange':
                        $script = 'workflowChanged.phtml';
                        break;
                    default:
                        throw new Exception('internal error',500);
                }
                return $script;
            } else {
                throw new Exception('That template does not exist',500);
            }
        }
}