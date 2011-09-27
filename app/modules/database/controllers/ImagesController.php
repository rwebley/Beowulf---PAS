<?php
/** Controller for displaying images
 * @todo replace some of functions when solr is installed
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Database_ImagesController extends Pas_Controller_Action_Admin
{
	protected $_auth, $_images, $_cache, $_zoomifyObject;
	/** Set up the ACL and contexts
	*/			
	public function init() {
		$this->_helper->_acl->allow('public',array('image','zoom','index'));
		$this->_helper->_acl->allow('member',array('add','delete','edit'));
		$this->_helper->_acl->allow('flos',null);
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
		$this->_helper->contextSwitch()
			 ->setAutoDisableLayout(true)
			 ->addContext('csv',array('suffix' => 'csv'))
 			 ->addContext('kml',array('suffix' => 'kml'))
  			 ->addContext('rss',array('suffix' => 'rss'))
			 ->addContext('atom',array('suffix' => 'atom'))
			 ->addActionContext('image', array('xml','json'))
             ->initContext();
	$this->_auth = Zend_Registry::get('auth');
	$this->_images = new Slides();
	$this->_cache = Zend_Registry::get('cache');
	$this->_zoomifyObject = new ZoomifyFileProcessor();
    }
	const REDIRECT = 'database/images/';
	
	const PATH = './images/';
	
	/** Retrieve the user's details
	*/			
	private function getUserDetails()	{
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	return (array)$user;
	}
	}
	/** Display index page of images
	*/		
	public function indexAction() {
	$this->view->paginator = $this->_images->getAllImages($this->_getAllParams());
	$form = new ImageFilterForm();
	$this->view->form = $form;
	$form->old_findID->setValue($this->_getParam('old_findID'));
	$form->label->setValue($this->_getParam('label'));
	$form->broadperiod->setValue($this->_getParam('broadperiod'));
	$form->county->setValue($this->_getParam('county'));
	
	if ($this->_request->isPost() && ($this->_getParam('submit') != NULL)) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
			$params = array_filter($formData);
			unset($params['submit']);
			unset($params['action']);
			unset($params['controller']);
			unset($params['module']);
			unset($params['page']);
			unset($params['csrf']);

			$where = array();
			foreach($params as $key => $value)
			{
				if($value != NULL){
				$where[] = $key . '/' . urlencode(strip_tags($value));
				}
			}
				$whereString = implode('/', $where);
	$query = $whereString;
	$this->_redirect(self::REDIRECT.'index/'.$query.'/');
	} else {
	$form->populate($formData);
	}
	}
	}
	/** Add a new image
	*/			
	public function addAction()	 {
	$form = new ImageForm();
	$form->submit->setLabel('Submit a new image.');
	$user = $this->getUserDetails();
	$username = $user['username'];
	if(is_dir(self::PATH . $username)){
	$form->image->setDestination(self::PATH . $username);
	} else {
	$path = mkdir(self::PATH . $username);
	$form->image->setDestination($path);
	}
	 
	$this->view->form = $form;
	$savePath = self::PATH . $username .'/medium/'; 
	$thumbPath = self::PATH . 'thumbnails/';
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();	{
    $upload = new Zend_File_Transfer_Adapter_Http();
	
    if ($form->isValid($formData)) {
    $upload = new Zend_File_Transfer_Adapter_Http();
   	$upload->addValidator('NotExists', false,array(self::PATH . $username));
	$filesize = $upload->getFileSize();
	if($upload->isValid()) 	{
	$filename = $form->getValue('image');
	$label = $formData['label'];
	$secuid = $this->secuid();
	$insertData = array();
	$insertData['filename'] = $filename;
	$insertData['label'] = $label;
	$insertData['county'] = $form->getValue('county');
	$insertData['period'] = $form->getValue('period');
	$insertData['filedate'] = $this->getTimeForForms();
	$insertData['created'] = $this->getTimeForForms();
	$insertData['createdBy'] = $this->getIdentityForForms();
	$insertData['filesize'] = $filesize;
	$insertData['imagerights'] = $form->getValue('copyrighttext');
	$insertData['secuid'] = $secuid;
	//$insertData['mimetype'] = $mimetype;
	foreach ($insertData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($insertData[$key]);
      }
    }	
	
	$upload->receive();
	
	$location = self::PATH . $username . '/' . $filename;
	$id = $this->_images->insert($insertData);
	
	$largepath   = self::PATH . $username . '/';
	$mediumpath  = self::PATH . $username . '/medium/';
	$smallpath   = self::PATH . $username . '/small/';
	$displaypath = self::PATH . $username.'/display/';
	$thumbpath   = self::PATH . 'thumbnails/';
	$name = substr($filename, 0, strrpos($filename, '.')); 
	$ext = '.jpg';
	//create medium size
	$phMagick = new phMagick($location, $mediumpath.$name.$ext);
	$phMagick->resize(500,0);
	$phMagick->convert();
	//Very small size
	$phMagick = new phMagick($location, $smallpath.$name.$ext);
	$phMagick->resize(40,0);
	$phMagick->convert();
	//Record display size
	$phMagick = new phMagick($location, $displaypath.$name.$ext);
	$phMagick->resize(0,150);
	$phMagick->convert();

	//Thumbnail size
	$phMagick = new phMagick($location, $thumbpath.$id.$ext);
	$phMagick->resize(100,100);
	$phMagick->convert();
 	
	$linkData = array();
	$linkData['find_id'] = $this->_getParam('findID');
	$linkData['image_id'] = $secuid;
	$linkData['secuid'] = $this->secuid();
	$imagelink = new FindsImages();
	$insertedlink = $imagelink->insert($linkData);
	$this->_cache->remove('findtoimage' . $this->_getParam('id'));

	$this->_flashMessenger->addMessage('The image has been resized and added!');
	$this->_redirect('/database/artefacts/record/id/' . $this->_getParam('id')); 
	} else {
	$this->_flashMessenger->addMessage('There is a problem with your upload. Probably that image exists.');
	$this->view->errors = $upload->getMessages();
	} 
	} else { 
	$form->populate($formData);
	$this->_flashMessenger->addMessage('Check your form for errors dude');
	}
	}
	}
	}

	/** View details of a specific image
	*/		
	public function imageAction() {
	if($this->_getParam('id',false)) {
	$images = new Slides();
	$this->view->images = $images->getImage((int)$this->_getParam('id'));
	$finds = new Slides();
	$this->view->finds = $finds->getLinkedFinds((int)$this->_getParam('id'));
	} else {
	throw new Exception('No parameter found on the url string');
	}
	}
	
	/** Edit a specific image
	*/	
	public function editAction() {
	if($this->_getParam('id',0)) {
	$form = new ImageEditForm();
	$form->submit->setLabel('Update image..');
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$updateData = array();
	$updateData['label'] = $form->getValue('label');
	$updateData['imagerights'] = $form->getValue('imagerights');
	$updateData['county'] = $form->getValue('county');
	$updateData['period'] = $form->getValue('period');
	$updateData['updated'] = $this->getTimeForForms();
	$updateData['updatedBy'] = $this->getIdentityForForms();
	foreach ($updateData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($updateData[$key]);
      }
    }
	$images = new Slides();
	$where =  $images->getAdapter()->quoteInto('imageID = ?', $this->_getParam('id'));
	$rotate = $form->getValue('rotate');
	$filename = $form->getValue('filename');
	$imagedir = $form->getValue('imagedir');
	$regenerate = $form->getValue('regenerate');
	$path = './'.$imagedir.$filename;
	$largepath = './'.$imagedir;
	$mediumpath = './'.$imagedir.'medium/';
	$smallpath = './'.$imagedir.'small/';
	$displaypath = './'.$imagedir.'display/';
	$thumbpath = self::PATH . 'thumbnails/';
	$id = $this->_getParam('id');
	$name = substr($filename, 0, strrpos($filename, '.')); 
	$ext = '.jpg';
	if(isset($rotate)) {
	//rotate original 
	$phMagickOriginal= new phMagick($largepath.$filename, $largepath.$filename);
	$phMagickOriginal->rotate($rotate);
	//rotate image for medium
	if(file_exists($mediumpath.$name.$ext)) {
	$phMagickMedium = new phMagick($mediumpath.$name.$ext, $mediumpath.$name.$ext);
	$phMagickMedium->rotate($rotate);
			Zend_Debug::dump($phMagickMedium);

	} else {
	$phMagickMediumCreate = new phMagick($largepath.$filename, $mediumpath.$name.$ext);
    $phMagickMediumCreate->resize(500,0);
    $phMagickMediumCreate->rotate($rotate);
	$phMagickMediumCreate->convert();
	Zend_Debug::dump($phMagickMediumCreate);

	}
	//rotate small image
	if(file_exists($smallpath.$name.$ext)) {
	$phMagickSmall = new phMagick($smallpath.$name.$ext, $smallpath.$name.$ext);
	$phMagickSmall->rotate($rotate);
	//Zend_Debug::dump($phMagickSmall);

	} else {
	$phMagickSmallCreate = new phMagick($largepath.$filename, $smallpath.$name.$ext);
    $phMagickSmallCreate->resize(40,0);
    $phMagickSmallCreate->rotate($rotate);
	$phMagickSmallCreate->convert();
	//Zend_Debug::dump($phMagickSmallCreate);

	}
	//rotate display image
	if(file_exists($displaypath.$name.$ext)) {
	$phMagickDisplay = new phMagick($displaypath.$name.$ext, $displaypath.$name.$ext);
	$phMagickDisplay->rotate($rotate);
	Zend_Debug::dump($phMagickDisplay);

	} else {
	$phMagickDisplayCreate = new phMagick($largepath.$name.$ext, $displaypath.$name.$ext);
    $phMagickDisplayCreate->resize(0,150);
    $phMagickDisplayCreate->rotate($rotate);
	$phMagickDisplayCreate->convert();
	//Zend_Debug::dump($phMagickDisplayCreate);
	}
	//rotate thumbnail
	if(file_exists($thumbpath.$id.'.jpg')) {
	$phMagickThumb = new phMagick($thumbpath.$id.'.jpg', $thumbpath.$id.'.jpg');
	$phMagickThumb->rotate($rotate);
	//Zend_Debug::dump($phMagickThumb);
	} else {
	$thumbpath = self::PATH . 'thumbnails/';
	$originalpath = $path;
	$phMagickRegen = new phMagick($originalpath, $thumbpath.$id.'.jpg');
	$phMagickRegen->resize(100,0);
	$phMagickRegen->convert();
	
	}
	}
	
	if(isset($regenerate)) {
	$thumbpath = self::PATH . 'thumbnails/';
	$originalpath = $path;
	$phMagickRegen = new phMagick($originalpath, $thumbpath.$id.'.jpg');
	$phMagickRegen->resize(100,0);
	$phMagickRegen->convert();
	}
	
	$update = $images->update($updateData,$where);
		$cache = Zend_Registry::get('cache');
	$cache->remove('findtoimage' . $this->_getParam('id'));

	$this->_flashMessenger->addMessage('Image and metadata updated!');
	$this->_redirect(self::REDIRECT . 'image/id/' . $this->_getParam('id'));
	
	} else {
	$form->populate($formData);
	}
	} else {
	$id = (int)$this->_request->getParam('id', 0);
	if ($id > 0) {
	$images = new Slides();
	$image = $images->getImage($id);
	$form->populate($image['0']);
	}
	}
	} else {
		throw new Exception($this->_missingParameter);
	}
	}
	/** Delete an image
	*/		
	public function deleteAction() {
	$this->_flashMessenger->addMessage('Image and links deleted!');
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {

	$imagedata = $slides->getFileName($id);
	$filename = $imagedata['0']['f'];
	$splitf = explode('.',$filename);
	$spf = $splitf['0'];
	$imagedir = $imagedata['0']['imagedir'];
	$imagenumber = $imagedata['0']['imageID'];
	$zoom = './'.$imagedir.'zoom/'.$spf.'_zdata';
	$thumb = self::PATH . 'thumbnails/'.$imagenumber.'.jpg';
	$small = './'.$imagedir.'small/'.$filename;
	$display = './'.$imagedir.'display/'.$filename;
	$medium = './'.$imagedir.'medium/'.$filename;
	$original = './'.$imagedir.$filename;
	$where = 'imageID = ' . $id;
	
	$this->_images->delete($where);
	$linked = new FindsImages();
	$wherelinks = array();
	$wherelinks[] = $linked->getAdapter()->quoteInto('image_id = ?', $imagedata['0']['secuid']);
	$deletelinks = $linked->delete($wherelinks);
	unlink($thumb);
	unlink($display);
	unlink($small);
	unlink($original);
	unlink($medium);
	unlink(strtolower($thumb));
	unlink(strtolower($display));
	unlink(strtolower($small));
	unlink(strtolower($original));
	unlink(strtolower($medium));
	unlink($zoom);
	$cache = Zend_Registry::get('cache');
	$cache->remove('findtoimage' . $imagedata['0']['id']);
	
	}
	$this->_flashMessenger->addMessage('Image and metadata deleted!');
	$this->_redirect('/database/myscheme/myimages/');
	}  else  {
	$id = (int)$this->_request->getParam('id');
	if ((int)$id > 0) {
	$slides = new Slides();
	$this->view->slide = $slides->fetchRow('imageID ='.$id);
	}
	}
	}
	/** Link an image to a record
	*/		
	public function linkAction() {
	if($this->_getParam('imageID',false)) {
	$form = new ImageLinkForm();
	$this->view->form = $form;
	if ($this->_request->isPost()) {
	$formData = $this->_request->getPost();
	if ($form->isValid($formData)) {
	$updateData = array();
	$updateData['image_id'] = $this->_getParam('imageID');
	$updateData['find_id'] = $form->getValue('findID');
	$updateData['secuid'] = $this->secuid();
	$updateData['created'] = $this->getTimeForForms();
	$updateData['createdBy'] = $this->getIdentityForForms();
	foreach ($updateData as $key => $value) {
      if (is_null($value) || $value=="") {
        unset($updateData[$key]);
      }
    }
	$images = new FindsImages();
	$insert = $images->insert($updateData);
	$findID = $form->getValue('findID');
	$finds = new Finds();
	$returns = $finds->fetchRow($finds->select()->where('secuid = ?',$findID));
	
	$returnID = $returns->id;

	$cache->remove('findtoimage' . $returnID);
	$this->_flashMessenger->addMessage('You just linked an image to this record');
	$this->_redirect('/database/artefacts/record/id/'.$returnID);
	}
	}
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}
	/** Unlink an image from a record
	*/		
	public function unlinkAction() {
	if($this->_getParam('returnID',false)) {
	$this->view->findID = $this->_getParam('secuid');
	$this->view->returnID = $this->_getParam('returnID');
	if ($this->_request->isPost()) {
	$id = (int)$this->_request->getPost('id');
	$del = $this->_request->getPost('del');
	if ($del == 'Yes' && $id > 0) {
	$imageID = $this->_request->getPost('imageID');;
	$findID = $this->_request->getPost('findID');;
	$slides = new Slides();
	$imagedata = $slides->fetchRow('imageID = '.$id);
	$imageID = $imagedata['secuid'];
	$linked = new FindsImages();
	$where = array();

	$where[] = $linked->getAdapter()->quoteInto('image_id = ?', $imageID);
	$where[] = $linked->getAdapter()->quoteInto('find_id = ?', $findID);
	$linked->delete($where);
	$this->_flashMessenger->addMessage('Image and links deleted!');
	$this->_redirect('/database/artefacts/record/id/'.$this->_getParam('returnID'));
	$cache = Zend_Registry::get('cache');
	$cache->clean(Zend_Cache::CLEANING_MODE_OLD);	
	}
	} else {
	$id = (int)$this->_request->getParam('id');
	
	if ((int)$id > 0) {
	$slides = new Slides();
	$this->view->slide = $slides->fetchRow($slides->select()->where('imageID = ?', $id));
	
	$this->view->params = $this->_getAllParams();
	}
	}
	} else {
	throw new Pas_ParamException($this->_missingParameter);
	}
	}
	
	/** View a zooming image of the file
	*/	
	public function zoomAction() {
	if($this->_getParam('id',false)) {
	$imageID = $this->_getParam('id');
	$images = new Slides();
	$imagedata = $images->getFileName($imageID);
	$this->view->data = $imagedata;
	$zoomdir = 'zoom/';
	$imagepath = $imagedata['0']['imagedir'];
	$filename = $imagedata['0']['f'];
	$stripped = explode('.',$filename);
	$stripped = end($stripped); 
	$new = str_replace('.', '_', $filename); 
	$new[strrpos($new, '_')] = '.';
	$stripit = explode('.',$new);
	$zoomedimagepath = $stripit['0'];
	
	$filepath = './' . $imagepath . $filename;
	$path = './' . $imagepath . $zoomdir;
	$ord = $imagepath . $zoomdir;
	
	
	if(file_exists($filepath)) {
	if(!file_exists($path)){
	mkdir($path, 0777);
	}
	if(!file_exists($path . $zoomedimagepath.'_zdata')) {

	$this->_zoomifyObject->_filegroup = "www-data"; // name of group to write files as
	$this->_zoomifyObject->_filemode = '664';
	$this->_zoomifyObject->_dirmode = '2775';
	$this->_zoomifyObject->_dir = $imagepath;
	$this->_zoomifyObject->_v_saveToLocation = $ord . $zoomedimagepath.'_zdata';
	$this->_zoomifyObject->ZoomifyProcess($filename, $imagepath);
	
	$this->view->path = $ord . $zoomedimagepath . '_zdata';

	} else {
	$this->view->path = $ord . $zoomedimagepath . '_zdata';
	}
	}
	} else {
		throw new Pas_ParamException($this->_missingParameter);
	}
	}
	//EOF controller
}