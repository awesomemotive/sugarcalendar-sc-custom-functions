<?php
/**
 * Plugin Name: Sugar Calendar - Site Functionality
 * Plugin URI:  https://sugarcalendar.com
 * Description: Custom functionality for Sugar Calendar website.
 * Author:      Sandhills Development, LLC
 * Author URI:  https://sandhillsdev.com
 * Version:     1.0.1
 *
 * Please think about where your functions belong and place them there.
 * Create new files and directories if necessary.
 */


/**
 * Definitions
 */
define( 'SC_INCLUDES', dirname( __FILE__ ) . '/includes/' );
define( 'SC_INTEGRATIONS', SC_INCLUDES . 'integrations/' );


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
		include( SC_INCLUDES . 'misc-functions.php' );
		include( SC_INCLUDES . 'metaboxes.php' );

		// Integration functions
		include( SC_INTEGRATIONS . 'edd-all-access.php' );
		include( SC_INTEGRATIONS . 'simple-notices-pro/simple-notices-pro.php' );
		include( SC_INTEGRATIONS . 'simple-notices-pro/countdown/countdown.php' );
	}
}

function SC_Custom_Functions() {
	return SC_Custom_Functions::instance();
}
add_action( 'plugins_loaded', 'SC_Custom_Functions', 11 );