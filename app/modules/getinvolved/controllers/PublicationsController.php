<?php 
/** Controller for manipulating publications data
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Move adding data and editing into model
*/
class Getinvolved_PublicationsController extends Pas_Controller_ActionAdmin {

	protected $_cache = NULL;
	protected $_config = NULL;
	
	/** Initialise the ACL, cache and config
	*/ 
    public function init() {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->acl->allow(null);
		$this->_config = Zend_Registry::get('config');
		$this->_cache = Zend_Registry::get('rulercache');
    }
	
    /** Render documents on the index page
	*/ 
	public function indexAction() {
			$content = new Content();
			$this->view->contents = $content->getFrontContent('publications');
			$service = Zend_Gdata_Docs::AUTH_SERVICE_NAME;
			$client = Zend_Gdata_ClientLogin::getHttpClient($this->_config->webservice->google->username, 
		$this->_config->webservice->google->password, $service);
		$docs = new Zend_Gdata_Docs($client);
		
		$feed = $docs->getDocumentListFeed();
		$documents = array();	
		foreach ($feed->entries as $entry) {
		$title = $entry->title;
		foreach ($entry->link as $link) {
	    if ($link->getRel() === 'alternate') {
	    $altlink = $link->getHref();
	    }
		}
	    $documents[]=array('title' => $title, 
	    'altlink' => $altlink,
	    'updated' => $entry->updated,
	    'type' => $entry->content->type,
	    'published' => $entry->published
	    );    
		}
		$this->view->documents = $documents;
	}
	
	/** Handle the requests for publications
	*/ 
	public function requestAction() {
		$form = new RequestForm();
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
		$formData = $this->_request->getPost();
		if (($formData['email'] != NULL) && ($formData['maillist'] == 1)) {
    	$form->getElement('email')->addValidator(new Zend_Validate_Db_NoRecordExists('mailinglist', 'email'))
			->addErrorMessage('Your email address is already on our mailing list, uncheck mailing list sign up if you are just requesting more publications')->setRequired(true)  ;
		} else {
		$form->getElement('email')->addValidator('EmailAddress',array( 'allow' => Zend_Validate_Hostname::ALLOW_DNS,
        	'mx'    => true,'deep'  => true
    	)) 
			->setRequired(true)  
			->addErrorMessage('Please enter a valid email address!');
		}
		if ($form->isValid($formData)) {
			$ip = $_SERVER['REMOTE_ADDR'];
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$email = $formData['email'];
			$title = $formData['title'];
			$fullname = $formData['fullname'];
			$created = $this->getTimeForForms();
			$createdBy = $this->getIdentityForForms();
			$address = $formData['address'];
			$county = $formData['county'];
			$postcode = $formData['postcode'];
			$town_city = $formData['town_city'];
			$country = $formData['country'];
			$tel = $formData['tel'];
			$maillist = $formData['maillist'];
		if(isset($formData['maillist'])) {
			$insertData = array();
			$insertData['fullname'] = $fullname;
			$insertData['email'] = $email;
			$insertData['address'] = $address;
			$insertData['town_city'] = $town_city;
			$insertData['postcode'] = $postcode;
			$insertData['county'] = $county;
			$insertData['country'] = $country;
			$insertData['created'] = $created;
			$insertData['createdBy'] = $createdBy;
			$insertData['tel'] = $tel;
			$insertData['ip_address'] = $ip;
			$list = new MailingList();
			$insert = $list->insert($insertData);
			$signup = 'Dear '.$fullname."\n";
			$signup .= 'Thank you for registering for our mailing list. The Scheme will not pass on your details
			to third parties. To unsubscribe from our list please use this link:'."\n";
			$signup .= 'http://www.antiquities.7pillarsofwisdom.co.uk/getinvolved/publications/maillist/'.$email."\n";
			$signup .= "\n".'Yours,'."\n";
			$signup .= 'The Portable Antiquities Scheme';
			$mail = new Zend_Mail();
			$mail->setBodyText($signup);
			$mail->setFrom($email, $fullname);
			$mail->addTo($email, $fullname);
			$mail->setSubject('Request for Scheme Publications');
			$mail->send(); 
			}
			$mailtemp = 'A request was submitted from '. $title. ' '.$fullname.' for the following Scheme publications.'."\n\n";
			if(isset($formData['leaflets'])) {
			$leaflets = 'Requested leaflets: '."\n";
			foreach($formData['leaflets'] as $key => $value) {
			$leaflets .= $value."\n";
			}
			$mailtemp .= $leaflets."\n";
			}
			if(isset($formData['reports'])){
			$reports = 'Requested reports: '."\n";
			foreach($formData['reports'] as $key => $value) {
			$reports .= $value."\n";
			}		
			$mailtemp .= $reports."\n";
			} 
			if(isset($formData['codes'])){
			$codes = 'Requested codes of practice: '."\n";
			foreach($formData['codes'] as $key => $value) {
			$codes .= $value."\n";
			}		
			$mailtemp .= $codes."\n"; 
			}
			if(isset($formData['treasure'])){
			$treports = 'Requested Treasure reports: '."\n";
			foreach($formData['treasure'] as $key => $value) {
			$treports .= $value."\n";
			}
			$mailtemp .= $treports."\n";
			} 
			$mailtemp .= 'The publication should be sent to: '."\n";
			$mailtemp .= $address."\n";
			$mailtemp .= $town_city."\n";
			$mailtemp .= $county."\n";
			$mailtemp .= $postcode."\n";
			$mailtemp .= $country."\n";
			$mailtemp .= 'Tel:'.$tel."\n";
			$mailtemp .= 'Email: '.$email."\n";
			
				
			$mail = new Zend_Mail();
			$mail->setBodyText($mailtemp);
			$mail->setFrom($email, $fullname);
			$mail->addTo('dpett@british-museum.ac.uk', 'The Portable Antiquities Scheme');
			$mail->addCC($email, $fullname);
			$mail->setSubject('Request for Scheme Publications');
			$mail->send();
			
			$this->_flashMessenger->addMessage('Your request has been submitted');
			$this->_redirect('getinvolved/publications/');
			} 
			else 
			{
			$this->_flashMessenger->addMessage('There are problems with your submission');
			$form->populate($formData);
			}
			}
		}

}