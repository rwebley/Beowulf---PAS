<?php

class Pas_VCard {

	protected $_data;  //array of this vcard's contact data
  	
	protected $_filename; //filename for download file naming
  
	protected $_revisionDate;
	
	protected $_vcard;
	
	protected $_imagetypes = array(
	IMAGETYPE_JPEG => 'JPEG',
    IMAGETYPE_GIF  => 'GIF',
    IMAGETYPE_PNG  => 'PNG',
    IMAGETYPE_BMP  => 'BMP'
	);


	public function vcard() {
    $this->_data = array(
	'display_name' => NULL, 'first_name' => NULL, 'last_name' => NULL,
	'additional_name' => NULL, "name_prefix"=>NULL, 'name_suffix' => NULL,
    'nickname' => NULL, 'title' => NULL, 'role' => NULL,
	'department' => NULL, 'company'=> NULL, 'work_po_box' => NULL,
	'work_extended_address'=> NULL ,'work_address'=> NULL, 'work_city'=> NULL,
	'work_state'=> NULL, 'work_postal_code'=> NULL, 'work_country'=> NULL, 
	'home_po_box' => NULL, 'home_extended_address' => NULL, 'home_address' => NULL,
	'home_city' => NULL, 'home_state'=> NULL, 'home_postal_code' => NULL,
	'home_country' => NULL, 'office_tel' => NULL, 'home_tel' => NULL,
	'cell_tel' => NULL, 'fax_tel' => NULL, 'pager_tel' => NULL,
	'email1' => NULL, 'email2'=> NULL, 'url'=> NULL,
	'photo'=> NULL, 'birthday'=> NULL, 'timezone'=> NULL,
	'sort_string'=> NULL, 'note'=> NULL
	);
	return true;
  	}

