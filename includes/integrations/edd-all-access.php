<?php
/**
 * Easy Digital Downloads - All Access
 */


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

	if ( 21 === (int) $download_id || 20 === (int) $download_id ) {
		$included_categories = array( 7, 2, 5 );
	}

	return $included_categories;
}
add_filter( 'edd_all_access_included_categories', 'sc_custom_aa_categories', 10, 2 );