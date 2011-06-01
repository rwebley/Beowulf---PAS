<?php
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
$q->setLabel('Search the database: ')
->setRequired(false)
->addFilter('HtmlBody')
->addFilter('StringTrim')
->addValidator('NotEmpty')
->setAttrib('size', 20)
->addErrorMessage('Please enter a valid string!')
->setDecorators($decorators);

$submit = new Zend_Form_Element_Submit('submit');
$submit->setLabel('Search!')
->setAttribs(array('class'=> 'large'))
->removeDecorator('DtDdWrapper')
->removeDecorator('HtmlTag');

$this->addElements(array($q,$submit));


$this->addDisplayGroup(array('q','submit'), 'Search');
$this->Search->removeDecorator('DtDdWrapper');
$this->Search->removeDecorator('HtmlTag');
$this->Search->addDecorators(array(array('HtmlTag', array('tag' => 'ul','id' => 'www'))
))->setLegend('Solr search engine')
->addDecorator('FieldSet');

}
}