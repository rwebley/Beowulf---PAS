<?php
/**
 * @package OaiPmhRepository
 * @subpackage MetadataFormats
 * @author John Flatness, Yu-Hsun Lin
 * @copyright Copyright 2009 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

require_once('Pas/OaiPmhRepository/Metadata/Abstract.php');


/**
 * Class implmenting metadata output for the required oai_dc metadata format.
 * oai_dc is output of the 15 unqualified Dublin Core fields.
 *
 * @package OaiPmhRepository
 * @subpackage Metadata Formats
 */
class Pas_OaiPmhRepository_Metadata_PndsDc extends Pas_OaiPmhRepository_Metadata_Abstract {

	
	public function institution($inst) {
		if(!is_null($inst)){
		$institutions = new Institutions();
		$where = array();
		$where[] = $institutions->getAdapter()->quoteInto('institution = ?',$inst);
		$institution = $institutions->fetchRow($where);
		if(!is_null($institution)){
			return $institution->description;
		} 
		} else {
		return 'The Portable Antiquities Scheme';
		}
	}
    
    /** OAI-PMH metadata prefix */
    const METADATA_PREFIX = 'pnds_dc';
    
    const OAI_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/static-repository';
    
    /** XML namespace for output format */
    const METADATA_NAMESPACE = 'http://purl.org/mla/pnds/pndsdc/';
    
    /** XML schema for output format */
    const METADATA_SCHEMA = 'http://www.ukoln.ac.uk/metadata/pns/pndsdcxml/2005-06-13/xmls/pndsdc.xsd';
    
    /** XML namespace for unqualified Dublin Core */
    const DC_NAMESPACE_URI = 'http://purl.org/dc/elements/1.1/';
    
    const DC_TERMS_NAMESPACE = 'http://purl.org/dc/terms/';
    
    const PNDS_TERMS_NAMESPACE = 'http://purl.org/mla/pnds/terms/';
    
    const PAS_RECORD_URL = 'http://www.finds.org.uk/database/artefacts/record/id/';
    /**
     * Appends Dublin Core metadata. 
     *
     * Appends a metadata element, an child element with the required format,
     * and further children for each of the Dublin Core fields present in the
     * item.
     */
    
protected $view;
		
	public function init()
	{
	$this->view = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;	
	}
	protected function _xmlEscape($string)
    {
        $enc = 'UTF-8';
        if ($this->view instanceof Zend_View_Interface
            && method_exists($this->view, 'getEncoding')
        ) {
            $enc = $this->view->getEncoding();
        }

        // TODO: remove check when minimum PHP version is >= 5.2.3
        if (version_compare(PHP_VERSION, '5.2.3', '>=')) {
            // do not encode existing HTML entities
            return htmlspecialchars($string, ENT_QUOTES, $enc, false);
        } else {
            $string = preg_replace('/&(?!(?:#\d++|[a-z]++);)/ui', '&amp;', $string);
            $string = str_replace(array('<', '>', '\'', '"'), array('&lt;', '&gt;', '&#39;', '&quot;'), $string);
            return $string;
        }
    }
    
