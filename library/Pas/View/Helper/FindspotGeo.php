<?php
class Pas_View_Helper_FindspotGeo extends Zend_View_Helper_Abstract
{
	
	
	protected $_auth = NULL;
	protected $_cache = NULL;
	protected $_config = NULL;
	protected $_geoplanet;
	protected $_appid = NULL;
	protected $_flickr = NULL;
	
	public function __construct()
    { 
        $this->_auth = Zend_Registry::get('auth');
        $this->_cache = Zend_Registry::get('rulercache');
        $this->_config = Zend_Registry::get('config');
        $this->_appid = $this->_config->webservice->ydnkeys->placemakerkey;
        $this->_geoplanet = new Pas_Service_Geoplanet2($this->_appid);
        $this->_flickr = $this->_config->webservice->flickr_apikey; 
    }	
	
    public function FindspotGeo($woeid,$lat,$lon)
    {
    if(is_null($woeid)){
	$place = $this->_geoplanet->reverseGeocode($lat,$lon);
	$placeData = $this->_geoplanet->getPlace($place['woeid']);
//	$elevation = $this->_geoplanet->getElevation(NULL,$lat,$lon);
    } else {
    $placeData = $this->_geoplanet->getPlace($woeid);
//    $elevation = $this->_geoplanet->getElevation($woeid,NULL,NULL);
    }
    if(is_array($placeData) 
    //&& is_array($elevation)
    ) {
    $placeinfo = 
//    array_merge(
    $placeData    
//    ,$elevation)
    ;
	return $this->buildHtml($placeinfo);
    } else {
    	return false;
    }
	}
	
    public function metres($elevation)
    {
    switch($elevation) {
    	case ($elevation === 0):
    		$string = 'sea level.';
    		break;
    	case ($elevation > 0):
    		$string = $elevation . ' metres above sea level.';
    		break;
    	case ($elevation < 0):
    		$string = $elevation . ' metres below sea level.';
    		break;
    }	
    return $string;
    }
    public function buildHtml($data)
    {
    $html = '';
    $html .= '<h4>Data from Yahoo! GeoPlanet</h4>';
    $html .= '<p>The spatially enriched data provided here was sourced from the excellent Places/Placemaker service from Yahoo\'s geo team.<br />
	<img src="'.$this->view->baseUrl().'/images/logos/yahoogeo.jpg" height="89" width="250" alt="Yahoo Geo Developer logo" class="geo">';
    $html .= 'Settlement type: ' . $data['placeTypeName'] . '<br/>';
    $html .= 'WOEID: ' . $data['woeid'] . '<br/>';
    if(array_key_exists('postal',$data)){
    $html .= 'Postcode: ' . $data['postal'] . '<br/>'; 
    }
    $html .= 'Country: ' . $data['admin1'] . '<br/>';
//    $html .= 'Astergdem generated elevation: ' . $this->metres($data['elevation']);
    $html .= '</p>';	
  	$html .= $this->view->YahooGeoAdjacent($data['woeid']);
  	return $html;
    }
	
	
}
