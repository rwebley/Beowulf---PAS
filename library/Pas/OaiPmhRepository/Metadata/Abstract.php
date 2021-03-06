<?php
/** Abstract class on which all other metadata format handlers are based.
 * Includes logic for all metadata-independent record output.
 * @category Pas
 * @package Pas_OaiPmhRepository
 * @subpackage Metadata
 * @author John Flatness, Yu-Hsun Lin
 * @copyright Copyright 2009 John Flatness, Yu-Hsun Lin
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

require_once('Pas/OaiPmhRepository/OaiXmlGeneratorAbstract.php');
require_once('Pas/OaiPmhRepository/OaiIdentifier.php');

abstract class Pas_OaiPmhRepository_Metadata_Abstract
	extends Pas_OaiPmhRepository_OaiXmlGeneratorAbstract {   
    /**
     * Item object for this record.
     * @var Item
     */
    protected $item;
    
    /**
     * Parent DOMElement element for XML output.
     * @var DOMElement
     */
    protected $parentElement;
    
    /**
     * Metadata_Abstract constructor
     *
     * Sets base class properties.
     *
     * @param Item item Item object whose metadata will be output.
     * @param DOMElement element Parent element for XML output.
     */
    public function __construct($item, $element){
	$this->item = $item;
	$this->parentElement = $element;
	if(isset($element)){
	$this->document = $element->ownerDocument;
	}
    }
    
    /**
     * Appends the record to the XML response.
     *
     * Adds both the header and metadata elements as children of a record
     * element, which is appended to the document.
     *
     * @uses appendHeader
     * @uses appendMetadata
     */
    public function appendRecord(){
	$record = $this->document->createElement('record');
    $this->parentElement->appendChild($record);
    // Sets the parent of the next append functions
    $this->parentElement = $record;
	$this->appendHeader();
	$this->appendMetadata();
    }
    
    /**
     * Appends the record's header to the XML response.
     *
     * Adds the identifier, datestamp and setSpec to a header element, and
     * appends in to the document.  
     *
     * @uses appendHeader
     * @uses appendMetadata
     */
    public function appendHeader() {
	$table = new OaiFinds();
	if(array_key_exists('0',$this->item)) {
	$itemid = $this->item['0']['id'];
	$updated = $this->item['0']['created'];
	$collectionId = $this->item['0']['institution'];
	} else {
	$itemid = $this->item['id'];
	$updated = $this->item['created'];
	$collectionId = $this->item['institution'];
	}
	$item = $table->fetchRow($table->select()->where('finds.id = ?',$itemid));
  	$object = new Pas_OaiPmhRepository_OaiIdentifier();
   	$itemNumber = $object->itemToOaiId($itemid);
    $headerData['identifier'] = $itemNumber;
    $headerData['datestamp'] = self::dbToUtc($updated);
	if ($collectionId)
    $headerData['setSpec'] = $collectionId;
    $this->createElementWithChildren(
    $this->parentElement, 'header', $headerData);
    }
    
    /**
     * Appends a metadataFormat element to the document. 
     *
     * Declares the metadataPrefix, schema URI, and namespace for the oai_dc
     * metadata format.
     */    
    public function declareMetadataFormat(){
	$elements = array( 
	'metadataPrefix'    => $this->getMetadataPrefix(),
    'schema'            => $this->getMetadataSchema(),
    'metadataNamespace' => $this->getMetadataNamespace() 
	);
    $this->createElementWithChildren(
    $this->parentElement, 'metadataFormat', $elements);
    }
    
    /**
     * Returns the OAI-PMH metadata prefix for the output format.
     *
     * @return string Metadata prefix
     */
    abstract public function getMetadataPrefix();
    
    /**
     * Returns the XML schema for the output format.
     *
     * @return string XML schema URI
     */
    abstract public function getMetadataSchema();
    
    /**
     * Returns the XML namespace for the output format.
     *
     * @return string XML namespace URI
     */
    abstract public function getMetadataNamespace();
    
    /**
     * Appends the metadata for one Omeka item to the XML document.
     */
    abstract public function appendMetadata();
}