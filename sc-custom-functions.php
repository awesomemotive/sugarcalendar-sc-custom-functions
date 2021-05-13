<?php
/**
 * Plugin Name: SC Custom Functions
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
define( 'SCCF_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'SCCF_INCLUDES', SCCF_PLUGIN_DIR . '/includes/' );
define( 'SCCF_INTEGRATIONS', SCCF_INCLUDES . 'integrations/' );
$sc_theme = wp_get_theme();
define( 'SCCF_THEME_VERSION', $sc_theme->get( 'Version' ) );

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
		include( SCCF_INCLUDES . 'actions-filters.php' );

		// Settings functions
		include( SCCF_INCLUDES . 'settings/main.php' );

		// Integrations
		include( SCCF_INTEGRATIONS . 'easy-digital-downloads/software-licensing.php' );
		include( SCCF_INTEGRATIONS . 'gravity-forms/help-scout-add-on.php' );

		// Custom Post Type functions
		include( SCCF_INCLUDES . 'post-types/post-types.php' );
	}
}

function SC_Custom_Functions() {
	return SC_Custom_Functions::instance();
}
add_action( 'plugins_loaded', 'SC_Custom_Functions', 11 );
