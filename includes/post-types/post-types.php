<?php
/**
 * Custom post types used throughout the theme
 */

/**
 * Register custom post types
 */
function sc_theme_custom_post_types() {

	// Integration post type
	$integration_labels = array(
		'name'               => 'Integrations',
		'singular_name'      => 'Integration',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Integration',
		'edit_item'          => 'Edit Integration',
		'new_item'           => 'New Integration',
		'view_item'          => 'View Integration',
		'all_items'          => 'All Integrations',
		'search_items'       => 'Search Integrations',
		'not_found'          => 'No integrations found.',
		'not_found_in_trash' => 'No integrations found in Trash.',
		'menu_name'          => 'Integrations',
		'name_admin_bar'     => 'Integration',
	);
	$integration_taxonomies = array();
	$integration_args       = array(
		'labels'              => $integration_labels,
		'singular_label'      => 'Integration',
		'public'              => true,
		'show_ui'             => true,
		'publicly_queryable'  => true,
		'query_var'           => true,
		'exclude_from_search' => false,
		'show_in_nav_menus'   => false,
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'rewrite'             => array( 'slug' => 'integration', 'with_front' => false ),
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-admin-generic',
		'taxonomies'          => $integration_taxonomies,
	);
	register_post_type( 'integration', $integration_args );
}
add_action( 'init', 'sc_theme_custom_post_types' );