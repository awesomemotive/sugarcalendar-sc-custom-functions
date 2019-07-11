<?php
/**
 * Plugin Name: Sugar Calendar - Site Functionality
 * Plugin URI:  https://sugarcalendar.com
 * Description: Custom functionality for Sugar Calendar website.
 * Author:      Sandhills Development, LLC
 * Author URI:  https://sandhillsdev.com
 * Version:     1.0.0
 */

/**
 * Remove the "slim" class from the array of body classes.
 *
 * @since 1.0.0
 *
 * @param array $classes
 *
 * @return array
 */
function sc_remove_slim_body_class( $classes = array() ) {

	$found = array_search( 'slim', $classes );

	if ( is_page() && ( false !== $found ) ) {
		unset( $classes[ $found ] );
	}

	return $classes;
}
add_filter( 'body_class', 'sc_remove_slim_body_class', 11 );
