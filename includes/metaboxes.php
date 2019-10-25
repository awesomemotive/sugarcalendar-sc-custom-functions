<?php
/**
 * Custom metabox settings for post types
 */


/**
 * Add a metabox to the notices post type
 */
function sc_notices_add_meta_box() {
	add_meta_box( 'sc_sale_notice', 'Sale Notice', 'sc_notices_sale_meta_box', array( 'notices' ), 'side', 'default' );
}
add_action( 'add_meta_boxes', 'sc_notices_add_meta_box' );


/**
 * Display the meta box
 */
function sc_notices_sale_meta_box( $post ) {

	$is_sale_notice = get_post_meta( $post->ID, 'sc_notice_is_sale', true );

	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'sc_sale_notice_nonce', 'sc_sale_notice_nonce' );
	?>

	<p><input type="checkbox" name="sc_notice_is_sale" id="sc-notice-is-sale" value="1" <?php echo checked( $is_sale_notice, 1 ); ?>/>
		<label for="sc-notice-is-sale">This is a sale notice</label>
	</p>

	<?php
}


/**
 * Save post meta when the save_post action is called
 */
function sc_notices_save_meta_box( $post_id, $post ) {

	// Check if our nonce is set and verify that the nonce is valid.
	if ( ! isset( $_POST['sc_sale_notice_nonce'] ) || ! wp_verify_nonce( $_POST['sc_sale_notice_nonce'], 'sc_sale_notice_nonce' ) ) {
		return $post_id;
	}

	// Do nothing on autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	// Check the user's permissions.
	if ( 'notices' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

	// It's safe. Let's save.
	$is_sale_notice = isset( $_POST['sc_notice_is_sale'] ) ? sanitize_text_field( $_POST['sc_notice_is_sale'] ) : '';

	if ( $is_sale_notice ) {

		// Update post meta.
		update_post_meta( $post_id, 'sc_notice_is_sale', true );
	} else {

		// Delete post meta.
		delete_post_meta( $post_id, 'sc_notice_is_sale' );
	}
}
add_action( 'save_post', 'sc_notices_save_meta_box', 10, 2 );