<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Global_Settings
 *
 * @wordpress-plugin
 * Plugin Name:       GlobalSettings
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Richard
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       global-settings
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
define( 'GLOBAL_SETTINGS_VERSION', '1.0.0' );

define('GLOBAL_SETTINGS_LANG_DOMAIN', 'global-settings');
define('GLOBAL_SETTINGS_LANGUAGE_PATH', plugin_basename(__DIR__) . '/languages/');
define('GLOBAL_SETTINGS_DIR', plugin_dir_path( __FILE__ ) );
define('GLOBAL_SETTINGS_URL', plugin_dir_url(__FILE__) );
define('GLOBAL_SETTINGS_PAGE_URL', 'global-settings' );
define('GLOBAL_SETTINGS_PUBLIC_URL', GLOBAL_SETTINGS_URL . 'public/' );
if ( !defined("GLOBAL_SETTINGS_AJAX_URL") ) {
	define( 'GLOBAL_SETTINGS_AJAX_URL', admin_url("admin-ajax.php") );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-global-settings-activator.php
 */
function activate_global_settings() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-global-settings-activator.php';
	Global_Settings_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-global-settings-deactivator.php
 */
function deactivate_global_settings() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-global-settings-deactivator.php';
	Global_Settings_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_global_settings' );
register_deactivation_hook( __FILE__, 'deactivate_global_settings' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/ajax.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-global-settings.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_global_settings() {

	$plugin = new Global_Settings();
	$plugin->run();

}
run_global_settings();
