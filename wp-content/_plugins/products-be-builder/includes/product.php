<?php

namespace REP\Includes;

class Product
{
	CONST PRODUCT_TYPE = 'b_product';
	CONST PRODUCT_CATEGORIES_TYPE = 'product_categories';
	CONST PRODUCT_META_SYNC = '__product_id_sync';
	public function __construct()
	{
		add_action('init', [$this, 'initialize'], 100);
		add_action('save_post_' . self::PRODUCT_TYPE, [$this, 'save_post'], 100, 3);
		add_filter('wp_insert_post_empty_content', [$this, 'hook_wp_insert_post_empty_content'], 999, 2);

		/**
		 * FILE: wp-content/themes/beme/includes/builder/feature/Library.php
		 * LINE: 833
		 */
		add_filter('et_builder_library_modal_custom_tabs', [$this, 'hook_et_builder_library_modal_custom_tabs'], 999, 2);
	}

	public function initialize(){
		$this->register();
	}

	public function register()
	{
		$slug = esc_attr(__('myposts', 'Divi'));
		$tax = esc_attr(__('product-categories', 'Divi'));

		$labels = array(
			'name' => esc_html__('Product', 'Divi'),
			'singular_name' => esc_html__('Product', 'Divi'),
			'add_new' => esc_html__('Thêm mới', 'Divi'),
			'add_new_item' => esc_html__('Thêm mới', 'Divi'),
			'edit_item' => esc_html__('Chỉnh sửa', 'Divi'),
			'new_item' => esc_html__('Thêm', 'Divi'),
			'view_item' => esc_html__('Xem', 'Divi'),
			'search_items' => esc_html__('Tìm kiếm', 'Divi'),
			'not_found' => esc_html__('Không tìm thấy', 'Divi'),
			'not_found_in_trash' => esc_html__('Không tìm thấy trong thùng rác', 'Divi'),
			'parent_item_colon' => '',
		);
		$plural_base = self::PRODUCT_TYPE;
		$capabilities = [
			'edit_post' => 'edit_' . $plural_base,
			'edit_posts' => "edit_{$plural_base}s",
			'edit_others_posts' => 'edit_other_' . $plural_base,
			'edit_private_posts'     => 'edit_private_' . $plural_base,
			'edit_published_posts'   => 'edit_published_' . $plural_base,
			'publish_posts' => 'publish_' . $plural_base,
			'read_post' => 'read_' . $plural_base,
			'read_private_posts' => 'read_private_' . $plural_base . 's',
			'delete_post' => 'delete_' . $plural_base,
			'delete_posts'           => 'delete_' . $plural_base . 's',
			'delete_private_posts'   => 'delete_private_' . $plural_base,
			'delete_published_posts' => 'delete_published_' . $plural_base,
			'delete_others_posts'    => 'delete_others_' . $plural_base,
		];

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			"show_in_rest" => true,
			'query_var' => true,
			'capability_type' => 'post',
			"capabilities" => $capabilities,
			"show_in_quick_edit" => true,
			"has_multiple" => false,
			"show_in_menu"       => true,
			"show_in_nav_menus"  => true,
			"show_admin_column" => true,
			"has_archive" => true,
			"exclude_from_search" => true,
			"map_meta_cap" => true,
			"hierarchical" => false,
			'menu_position' => 10,
			'rewrite' => array(
				'slug' => $slug,
				"with_front" => true,
				'feed' => true,
				'pages' => true,
			),
			'supports' => array('author', 'comments' ,'custom-fields', 'editor', 'excerpt', 'page-attributes', 'thumbnail', 'title'),
		);

		register_post_type(self::PRODUCT_TYPE, $args);

		$capabilities_taxonomy = [
			'manage_terms' => 'manage_categories_' . self::PRODUCT_TYPE,
			'edit_terms'   => 'manage_categories_' . self::PRODUCT_TYPE,
			'delete_terms' => 'manage_categories_' . self::PRODUCT_TYPE,
			'assign_terms' => 'assign_terms_' . self::PRODUCT_TYPE,
		];

