<?php
/**
 * Various theme and plugin actions and filters
 */

/**
 * Remove the "slim" class from the array of body classes.
 *
 * @param array $classes
 *
 * @return array
 *@since 1.0.0
 *
 */
function sc_remove_slim_body_class( $classes = array() ) {

	$found = array_search( 'slim', $classes );

	if ( is_page() && ( false !== $found ) ) {
		unset( $classes[ $found ] );
	}

	if ( is_front_page() && ( 1 === get_theme_mod( 'sc_show_promotional_hero', 0 ) ) ) {
		$classes[] = 'has-promotional-hero';
	}

	if ( true === sc_discount_promo_is_active() ) {
		$classes[] = 'promo-is-active';
	}

	return $classes;
}
add_filter( 'body_class', 'sc_remove_slim_body_class', 11 );

/**
 * Add promotional hero area to front page
 */
function sc_promotional_hero() {
	$promo_is_active = 1 === get_theme_mod( 'sc_show_promotional_hero', 0 ) ? true : false;
	if ( sc_discount_promo_is_active() && is_front_page() && $promo_is_active ) {
		include( SC_PLUGIN_DIR . '/template-parts/hero-front-page-promo.php' );
	}
}
add_action( 'themedd_content_start', 'sc_promotional_hero' );


// Load style for the front page
function sc_styles() {
	wp_enqueue_style( 'sc-style', plugins_url( 'includes/assets/css/style.css', dirname(__FILE__) ) , array() ,SC_THEME_VERSION, 'all' );
}
add_action( 'wp_enqueue_scripts', 'sc_styles' );