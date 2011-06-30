<?php

/**
* Extension of Zend form for PAS project
*
* @category   Pas
* @package    Form
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Pas_Form extends Zend_Form {

    protected $_standardElementDecorator = array(
        'ViewHelper',
        array('LabelError', array('escape'=>false)),
        array('HtmlTag', array('tag'=>'li'))
    );

    protected $_buttonElementDecorator = array(
        'ViewHelper'
    );

    protected $_standardGroupDecorator = array(
        'FormElements',
        array('HtmlTag', array('tag'=>'ol')),
        'Fieldset'
    );

    protected $_buttonGroupDecorator = array(
        'FormElements',
        'Fieldset'
    );
   
    protected $_noElementDecorator = array(
        'ViewHelper'
    );

	public function init()  {
	$this->setDisableTranslator(true);
    }
	
    public function __construct($options = null) {
	$this->addElementPrefixPath('Pas_Filter', 'Pas/Filter/', 'filter');
	$this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element', 'element')
	->addPrefixPath('Pas_Form_Decorator', 'Pas/Form/Decorator', 'decorator');
	$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
	
	parent::__construct($options);

	$this->setAttrib('accept-charset', 'UTF-8');
	$this->clearDecorators();
	
	$this->setDecorators(array(
		'FormElements',
		'Form'
	));
    }

	public function addElement($element, $name = null, $options = null) {
	if (!is_array($options)) {
	$options = array();
	}
	$options['disableTranslator'] = true;
	return parent::addElement($element, $name, $options);
    }
}