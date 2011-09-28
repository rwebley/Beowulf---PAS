<?php
/** A class for geocoding against the google api
 * @version 1
 * @author Daniel Pett
 * @license GNU
 * @package Pas_Service
 * @subpackage Geo
 * @category Pas
 */
class Pas_Service_Geo_Coder{

	/** The google map key
	 * 
	 * @var string $api_key
	 */
    protected $_key;

    const GEOCODEURI = 'http://maps.google.com/maps/geo';
    
    public function __construct($api_key)  {
        $this->_key = $api_key;
    }

    /** Get the coordinates from an address string
     * @param string $address
     */
    public function _getGeocodedLatitudeAndLongitude($address) {
        $client = new Zend_Http_Client();
        $client->setUri(self::GEOCODEURI);
        $client->setParameterGet('q', urlencode($address))
               ->setParameterGet('output', 'json')
               ->setParameterGet('sensor', 'false')
               ->setParameterGet('key', (string)$this->_key);
        $result = $client->request('GET');
        $response = Zend_Json_Decoder::decode($result->getBody(),
                    Zend_Json::TYPE_OBJECT);
        return $response;
    }
	
    /** Get the coordinates of an address
     * 
     * @param string $address
     */
    public function getCoordinates($address)  {
        $response = $this->_getGeocodedLatitudeAndLongitude($address);
        if(isset($response->Placemark[0]->Point->coordinates[1])){
             return array(
                'lat' => $response->Placemark[0]->Point->coordinates[1],
                'lon' => $response->Placemark[0]->Point->coordinates[0]
            );
        } else {
			return null;
		}
    }

}
