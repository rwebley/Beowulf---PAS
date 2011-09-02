<?php
/**
 * A view helper for retrieving the geographic boundaries of a parliamentary constituency
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @see  http://www.theyworkforyou.com/ for documentation
 */
class Pas_View_Helper_TwfyGeo extends Zend_View_Helper_Abstract {
	
	/** Set the api url
	 * 
	 * @var string
	 */
	const TWFYURL = 'http://www.theyworkforyou.com/api/';
	
	/** Set the type of response to retrieve
	* 
	* @var string $format
	*/
	protected $format = 'js';
	
	protected $_cache;
	protected $_apikey;

	/** Construct the object, sets the cache and need the api key for twfy
	 * 
	 * @param string $key
	 */
	public function __construct($key){
	$this->_apikey = $key;
	$this->_cache = Zend_Registry::get('rulercache');
	}
	
	/** Perform a curl request based on url provided
	* 
	* @param string $url
	*/
	public function get($url) {
	$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(
	CURLOPT_POST =>  true,
	CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_LOW_SPEED_TIME => 1,
	),
	);
	
	$client = new Zend_Http_Client($url, $config);
	$response = $client->request();
	
	$data = $response->getBody();
	$status = $response->getStatus();
	$json = json_decode($data);
	
	return $json;
	}

	/** Comvert array to object
	 * 
	 * @param $array
	 */
	public function toarray($array) {
	$data = get_object_vars($array);
	return $data;
	}

	public function convertObject($object) {
	$array = array();
	foreach($object as $key => $value) {
	$array[$key] = $value;
	}
	return $array;
	}
	/** Get the boundary details for the constituency provided
	 * 
	 * @param string $constituency
	 */
	public function TwfyGeo($constituency) {
	if(!is_null($constituency)) {
	$key = '&key=' . $this->_apikey;
	$query = 'getGeometry?name=' . urlencode($constituency);
	$format = '&format=' . $this->format;
	$url = self::TWFYURL . $query.$key.$format;
	$geometry = $this->get($url);
	if(!array_key_exists('error',$geometry)){
	$woeid = $this->YahooGeoAltitude($geometry->centre_lat, $geometry->centre_lon);
	$geometry = $this->convertObject($geometry);
	$woeid = $woeid->query->results->places->place;
	$woeid = $this->convertObject($woeid);
	$useful = array_merge($geometry,$woeid);
	return $useful;
	} else {
	return false;
	}
	}
	}
	
	/** Get the altitude based on lat lon
	 * 
	 * @param $lat
	 * @param $lon
	 */
	public function getAltitudeLatLon($lat,$lon) {
	$key = 'geocode' . md5($lat.$lon);	
    if (!$json = $this->_cache->load($key)) {
	$query = 'SELECT * FROM flickr.places WHERE lat =\'' . $lat . '\' AND lon =\'' . $lon.'\'';
	$yahoo = 'http://query.yahooapis.com/v1/public/yql?q=';
	$format = '&format=json';
	$url = $yahoo . urlencode($query).$format;
	$json = $this->get($url);
	$this->_cache->save($json, $key);
	}
	return $json;
	}
	
	public function YahooGeoAltitude($lat,$lon) {
	if(!is_null($lat) && !is_null($lon)) {
	return $this->getAltitudeLatLon($lat,$lon);
	} 
	}

}