<?php // EDD Software Licensing functions

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


/**
 * Use previous price paid (instead or current price) as the license upgrade old price
 */
add_filter( 'edd_sl_use_current_price_proration', '__return_false' );