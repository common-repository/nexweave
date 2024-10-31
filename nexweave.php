<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://nexweave.com
 * @since             1.1.0
 * @package           Nexweave
 *
 * @wordpress-plugin
 * Plugin Name:       Nexweave
 * Plugin URI:        https://documentation.nexweave.com/wordpress-plugin
 * Description:       Create Interactive Videos with Personalization on your wordpress pages, blogs.
 * Version:           1.1.0
 * Author:            Nexweave
 * Author URI:        https://nexweave.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nexweave
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

// Nexweave player URL
$nexweavePlayerUrl = array('beta'=>'https://beta.nxwv.io', 'production'=>'https://embed.nxwv.io');

// Nexweave API links
$nexweaveApiUrl = array('beta'=>'https://beta-api.nexweave.com/api/v1', 'production'=>'https://api.nexweave.com/api/v1');

// Nexweave Platform URL
$nexweavePlatformUrl = array('beta'=>'https://beta-app.nexweave.com', 'production'=>'https://app.nexweave.com');

define( 'NEXWEAVE_VERSION', '1.1.0' );
define( 'NEXWEAVE_PLUGIN_URL', plugin_dir_url(__FILE__) );
define( 'NEXWEAVE_PLUGIN_PATH', plugin_dir_path(__FILE__) );
define( 'NEXWEAVE_PLAYER_URL', $nexweavePlayerUrl );
define( 'NEXWEAVE_API_URL', $nexweaveApiUrl );
define( 'NEXWEAVE_PLATFORM_URL', $nexweavePlatformUrl );




/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nexweave-activator.php
 */
function activate_nexweave() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nexweave-activator.php';
	$activator = new Nexweave_Activator();
	$activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nexweave-deactivator.php
 */
function deactivate_nexweave() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nexweave-deactivator.php';
	Nexweave_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nexweave' );
register_deactivation_hook( __FILE__, 'deactivate_nexweave' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nexweave.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nexweave() {

	$plugin = new Nexweave();
	$plugin->run();

}
run_nexweave();
