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
	
	protected $_types;
	
        
	public function init(){
		$this->_view = new Zend_View();	
        $this->_mail = new Zend_Mail('utf-8');
        $this->_templates = APPLICATION_PATH . '/views/scripts/email/';
        $this->_markdown = new Pas_Filter_EmailTextOnly();
        $this->_types = $this->getTypes();
	}
	
	
	private function getTypes() {
        $dir = new DirectoryIterator($this->_templates);
        $files = array();
        foreach ($dir as $dirEntry) {
        	
            if ($dirEntry->isFile() && !$dirEntry->isDot()) {
                $filename = $dirEntry->getFilename();
                $pathname = $dirEntry->getPathname();
                // Check for all PHP files, ignore the abstract class
                if(preg_match('/^(.+)\.phtml$/', $filename, $match)) {
                    $files[] = $match[1];
                }
            }
        }
        return $files;
    }
    
	public function direct(array $assignData = NULL, $type, array $to = NULL,
			 array $from = NULL, array $bcc = NULL, array $attachments = NULL ){
		$script = $this->_getTemplate($type);
        $message = $this->_view->setScriptPath($this->_templates);
        $this->_view->addHelperPath('Pas/View/Helper/', 'Pas_View_Helper'); 
        $message->assign($assignData);
        $html = $message->render($script);
        $text = $this->_stripper($html);
		$this->_mail->addHeader('X-MailGenerator', 'Portable Antiquities Scheme');
        $this->_mail->setBodyHtml($html);
        $this->_mail->setBodyText($text);

		$this->_setUpSending($to, $from, $bcc);
		                Zend_Debug::dump($this->_mail);
        exit;
        if(is_array($attachment)){
        	$this->_addAttachments($attachments);
        } else {
        	throw new Exception('Attachment paths need to be an array');
        }

        $this->_sendIt();
	}
        
	protected function _addttachments(array $attachments){
		if(is_array($attachments)){
			foreach($attachments as $k => $v){
				$file = file_get_contents($v);
				Zend_Debug::dump($file);
				$addition = $this->_mail->createAttachment($file);
				$addition->disposition = Zend_Mime::DISPOSITION_INLINE;
				$addition->encoding    = Zend_Mime::ENCODING_BASE64;
				$addition->filename	   = '';
			}
		} else {
			throw new Exception('The attachment list is not an array.');
		}
	}
	
		protected function _setUpSending($to, $from, $bcc){
       	if(is_array($to)){
            foreach($to as $k => $v) {
                $this->_mail->addTo($k, $v);   
            }
            } else {
                throw new Exception('There must be an addressee', 500);
            }
            if(is_array($from)){
            foreach($from as $email => $v) {
                $this->_mail->setFrom($k, $v);   
            }   
            } else {
                $this->_mail->setFrom('info@finds.org.uk', 'The PAS head office');
            }
            if(is_array($bcc)){
                foreach($bcc as $k => $v) {
                $this->_mail->addBcc($k, $v);   
            }
            }
        }    
        
        
        protected function _stripper($string){
        	$clean = $this->_markdown->filter($string);
        	return $clean;
        }
        
        protected function _sendIt(){
            return $this->_mail->send();
        }
        
        protected function _getTemplate($type){
            if(!is_null($type) && in_array($type, $this->_types)){
                $script = $type . '.phtml';
                } else {
                throw new Exception('That template does not exist',500);
            }
            return $script;
        }
}