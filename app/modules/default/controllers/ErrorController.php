<?php
class ErrorController extends Pas_Controller_ActionAdmin {

	protected $_log;

	public function init() {
		$this->_log = Zend_Registry::get('log');
		$this->_helper->_acl->allow(NULL);
        $this->_helper->layout()->setLayout('database');
		$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$response = $this->getResponse();
		$response->insert('header', $this->view->render('structure/header.phtml'));
		$response->insert('breadcrumb', $this->view->render('structure/breadcrumb.phtml'));
		$response->insert('navigation', $this->view->render('structure/navigation.phtml'));
		$response->insert('footer', $this->view->render('structure/footer.phtml'));
		$response->insert('messages', $this->view->render('structure/messages.phtml'));
		$response->insert('contexts',$this->view->render('structure/contexts.phtml'));
		}
		
	private static function addPadding ($number) {
		$formattedNumber = str_pad($number, 5, '_', STR_PAD_RIGHT);
		return str_replace('_', '&nbsp;', $formattedNumber );
	}	
	
	public function whois() {
	$user = $this->getAccount();
	if(is_null($user->username)) {
	$name = 'A public user';
	$string = $name .' had this error displayed below.';
	} else {
	$name = $user->fullname;
	$account = $user->username;
	$string = $name . ' with the username ' . $account . ' had this error displayed below.';
	}	
	return $string; 
	}
	
	public function ipAgent() {
	$ip = $_SERVER['REMOTE_ADDR'];
	$method = $_SERVER['REQUEST_METHOD'];
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$referrer = $_SERVER['HTTP_REFERER'];
	$details = 'Connection originated from: ' . $ip ."\n";
	$details .= 'Connection method: '. $method."\n";
	$details .= 'User agent: ' . $agent."\n";
	if(!is_null($referrer)) {
	$details .= 'Referrer: ' . $referrer."\n";
	}
	return $details;
	}
	
	public function sendEmail($message) {
	$mail = new Zend_Mail();
	$mail->addHeader('X-MailGenerator', 'The Portable Antiquities Scheme - Beowulf');
	$mail->setBodyText('Server down!' . "\n" . $message);
	$mail->setFrom('info@finds.org.uk', 'The Portable Antiquities Scheme');
	$mail->addTo('danielpett@gmail.com', 'Daniel Pett');
	$mail->setSubject('Do something about server');
	$mail->send();
	}
	
	private static function formatArgValues ($args) {
		$values = array();
		foreach($args as $arg) {
			if (is_object($arg)) {
				$values[] = get_class($arg);
			} elseif (is_null($arg)) {
				$values[] = 'NULL';
			} elseif (is_array($arg)) {
				$values[] = 'Array('.count($arg).')';
			} elseif (is_string($arg)) {
				$values[] = "'$arg'";
			} else {
				$values[] = (string) $arg;
			}
		}
		return implode(', ', $values);
	}
	
	private static function generateCodeBlock ($errorLine, $filePath) {
		$lines = explode( '<br />', highlight_file($filePath, true) );
		$errorID = '';
		for($n = 0; $n < count($lines); $n++) {
			$lineNumber = $n+1;
			$paddedNumber =  self::addPadding( $lineNumber );
			$errorClass = '';
			list($errorClass, $errorID) = ($lineNumber == $errorLine) ? array('errorLine', md5( $errorLine.$filePath ) ) : array('',$errorID);
			$lines[ $n ] = "<span class=\"lineNumbers $errorClass\" id=\"$errorID\">$paddedNumber</span>".$lines[ $n ];
		}
		return "<div class=\"codeFile\" errorid=\"$errorID\">".implode("<br />\n", $lines).'</div>';
	}	
	
public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
	
