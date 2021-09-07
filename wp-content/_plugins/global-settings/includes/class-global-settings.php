<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Global_Settings
 * @subpackage Global_Settings/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Global_Settings
 * @subpackage Global_Settings/includes
 * @author     Richard <#>
 */
class Global_Settings {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Global_Settings_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GLOBAL_SETTINGS_VERSION' ) ) {
			$this->version = GLOBAL_SETTINGS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'global-settings';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Global_Settings_Loader. Orchestrates the hooks of the plugin.
	 * - Global_Settings_i18n. Defines internationalization functionality.
	 * - Global_Settings_Admin. Defines all hooks for the admin area.
	 * - Global_Settings_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-global-settings-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-global-settings-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-global-settings-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-global-settings-public.php';

		$this->loader = new Global_Settings_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Global_Settings_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Global_Settings_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Global_Settings_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Global_Settings_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action('gs/ajax/register_actions', $plugin_public, 'register_actions');
		#$this->loader->add_action( 'parse_request', $this, 'hook_parse_request' );
		$this->loader->add_action( 'generate_rewrite_rules', $this, 'hook_generate_rewrite_rules' );
		$this->loader->add_action( 'template_redirect', $this, 'hook_template_redirect' );
		$this->loader->add_filter( 'query_vars', $this, 'hook_query_vars' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Global_Settings_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function hook_parse_request(\WP $wp){
		if( in_array($wp->matched_rule, ['[^/]+)(?:/([0-9]+))?/?$']) && $wp->matched_query == 'name=global-settings&page='){

		}
		dump($wp);
	}

	public function hook_generate_rewrite_rules($wp_rewrite){
		$wp_rewrite->rules = array_merge(
			[GLOBAL_SETTINGS_PAGE_URL . '/?$' => 'index.php?global_settings=global-settings'],
			$wp_rewrite->rules
		);
	}

	public function hook_template_redirect(){
		$custom = get_query_var( 'global_settings' );
		if ( $custom == 'global-settings' ) {
			include_once GLOBAL_SETTINGS_DIR . 'public/pages/global-settings.php';
			die;
		}
	}

	public function hook_query_vars($query_vars){
		$query_vars[] = 'global_settings';
		return $query_vars;
	}

	public static function get_define_settings(){
		$array = [
			'material-icons' => [
				'name' => 'material-icons',
				'title' => 'Material Icons',
				'path' => GLOBAL_SETTINGS_DIR . 'public/contents/material-icons.php',
			],
		];
		return $array;
	}

}
