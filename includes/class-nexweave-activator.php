<?php

/**
 * Fired during plugin activation
 *
 * @link       https://nexweave.com
 * @since      1.0.0
 *
 * @package    Nexweave
 * @subpackage Nexweave/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Nexweave
 * @subpackage Nexweave/includes
 * @author     Nexweave <developers@nexweave.com>
 */
class Nexweave_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public  function activate()
	{
		// Dynamic table generation code
		global $wpdb;
		$table_name = $wpdb->prefix . "nexweave";
		$my_products_db_version = '1.0.0';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE `{$table_name}` (
						`id` mediumint(9) NOT NULL AUTO_INCREMENT,
						`user_id` int(11) NOT NULL,
						`experience_id` varchar(200) NOT NULL,
						`experience_name` varchar (200) NOT NULL,
						`videoHeight` varchar(11) NOT NULL,
						`videoWidth` varchar(11) NOT NULL,
						`player_url` varchar(200) NOT NULL, 
						`params` TEXT NOT NULL,
						`variables` TEXT NOT NULL,
						`environment` TEXT NOT NULL,
						`campaign_id` varchar(50) NOT NULL,
						`is_form_visible` BOOLEAN NOT NULL,
						`api_key` varchar(100) NOT NULL,
						`form_title` varchar(1000) NOT NULL DEFAULT 'Generate personalized video for your friends',
						`button_text` varchar(1000) NOT NULL DEFAULT 'Send your friends a message too',
						`is_active` int(11) NOT NULL DEFAULT '1',
						`created_at` DATETIME NOT NULL DEFAULT current_timestamp(),
						PRIMARY KEY  (id)) {$charset_collate}";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	public function wp_nexweave()
	{
		global $wpdb;
		return $wpdb->perfix . "nexweave";
	}
}
