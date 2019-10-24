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

					<div id="notification-area" class="<?php echo strtolower( str_replace( ' ', '_', get_post_meta( $notice->ID, '_notice_color', true ) ) ); ?> snp-hidden" data-effect="<?php echo strtolower( get_post_meta( $notice->ID, '_notice_effect', true ) ); ?>">
						<div id="notice-content"<?php if ( $icon && $icon != 'none' ) { ?> class="with-icon"<?php } ?>>

							<?php echo do_shortcode( wpautop( $notice->post_content ) ); ?>
							<?php if ( !get_post_meta( $notice->ID, '_hide_close', true ) ) { ?>
								<a class="remove-notice" href="#" id="remove-notice" rel="<?php echo $notice->ID; ?>">&times;</a>
							<?php } ?>

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
			padding: 1rem;
			vertical-align: middle;
			text-align: center;
			position: relative;
		}

		#notification-area p {
			display: inline-block;
		}

		.remove-notice {
			float: right;
			display: none;
		}

		#notification-area p {
			margin-bottom: 0;
			color: #fff;
		}

		#notification-area a,
		#notification-area a:hover {
			color: #fff;
		}

		@media only screen and ( min-width: 700px ) {
			.remove-notice { display: inline-block; }
		}
	</style>

	<?php
}
add_action( 'wp_head', 'sc_simple_notices_pro_styles' );