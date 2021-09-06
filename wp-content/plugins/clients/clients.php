<?php
/**
 * @link              #
 * @since             1.0.0
 * @package           Clients
 *
 * @wordpress-plugin
 * Plugin Name:       Clients
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's authenticate into our system.
 * Version:           1.0.0
 * Author:            B
 * Author URI:        B
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       Clients
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
define( 'CLIENTS_VERSION', '1.0.3' );

define('CLIENTS', 'CLIENTS');
define('CLIENTS_LANG_DOMAIN', 'CLIENTS');
define('CLIENTS_LANGUAGE_PATH', plugin_basename(__DIR__) . '/languages/');
define('CLIENTS_MODULE_DIR', plugin_dir_path( __FILE__ ) );
define('CLIENTS_MODULE_URL', plugin_dir_url(__FILE__) );
define('CLIENTS_AJAX_URL', admin_url('admin-ajax.php'));
define('CLIENTS_PAGE', 'page_clients');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-locations-activator.php
 */
function activate_clients() {
	require_once CLIENTS_MODULE_DIR . 'includes/class-clients-activator.php';
    Clients_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-locations-deactivator.php
 */
function deactivate_clients() {
	require_once CLIENTS_MODULE_DIR . 'includes/class-clients-deactivator.php';
    Clients_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_clients' );
register_deactivation_hook( __FILE__, 'deactivate_clients' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require CLIENTS_MODULE_DIR . 'includes/class-clients.php';

require_once CLIENTS_MODULE_DIR . 'includes/core/clients.php';
new \Clients\Includes\Core\Clients();


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_clients() {

	$plugin = new Clients();
	$plugin->run();

}
run_clients();