    public function appendMetadata() 
    {
        $metadataElement = $this->document->createElement('metadata');
        $this->parentElement->appendChild($metadataElement);   
        
        $pnds = $this->document->createElementNS(
            self::METADATA_NAMESPACE, 'pndsdc:description');
        $metadataElement->appendChild($pnds);

        /* Must manually specify XML schema uri per spec, but DOM won't include
         * a redundant xmlns:xsi attribute, so we just set the attribute
         */
        $pnds->setAttribute('xmlns',self::OAI_NAMESPACE);
        $pnds->setAttribute('xmlns:dc', self::DC_NAMESPACE_URI);
        $pnds->setAttribute('xmlns:dcterms',self::DC_TERMS_NAMESPACE);
        $pnds->setAttribute('xmlns:xsi', parent::XML_SCHEMA_NAMESPACE_URI);
        $pnds->setAttribute('xmlns:pndsterms',self::PNDS_TERMS_NAMESPACE);
        $pnds->setAttribute('xsi:schemaLocation', self::METADATA_NAMESPACE.' '.
            self::METADATA_SCHEMA);
		
        if(!array_key_exists('0',$this->item))    	
        {
        
        	$data = array(
        	'identifier' => self::PAS_RECORD_URL . $this->item['id'],
        	'title' => $this->item['broadperiod']. ' ' . $this->item['objecttype'] ,
        	'description' => strip_tags($this->_xmlEscape($this->item['description'])),
        	'subject' => 'archaeology',
        	'type' => $this->item['objecttype']
        	
        	);
        	
        	
        	
        	foreach($data as $k => $v){
                $this->appendNewElement($pnds, 
                    'dc:'.$k, $v);
            }
            $rightsURI = $this->appendNewElement($pnds,'dcterms:license','');
            $rightsURI->setAttribute('valueURI','http://creativecommons.org/licenses/by-nc-sa/3.0/');
            $this->appendNewElement($pnds, 'dcterms:rightsHolder','The Portable Antiquities Scheme');
//            $type = $this->appendNewElement($pnds,'dc:type','PhysicalObject');
//            $type->setAttribute('encSchemeURI','http://purl.org/dc/terms/DCMIType');
//            $type->setAttribute('valueURI','http://purl.org/dc/terms/DCMIType');
            
            
            $data2= array(
        	'creator' => $this->item['identifier'],
        	'contributor' => $this->item['institution'],
        	'publisher' => 'The Portable Antiquities Scheme',
        	'language' => 'en',
        	'format' => 'text/html',
        	);
        	foreach($data2 as $k => $v){
                $this->appendNewElement($pnds, 
                    'dc:'.$k, $v);
            }
            
        	$spatial = array(
        	'county' => $this->item['county'],
        	'district' => $this->item['district']
        	);
        	$temporal = array(
        	'year1' => $this->item['numdate1'],
        	'year2' => $this->item['numdate2'],
        	);
        	if(!is_null($this->item['knownas']) && !is_null($this->item['fourFigure'])){
        	$coords = new Pas_Geo_Gridcalc($this->item['fourFigure']);
			$lat = $coords['decimalLatLon']['decimalLatitude'];
			$lon = $coords['decimalLatLon']['decimalLongitude'];
        	$spatial['coords'] = $lat . ',' . $lon;
        	}
        	foreach($spatial as $k => $v)
            {
                $this->appendNewElement($pnds, 
                    'dcterms:spatial', $v);
            }
        	foreach($temporal as $k => $v)
            {
                $this->appendNewElement($pnds, 
                    'dcterms:temporal', $v);
            }
        	$files = new OaiFinds();
            $images = $files->getImages($this->item['id']);
            if(count($images)){
            foreach($images as $image){
            if(!is_null($image['i'])){
            $thumbnail = 'http://www.finds.org.uk/images/thumbnails/' . $image['i'] . '.jpg'; 	
       		$this->appendNewElement($pnds, 'pndsterms:thumbnail', $thumbnail);
            } 
            }	
            }
        
            // Append the browse URI to all results
        	
     }           
        }
    
    
    /**
     * Returns the OAI-PMH metadata prefix for the output format.
     *
     * @return string Metadata prefix
     */
    public function getMetadataPrefix()
    {
        return self::METADATA_PREFIX;
    }
    
    /**
     * Returns the XML schema for the output format.
     *
     * @return string XML schema URI
     */
    public function getMetadataSchema()
    {
        return self::METADATA_SCHEMA;
    }
    
    /**
     * Returns the XML namespace for the output format.
     *
     * @return string XML namespace URI
     */
    public function getMetadataNamespace()
    {
        return self::METADATA_NAMESPACE;
    }
}
