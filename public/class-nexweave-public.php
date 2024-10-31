<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://nexweave.com
 * @since      1.0.0
 *
 * @package    Nexweave
 * @subpackage Nexweave/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nexweave
 * @subpackage Nexweave/public
 * @author     Nexweave <developers@nexweave.com>
 */
class Nexweave_Public
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/nexweave-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/nexweave-public.js', array('jquery'), $this->version, false);

		wp_localize_script($this->plugin_name, "nexweave", array(
			"playerUrl" => NEXWEAVE_PLAYER_URL,
			"apiUrl" => NEXWEAVE_API_URL
		));
	}

	public function isMobile()
	{
		return preg_match(`/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i`, $_SERVER["HTTP_USER_AGENT"]);
	}

	public function decode($data)
	{
		return json_decode(json_encode(unserialize($data)));
	}

	/**
	 *  This function will return a API key for the current environment
	 */
	public function getApiUrl($environment)
	{
		$apiUrlArray = NEXWEAVE_API_URL;
		return $apiUrlArray[$environment];
	}

	/**
	 * @param: row details
	 * This function will render a form
	 */
	function generateForm($row)
	{
		$formData = "";
		$is_form_visible = $row->is_form_visible;
		$experience_id = esc_attr($row->experience_id);
		$form_title = esc_attr($row->form_title);
		$api_key = esc_attr($row->api_key);
		$environment = esc_attr($row->environment);
		$campaign_id = esc_attr($row->campaign_id);
		$variables = $row->variables;
		$button_text = esc_attr($row->button_text);
		if ($is_form_visible == '1') {
			$formData .= "<div class='form-wrapper' style='border: 1px #cec5c5 solid; padding: 10px; color: #000'><h4 style='color: #000; margin-bottom: 0px;'>{$form_title}</h4><form id='nexweave-form'><input type='hidden' name='experience_id' class='experience_id' value='{$experience_id}'><input type='hidden' value='{$api_key}' class='api_key' name='api_key'><input type='hidden' name='environment' value='{$environment}'><input type='hidden' name='campaign_id' value='{$campaign_id}'>";

			$keysFromObject = array_keys(get_object_vars(unserialize($variables)));
			foreach ($keysFromObject as $key) {
				$variables_name = str_replace('_', ' ', ucwords(str_replace('_', ' ', strtolower(esc_attr($key)))));

				$formData .= "<div class='form_element_wrapper'><div><label style='margin: 15px 0px 5px 0px'>{$variables_name}</label></div><input type='text' name='{$key}'></div>";
			}
			$formData .= "<div class='form_element_wrapper'><input type='submit' id='nexweave-form-submit-btn' style='margin:10px 0px' value='{$button_text}' /></div></form><div id='generated-link-wrapper' style='height: auto; background-color: #eadfdf; padding: 5px; display: none'><a id='nexweave-generated-link' href='#' target='_blank' ></a></div></div>";
		}
		return $formData;
	}

	function getParams($row)
	{
		$output = "?";
		$params = $row->params;
		if (!empty($params)) {
			$current_user = wp_get_current_user();

			$wp_username = empty($current_user->user_login) ? 'Nexweaver' : $current_user->user_login;
			$wp_firstname = empty($current_user->user_firstname) ? 'Nexweaver' : $current_user->user_firstname;
			$wp_lastname = empty($current_user->user_lastname) ? 'Nexweaver' : $current_user->user_lastname;
			$wp_email = empty($current_user->user_email) ? 'Nexweaver' : $current_user->user_email;

			$params = str_replace("[WP_USERNAME]", $wp_username, $params);
			$params = str_replace("[WP_FIRSTNAME]", $wp_firstname, $params);
			$params = str_replace("[WP_LASTNAME]", $wp_lastname, $params);
			$params = str_replace("[WP_EMAIL]", $wp_email, $params);
		} else {
			$params = "";
		}
		$output .= sanitize_text_field($this->get_all_url_params().$params);
		return $output;
	}

	function getVideoDimensions($body)
	{
		$videoHeight = "";
		$videoWidth = "";
		// If the user is on a mobile device, redirect them
		if ($this->isMobile()) {
			// get video height and width
			$videoHeight = $body['experience']['_template']['meta']['mobile']['videoHeight'];
			$videoWidth = $body['experience']['_template']['meta']['mobile']['videoWidth'];
		} else {
			$videoHeight = $body['experience']['_template']['meta']['desktop']['videoHeight'];
			$videoWidth = $body['experience']['_template']['meta']['desktop']['videoWidth'];
		}
		return array('videoHeight' => $videoHeight, 'videoWidth' => $videoWidth);
	}

	function get_all_url_params()
	{
		$output = "";
		$firstRun = true;
		foreach ($_GET as $key => $val) {
			if ($key != $parameter) {
				$key = str_replace('VAR_', '', $key);
				if (!$firstRun) {
					$output .= "&";
				} else {
					$firstRun = false;
				}
				$output .= sanitize_text_field($key) . "=" . urlencode(sanitize_text_field($val));
			}
		}

		return $output;
	}

	public function load_nexweave_experience($attr)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "nexweave";
		$id = $attr['id'];
		$row = $wpdb->get_row("SELECT * FROM {$table_name} WHERE id = {$id}");

		$videoHeight = $row->videoHeight;
		$videoWidth = $row->videoWidth;
		$experience_id = esc_attr($row->experience_id);
		$player_url = $row->player_url;
		$params = $row->params;

		$environment = esc_attr($row->environment);
		$experience_name = $row->experience_name;

		$formData = $this->generateForm($row);
		$params = $this->getParams($row);

		if (isset($_GET['nexid'])) {
			$experience_id = sanitize_text_field($_GET['nexid']);
			$nexweave_service_url = $this->getApiUrl($environment);
			$body = wp_remote_retrieve_body(wp_remote_get("{$nexweave_service_url}experience/{$experience_id}"));
			$body = json_decode($body, true);
			if ($body['experience'] != null) {
				$videoDimensions = $this->getVideoDimensions($body);
				$videoHeight = esc_attr($videoDimensions('videoHeight'));
				$videoWidth = esc_attr($videoDimensions('videoWidth'));
			}
		}

		// print_r(parse_url($_SERVER['REQUEST_URI'])['query']);

		$updatedPlayerUrl = esc_url("{$player_url}/{$experience_id}{$params}");
		$template = "<iframe data-experience-name='{$experience_name}' frameborder='0' vH='{$videoHeight}' vW='{$videoWidth}' scrolling='no' allowfullscreen='true' onload='(function(i){const d=getAttribute(`vH`);const c=getAttribute(`vW`);const g=document.getElementById(`n-{$experience_id}`);const f=(g.clientWidth*d)/c;g.style.height=f+`px`})();' id='n-{$experience_id}' src='{$updatedPlayerUrl}'  width='100%'></iframe>{$formData}";

		return $template;
	}
}
