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
 * @package           Posts_Be_Builder
 *
 * @wordpress-plugin
 * Plugin Name:       Posts Be Builder
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Richard
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       posts-be-builder
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
define( 'POSTS_BE_BUILDER_VERSION', '1.0.0' );

define('POSTS_BE_BUILDER_LANG_DOMAIN', 'pages-be-builder');
define('POSTS_BE_BUILDER_LANGUAGE_PATH', plugin_basename(__DIR__) . '/languages/');
define('POSTS_BE_BUILDER_DIR', plugin_dir_path( __FILE__ ) );
define('POSTS_BE_BUILDER_URL', plugin_dir_url(__FILE__) );
define('POSTS_BE_BUILDER_PUBLIC_URL', POSTS_BE_BUILDER_URL . 'public/' );
define('POSTS_BE_BUILDER_PAGE_URL', 'posts-be-builder' );
if ( !defined("POSTS_BE_BUILDER_AJAX_URL") ) {
	define( 'POSTS_BE_BUILDER_AJAX_URL', admin_url("admin-ajax.php") );
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-posts-be-builder-activator.php
 */
function activate_posts_be_builder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-posts-be-builder-activator.php';
	Posts_Be_Builder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-posts-be-builder-deactivator.php
 */
function deactivate_posts_be_builder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-posts-be-builder-deactivator.php';
	Posts_Be_Builder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_posts_be_builder' );
register_deactivation_hook( __FILE__, 'deactivate_posts_be_builder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/ajax.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-posts-be-builder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_posts_be_builder() {

	$plugin = new Posts_Be_Builder();
	$plugin->run();

}
run_posts_be_builder();
