<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * Politicalparty helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_Politicalparty extends Zend_View_Helper_Abstract{
	
	protected $_conservatives = '/images/logos/conservatives.png';
	protected $_labour = '/images/logos/labour.jpg';
	protected $_libdem = '/images/logos/libdem.jpg';
	protected $_cache = NULL;
	
	public function init() {
		$this->_cache = Zend_Registry::get('rulercache');
	}
	
	public function buildImage($image,$party) {
		$party = str_replace(' ','_',$party);
		
		list($w, $h, $type, $attr) = getimagesize('./'.$image);
		$string = '<img src="' . $image . '" alt="Party political logo" width="' . $w . '" height="' . $h .'" />';
		
		return $string;
		
	}
	/**
	 * 
	 */
	public function politicalparty($party) {
		if(!is_null($party) || $party != ""){
		switch ($party){
			case($party == 'Labour'):
				$partyImage = $this->buildImage($this->_labour,$party);
				break;
			case($party == 'Conservative'):
				$partyImage = $this->buildImage($this->_conservatives,$party);
				break;
			case($party == 'Liberal Democrat');
				$partyImage = $this->buildImage($this->_libdem,$party);
				break;
			default: 
				$partyImage = NULL;
		}
		return $partyImage;
		} 
	}
	
}

