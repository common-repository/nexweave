<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://nexweave.com
 * @since      1.0.0
 *
 * @package    Nexweave
 * @subpackage Nexweave/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nexweave
 * @subpackage Nexweave/admin
 * @author     Nexweave <developers@nexweave.com>
 */
class Nexweave_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nexweave_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nexweave_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$allowed_pages = array('nexweave');
		$page = esc_attr($_REQUEST['page']);

		if (isset($_REQUEST['page']) && in_array($page, $allowed_pages)) {
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/nexweave-admin.css', array(), $this->version, 'all');

			// bootstrap
			wp_enqueue_style("nexweave-bootstrap-css", NEXWEAVE_PLUGIN_URL . 'assets/css/Bootstrap/bootstrap.min.css', array(), $this->version, 'all');

			// font awsome
			wp_register_style('Nexweave Font Awesome', plugin_dir_url(__FILE__) . 'css/font-awesome-4.7.0/css/font-awesome.min.css', array(), $this->version, 'all');
			wp_enqueue_style('Nexweave Font Awesome');

			// datatables CSS
			wp_register_style('Nexweave-datatables-css', plugin_dir_url(__FILE__) . 'js/DataTables/datatables.min.css', array(), $this->version, 'all');
			wp_enqueue_style('Nexweave-datatables-css');

			// sweetalert CSS
			wp_register_style('Nexweave-sweetalert-css', plugin_dir_url(__FILE__) . 'js/sweet-alert/css/sweetalert2.min.css', array(), $this->version, 'all');
			wp_enqueue_style('Nexweave-sweetalert-css');
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nexweave_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nexweave_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$allowed_pages = array('nexweave');
		$page = esc_attr($_REQUEST['page']);

		if (isset($_REQUEST['page']) && in_array($page, $allowed_pages)) {

			// hook core functions
			wp_enqueue_script("jquery");
			wp_enqueue_script("clipboard");

			// nexweave-bootstrap.js
			wp_enqueue_script("nexweave-bootstrap-js", NEXWEAVE_PLUGIN_URL . 'assets/js/Bootstrap/bootstrap.min.js', array('jquery'), $this->version, false);

			// sweetalert.js
			wp_register_script("nexweave-sweetalert-js", plugin_dir_url(__FILE__) . 'js/sweet-alert/js/sweetalert2.min.js', array('jquery'), $this->version, false);
			wp_enqueue_script("nexweave-sweetalert-js");

			// datatables.js
			wp_register_script("nexweave-datatables-js", plugin_dir_url(__FILE__) . 'js/DataTables/datatables.min.js', array('jquery'), $this->version, false);
			wp_enqueue_script("nexweave-datatables-js");

			// nexweave-admin.js
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/nexweave-admin.js', array('jquery'), $this->version, false);

			wp_localize_script($this->plugin_name, "nexweave", array(
				"name" => "Nexweave V2",
				"author" => "Nexweave",
				"ajaxUrl" => admin_url("admin-ajax.php"),
				"playerUrl" => NEXWEAVE_PLAYER_URL,
				"apiUrl" => NEXWEAVE_API_URL,
				"nexweavePlatform" => NEXWEAVE_PLATFORM_URL,
			));
		}
	}

	/**
	 * Register  menu for admin area
	 * @since    1.0.0
	 */
	public function nexweave_menu()
	{
		# code...
		add_menu_page("Nexweave", "Nexweave", "manage_options", "nexweave", array($this, "nexweave_dashboard"), "dashicons-video-alt2");
	}

	/**
	 * Menu Callback function
	 * @since 1.0.0
	 */
	public function nexweave_dashboard()
	{
		ob_start();
		include_once(NEXWEAVE_PLUGIN_PATH . "admin/partials/template-dashboard.php");
		$template = ob_get_contents();
		ob_end_clean();
		echo $template;
	}

	/**
	 * @since 1.0.0
	 */
	public function handle_ajax_request_admin()
	{
		// handles all ajax request for admin
		global $wpdb;
		$params = wp_filter_nohtml_kses($_REQUEST['params']);
		if (!empty($params)) {
			$params = utf8_decode(urldecode($params));
			try {
				$data = json_decode(stripcslashes($params));
				$experience_id = sanitize_text_field($data->experienceId);
				$video_height = sanitize_text_field($data->videoHeight);
				$variables = $data->variables; // Object of the template variables
				$video_width = sanitize_text_field($data->videoWidth);
				$url_params_obj = $data->urlParamsObject; // overrider parameter object
				$player_url = esc_url_raw($data->playerUrl);
				$is_form_visible = sanitize_text_field($data->isFormVisible);
				$api_key = sanitize_text_field($data->apiKey);
				$environment = sanitize_text_field($data->environment);
				$campaign_id = sanitize_text_field($data->campaign_id);
				$experience_name = sanitize_text_field($data->experience_name);
				$form_title = sanitize_text_field($data->form_title);
				$button_text = sanitize_text_field($data->button_text);

				$paramString = '';
				foreach ($url_params_obj as $key => $value) {
					if (!empty($key) && !empty($value)) {
						$sanitized_key = sanitize_text_field($key);
						$sanitized_value = sanitize_text_field($value);
						$paramString = "{$paramString}&{$sanitized_key}={$sanitized_value}";
					}
				}

				$table = $wpdb->prefix . 'nexweave';
				$data = array(
					"user_id" => get_current_user_id(),
					"experience_name" => $experience_name,
					"experience_id" => $experience_id,
					"videoHeight" => $video_height,
					"videoWidth" => $video_width,
					"player_url" => $player_url,
					"params" => $paramString,
					"is_form_visible" => $is_form_visible,
					"variables" => serialize($variables),
					"environment" => $environment,
					"api_key" => $api_key,
					"campaign_id" => $campaign_id,
					"form_title" => $form_title,
					"button_text" => $button_text,
					// "created_at" => date("Y-m-d H:i:s", strtotime("now"))
				);
				$format = array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');
				$success = $wpdb->insert($table, $data, $format);
				$lastid = $wpdb->insert_id;

				$shortcode = "[nexweave-experience id={$lastid}]";
				if ($success) {
					echo json_encode(array(
						"status" => 1,
						"shortcode" => $shortcode
					));
				} else {
					echo json_encode(array(
						"status" => 0,
						"shortcode" => 'null'
					));
				}
			} catch (\Throwable $th) {
				echo $th;
			}
		}
		wp_die();
	}

	public function delete_record_admin()
	{
		global $wpdb;
		$params = wp_filter_nohtml_kses($_REQUEST['params']);
		if (!empty($params)) {
			$params = utf8_decode(urldecode($params));
			try {
				$data = json_decode(stripcslashes($params));
				$experience_id = $data->experience_id;
				$table = $wpdb->prefix . 'nexweave';
				$success = $wpdb->delete($table, ['id' => $experience_id], ['%d']);
				if ($success) {
					echo json_encode(array(
						"status" => 1
					));
				} else {
					echo json_encode(array(
						"status" => 0,
					));
				}
			} catch (\Throwable $th) {
				//throw $th;
				echo $th;
			}
		}
		wp_die();
	}
}
