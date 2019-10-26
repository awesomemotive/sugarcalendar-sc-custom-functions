<?php
/**
 * Countdown timer
 *
 * Typically used in Simple Notices Pro
 */


/**
 * Enqueue countdown scripts
 */
function sc_enqueue_countdown_scripts() {

	$path     = plugin_dir_url( __FILE__ ) . 'js/';
	$ver      = SC_THEME_VERSION;

	// Register countdown script.
	wp_register_script( 'moment-js', $path . 'moment.min.js', array(), $ver );
	wp_register_script( 'countdown', $path . 'jquery.countdown.min.js', array( 'jquery' ), $ver );
	wp_register_script( 'moment-timezone', $path . 'moment-timezone.min.js', array(), $ver );
	wp_register_script( 'moment-timezone-with-data', $path . 'moment-timezone-with-data.min.js', array(), $ver );

	// Only enqueue script if a sale notice is published.
	if ( sc_sale_notice_active() ) {
		wp_enqueue_script( 'moment-js' );
		wp_enqueue_script( 'countdown' );
		wp_enqueue_script( 'moment-timezone' );
		wp_enqueue_script( 'moment-timezone-with-data' );
	}
}
add_action( 'wp_enqueue_scripts', 'sc_enqueue_countdown_scripts' );


/**
 * Determine if a sale notice is active (published)
 */
function sc_sale_notice_active() {

	$args = array(
		'posts_per_page'   => -1,
		'meta_key'         => 'sc_notice_is_sale',
		'meta_value'       => true,
		'post_type'        => 'notices',
		'post_status'      => 'publish',
	);

	$posts = get_posts( $args );

	$found = false;

	if ( $posts ) {
		foreach ( $posts as $post ) {
			if ( 'publish' === $post->post_status && get_post_meta( $post->ID, '_enabled', true ) ) {
				$found = true;
			}
		}
	}

	return $found;
}


/**
 * Add a [countdown] shortcode
 */
function sc_countdown_shortcode( $atts, $content = null ) {

	$atts = shortcode_atts(
		array(
			'end' => '',
		),
		$atts,
		'countdown'
	);

	$end_date = isset( $atts['end'] ) ? $atts['end'] : false;

	if ( ! $end_date ) {
		return $content;
	}

	// Bail if countdown script hasn't been enqueued.
	if ( ! wp_script_is( 'countdown', 'enqueued' ) ) {
		return $content;
	}

	$content = sc_get_countdown( $end_date );

	return $content;
}
add_shortcode( 'countdown', 'sc_countdown_shortcode' );


/**
 * Get the countdown timer
 */
function sc_get_countdown( $end_date = '' ) {

	if ( empty( $end_date ) ) {
		return;
	}

	ob_start();
	?>

	<span id="countdown"><span id="countdown-text">Sale ends in</span> <span id="countdown-date"></span></span><script type="text/javascript">

		var endDate = moment.tz("<?php echo $end_date; ?>", "America/Chicago");

		jQuery('#countdown-date').countdown( endDate.toDate() ).on('update.countdown', function(event) {

			var format = '%H:%M:%S';

			if ( event.offset.totalDays > 0 ) {
				format = '%-d day%!d ' + format;
			}

			if ( event.offset.weeks > 0 ) {
				format = '%-w week%!w ' + format;
			}

			jQuery(this).html(event.strftime(format));

		}).on('finish.countdown', function(event) {
			jQuery('#notification-area').hide();
		});
	</script>

	<?php
	return ob_get_clean();
}