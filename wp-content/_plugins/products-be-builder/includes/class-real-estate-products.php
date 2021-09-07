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
 * @package    Real_Estate_Products
 * @subpackage Real_Estate_Products/includes
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
 * @package    Real_Estate_Products
 * @subpackage Real_Estate_Products/includes
 * @author     Richard <#>
 */
class Real_Estate_Products {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Real_Estate_Products_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'REAL_ESTATE_PRODUCTS_VERSION' ) ) {
			$this->version = REAL_ESTATE_PRODUCTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'real-estate-products';

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
	 * - Real_Estate_Products_Loader. Orchestrates the hooks of the plugin.
	 * - Real_Estate_Products_i18n. Defines internationalization functionality.
	 * - Real_Estate_Products_Admin. Defines all hooks for the admin area.
	 * - Real_Estate_Products_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-real-estate-products-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-real-estate-products-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-real-estate-products-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-real-estate-products-public.php';

		require_once REAL_ESTATE_PRODUCTS_DIR . 'components/widgets/product-gallery.php';
		require_once REAL_ESTATE_PRODUCTS_DIR . 'components/widgets/product-title.php';
		require_once REAL_ESTATE_PRODUCTS_DIR . 'components/widgets/product-information.php';
		require_once REAL_ESTATE_PRODUCTS_DIR . 'components/widgets/product-category.php';
		require_once REAL_ESTATE_PRODUCTS_DIR . 'components/widgets/product-category-slider.php';
		require_once REAL_ESTATE_PRODUCTS_DIR . 'components/widgets/product-form-submit.php';

		$this->loader = new Real_Estate_Products_Loader();
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/product.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Real_Estate_Products_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Real_Estate_Products_i18n();

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

		$plugin_admin = new Real_Estate_Products_Admin( $this->get_plugin_name(), $this->get_version() );

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

		$plugin_public = new Real_Estate_Products_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'archive_template', $plugin_public, 'hook_custom_template', 100, 3 );
		$this->loader->add_action('rep/ajax/register_actions', $plugin_public, 'register_actions');
		$this->loader->add_action( 'widgets_init', $plugin_public, 'load_widgets' );
		#$this->loader->add_filter('page_attributes_dropdown_pages_args', $plugin_public, 'hook_page_attributes_dropdown_pages_args', 100, 2);
		$this->loader->add_filter('theme_page_templates', $plugin_public, 'hook_theme_page_templates', 100, 4);
		$this->loader->add_filter('template_include', $plugin_public, 'hook_template_include');

		$this->loader->add_action( 'generate_rewrite_rules', $this, 'hook_generate_rewrite_rules' );
		$this->loader->add_action( 'template_redirect', $this, 'hook_template_redirect' );
		$this->loader->add_filter( 'query_vars', $this, 'hook_query_vars' );
		$this->loader->add_filter( 'parse_request', $this, 'hook_parse_request' );

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
	 * @return    Real_Estate_Products_Loader    Orchestrates the hooks of the plugin.
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

	public function hook_generate_rewrite_rules($wp_rewrite){
		$wp_rewrite->rules = array_merge(
			['job/([^/]+)/?$' => 'index.php?job_name=$matches[1]'],
			$wp_rewrite->rules
		);
	}

	public function hook_template_redirect(){
		$job_name = get_query_var( 'job_name' );
		if ( $job_name ) {
			$products = \DIVI\Includes\Core\Product::products();
			$products = array_filter($products, function ($item) use ($job_name){
				return $item['product_slug'] == $job_name;
			});
			$product = [];
			if( $products ){
				$product = array_shift($products);
			}
//			$fields = [
//				'id',
//				'product_code',
//				'product_title',
//				'product_unit',
//				'product_price',
//				'product_category' => [
//					'id',
//					'cate_title'
//				],
//				'product_description',
//				'product_excerpt',
//				'product_slug',
//				'product_properties',
//				'address',
//				'product_number',
//				'product_pay',
//				'product_status',
//				'product_gallery',
//				'updated',
//				'created'
//			];
//			$product = \DIVI\Includes\Core\Product::get_by_slug($job_name, $fields);
			if( is_wp_error($product) || !$product ){
				global $wp_query;
				$wp_query->set_404();
				status_header( 404 );
				get_template_part( 404 );
				die;
			}
			global $rep_product;
			$rep_product = $product;
			include_once REAL_ESTATE_PRODUCTS_DIR . 'public/pages/single-product.php';
			die;
		}
	}

	public function hook_query_vars($query_vars){
		$query_vars[] = 'job';
		return $query_vars;
	}

	public function hook_parse_request(\WP $wp){
		if( in_array($wp->matched_rule, ['job/([^/]+)/?$']) || in_array($wp->matched_rule, ['job/([^/]+)/?$'])){
			preg_match('/job_name=(.*)/', $wp->matched_query, $matches);
			$wp->query_vars['job_name'] = isset($matches[1]) ? $matches[1] : '';
		}
	}

}
