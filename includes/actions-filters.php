<?php
/**
 * Various site actions and filters
 */


/**
 * Add the media namespace to the RSS feed header
 */
function sc_add_media_namespace() {
	echo 'xmlns:media="http://search.yahoo.com/mrss/"';
}
add_action( 'rss2_ns', 'sc_add_media_namespace' );


/**
 * Add featured image data to the RSS feed
 */
function sc_rss_featured_image() {
	global $post;

	if ( has_post_thumbnail( $post->ID ) ) {

		// get the full post thumbnail data
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

		// get the width to height ratio of the full image
		$ratio = $thumbnail[2] / $thumbnail[1];

		// maintaining the ratio, calculate a new height based on a forced 600px width
		$height = intval( 600 * $ratio );

		// get the mime type
		$mime_type = get_post_mime_type( get_post_thumbnail_id( $post->ID ) );
		?>

		<media:content url="<?php echo $thumbnail[0]; ?>" type="<?php echo $mime_type; ?>" medium="image" width="600" height="<?php echo $height; ?>"></media:content>

		<?php
	}
}
add_filter( 'rss2_item', 'sc_rss_featured_image' );


/**
 * Set the price that customers pay prior to March 1, 2021
 *
 * @param $price float The current item price
 * @param $download_id int Download product ID
 * @param $options array the cart item options
 *
 * @return float
 */
function sc_edd_sl_grandfather( $price, $download_id, $options ) {


	if ( ! empty( $options['license_id'] ) && empty( $options['upgrade_id'] ) ) {

		// Only existing license keys get grandfathered
		$license = edd_software_licensing()->get_license( $options['license_id'] );

		if ( $license && $license->date_created < '2021-03-01 00:00:00' ) {

			switch ( $license->download_id ) {

				case 20 :

					$price = 89;
					break;

				case 19 :

					$price = 49;
					break;

				case 18 :

					$price = 29;
					break;

			}
		}
	}

	return $price;
}
add_filter( 'edd_cart_item_price', 'sc_edd_sl_grandfather', 10, 3 );