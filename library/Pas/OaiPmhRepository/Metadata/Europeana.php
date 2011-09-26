<?php
/**
 * @package OaiPmhRepository
 * @subpackage MetadataFormats

 * Class implmenting metadata output for the required ESE metadata format.
 * Also uses grid reference stripping and redisplay tools
 * @package OaiPmhRepository
 * @subpackage Metadata Formats
 */
class Pas_OaiPmhRepository_Metadata_Europeana extends Pas_OaiPmhRepository_Metadata_Abstract {
	
	
	/** OAI-PMH metadata prefix */
    const METADATA_PREFIX = 'ese';
    
    /** XML namespace for output format */
    const METADATA_NAMESPACE = 'http://www.europeana.eu/schemas/ese/';
    
    /** XML schema for output format */
    const METADATA_SCHEMA = 'http://www.europeana.eu/schemas/ese/ESE-V3.3.xsd';
    
    /** XML namespace for unqualified Dublin Core */
    const DC_NAMESPACE_URI = 'http://purl.org/dc/elements/1.1/';
    
    const DC_METADATA_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';
    
    const PAS_RECORD_URL = 'http://www.finds.org.uk/database/artefacts/record/id/';
    
    const DC_TERMS_NAMESPACE = 'http://purl.org/dc/terms/';
    /**
     * Appends Dublin Core metadata. 
     *
     * Appends a metadata element, an child element with the required format,
     * and further children for each of the Dublin Core fields present in the
     * item.
     */
    
	protected function _xmlEscape($string)  {
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
        
        $europeana = $this->document->createElementNS(
            self::METADATA_NAMESPACE, 'ese:record');
        $metadataElement->appendChild($europeana);

        /* Must manually specify XML schema uri per spec, but DOM won't include
         * a redundant xmlns:xsi attribute, so we just set the attribute
         */
        
        $europeana->setAttribute('xmlns:dc',self:: DC_NAMESPACE_URI);
        $europeana->setAttribute('xmlns:oai_dc',self::DC_METADATA_NAMESPACE);
        $europeana->setAttribute('xmlns:ese', self::DC_NAMESPACE_URI);
        $europeana->setAttribute('xmlns:xsi', parent::XML_SCHEMA_NAMESPACE_URI);
        $europeana->setAttribute('xsi:schemaLocation', self::METADATA_NAMESPACE.' '.
            self::METADATA_SCHEMA);
        $europeana->setAttribute('xmlns:dcterms',self::DC_TERMS_NAMESPACE);
		
        if(!array_key_exists('0',$this->item))  {
        	$dc = array(
        	'title' => $this->item['broadperiod']. ' ' . $this->item['objecttype'] ,
        	'creator' => $this->item['identifier'],
        	'subject' => 'archaeology' . ' - ' . $this->item['broadperiod'],
        	'description' => strip_tags(str_replace(array("\n","\r",'    '),array('','',' '),$this->item['description'])),
        	'publisher' => 'The Portable Antiquities Scheme',
        	'contributor' => $this->institution($this->item['institution']),
        	'date' => $this->item['created'],
        	'type' => $this->item['objecttype'],
        	'format' => 'text/html',
        	'source' => 'The Portable Antiquities Scheme Database',
        	'language' => 'en',
        	'identifier' => $this->item['old_findID'],
        	'coverage' => $this->item['broadperiod'],
        	'rights' => 'Creative Commons General Public License "Attribution, Non-Commercial, Share-Alike", version 3.',
//        	'year' => date('Y',strtotime($this->item['created']))
        );  
        	$spatial = array(
        	'county' => $this->item['county'],
        	'district' => $this->item['district']
        	);
        	if(is_null($this->item['knownas']) && !is_null($this->item['fourFigure'])){
        	$coords = new Pas_Geo_Gridcalc($this->item['fourFigure']);
			$lat = $coords['decimalLatLon']['decimalLatitude'];
			$lon = $coords['decimalLatLon']['decimalLongitude'];
        	$spatial['coords'] = $lat . ',' . $lon;
        	}
        	
        	$dcterms = array(
        	'created' => date('Y-m-d',strtotime($this->item['created'])),
        	'medium' => $this->item['primaryMaterial'],
        	'isPartOf' => 'Beowulf: The Portable Antiquities Scheme database',
        	'provenance' => 'Crowdsourced from the public of England and Wales'
        	);  
        	$ese = array();
        	$ese['provider'] = 'The Portable Antiquities Scheme';
        	$ese['type'] = 'TEXT';
        	
        	$temporal = array(
        	'year1' => $this->item['numdate1'],
        	'year2' => $this->item['numdate2'],
        	);
        	
        	$files = new OaiFinds();
       		$images = $files->getImages($this->item['id']);
       		$formats = array();
        	if(count($images)){
            foreach($images as $image){
            if(!is_null($image['i'])){
            $thumbnail = 'http://www.finds.org.uk/images/thumbnails/' . $image['i'] . '.jpg'; 
       		$ese['isShownBy'] = $thumbnail;
       		$number = $image['f'];
       		$formats[$number] = 'http://www.finds.org.uk/' . $image['imagedir'] . $image['f'];	
            }	
            }
        	}
        	$ese['isShownAt'] = self::PAS_RECORD_URL . $this->item['id'];
        	foreach($dc as $k => $v)
            {
                $this->appendNewElement($europeana, 
                    'dc:'.$k, $v);
            }
        	
        	foreach($dcterms as $k => $v)
            {
            $this->appendNewElement($europeana, 
                    'dcterms:'.$k, $v);
            }
            foreach($formats as $k => $v) {
            	$this->appendNewElement($europeana, 'dcterms:hasFormat',$v);
            }
        	foreach($temporal as $k => $v){
            	 $this->appendNewElement($europeana, 
                    'dcterms:temporal', $v);
            }
        	foreach($spatial as $k => $v)
            {
                $this->appendNewElement($europeana, 
                    'dcterms:spatial', $v);
            }
        	foreach($ese as $k => $v)
            {
                $this->appendNewElement($europeana, 
                    'ese:'.$k, $v);
            }
            
        
        	
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
    
}
