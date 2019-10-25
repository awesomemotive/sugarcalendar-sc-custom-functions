<?php
/**
 * Simple Notices Pro integration
 */


/**
 * Dequeue default notice styling
 */
function sc_simple_notices_pro_dequeue_style() {
	wp_dequeue_style( 'notifications' );
}
add_action( 'wp_enqueue_scripts', 'sc_simple_notices_pro_dequeue_style' );


/**
 * Remove notice from bottom of site and place at the top of the site in Themedd.
 */
remove_action( 'wp_footer', 'pippin_display_notice' );
add_action( 'themedd_site_before', 'sc_simple_notices_pro_display' );


/**
 * Notice output
 */
function sc_simple_notices_pro_display() {

	if ( ! function_exists( 'pippin_check_notice_is_read' ) ) {
		return;
	}

	// this displays the notification area if the user has not read it before
	global $user_ID;

	$notice_args = array(
		'post_type'      => 'notices',
		'posts_per_page' => 1,
		'meta_key'       => '_enabled',
		'meta_value'     => '1'
	);
	$notices = get_posts( $notice_args );

	if ( $notices ) :

		foreach ( $notices as $notice ) {

			$icon           = strtolower( str_replace( ' ', '_', get_post_meta( $notice->ID, '_icon', true ) ) );
			$logged_in_only = get_post_meta( $notice->ID, '_notice_for_logged_in_only', true );

			$can_view = false;
			if ( $logged_in_only == 'All' ) {
				$can_view = true;
			} elseif ( $logged_in_only == 'Logged In' && is_user_logged_in() ) {
				$can_view = true;
			} elseif ( $logged_in_only == 'Logged Out' && !is_user_logged_in() ) {
				$can_view = true;
			}

			if ( $can_view ) {

				if ( pippin_check_notice_is_read( $notice->ID, $user_ID ) != true ) {
					?>

					<div id="notification-area" class="snp-hidden">
						<div class="notice-content">
							<div class="notice-icon">
								Horn
							</div>
							<div class="notice-message">
								<?php echo do_shortcode( wpautop( $notice->post_content ) ); ?>
							</div>
							<div class="notice-remove">
								<a class="remove-notice" href="#" id="remove-notice" rel="<?php echo $notice->ID; ?>">x</a>
							</div>
						</div>
					</div>

					<?php
				}
			}
		}
	endif;
}


/**
 * Add notice styles to the header
 *
 * TODO: Once we're using a child theme, ditch this.
 */
function sc_simple_notices_pro_styles() {
	?>

	<style>
		#notification-area {
			background: #333a42;
			width: 100%;
			padding: 1rem 2rem;
			vertical-align: middle;
			text-align: center;
			position: relative;
		}

		#notification-area .notice-content {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		#notification-area p {
			display: inline-block;
			margin-bottom: 0;
		}

		#notification-area p {
			margin-bottom: 0;
			color: #fff;
		}

		#notification-area a,
		#notification-area a:hover {
			color: #fff;
		}

		.notice-content .notice-message {
			padding: 0 1rem;
		}

		@media all and ( min-width: 768px ) {

			.notice-content [class*="notice-"] {
				display: inline-block;
			}

			.notice-content .notice-icon {
				text-align: left;
				width: 7%;
			}

			.notice-content .notice-message {
				width: 86%;
			}

			.notice-content .notice-remove {
				text-align: right;
				width: 7%;
			}
		}

		@media all and ( min-width: 576px ) {

			#announcement {
				margin: 0 0 1rem 0;
				display: none;
			}

			#notification-area p {
				margin-bottom: 0;
			}
		}
	</style>

	<?php
}
add_action( 'wp_head', 'sc_simple_notices_pro_styles' );