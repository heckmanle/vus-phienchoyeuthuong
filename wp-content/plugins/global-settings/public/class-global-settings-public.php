<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Global_Settings
 * @subpackage Global_Settings/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Global_Settings
 * @subpackage Global_Settings/public
 * @author     Richard <#>
 */

use \DIVI\Includes\Core\GlobalSettings;

class Global_Settings_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Global_Settings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Global_Settings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		#wp_register_style('bootstrap', plugin_dir_url( __FILE__ ) . 'libs/bootstrap/css/bootstrap.css', [], $this->version, 'all');
		wp_register_style('select2', plugin_dir_url( __FILE__ ) . 'libs/select2/css/select2.css', [], $this->version, 'all');
		wp_register_style( 'toastr', plugin_dir_url( __FILE__ ) . 'libs/toastr/css/toastr.min.css', array(), $this->version, false );
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/global-settings-public.css', array(/*'bootstrap',*/ 'select2', 'toastr'), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Global_Settings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Global_Settings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script( 'jquery-block-ui', plugin_dir_url( __FILE__ ) . 'libs/jquery/js/jquery.blockui.js', array( 'jquery' ), $this->version, false );
		#wp_register_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'libs/bootstrap/js/bootstrap.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . 'libs/select2/js/select2.full.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'toastr', plugin_dir_url( __FILE__ ) . 'libs/toastr/js/toastr.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . 'libs/jquery/js/jquery.validate.min.js', array( 'jquery', 'jquery-form' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/global-settings-public.js', array( 'jquery', /*'bootstrap',*/ 'select2', 'underscore', 'backbone', 'jquery-block-ui', 'toastr', 'jquery-validate' ), $this->version, false );
		wp_register_script( $this->plugin_name . '-material-icons', plugin_dir_url( __FILE__ ) . 'js/material-icons.js', array( 'jquery', /*'bootstrap',*/ 'select2', 'underscore', 'backbone', 'jquery-block-ui', 'toastr', 'jquery-validate', 'global-settings' ), $this->version, false );
	}

	public function register_actions(\GS\Includes\AJAX $ajax){
		$ajax->register_ajax_action('gs_get_material_icons', [$this, 'material_icons_get_all']);
		$ajax->register_ajax_action('gs_upload_file', [$this, 'material_icons_upload_file']);
		$ajax->register_ajax_action('gs_material_icons_delete', [$this, 'material_icons_delete']);
	}

	public function material_icons_get_all($data, $ajax){
		$icons = GlobalSettings::publishGlobalSetting('material-icons');
		if( is_wp_error($icons) ){
			return $icons;
		}
		$values = [];
		if( $icons ){
			$id = $icons['id'];
			$values = $icons['value'];
			if( !empty($values) ){
				$values = explode(',', $values);
				$values = array_map('trim', $values);
			}else{
				$values = [];
			}
		}
		return ['result' => $values];
	}

	public function material_icons_upload_file($data, $ajax){
		$mimes = [
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif' => 'image/gif',
			'png' => 'image/png',
			'bmp' => 'image/bmp',
			'svg' => 'image/svg+xml',
		];
		$file = $_FILES['material_icons'] ?? [];
		if( $file ){
			$icons = GlobalSettings::publishGlobalSetting('material-icons');
			if( is_wp_error($icons) ){
				return $icons;
			}
			$values = [];
			$id = '';
			if( $icons ) {
				$id = $icons['id'];
				$values = $icons['value'];
				$values = explode(',', $values);
				$values = array_map('trim', $values);
			}
			$upload = new \SME\Inc\UploadFile($file, $mimes);
			$url = $upload->save_file();
			if( is_wp_error($url) ){
				return $url;
			}
			$values[] = $url;
			$values = implode(', ', $values);
			if($id){
				$response = GlobalSettings::updatePublishGlobalSetting($id, 'material-icons', $values);
			}else{
				$response = GlobalSettings::addPublishGlobalSetting('material-icons', $values);
			}
			if( is_wp_error($response) ){
				return $response;
			}
		}
		return ['message' => 'Success'];
	}

	public function material_icons_delete($data, $ajax){
		$data['icon'] = $data['icon'] ?? '';
		if( !$data['icon'] ){
			return new WP_Error(404, __('Not found', GLOBAL_SETTINGS_LANG_DOMAIN));
		}
		$icons = GlobalSettings::publishGlobalSetting('material-icons');
		if( is_wp_error($icons) ){
			return $icons;
		}
		if( $icons ){
			$id = $icons['id'];
			$values = $icons['value'];
			if( !empty($values) ){
				$values = explode(',', $values);
				$values = array_map('trim', $values);
				$search = array_search($data['icon'], $values);
				unset($values[$search]);
				$values = implode(', ', $values);
				$response = GlobalSettings::updatePublishGlobalSetting($id, 'material-icons', $values);
				if( is_wp_error($response) )
					return $response;
			}
		}
		return ['message' => __('Success', GLOBAL_SETTINGS_LANG_DOMAIN)];
	}
}
