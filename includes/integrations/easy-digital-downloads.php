<?php
/**
 * EDD-specific functions
 */

/**
 * Based on an EDD discount code entered into the Customizer, found out if we're running a sale/promotion.
 *
 * @return bool
 */
function sc_discount_promo_is_active() {
	$promo_is_active  = false;
	$discount_code_id = sc_discount_promo_code_id();

	// is the discount code valid?
	if ( function_exists( 'edd_is_discount_active' ) && edd_is_discount_active( $discount_code_id, '', false ) && edd_is_discount_started( $discount_code_id, false ) ) {
		$promo_is_active = true;
	}

	return $promo_is_active;
}

/**
 * Get the value of the currently promoted discount code
 *
 * @return int
 */
function sc_discount_promo_int_value() {
	$promo_int_value  = 0;
	$discount_code_id = sc_discount_promo_code_id();

	// Get the value of the active discount as integer
	if ( function_exists( 'edd_get_discount_amount' ) ) {
		$promo_int_value = absint( edd_get_discount_amount( $discount_code_id ) );
	}

	return $promo_int_value;
}

/**
 * Get the ID of the currently promoted discount code
 *
 * @return int
 */
function sc_discount_promo_code_id() {

	// Having a sale? Get the active discount code ID (used when we run special sales with a discount)
	$discount_code    = get_theme_mod( 'sc_active_discount_code', 0 );
	$discount_code_id = function_exists( 'edd_get_discount_id_by_code' ) ? edd_get_discount_id_by_code( $discount_code ) : 0;

	return $discount_code_id;
}