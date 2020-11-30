<?php
/**
 * Plugin Name: Custom Functions
 * Plugin URI:  https://sugarcalendar.com
 * Description: Custom functionality for Sugar Calendar website.
 * Author:      Sandhills Development, LLC
 * Author URI:  https://sandhillsdev.com
 * Version:     2.0.0
 *
 * Please think about where your functions belong and place them there.
 * Create new files and directories if necessary.
 */


/**
 * Definitions
 */
define( 'SC_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'SC_PLUGIN_INCLUDES', SC_PLUGIN_DIR . '/includes/' );
$sc_theme = wp_get_theme();
define( 'SC_THEME_VERSION', $sc_theme->get( 'Version' ) );

/**
 * Class SC_Custom_Functions
 */
class SC_Custom_Functions {

	private static $instance;

	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof SC_Custom_Functions ) ) {
			self::$instance = new SC_Custom_Functions;
			self::$instance->includes();
		}

		return self::$instance;
	}

	private function includes() {

		// General functions
		include( SC_PLUGIN_INCLUDES . 'actions-filters.php' );
		include( SC_PLUGIN_INCLUDES . '3rd-party-plugins.php' );

		// Custom Post Type functions
		include( SC_PLUGIN_INCLUDES . 'post-types/post-types.php' );
	}
}

function SC_Custom_Functions() {
	return SC_Custom_Functions::instance();
}
add_action( 'plugins_loaded', 'SC_Custom_Functions', 11 );