	public function indexAction()
	{

	}	
	public function errorAction($extended =false) 
    { 
        // Ensure the default view suffix is used so we always return good 
        // content
		$this->view->headTitle('An error has occurred.');
        $this->_helper->viewRenderer->setViewSuffix('phtml');
        // Grab the error object from the request
        $errors = $this->_getParam('error_handler'); 
		if($errors) {
		$data = array();
		$data['errorMessage'] = $errors['exception']->getMessage();
		$data['errorType'] = $errors['type'];
		$data['errorCode'] = $errors['exception']->getCode();
		$data['errorFilePath'] = $errors['exception']->getFile();
		$data['errorLineNumber'] = $errors['exception']->getLine();
		$data['errorLineNumberFormatted'] = self::addPadding( $errors['exception']->getLine() );
		$data['traceStack'] = array();
		foreach( $errors['exception']->getTrace() as $trace) {
			if ($extended) {
				$trace['lineNumberFormatted'] = self::addPadding( $trace['line'] );
				$trace['codeBlock'] = self::generateCodeBlock( $trace['line'], $trace['file'] );
			}
			$data['traceStack'][] = $trace;
		}
		
			$compiledTrace = '';
		foreach($data['traceStack'] as $trace) {
			$compiledTrace .= '<li class="codeBlock">'."\n";
			if ($extended) {
				$compiledTrace .= '<div class="filePath"><a class="openLink" href="javascript://">open</a>'.$trace['file'].'</div>'."\n";
			} else {
				$compiledTrace .= '<div class="filePath"><span class="openLink">'.$trace['line'].'</span> '.$trace['file'].'</div>'."\n";
			}
			$compiledTrace .= '<div class="functionCall">'.$trace['class'].'->'.$trace['function'].'('.self::formatArgValues($trace['args']).')</div>'."\n";
			if ($extended) {
				$compiledTrace .= $trace['codeBlock']."\n";
			}
			$compiledTrace .= '</li>'."\n";
		}
		
		//Zend_debug::dump($compiledTrace);
     
      switch ($errors->type) {
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        // 404 error -- controller or action not found
        $this->getResponse()->setHttpResponseCode(404);
        $this->view->message = 'Page not found';
        $this->view->code  = 404;
        if ($errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER) {

          $this->view->info = sprintf(
                      'Unable to find controller "%s" in module "%s"',
                      $errors->request->getControllerName(),
                      $errors->request->getModuleName()
                    );
        }
        if ($errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION) {
          $this->view->message = sprintf(
                      'Unable to find action "%s" in controller "%s" in module "%s"',
                      $errors->request->getActionName(),
                      $errors->request->getControllerName(),
                      $errors->request->getModuleName()
                    );
		$priority = Zend_Log::NOTICE;
        if ($log = $this->getLog()) {
            $log->log($this->view->message . ' ' . $errors->exception, $priority, $errors->exception);
            $log->log('Request Parameters' . ' ' . $errors->request->getParams(), $priority, $errors->request->getParams());
        }        
		$this->view->compiled = $compiledTrace;

        }
		break;
	  case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
	  	switch (get_class($errors['exception'])) {
                    case 'Pas_NotAuthorisedException' :
					        $this->getResponse()->setHttpResponseCode(401);
					        $this->view->message = 'This record falls outside your access levels. If you contact us, 
					        we can let you know when you can see it. This normally means the record is not on public view.';
							$this->view->info  = $errors->exception;
							$this->view->code  = 403;
							$this->view->headTitle('Not authorised.');
                    break;
					case 'Pas_BadJuJuException':
					        $this->getResponse()->setHttpResponseCode(500);
							$this->view->message = 'Bad JuJu on your part!';
							$this->view->info  = $errors->exception;
							$this->view->code  = 500;
							$this->view->compiled = $compiledTrace;
	
                    break;
					case 'Pas_ParamException':
					        $this->getResponse()->setHttpResponseCode(500);
							$this->view->message = 'Something has gone wrong with what you are trying to do';
							$this->view->info  = $errors->exception;
							$this->view->code  = 500;
							$this->view->compiled = $compiledTrace;
							
                    break;
					case 'Zend_Db_Statement_Exception' :
				        $this->getResponse()->setHttpResponseCode(503);
						$this->view->info  = $errors->exception;
                        $this->view->code = 503;
						$this->view->message = 'Somebody had fat fingers and made a boo boo with the SQL.';
						$this->view->compiled = $compiledTrace;
						$message = $this->whois()."\n";
						$message .= $this->view->CurUrl()."\n";
						$message .= $this->ipAgent()."\n";
						$message .= $errors->exception;
						$this->sendEmail($message);
							
                    break;
					case 'Zend_Db_Adapter_Exception':
						$this->getResponse()->setHttpResponseCode(500);
						$this->view->message = 'Server has gone away';
						$message = $this->whois()."\n";
						$message .= $this->view->CurUrl()."\n";
						$message .= $this->ipAgent()."\n";
						$message .= $errors->exception;
						$this->sendEmail($message);
					break;
					case 'Zend_Db_Table_Exception':
						if(preg_match("/primary/i",$errors->exception->getMessage())){
						$cache = Zend_Registry::get('cache');
						$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
						$this->getResponse()->setHttpResponseCode(500);
						$this->view->message = 'Cache file needs a clean! Please try again.';
						$message = $this->whois()."\n";
						$message .= $this->view->CurUrl()."\n";
						$message .= $this->ipAgent()."\n";
						$message .= $errors->exception;
						$this->sendEmail($message);	
						}
					break;
					case 'Zend_Db_Statement_Mysqli_Exception':
					 	$this->getResponse()->setHttpResponseCode(500);
						$this->view->message = 'Server has gone away';
						$message = $this->whois()."\n";
						$message .= $this->view->CurUrl()."\n";
						$message .= $errors->exception;
						$this->sendEmail($message);
					break;
					case 'PDOException':
					 	$this->getResponse()->setHttpResponseCode(500);
						$this->view->message = 'PDO exception has been caught';
						$message = $this->whois()."\n";
						$message .= $this->view->CurUrl()."\n";
						$message .= $this->ipAgent()."\n";
						$message .= $errors->exception;
						$this->sendEmail($message);
					break;
			
					case 'Zend_View_Exception' :
				        $this->getResponse()->setHttpResponseCode(500);
                        $this->view->code =500;
						$this->view->message = 'Rendering of view error.';
						$this->view->compiled = $compiledTrace;
                    break;
	}
		break;
     default:
        // application error
        $this->getResponse()->setHttpResponseCode(500);
        $this->view->message = 'Application error';
        $this->view->code  = 500;
        $this->view->info  = $errors->exception;
	      break;      
    }

        
        // pass the actual exception object to the view
        $this->view->exception = $errors->exception; 
        
        // pass the request to the view
        $this->view->request   = $errors->request; 
		//Zend_Debug::dump($errors->type);
		} else {
		
		$this->_redirect('/error/notauthorised');
		}
    } 

public function notauthorisedAction()
{
        $this->getResponse()->setHttpResponseCode(401);
		 $this->_helper->layout()->setLayout('database');
		$this->view->headTitle('None shall pass');
		$this->view->message = 'You are not authorised to view this resource';
        $this->view->code  = 401;
        
}				
	public function accountproblemAction(){
		
	}				
}