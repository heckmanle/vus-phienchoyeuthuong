<?php
/**
 * @link              #
 * @since             1.0.0
 * @package           CMS Dashboard
 *
 * @wordpress-plugin
 * Plugin Name:       CMS Dashboard
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's authenticate into our system.
 * Version:           1.0.0
 * Author:            B
 * Author URI:        B
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       Dashboard
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
define( 'DASHBOARD_VERSION', '1.0.0' );

define('DASHBOARD', 'DASHBOARD');
define('DASHBOARD_LANG_DOMAIN', 'DASHBOARD');
define('DASHBOARD_LANGUAGE_PATH', plugin_basename(__DIR__) . '/languages/');
define('DASHBOARD_MODULE_DIR', plugin_dir_path( __FILE__ ) );
define('DASHBOARD_MODULE_URL', plugin_dir_url(__FILE__) );
define('DASHBOARD_AJAX_URL', admin_url('admin-ajax.php'));
define('DASHBOARD_PAGE', 'page_dashboard');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dashboard-activator.php
 */
function activate_dashboard() {
	require_once DASHBOARD_MODULE_DIR . 'includes/class-dashboard-activator.php';
    Dashboard_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dashboard-deactivator.php
 */
function deactivate_dashboard() {
	require_once DASHBOARD_MODULE_DIR . 'includes/class-dashboard-deactivator.php';
    Dashboard_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dashboard' );
register_deactivation_hook( __FILE__, 'deactivate_dashboard' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require DASHBOARD_MODULE_DIR . 'includes/class-dashboard.php';

require_once DASHBOARD_MODULE_DIR . 'includes/core/dashboard.php';
new \Dashboard\Includes\Core\Dashboard();


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dashboard() {

	$plugin = new Dashboard();
	$plugin->run();

}
run_dashboard();
