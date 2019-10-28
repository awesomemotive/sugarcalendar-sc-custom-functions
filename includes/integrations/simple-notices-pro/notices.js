/**
 * Site-wide theme JS
 */
(function ($) {
	"use strict";

	$(function () {

		// Simple Notices remove notice
		$('.remove-notice').on('click', function() {
			$('#notification-area').slideUp();
		});

	});
}(jQuery));