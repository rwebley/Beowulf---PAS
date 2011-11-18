<?php
/** Form for solr based single word search
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SolrForm extends Pas_Form
{
public function __construct($options = null)
{
parent::__construct($options);

	$decorators = array(
            array('ViewHelper'), 
            array('Description', array('placement' => 'append','class' => 'info')),
            array('Errors',array('placement' => 'prepend','class'=>'error','tag' => 'li')),
            array('Label'),
            array('HtmlTag', array('tag' => 'li')),
		    );

	$this->setName('solr')->removeDecorator('HtmlTag');

	$q = new Zend_Form_Element_Text('q');
	$q->setLabel('Search content: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addErrorMessage('Please enter a valid string!')
		->setDecorators($decorators);

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Search!')
		->setAttribs(array('class'=> 'large'))
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag');
	
	$config = Zend_Registry::get('config');
	$_formsalt = $config->form->salt;
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_config->form->salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);
	
	$this->addElements(array($q, $submit, $hash ));
	
	
	$this->addDisplayGroup(array('q','submit'), 'Search');
	$this->Search->removeDecorator('DtDdWrapper');
	$this->Search->removeDecorator('HtmlTag');
	$this->Search->addDecorators(array(array('HtmlTag', array('tag' => 'ul','id' => 'www'))))
	//->setLegend('Solr search engine')
	->addDecorator('FieldSet');
	
	}
}