		register_taxonomy(self::PRODUCT_CATEGORIES_TYPE, self::PRODUCT_TYPE, array(
			'label' => esc_html__('Product categories', 'Divi'),
			'hierarchical' => true,
			'query_var' => true,
			"show_in_quick_edit" => true,
			"publicly_queryable" => true,
			"has_multiple"       => false,
			"capabilities"       => $capabilities_taxonomy,
			"public"             => true,
			"show_ui"            => true,
			"show_in_menu"       => true,
			"show_in_nav_menus"  => true,
			"show_admin_column"  => true,
			"show_in_rest"       => true,
			'rewrite' => array(
				'slug' => $tax,
			),
		));
		flush_rewrite_rules(true);
		$role = get_role( 'administrator' );
		foreach (array_merge($capabilities_taxonomy, $capabilities) as $k => $item){
			$role->add_cap( $item );
		}
	}

	public static function get_by_product_id($pro_id){
		$query = new \WP_Query([
			'post_type' => self::PRODUCT_TYPE,
			'meta_query' => array(
				array(
					'key' => '__product_id_sync',
					'value' => $pro_id,
					'compare' => 'LIKE',
				)
			)
		]);
		$result = $query->get_posts();
		if( !empty($result) ){
			$result = array_shift($result);
			$result = (array)$result;
		}
		return $result;
	}

	public function save_post($post_ID, $_post, $update){
		global $post;
		$post = $_post;
		remove_action('the_content', 'et_fb_app_boot', 1);
		$_GET['is_new_page'] = '1';
		$is_framework = et_builder_should_load_framework();
		$style_url = '';
		if( $is_framework && class_exists('\ET_Builder_Element') ) {
			$result = \ET_Builder_Element::setup_advanced_styles_manager($post_ID);
			$style_url = $result['manager']->URL;
		}
		ob_start();
		the_content();
		$post_content = ob_get_clean();
		if( $is_framework && $style_url ) {
			$post_content = '<link rel="stylesheet" href="' . $style_url . '">' . $post_content;
		}
		$post_content = apply_filters('app/product/the_content', $post_content, $post_ID, $_post);
		$currentUser = \DIVI\Includes\Core\User::get_current();
		$post_content = $this->sanitize_output($post_content);
		$data = [
			'product_code' => 'Product Code ' . uniqid(),
			'product_title' => $post->post_title,
			'product_description' => $post_content,
			'product_price' => 5000000,
			'product_category' => '60f10a79f3ae14786d155d38',
			'user_id' => $currentUser['id'],
			'product_lang' => 'vi',
			'product_relative_lang' => 'vi',
			'product_status' => $post->post_status,
		];
		$postarr_product = $_POST['product'] ?? [];
		if( !empty($postarr_product) ){
			$data['product_title'] = $postarr_product['product_title'];
			$data['product_price'] = (int)core_convert_number_to_syntax($postarr_product['product_price']);
			$data['product_pay'] = (int)core_convert_number_to_syntax($postarr_product['product_pay']);
			$data['address'] = $postarr_product['address'];
			$data['product_category'] = $postarr_product['product_category'];
			$data['product_unit'] = $postarr_product['product_unit'];
			$data['product_slug'] = sanitize_title($postarr_product['product_slug']);
			$data['product_seo_keywords'] = $postarr_product['product_seo_keywords'];
			$data['product_seo_description'] = $postarr_product['product_seo_description'];
			$data['product_excerpt'] = strip_tags($postarr_product['product_excerpt']);
			$data['product_excerpt'] = htmlentities($data['product_excerpt']);
			$data['product_excerpt'] = nl2br($data['product_excerpt']);
			$data['product_excerpt'] = trim(preg_replace('/\s\s+/', '', $data['product_excerpt']));
			$data['product_excerpt'] = str_replace(str_split("\|"), "", $data['product_excerpt']);
			$data['product_gallery'] = $postarr_product['product_gallery'];
			$data['product_properties'] = $postarr_product['product_properties'];
		}
		if( !$update ){
			$response = \DIVI\Includes\Core\Product::add($data);
			if( !is_wp_error($response) ){
				update_post_meta($post_ID, '__product_id_sync', $response['id']);
			}
		}else{
			$data['id'] = get_post_meta($post_ID, '__product_id_sync', true);
			$response = \DIVI\Includes\Core\Product::update($data);
		}
		if( is_wp_error($response) ){
			global $bf_errors;
			$bf_errors = $response;
		}
	}
	function sanitize_output($buffer) {

		$search = array(
			'/\>[^\S ]+/s',     // strip whitespaces after tags, except space
			'/[^\S ]+\</s',     // strip whitespaces before tags, except space
			'/(\s)+/s',         // shorten multiple whitespace sequences
			'/<!--(.|\s)*?-->/', // Remove HTML comments
			'/(\")/'
		);
		$replace = array(
			'>',
			'<',
			'\\1',
			'',
			"'",
		);

		$buffer = preg_replace($search, $replace, $buffer);

		return $buffer;
	}

	public function hook_wp_insert_post_empty_content($maybe_empty, $postarr){
		return false;
	}

	public function hook_et_builder_library_modal_custom_tabs($custom_tabs, $post_type){
		$custom_tabs = [];
		return $custom_tabs;
	}

}
new Product();