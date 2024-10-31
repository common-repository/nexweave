(function ($) {
	'use strict';
	$(document).ready(function () {
		var api_key = $('.api_key').val();
		$('.api_key').val("")

		var apiUrl = nexweave.apiUrl;

		$("#nexweave-form").submit(function (e) {
			e.preventDefault();
			var values = {};
			var template_id;
			var campaign_id = "";
			$.each($(this).serializeArray(), function (index, item) {
				values[item.name] = item.value
			})
			var betaUrl = apiUrl[values.environment];
			if(values.campaign_id){
				campaign_id = values.campaign_id
			}
			$.ajax({
				type: "GET",
				url: `${betaUrl}/experience/${values.experience_id}`,
				async: false,
				success: function (response) {
					template_id = response['experience']['_template']['_id'];
					delete values.experience_id;
					delete values.api_key;
					delete values.environment;
					delete values.campaign_id;
					const userdata = values
					var payload = {
						template_id,
						campaign_id,
						"data": {
							...userdata
						}
					}
					$.ajax({
						type: "POST",
						url: `${betaUrl}/nexweave-integration/experience`,
						data: JSON.stringify(payload),
						async: false,
						headers: {
							'Authorization': api_key,
							'Content-Type': 'application/json'
						},
						success: function (res) {
							if (res.video_link) {
								$('#generated-link-wrapper').show();
								$('#nexweave-generated-link').html(res.video_link)
								$('#nexweave-generated-link').attr('href', res.video_link)
							} else {
								
							}
						}
					})
				}
			});

		});
	})
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})(jQuery);
