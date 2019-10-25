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
								<img src="<?php echo plugin_dir_url( __FILE__ ) . 'bullhorn.svg'; ?>" alt="">
							</div>
							<div class="notice-message">
								<?php echo do_shortcode( wpautop( $notice->post_content ) ); ?>
							</div>
							<div class="notice-remove">
								<a class="remove-notice" href="#" id="remove-notice" rel="<?php echo $notice->ID; ?>">
									<img src="<?php echo plugin_dir_url( __FILE__ ) . 'close.svg'; ?>" alt="">
								</a>
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
			color: #ddd;
			width: 100%;
			padding: 1.1rem 2rem;
			vertical-align: middle;
			text-align: center;
			position: relative;
		}

		#notification-area .notice-content {
			display: block;
		}

		#notification-area strong,
		#countdown-date {
			color: #fff;
			font-weight: 600;
			text-transform: uppercase;
		}

		#countdown {
			display: block;
			text-align: center;
			margin-top: 6px;
		}

		#notification-area p {
			display: inline-block;
			text-shadow: 1px 1px 3px rgba(0,0,0,.2);
			font-size: 18px;
			margin-bottom: 0;
		}

		#notification-area p {
			margin-bottom: 0;
		}

		#notification-area a:not(#remove-notice) {
			color: #fff;
			border-bottom: 2px dotted rgba(255,255,255,.5);
		}

		#notification-area a:hover:not(#remove-notice) {
			color: #fff;
			border-bottom: 2px solid #fff;
		}

		div[class*="notice-"] {
			display: block;
			width: 100%;
		}

		.notice-icon {
			margin-bottom: 6px;
		}

		.notice-icon img {
			position: relative;
			top: 5px;
			width: 32px;
			height: 32px;
		}

		.notice-message {
			padding: 0 1rem;
			margin-bottom: 10px;
		}

		.notice-remove img {
			width: 16px;
			height: 16px;
			opacity: .5;
			transition: opacity .2s;
		}

		.notice-remove img:hover {
			opacity: 1;
		}

		@media all and ( min-width: 768px ) {

			#notification-area .notice-content {
				display: flex;
				justify-content: space-between;
				align-items: center;
			}

			.notice-content [class*="notice-"] {
				display: inline-block;
			}

			.notice-content .notice-icon {
				text-align: left;
				width: 7%;
			}

			.notice-content .notice-message {
				width: 86%;
				margin-bottom: 0;
			}

			.notice-content .notice-remove {
				text-align: right;
				width: 7%;
			}
		}

		@media all and ( min-width: 1200px ) {

			#countdown {
				display: inline;
				text-align: left;
			}
		}
	</style>

	<?php
}
add_action( 'wp_head', 'sc_simple_notices_pro_styles' );