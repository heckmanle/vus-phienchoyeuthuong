<?php
/**
 * @link              #
 * @since             1.0.0
 * @package           Users management
 *
 * @wordpress-plugin
 * Plugin Name:       Users management
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's authenticate into our system.
 * Version:           1.0.0
 * Author:            B
 * Author URI:        B
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       Users management
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
define( 'USERSMANAGEMENT_VERSION', '1.0.0' );
define( 'USERSMANAGEMENT_VERSION_ENQUEUE', '0.0.1' );

define('USERSMANAGEMENT', 'USERSMANAGEMENT');
define('USERSMANAGEMENT_LANG_DOMAIN', 'USERSMANAGEMENT');
define('USERSMANAGEMENT_LANGUAGE_PATH', plugin_basename(__DIR__) . '/languages/');
define('USERSMANAGEMENT_MODULE_DIR', plugin_dir_path( __FILE__ ) );
define('USERSMANAGEMENT_MODULE_URL', plugin_dir_url(__FILE__) );
define('USERSMANAGEMENT_AJAX_URL', admin_url('admin-ajax.php'));
define('USERSMANAGEMENT_PAGE', 'page_usersmanagement');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-usersmanagement-activator.php
 */
function activate_usersmanagement() {
	require_once USERSMANAGEMENT_MODULE_DIR . 'includes/class-usersmanagement-activator.php';
    Usersmanagement_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-usersmanagement-deactivator.php
 */
function deactivate_usersmanagement() {
	require_once USERSMANAGEMENT_MODULE_DIR . 'includes/class-usersmanagement-deactivator.php';
    Usersmanagement_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_usersmanagement' );
register_deactivation_hook( __FILE__, 'deactivate_usersmanagement' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require USERSMANAGEMENT_MODULE_DIR . 'includes/class-usersmanagement.php';

require_once USERSMANAGEMENT_MODULE_DIR . 'includes/core/usersmanagement.php';
new \Usersmanagement\Includes\Core\Usersmanagement();


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_usersmanagement() {

	$plugin = new Usersmanagement();
	$plugin->run();

}
run_usersmanagement();
