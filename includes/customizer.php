<?php // Custom theme customizer settings

/**
 * Register the customizer settings
 */
function sc_customize_register( $wp_customize ) {

	/**
	 * Extends controls class to add descriptions to text input controls
	 */
	class SC_Customize_Text_Control extends WP_Customize_Control {
		public $type = 'customtext';
		public $description = '';
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ) . ' '; ?></span>
				<div class="control-description sc-control-description"><?php echo esc_html( $this->description ); ?></div>
				<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
			</label>
			<?php
		}
	}

	// Sales & Promotions
	$wp_customize->add_section( 'sc_sales_promotions', array(
		'title'    => 'Sales & Promotions',
		'priority' => 999,
	) );

	// Active discount code
	$wp_customize->add_setting( 'sc_active_discount_code', array(
		'default'           => null,
		'sanitize_callback' => 'sc_sanitize_text'
	) );
	$wp_customize->add_control( new SC_Customize_Text_Control( $wp_customize, 'sc_active_discount_code', array(
		'label'       => 'Active discount code',
		'section'     => 'sc_sales_promotions',
		'description' => 'Enter a discount code that we are promoting for a sale. The status of this discount code controls various theme features, like content changes based on whether or not we are running a sale. That means a scheduled discount code can be entered here and it will automatically make site changes when the start date is reached. Likewise, changes will be reversed when the discount code expires.',
		'priority'    => 1,
	) ) );


	// Promotional front page hero
	$wp_customize->add_setting( 'sc_show_promotional_hero', array(
		'default'			=> 0,
		'sanitize_callback'	=> 'sc_sanitize_checkbox'
	) );
	$wp_customize->add_control( 'sc_show_promotional_hero', array(
		'label'     => 'Display front page promotional hero',
		'section'   => 'sc_sales_promotions',
		'priority'  => 2,
		'type'      => 'checkbox',
	) );
}
add_action( 'customize_register', 'sc_customize_register' );

/**
 * Sanitize text input
 */
function sc_sanitize_text( $input ) {
	return strip_tags( stripslashes( $input ) );
}

/**
 * Sanitize checkbox options
 */
function sc_sanitize_checkbox( $input ) {
	return 1 == $input ? 1 : 0;
}

/**
 * Add Customizer UI styles to the <head> only on Customizer page.
 */
function sc_customizer_styles() {
	?>
	<style type="text/css">
		.sc-control-description {
			color: #666;
			font-style: italic;
			margin-bottom: 10px;
		}
	</style>
	<?php
}
add_action( 'customize_controls_print_styles', 'sc_customizer_styles' );
