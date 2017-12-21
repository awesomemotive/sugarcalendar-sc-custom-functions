<?php
/**
 * Plugin Name: Sugar Calendar - Site Functionality
 * Plugin URI: https://sugarcalendar.com
 * Description: Custom functionality for Sugar Calendar website.
 * Author: Easy Digital Downloads
 * Author URI: https://sugarcalendar.com
 * Version: 1.0
 */

function sc_remove_slim_body_class( $classes ) {

	$found = array_search( 'slim', $classes );

	if( is_page() && false !== $found ) {
		unset( $classes[ $found ] );
	}

	return $classes;

}
add_filter( 'body_class', 'sc_remove_slim_body_class', 11 );