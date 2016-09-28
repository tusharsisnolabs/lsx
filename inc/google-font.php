<?php
if ( ! defined( 'ABSPATH' ) ) {
	return; // Exit if accessed directly
}

/**
 * Google_Font Class
**/
class LSX_Google_Font {
	
	private $title; // the name of the font
	private $location; // the http location of the font file
	private $cssDeclaration; // the css declaration used to reference the font
	private $cssClass; // the css class used in the theme customizer to preview the font

	/**
	 * Constructor
	**/
	public function __construct( $title, $location, $css_declaration, $css_class ) {
		$this->title          = $title;
		$this->location       = $location;
		$this->cssDeclaration = $css_declaration;
		$this->cssClass       = $css_class;
	}

	/**
	 * Getters
	 * taken from: http://stackoverflow.com/questions/4478661/getter-and-setter
	**/
	public function __get( $property ) {
		if ( property_exists( $this, $property ) ) {
			return $this->$property;
		}
	}

}
