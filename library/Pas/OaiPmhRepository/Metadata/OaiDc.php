<?php
/** Class implmenting metadata output for the required oai_dc metadata format.
 * oai_dc is output of the 15 unqualified Dublin Core fields.
 *
 * @package OaiPmhRepository
 * @subpackage MetadataFormats
 * @author John Flatness, Yu-Hsun Lin
 * @copyright Copyright 2009 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

require_once('Pas/OaiPmhRepository/Metadata/Abstract.php');

class Pas_OaiPmhRepository_Metadata_OaiDc extends Pas_OaiPmhRepository_Metadata_Abstract
{
    /** OAI-PMH metadata prefix */
    const METADATA_PREFIX = 'oai_dc';
    
    /** XML namespace for output format */
    const METADATA_NAMESPACE = 'http://www.openarchives.org/OAI/2.0/oai_dc/';
    
    /** XML schema for output format */
    const METADATA_SCHEMA = 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd';
    
    /** XML namespace for unqualified Dublin Core */
    const DC_NAMESPACE_URI = 'http://purl.org/dc/elements/1.1/';
    
    const PAS_RECORD_URL = 'http://www.finds.org.uk/database/artefacts/record/id/';
    /**
     * Appends Dublin Core metadata. 
     *
     * Appends a metadata element, an child element with the required format,
     * and further children for each of the Dublin Core fields present in the
     * item.
     */
    
    
    public function appendMetadata() {
	$metadataElement = $this->document->createElement('metadata');
	$this->parentElement->appendChild($metadataElement);   
        
	$oai_dc = $this->document->createElementNS( self::METADATA_NAMESPACE, 'oai_dc:dc');
	$metadataElement->appendChild($oai_dc);

	/* Must manually specify XML schema uri per spec, but DOM won't include
	* a redundant xmlns:xsi attribute, so we just set the attribute
	*/
	$oai_dc->setAttribute('xmlns:dc', self::DC_NAMESPACE_URI);
	$oai_dc->setAttribute('xmlns:xsi', parent::XML_SCHEMA_NAMESPACE_URI);
	$oai_dc->setAttribute('xsi:schemaLocation', self::METADATA_NAMESPACE . ' ' . self::METADATA_SCHEMA);
		
	if(!array_key_exists('0',$this->item)) {
        
	$data = array(
   	'title'			=> $this->item['broadperiod']. ' ' . $this->item['objecttype'] ,
	'creator'		=> $this->item['identifier'],
	'subject'		=> 'archaeology',
	'description'           => strip_tags($this->item['description']),
	'publisher'             => 'The Portable Antiquities Scheme',
	'contributor'           => $this->item['institution'],
	'date' 			=> $this->item['created'],
 	'type' 			=> $this->item['objecttype'],
	'format' 		=> 'text/html',
	'id' 			=> $this->item['id'],
	'identifier'            => self::PAS_RECORD_URL . $this->item['id'],
	'source' 		=> 'The Portable Antiquities Scheme Database',
	'language' 		=> 'en-GB');
        	
    $files = new OaiFinds();
    $images = $files->getImages($this->item['id']);
    if(count($images)){
    foreach($images as $image){
	if(!is_null($image['i'])){
    $thumbnail = 'http://www.finds.org.uk/images/thumbnails/' . $image['i'] . '.jpg'; 	
	$data['relation'] = $thumbnail;
	} else {
	$data['relation'] = '';	
	}	
	}	
	}
	$data['coverage'] = $this->item['broadperiod'];
	$data['rights'] = 'The Portable Antiquities Scheme - Creative Commons Share-Alike Non-Commercial';	
	unset($data['id']);
	foreach($data as $k => $v){
	$this->appendNewElement($oai_dc, 'dc:' . $k, $v);
	}
	}
    }
    
    
    /**
     * Returns the OAI-PMH metadata prefix for the output format.
     *
     * @return string Metadata prefix
     */
    public function getMetadataPrefix() {
        return self::METADATA_PREFIX;
    }
    
    /**
     * Returns the XML schema for the output format.
     *
     * @return string XML schema URI
     */
    public function getMetadataSchema() {
        return self::METADATA_SCHEMA;
    }
    
    /**
     * Returns the XML namespace for the output format.
     *
     * @return string XML namespace URI
     */
    public function getMetadataNamespace() {
        return self::METADATA_NAMESPACE;
    }
}