	/*
	build() method checks all the values, builds appropriate defaults for
	missing values, generates the vcard data string.
	*/  
	public function build() {
	if (!$this->_data['display_name']) {
	$this->_data['display_name'] = trim($this->_data['first_name'] 
	. ' ' . $this->_data['last_name']);
    }
    if (!$this->_data['sort_string']) { 
	$this->_data['sort_string'] = $this->_data['last_name']; 
    }
    if (!$this->_data['sort_string']) { 
	$this->_data['sort_string'] = $this->_data['company']; 
    }
    if (!$this->_data['timezone']) {
	$this->_data['timezone'] = date('O'); 
    }
    if (!$this->_revisionDate) { 
	$this->_revisionDate = date('Y-m-d H:i:s'); 
    }
    
  	$this->_card = 'BEGIN:VCARD\r\n';
    $this->_card .= 'VERSION:3.0\r\n';
    $this->_card .= 'CLASS:PUBLIC\r\n';
    $this->_card .= 'PRODID:-//Beowulf generated vcard by DEJ PETT//NONSGML Version 1//EN\r\n';
    $this->_card .= 'REV:'.$this->_revisionDate.'\r\n';
  	$this->_card .= 'FN:'.$this->_data['display_name'].'\r\n';
    $this->_card .= 'N:'
	. $this->_data['last_name'].';'
	. $this->_data['first_name'].';'
	. $this->_data['additional_name'].';'
	. $this->_data['name_prefix'].';'
	. $this->_data['name_suffix'].'\r\n';
    if ($this->_data['nickname']) {
	$this->_card .= 'NICKNAME:' . $this->_data['nickname'] . '\r\n'; 
    }
  	if ($this->_data['title']) { $this->_card .= 'TITLE:'.$this->_data['title'].'\r\n'; }
  	if ($this->_data['company']) { $this->_card .= 'ORG:'.$this->_data['company']; }
  	if ($this->_data['department']) { $this->_card .= ';'.$this->_data['department']; }
  	
  	$this->_card .= '\r\n';
  	
  	if ($this->_data['work_po_box'] || $this->_data['work_extended_address']
    || $this->_data['work_address'] || $this->_data['work_city']
    || $this->_data['work_state']   || $this->_data['work_postal_code']
    || $this->_data['work_country']) {
	$this->_card .= 'ADR;TYPE=work:'
	. $this->_data['work_po_box'] . ';'
	. $this->_data['work_extended_address'] . ';'
	. $this->_data['work_address'] . ';'
	. $this->_data['work_city'] . ';'
	. $this->_data['work_state']. ';'
	. $this->_data['work_postal_code'] . ';'
	. $this->_data['work_country'] . '\r\n';
    }
  	if ($this->_data['home_po_box'] || $this->_data['home_extended_address']
    || $this->_data['home_address'] || $this->_data['home_city']
    || $this->_data['home_state']   || $this->_data['home_postal_code']
    || $this->_data['home_country']) {
	$this->_card .= 'ADR;TYPE=home:'
	. $this->_data['home_po_box'] . ';'
	. $this->_data['home_extended_address'] . ';'
	. $this->_data['home_address'] . ';'
	. $this->_data['home_city'] . ';'
	. $this->_data['home_state'] . ';'
	. $this->_data['home_postal_code'] . ';'
	. $this->_data['home_country'] . '\r\n';
    }
    
    if ($this->_data['email1']) { 
	$this->_card .= 'EMAIL;TYPE=internet,pref:' . $this->_data['email1'].'\r\n'; 
    }
    
    if ($this->_data['email2']) { 
	$this->_card .= 'EMAIL;TYPE=internet:' . $this->_data['email2'] . '\r\n'; 
    }
    
    if ($this->_data['office_tel']) { 
	$this->_card .= 'TEL;TYPE=work,voice:' . $this->_data['office_tel'] . '\r\n'; 
    }
    
	if ($this->_data['home_tel']) { 
	$this->_card .= 'TEL;TYPE=home,voice:' . $this->_data['home_tel'] . '\r\n'; 
	}
    
	if ($this->_data['cell_tel']) {
	$this->_card .= 'TEL;TYPE=cell,voice:' . $this->_data['cell_tel'] . '\r\n';
	}
    
	if ($this->_data['fax_tel']) { 
	$this->_card .= 'TEL;TYPE=work,fax:' . $this->_data['fax_tel'] . '\r\n'; 
    }
    
    if ($this->_data['pager_tel']) { 
	$this->_card .= 'TEL;TYPE=work,pager:' . $this->_data['pager_tel'].'\r\n'; 
    }
    
    if ($this->_data['url']) { 
	$this->_card .= 'URL;TYPE=work:' . $this->_data['url'] . '\r\n'; 
    }
  	
    if ($this->_data['birthday']) { 
	$this->_card .= 'BDAY:' . $this->_data['birthday'] . '\r\n'; 
  	}
  	
  	if ($this->_data['role']) {
	$this->_card .= 'ROLE:' . $this->_data['role'] . '\r\n'; 
  	}
  	
  	if ($this->_data['note']) { 
	$this->_card .= 'NOTE:' . $this->_data['note'] . '\r\n'; 
  	}
	
	if ($this->_data['photo']) { 
	$image = $this->_data['photo'];
	if ($imageinfo = @getimagesize($image) AND isset($this->_imagetypes[$imageinfo[2]])) {
    $photo = base64_encode(file_get_contents($image));
    $type  = $this->_imagetypes[$imageinfo[2]];
    $path = 'PHOTO;ENCODING=BASE64;TYPE=' . $type . ':' . $photo;
	}
	
	$photo = base64_encode(file_get_contents($this->_data['photo']));
	$this->_card .= $path . '\r\n'; 
	}
  	
	$this->_card .= 'TZ:' . $this->_data['timezone'].'\r\n';
    $this->_card .= 'END:VCARD\r\n';
  	}
  

	public function download() {
  	if (!$this->_card) { 
	$this->build(); 
    }
    
    if (!$this->filename) { 
	$this->filename = trim($this->_data['display_name']); 
    }
    $this->filename = str_replace(' ', '_', $this->filename);
    $this->getResponse()->setHeader('Content-type', 'text/directory');
	$this->getResponse()->setHeader('Content-Disposition','attachment');
	$this->getResponse()->setHeader('filename',$this->filename . '.vcf');
  	$this->getResponse()->setHeader('Pragma','public');
  	$this->getResponse()->setBody($this->_card);
	}
}