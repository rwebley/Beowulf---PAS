<?php
 class Pas_View_Helper_Acronyms extends Zend_View_Helper_Abstract {

	public function Acronyms($string) {
	$acros = new Acronyms();
	$acronyms = $acros->getValid();
	$text = " $string ";
	foreach ( $acronyms as $acronym => $fulltext )
	$text = preg_replace( "|(?!<[^<>]*?)(?<![?.&])\b$acronym\b(?!:)(?![^<>]*?>)|msU", "<abbr title=\"$fulltext\">$acronym</abbr>" , $text );
	$text = trim($text);
	return $text;
	}

}