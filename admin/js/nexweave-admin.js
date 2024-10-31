
(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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
	// new ClipboardJS('.btn');



	$(function () {
		var clipboard = new ClipboardJS('.btn');

		var ajaxUrl = nexweave.ajaxUrl;
		var nexweavePlayerUrl = nexweave.playerUrl;
		var apiUrl = nexweave.apiUrl;
		var nexweavePlatform = nexweave.nexweavePlatform

		$('#isFormVisible').on('change', function () {
			if (this.checked) {
				$("#apiKey-wrapper").fadeIn('normal')
			} else {
				$("#apiKey-wrapper").fadeOut('normal')
			}
		})

		function changeLoadingStatus(isLoading) {
			if (isLoading) {
				$('#generate_shortcode').hide();
				$('#loading_button').show();
				$('#generatedshortcode').val("");
			} else {
				$('#generate_shortcode').show();
				$('#loading_button').hide();
			}
		}

		function nexweaveDatatables() {
			let export_title = "Nexweave Experience Report";
			let filename = "Nexweave_Experience_Report"
			// experience datatables
			$('#experienceTable').DataTable({
				dom: 'Bfrtip',
				lengthMenu: [
					[10, 25, 50, -1],
					['10 rows', '25 rows', '50 rows', 'Show all']
				],
				buttons: [
					'pageLength',
					{
						extend: 'copyHtml5',
						title: export_title,
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						}
					},
					{
						extend: 'print',
						title: export_title,
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						}
					},
					{
						extend: 'excelHtml5',
						filename,
						title: export_title,
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						}
					},
					{
						extend: 'csvHtml5',
						filename,
						title: export_title,
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						}
					},
					{
						extend: 'pdfHtml5',
						filename,
						title: export_title,
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						}
					},
				],
				select: true
			});
		}


		$(document).ready(function () {
			nexweaveDatatables()
			$("#nexweave-experience-form").submit(function (e) {
				e.preventDefault();

				// display loading button
				changeLoadingStatus(true)
				// make API to experience and get height 
				// and width or else alert shortid not valid

				// get short if and textarea
				var values = {};
				var videoHeight;
				var videoWidth;
				var apiIsvalid = true;
				var templateVariables;
				var experience;
				var playerUrl;
				$.each($(this).serializeArray(), function (index, item) {
					values[item.name] = item.value
				})
				if (values.isFormVisible == '1') {
					$.ajax({
						type: "GET",
						url: `${apiUrl[values.environment]}/nexweave-integration/`,
						async: false,
						headers: {
							'Authorization': values.apiKey,
							'Content-Type': 'application/json'
						},
						success: function (res) {
							if (res && res.status === 'SUCCESS') {
								apiIsvalid = true
							}
						},
						error: function (error) {
							apiIsvalid = false
							Swal.fire({
								icon: 'error',
								text: 'Invalid API key',
								footer: `<b>Get your API key from&nbsp;</t><a href="${nexweavePlatform[values.environment]}">${nexweavePlatform[values.environment]}</a></b>`
							})
						}
					})
				}


				if (!apiIsvalid && values.isFormVisible == '1') {
					changeLoadingStatus(false)
					return false
				}

				if (values.isFormVisible == '1' && values.apiKey == '') {
					changeLoadingStatus(false)
					Swal.fire({
						icon: 'warning',
						text: 'API Key required',
						footer: `<b>Get your API key from&nbsp;</t><a href="${nexweavePlatform[values.environment]}">${nexweavePlatform[values.environment]}</a></b>`
					})
					return false;
				}

				var variablesArray = values.variables.replace(/(\r\n|\n|\r)/gm, "").split(',')
				var meta;
				$.ajax({
					type: "GET",
					url: `${apiUrl[values.environment]}/experience/${values.shortid}`,
					async: false,
					timeout: 4000, // 4 second timeout
					success: function (response) {
						if (response && response.experience) {
							experience = response.experience;
							var deviceType = 'desktop';
							if (window) {
								deviceType = window.innerWidth <= 420 ? 'mobile' : 'desktop'
							}
							meta = response.experience['_template']['meta'][deviceType]
							templateVariables = response.experience['_template']['variables']
							videoHeight = meta.videoHeight;
							videoWidth = meta.videoWidth;
						} else {
							changeLoadingStatus(false)
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Experience ID not found',
								footer: `<b>Get your experience ID from&nbsp;</t><a href="${nexweavePlatform[values.environment]}">${nexweavePlatform[values.environment]}</a></b>`
							})
							return false
						}
					},
					error: function (error) {
						changeLoadingStatus(false)
						Swal.fire({
							icon: 'error',
							title: 'Oops...',
							text: 'Experience ID not found',
							footer: `<b>Get your experience ID from&nbsp;</t><a href="${nexweavePlatform[values.environment]}">${nexweavePlatform[values.environment]}</a></b>`
						})
						return false
					},
				});

				let paramsObj = {}
				variablesArray.forEach(function (item) {
					var param = item.split(':')
					paramsObj[param[0]] = param[1] ? param[1] : 'Nexweaver'
				})

				// Generate payload
				var payload = {
					experienceId: values.shortid,
					videoHeight: videoHeight,
					videoWidth: videoWidth,
					urlParamsObject: paramsObj,
					isFormVisible: values.isFormVisible ? values.isFormVisible : '0',
					variables: templateVariables,
					environment: values.environment,
					apiKey: values.apiKey ? values.apiKey : '',
					form_title: values.formTitle ? values.formTitle : "",
					button_text: values.buttonText ? values.buttonText : "",
					campaign_id: values.campaignid ? values.campaignid : '',
					experience_name: values.experience_name ? values.experience_name : "",
					playerUrl: nexweavePlayerUrl[values.environment]
				}

				if (experience) {
					var postData = `action=admin_ajax_request&params=${encodeURI(JSON.stringify(payload))}`;
					jQuery.post(ajaxUrl, postData, function (response) {
						var res = JSON.parse(response)
						changeLoadingStatus(false)
						if (res.status === 1) {
							document.getElementById('generatedshortcode').value = res.shortcode;
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Some error occured',
								footer: '<b>contact us at&nbsp;<a href="mailto: developers@nexweave.com">developers@nexweave.com</a></b>'
							})
						}
					})
				}
			});

			// delete shortcode from experience table
			$(document).on("click", ".delete-short-code", function () {

				let id = this.dataset.id;
				var payload = {
					experience_id: id
				}

				Swal.fire({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.value) {
						var postData = `action=admin_ajax_delete_request&params=${encodeURI(JSON.stringify(payload))}`;
						jQuery.post(ajaxUrl, postData, function (response) {
							var res = JSON.parse(response)
							if (res.status === 1) {
								Swal.fire({
									icon: 'success',
									title: 'Experience deleted successfully',
								})

								$(`.experience_id_${id}`).remove()
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Oops...',
									text: 'Some error occured',
									footer: '<b>contact us at&nbsp;<a href="mailto: developers@nexweave.com">developers@nexweave.com</a></b>'
								})
							}
						})
					}
				})
			})
		});
	});

})(jQuery);
