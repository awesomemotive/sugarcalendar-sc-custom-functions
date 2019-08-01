<?php
/**
 * Plugin Name: Sugar Calendar - Site Functionality
 * Plugin URI:  https://sugarcalendar.com
 * Description: Custom functionality for Sugar Calendar website.
 * Author:      Sandhills Development, LLC
 * Author URI:  https://sandhillsdev.com
 * Version:     1.0.1
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

/**
 * Enables all add-on categories for All Access Passes
 *
 * @since 1.0.1
 *
 * @param array $classes
 *
 * @return array
 */
function sc_custom_aa_categories( $included_categories, $all_access_pass_object ) {

    $download_id = $all_access_pass_object->download_id;
    $price_id = $all_access_pass_object->price_id;

    if ( 21 === $download_id || 20 === $download_id ) {
        $included_categories = array( 7, 2, 5 );
    }

    return $included_categories;
}
add_filter( 'edd_all_access_included_categories', 'sc_custom_aa_categories' );
