<?php
/**
 * The front page promotional hero markup
 */
?>

<div class="promotional-hero">
	<section class="wrapper">
		<div class="promo-content">

			<h1 class="promo-title">Black Friday <span class="bf-plus-cm">+</span> <span class="cyber-monday">Cyber Monday</span>
				<img class="promo-sale-tag" src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'includes/assets/images/sale-tag.svg'; ?>" alt="">
			</h1>

			<div class="promo-sale-text-wrap">
				<span class="promo-sale-text">Sale!</span>
			</div>

			<div class="promo-percent-off-wrap">
				<div class="promo-percent-off-row">
					<div class="promo-percent-col">
						<div class="promo-percent">
							<span><?php echo sc_discount_promo_int_value(); ?>%</span>
						</div>
						<span class="tiny-response">Off all purchases*</span>
					</div>
					<div class="promo-off-col">
						<div class="promo-off">
							<span>Off</span>
						</div>
						<div class="promo-all-purchases">
							<span>All purchases*</span>
						</div>
					</div>
				</div>
			</div>

			<div class="promo-length-wrap">
				<span class="promo-length">One week only</span>
			</div>

			<div class="promo-cta">
				<div class="promo-coupon-code-row">
					<div class="code-col col">
						<span class="promo-code-label">Use code:</span>
						<span class="promo-code"><?php echo get_theme_mod( 'sc_active_discount_code'); ?></span>
					</div>
				</div>
			</div>

			<div class="promo-ends">Sale ends <strong>23:59 PM December 6th CST</strong></div>

			<div class="promo-fine-print">*Purchases include upgrades and renewals</div>

		</div>
	</section>
</div>
