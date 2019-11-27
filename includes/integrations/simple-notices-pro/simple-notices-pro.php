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
 * Custom JS to close notice
 */
function sc_simple_notices_pro_remove_notice() {
	wp_enqueue_script( 'remove-notice', plugin_dir_url( __FILE__ ) . 'notices.js', array( 'jquery' ), SC_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'sc_simple_notices_pro_remove_notice' );


/**
 * Remove notice from bottom of site and place at the top of the site in Themedd.
 */
remove_action( 'wp_footer', 'pippin_display_notice' );
add_action( 'themedd_site_before', 'sc_simple_notices_pro_display' );


/**
 * Notice output
 */
function sc_simple_notices_pro_display() {

	// If we're on the front page and a promotional hero is showing, don't display the notice
	if ( is_front_page() && ( 1 === get_theme_mod( 'sc_show_promotional_hero', 0 ) ) ) {
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

				if ( function_exists( 'pippin_check_notice_is_read' ) && pippin_check_notice_is_read( $notice->ID, $user_ID ) != true ) {
					?>

					<div id="notification-area" class="snp-hidden">
						<div class="notice-content">
							<div class="notice-icon">
								<img class="bullhorn-icon" src="<?php echo plugin_dir_url( __FILE__ ) . 'bullhorn.svg'; ?>" alt="">
							</div>
							<div class="notice-message">
								<?php echo do_shortcode( wpautop( $notice->post_content ) ); ?>
							</div>
							<div class="notice-remove">
								<a class="remove-notice" href="#" id="remove-notice" rel="<?php echo $notice->ID; ?>">
									<img class="remove-notice-icon" src="<?php echo plugin_dir_url( __FILE__ ) . 'close.svg'; ?>" alt="">
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

		/* General */
		#notification-area {
			background: #203E4D;
			color: #ddd;
			width: 100%;
			padding: 1rem;
			vertical-align: middle;
			text-align: center;
			position: relative;
		}

		#notification-area.snp-hidden {
			display: none;
		}

		.logged-in #notification-area.snp-hidden, /* don't hide notice for logged in users (WHY?) */
		#notification-area .notice-content {
			display: block;
		}

		#notification-area p {
			display: inline-block;
			text-shadow: 1px 1px 3px rgba(0,0,0,.2);
			font-size: 18px;
			margin-bottom: 0;
		}

		#notification-area strong {
			color: #fff;
			font-weight: 600;
			text-transform: uppercase;
		}

		#notification-area a {
			color: #fff;
			border-bottom: 2px dotted rgba(255,255,255,.5);
		}

		#notification-area a:hover {
			color: #fff;
			border-bottom: 2px solid #fff;
		}

		/* Structure */
		#notification-area .notice-content {
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.notice-icon {
			text-align: left;
		}

		.notice-message {
			margin-bottom: 0;
		}

		.notice-remove {
			text-align: right;
		}

		@media all and ( max-width: 1000px ) {

			#notification-area .notice-content {
				display: block;
			}

			#notification-area .notice-content > .notice-icon {
				display: none;
			}

			.notice-content .notice-message {
				text-align: center;
			}

			.notice-content #countdown {
				display: block;
				margin: 0 auto 1rem;
			}

			#notification-area .remove-notice-icon {
				position: absolute;
				top: 1rem;
				right: 1rem;
			}
		}

		/* Countdown */
		#countdown {
			display: inline-block;
			text-align: center;
			line-height: 1.2;
			padding: 0.5rem 1rem;
			border: 2px solid #fff;
			margin: 0 1rem 0 0;
			width: 200px;
			vertical-align: middle;
		}

		#countdown-text {
			text-transform: uppercase;
			font-size: .875rem;
		}

		#countdown-date {
			font-weight: 600;
		}

		/* Icons */
		.notice-icon .bullhorn-icon {
			position: relative;
			width: 32px;
			height: 32px;
		}

		.notice-remove #remove-notice,
		.notice-remove #remove-notice:hover {
			text-decoration: none;
			border: 0;
		}

		.notice-remove .remove-notice-icon {
			width: 16px;
			height: 16px;
			opacity: .7;
			transition: opacity .2s;
		}

		.notice-remove .remove-notice-icon:hover {
			opacity: 1;
		}

		.logged-in .notice-remove .remove-notice-icon {
			/*display: none;*/
		}
	</style>

	<?php
}
add_action( 'wp_head', 'sc_simple_notices_pro_styles' );