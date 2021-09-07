<?php
/**
 * @link              #
 * @since             1.0.0
 * @package           Events
 *
 * @wordpress-plugin
 * Plugin Name:       Events
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's authenticate into our system.
 * Version:           1.0.0
 * Author:            B
 * Author URI:        B
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       events
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
define( 'EVENTS_VERSION', '1.0.5' );

define('EVENTS', 'EVENTS');
define('EVENTS_LANG_DOMAIN', 'EVENTS');
define('EVENTS_LANGUAGE_PATH', plugin_basename(__DIR__) . '/languages/');
define('EVENTS_MODULE_DIR', plugin_dir_path( __FILE__ ) );
define('EVENTS_MODULE_URL', plugin_dir_url(__FILE__) );
define('EVENTS_AJAX_URL', admin_url('admin-ajax.php'));
define('EVENTS_PAGE', 'page_events');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-events-activator.php
 */
function activate_events() {
	require_once EVENTS_MODULE_DIR . 'includes/class-events-activator.php';
    Events_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-events-deactivator.php
 */
function deactivate_events() {
	require_once EVENTS_MODULE_DIR . 'includes/class-events-deactivator.php';
    Events_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_events' );
register_deactivation_hook( __FILE__, 'deactivate_events' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require EVENTS_MODULE_DIR . 'includes/class-events.php';

require_once EVENTS_MODULE_DIR . 'includes/core/events.php';
new \Events\Includes\Core\Events();


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_events() {

	$plugin = new Events();
	$plugin->run();

}
run_events();
