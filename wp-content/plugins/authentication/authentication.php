<?php
/**
 * @link              #
 * @since             1.0.0
 * @package           Authentication
 *
 * @wordpress-plugin
 * Plugin Name:       Authentication
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's authenticate into our system.
 * Version:           1.0.0
 * Author:            B
 * Author URI:        B
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       authentication
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
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

define('AUTHENTICATION', 'AUTHENTICATION');
define('AUTHENTICATION_LANG_DOMAIN', 'AUTHENTICATION');
define('AUTHENTICATION_LANGUAGE_PATH', plugin_basename(__DIR__) . '/languages/');
define('AUTHENTICATION_MODULE_DIR', plugin_dir_path( __FILE__ ) );
define('AUTHENTICATION_MODULE_URL', plugin_dir_url(__FILE__) );
define('AUTHENTICATION_AJAX_URL', admin_url('admin-ajax.php'));
define('AUTHENTICATION_PAGE', 'page_authentication');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-authentication-activator.php
 */
function activate_authentication() {
	require_once AUTHENTICATION_MODULE_DIR . 'includes/class-authentication-activator.php';
    Authentication_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-authentication-deactivator.php
 */
function deactivate_authentication() {
	require_once AUTHENTICATION_MODULE_DIR . 'includes/class-authentication-deactivator.php';
    Authentication_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_authentication' );
register_deactivation_hook( __FILE__, 'deactivate_authentication' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require AUTHENTICATION_MODULE_DIR . 'includes/class-authentication.php';

require_once AUTHENTICATION_MODULE_DIR . 'includes/core/authentication.php';
new \Authentication\Includes\Core\Authentication();

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_authentication() {

	$plugin = new Authentication();
	$plugin->run();

}
run_authentication();
