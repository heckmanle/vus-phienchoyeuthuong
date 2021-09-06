<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Posts_Be_Builder
 * @subpackage Posts_Be_Builder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Posts_Be_Builder
 * @subpackage Posts_Be_Builder/public
 * @author     Richard <#>
 */
class Posts_Be_Builder_Public {
	CONST LIMIT_FILE = 8;
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
		 * defined in Posts_Be_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Posts_Be_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_style('bootstrap', plugin_dir_url( __FILE__ ) . 'libs/bootstrap/css/bootstrap.css', [], $this->version, 'all');
		wp_register_style('select2', plugin_dir_url( __FILE__ ) . 'libs/select2/css/select2.css', [], $this->version, 'all');
		wp_register_style( 'toastr', plugin_dir_url( __FILE__ ) . 'libs/toastr/css/toastr.min.css', array(), $this->version, false );
		wp_register_style( 'simplebar', plugin_dir_url( __FILE__ ) . 'libs/simplebar/css/simplebar.css', array(), $this->version, false );
		wp_register_style( 'lightgallery', plugin_dir_url( __FILE__ ) . 'libs/lightgallery/css/lightgallery.css', array(), $this->version, false );
		wp_register_style( 'swiper', plugin_dir_url( __FILE__ ) . 'libs/swiper/css/swiper-bundle.min.css', array(), $this->version, false );
		wp_enqueue_style('postsbb-styles', plugin_dir_url( __FILE__ ) . 'css/styles.css', ['bootstrap'], $this->version, 'all');
		wp_enqueue_style('postsbb-styles-responsive', plugin_dir_url( __FILE__ ) . 'css/responsive.css', ['bootstrap'], $this->version, 'all');
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/posts-be-builder-public.css', array('bootstrap', 'select2', 'toastr', 'simplebar'), $this->version, 'all' );
		if( isset($_GET['et_fb']) && $_GET['et_fb'] == 1 ){
			wp_enqueue_style('swiper');
		}
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
		 * defined in Posts_Be_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Posts_Be_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_register_script( 'jquery-block-ui', plugin_dir_url( __FILE__ ) . 'libs/jquery/js/jquery.blockui.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'libs/bootstrap/js/bootstrap.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . 'libs/select2/js/select2.full.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'toastr', plugin_dir_url( __FILE__ ) . 'libs/toastr/js/toastr.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'simplebar', plugin_dir_url( __FILE__ ) . 'libs/simplebar/js/simplebar.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'jquery-validate', plugin_dir_url( __FILE__ ) . 'libs/jquery/js/jquery.validate.min.js', array( 'jquery', 'jquery-form' ), $this->version, false );
		wp_register_script( 'lightgallery', plugin_dir_url( __FILE__ ) . 'libs/lightgallery/js/lightgallery.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'swiper', plugin_dir_url( __FILE__ ) . 'libs/swiper/js/swiper-bundle.min.js', array( 'jquery' ), $this->version, false );
		wp_register_script( 'moment-front', plugin_dir_url( __FILE__ ) . 'js/moment-with-locales.js', array( 'jquery' ), $this->version, false );
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/posts-be-builder-public.js', array( 'jquery', 'bootstrap', 'select2', 'underscore', 'backbone', 'jquery-block-ui', 'toastr', 'jquery-validate', 'simplebar', 'moment-front' ), $this->version, false );
		if( isset($_GET['et_fb']) && $_GET['et_fb'] == 1 ){
			wp_enqueue_script('swiper');
		}
	}

	public function hook_custom_template($template, $type, $templates){
		if( 'archive' == $type && in_array('archive-posts_be_builder.php', $templates) ){
			$template = POSTS_BE_BUILDER_DIR . 'public/pages/archive-post.php';
		}
		return $template;
	}

	public function register_actions($ajax){
		$ajax->register_ajax_action('postsbb_post_handle', [$this, 'post_handle']);
		$ajax->register_ajax_action('postsbb_get_my_posts', [$this, 'get_my_posts']);
		$ajax->register_ajax_action('postsbb_get_post_by_id', [$this, 'get_post_by_id']);
		$ajax->register_ajax_action('postsbb_delete_post_by_id', [$this, 'delete_post_by_id']);
	}

	public function post_handle($data, $ajax){
		$data['id'] = $data['id'] ?? '';
		$data['post_title'] = $data['post_title'] ?? '';
		$data['post_price'] = $data['post_price'] ?? '';
		$data['address'] = $data['address'] ?? '';
		$data['post_category'] = $data['post_category'] ?? [];
		$data['pro_gallery_img_url'] = $data['pro_gallery_img_url'] ?? [];

		if( empty($data['post_title']) ){
			return new WP_Error(401, __('Title is not empty'));
		}

		$post_gallery = $data['pro_gallery_img_url'];
		$gallery_files = $_FILES['gallery_files'] ?? [];
		if( !empty($gallery_files) ){
			$names = $gallery_files['name'] ?? [];
			if( !empty($names) ){
				foreach ($names as $idx => $name){
					$type = $gallery_files['type'][$idx];
					$tmp_name = $gallery_files['tmp_name'][$idx];
					$error = $gallery_files['error'][$idx];
					$size = $gallery_files['size'][$idx];
					$file = [
						'name' => $name,
						'tmp_name' => $tmp_name,
						'error' => $error,
						'size' => $size,
						'type' => $type,
					];
					$allowed_mime_types = [
						'jpg|jpeg|jpe' => 'image/jpeg',
						'gif' => 'image/gif',
						'png' => 'image/png',
						'bmp' => 'image/bmp',
					];
					$check_file_type = wp_check_filetype($name, $allowed_mime_types);
					if( !$check_file_type['type'] ){
						$message = __('Sorry, you are not allowed to upload files.');
						return new WP_Error(403, $message);
					}
					if (!function_exists('wp_handle_upload')) {
						require_once(ABSPATH . 'wp-admin/includes/file.php');
					}
					$uploadedfile = $file;
					$upload_overrides = array('test_form' => false, 'mimes' => $allowed_mime_types);

					$movefile = wp_handle_upload($uploadedfile, $upload_overrides);
					if( $movefile && isset($movefile['error']) && !empty($movefile['error']) ){
						$msg = $movefile['error'];
						$httpCode = 410;
						return new WP_Error($httpCode, $msg);
					}
					$post_gallery[] = $movefile['url'];
				}
			}
		}
		$postarr = [
			'post_title' => $data['post_title'],
			'post_name' => !empty($data['post_slug']) ? $data['post_slug'] : $data['post_title'],
			'post_status' => $data['post_status'],
			'post_category' => $data['post_category'],
			'post_slug' => !empty($data['post_slug']) ? $data['post_slug'] : $data['post_title'],
			'post_seo_keywords' => $data['post_seo_keywords'],
			'post_seo_description' => $data['post_seo_description'],
			'post_excerpt' => $data['post_excerpt'],
			'post_gallery' => $post_gallery,
			'post_type' => \POSTSBB\Includes\PostsBeBuilder::POST_TYPE,
		];
		$_POST['postsbb'] = $postarr;
		$update = false;
		if( !empty($data['id']) ){
			$_post = \POSTSBB\Includes\PostsBeBuilder::get_by_post_id($data['id']);
			if( $_post ){
				$postarr['ID'] = $_post['ID'];
				$update = true;
			}
		}
		global $bf_errors;
		$bf_errors = new WP_Error();
		if( $update ){
			$post_ID = wp_update_post($postarr);
		}else {
			$post_ID = wp_insert_post($postarr);
		}
		if( $bf_errors->has_errors() ){
			return $bf_errors;
		}
		if( is_wp_error($post_ID) ){
			return $post_ID;
		}elseif(!$post_ID){
			return new WP_Error(401, 'An error occurred, please try again');
		}
		$set_content  = et_builder_set_content_activation( $post_ID );
		$is_divi_library = 'et_pb_layout' === get_post_type( $post_ID );
		$edit_url        = $is_divi_library ? get_edit_post_link( $post_ID, 'raw' ) : get_permalink( $post_ID );
		$edit_url = et_fb_get_vb_url( $edit_url );
		return ['message' => 'Success', 'edit_post_url' => $edit_url, 'id' => get_post_meta($post_ID, \POSTSBB\Includes\PostsBeBuilder::POST_META_SYNC, true)];
	}

	public function get_my_posts($data, $ajax){
		$currentUser = \DIVI\Includes\Core\User::get_current();
		$response = ['result' => '', 'pagination' => ''];
		if( !empty($currentUser) && !is_wp_error($currentUser) ){
			$limit = 20;
			$page = abs($data['page']) ?? 1;
			set_query_var('page', $page);
			$offset = ($page - 1) * $limit;
			$user_id = $currentUser['id'];
			#$result = \DIVI\Includes\Core\Product::your_products();
			$result = \DIVI\Includes\Core\Post::posts();
			$total = count($result);
			$result = array_slice($result, $offset, $limit);
			add_filter('get_pagenum_link', function($result, $pagenum){
				global $wp_rewrite;
				$query_str = core_get_query_str($result, -1, []);
				extract($query_str);
				$array_query_str = ['orderby', 'order', 'limit', 'ty', 'st', 'start_date', 'end_date', 'mode'];
				$array_query_str = compact($array_query_str);
				$request = get_post_type_archive_link(\POSTSBB\Includes\PostsBeBuilder::POST_TYPE) . user_trailingslashit( $wp_rewrite->pagination_base . '/' . $pagenum, 'paged' );
				$result = add_query_arg(array_merge($array_query_str, []), $request);
				return $result;
			}, 100, 2);
			$args = [
				'prev_text' => '<img width="8" src="' . POSTS_BE_BUILDER_PUBLIC_URL . 'images/icons/icon-prev.png">',
				'next_text' => '<img width="8" src="' . POSTS_BE_BUILDER_PUBLIC_URL . 'images/icons/icon-nt.png">',
			];
			$pagination = core_get_pagination($total, $limit, '', $args);
			$result = array_map(function ($item){
				$updated_time = $item['updated'] / 1000;
				$updated = date('j F Y', $updated_time);
				$current_time = current_time('timestamp');
				$item['livetimestamp'] = sprintf(__('%s ago'), human_time_diff($updated_time, $current_time));
				$item['updated'] = $updated;
				if( !empty($item['post_gallery']) ) {
					$post_gallery = $item['post_gallery'];
					$item['thumbnail'] = $post_gallery[0] ?? '';
					$item['post_gallery'] = $post_gallery;
				}else{
					$item['thumbnail'] = '';
					$item['post_gallery'] = [];
				}
				return $item;
			}, $result);
			$response['result'] = $result;
			$response['pagination'] = $pagination;
		}
		return $response;
	}

	public function get_post_by_id($data, $ajax){
		$currentUser = \DIVI\Includes\Core\User::get_current();
		$id = $data['id'] ?? '';
		if( !$id ){
			return [];
		}
		$fields = [
			'id',
			'post_code',
			'post_title',
			'post_category' => [
				'id',
				'post_cate_title'
			],
			'post_description',
			'post_status',
			'post_gallery',
			'post_slug',
			'post_seo_keywords',
			'post_seo_description',
			'post_excerpt',
			'updated',
			'created',
			'author' => [
				'id'
			],
		];
		$response = \DIVI\Includes\Core\Post::get_by_id($id, $fields);
//		if( is_wp_error($response) || !$response || (!empty($response) && $currentUser['id'] != $response['user_id']['id']) ){
//			return [];
//		}
		$updated_time = $response['updated'] / 1000;
		$updated = date('j F Y', $updated_time);
		$current_time = current_time('timestamp');
		$response['livetimestamp'] = sprintf(__('%s ago'), human_time_diff($updated_time, $current_time));
		$response['updated'] = $updated;
		if( !empty($response['post_gallery']) ) {
			$post_gallery = $response['post_gallery'];
			$response['thumbnail'] = $post_gallery[0] ?? '';
			$response['post_gallery'] = $post_gallery;
		}else{
			$response['thumbnail'] = '';
			$response['post_gallery'] = [];
		}
		$response['post_excerpt'] = html_entity_decode($response['post_excerpt']);
		$response['post_excerpt'] = str_replace("<br />", "\n", $response['post_excerpt']);
		$_post = \POSTSBB\Includes\PostsBeBuilder::get_by_post_id($response['id']);
		$response['edit_post_url'] = '';
		if( $_post ) {
			$post_id = $_post['ID'];
			$is_divi_library = 'et_pb_layout' === get_post_type($post_id);
			$edit_url = $is_divi_library ? get_edit_post_link($post_id, 'raw') : get_permalink($post_id);
			$edit_url = et_fb_get_vb_url($edit_url);
			$response['edit_post_url'] = $edit_url;
		}
		return $response;
	}

	public function delete_post_by_id($data, $ajax){
		$data['id'] = $data['id'] ?? '';
		$response = \DIVI\Includes\Core\Post::delete($data['id']);
		if( is_wp_error($response) )
			return $response;
		$get_post = \POSTSBB\Includes\PostsBeBuilder::get_by_post_id($data['id']);
		if( $get_post ) {
			wp_delete_post($get_post['ID'], true);
		}
		return ['message' => __('Success')];
	}

	public function load_widgets(){
		register_widget('\POSTSBB\Components\Widgets\PostGallery');
		register_widget('\POSTSBB\Components\Widgets\PostInformation');
		register_widget('\POSTSBB\Components\Widgets\PostCategories');
		register_widget('\POSTSBB\Components\Widgets\PostCategoriesSlider');
	}
}
