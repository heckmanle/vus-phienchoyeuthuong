<?php
/**
 * @link              #
 * @since             1.0.0
 * @package           Booking services
 *
 * @wordpress-plugin
 * Plugin Name:       Booking services
 * Plugin URI:        #
 * Description:       This is a short description of what the plugin does. It's authenticate into our system.
 * Version:           1.0.0
 * Author:            B
 * Author URI:        B
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bookingservices
 * Domain Path:       /bookingservices
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
define( 'BOOKINGSERVICES_VERSION', '1.0.1' );

define('BOOKINGSERVICES', 'BOOKINGSERVICES');
define('BOOKINGSERVICES_LANG_DOMAIN', 'BOOKINGSERVICES');
define('BOOKINGSERVICES_LANGUAGE_PATH', plugin_basename(__DIR__) . '/languages/');
define('BOOKINGSERVICES_MODULE_DIR', plugin_dir_path( __FILE__ ) );
define('BOOKINGSERVICES_MODULE_URL', plugin_dir_url(__FILE__) );
define('BOOKINGSERVICES_AJAX_URL', admin_url('admin-ajax.php'));
define('BOOKINGSERVICES_PAGE', 'page_bookingservices');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bookingservices-activator.php
 */
function activate_bookingservices() {
    require_once BOOKINGSERVICES_MODULE_DIR . 'includes/class-bookingservices-activator.php';
    Bookingservices_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-notification-deactivator.php
 */
function deactivate_bookingservices() {
    require_once BOOKINGSERVICES_MODULE_DIR . 'includes/class-bookingservices-deactivator.php';
    Bookingservices_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bookingservices' );
register_deactivation_hook( __FILE__, 'deactivate_bookingservices' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require BOOKINGSERVICES_MODULE_DIR . 'includes/class-bookingservices.php';

require_once BOOKINGSERVICES_MODULE_DIR . 'includes/core/bookingservices.php';
new \Bookingservices\Includes\Bookingservices\Bookingservices();


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bookingservices() {

    $plugin = new Bookingservices();
    $plugin->run();

}
run_bookingservices();
