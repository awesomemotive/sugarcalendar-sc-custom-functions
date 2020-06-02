<?php

/**
 * Send a pushover notification when the Gravity Forms Help Scout addon is not authenticated.
 *
 */
function sc_helpscout_authentication_notification( $feed, $entry, $form, $addon ) {
	// Only run this code if there is a problem with Help Scout API authentication.
	if ( ! $addon->is_authenticated() && function_exists( 'ckpn_send_notification' ) ) {

		$users = ckpn_get_users_with_keys();

		$options = ckpn_get_options();

		// Add the default admin key from settings.
		$user_keys = array( $options['api_key'] );

		$alert_capability = apply_filters( 'ckpn_sales_alert_capability', 'manage_options' );

		// Find the users who can view_shop_reports and have a user key.
		foreach ( $users as $user_id => $user_key ) {
			if ( ! user_can( $user_id, $alert_capability ) ) {
				continue;
			}

			$user_keys[] = $user_key;
		}


		$args = array(
			'priority'  => 1,
			'title'     => 'Gravity Forms Error',
			'message'   => 'There was an error sending a Support request to Help Scout. Please reconnect the API.',
			'token'     => ckpn_get_application_key_by_id(),
			'url'       => $addon->get_redirect_url(),
			'url_title' => 'Reconnect Help Scout',
			'sound'     => 'gamelan',
		);

		foreach ( $user_keys as $user ) {
			$args['user'] = $user;
			ckpn_send_notification( $args );
		}

	}
}
add_action( 'gform_gravityformshelpscout_post_process_feed', 'sc_helpscout_authentication_notification', 10, 4 );
