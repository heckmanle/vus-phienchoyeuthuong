<?php

namespace PBB\Includes;

class PagesBeBuilder
{
	#CONST POST_TYPE = 'pages_be_builder';
	CONST POST_TYPE = 'page';
	CONST POST_CATEGORIES_TYPE = 'pbb_categories';
	CONST POST_META_SYNC = '__pbb_id_sync';
	public function __construct()
	{
		add_action('init', [$this, 'initialize'], 100);
		add_action('save_post_' . self::POST_TYPE, [$this, 'save_post'], 100, 3);
		add_filter('wp_insert_post_empty_content', [$this, 'hook_wp_insert_post_empty_content'], 999, 2);

		/**
		 * FILE: wp-content/themes/beme/includes/builder/feature/Library.php
		 * LINE: 833
		 */
		add_filter('et_builder_library_modal_custom_tabs', [$this, 'hook_et_builder_library_modal_custom_tabs'], 999, 2);
	}

	public function initialize(){
		#$this->register();
	}

	public function register()
	{
		$slug = esc_attr(__('pages-be-builder', PAGES_BE_BUILDER_LANG_DOMAIN));
		$tax = esc_attr(__('pbb-categories', PAGES_BE_BUILDER_LANG_DOMAIN));

		$labels = array(
			'name' => esc_html__('Pages Be Builder', PAGES_BE_BUILDER_LANG_DOMAIN),
			'singular_name' => esc_html__('Pages Be Builder', PAGES_BE_BUILDER_LANG_DOMAIN),
			'add_new' => esc_html__('Thêm mới', PAGES_BE_BUILDER_LANG_DOMAIN),
			'add_new_item' => esc_html__('Thêm mới', PAGES_BE_BUILDER_LANG_DOMAIN),
			'edit_item' => esc_html__('Chỉnh sửa', PAGES_BE_BUILDER_LANG_DOMAIN),
			'new_item' => esc_html__('Thêm', PAGES_BE_BUILDER_LANG_DOMAIN),
			'view_item' => esc_html__('Xem', PAGES_BE_BUILDER_LANG_DOMAIN),
			'search_items' => esc_html__('Tìm kiếm', PAGES_BE_BUILDER_LANG_DOMAIN),
			'not_found' => esc_html__('Không tìm thấy', PAGES_BE_BUILDER_LANG_DOMAIN),
			'not_found_in_trash' => esc_html__('Không tìm thấy trong thùng rác', PAGES_BE_BUILDER_LANG_DOMAIN),
			'parent_item_colon' => '',
		);
		$plural_base = self::POST_TYPE;
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
//                'slug' => "/",
//				"with_front" => false,
				'feed' => true,
				'pages' => true,
			),
			'supports' => array('author', 'comments' ,'custom-fields', 'editor', 'excerpt', 'page-attributes', 'thumbnail', 'title'),
		);

		register_post_type(self::POST_TYPE, $args);

		$capabilities_taxonomy = [
			'manage_terms' => 'manage_categories_' . self::POST_TYPE,
			'edit_terms'   => 'manage_categories_' . self::POST_TYPE,
			'delete_terms' => 'manage_categories_' . self::POST_TYPE,
			'assign_terms' => 'assign_terms_' . self::POST_TYPE,
		];

		register_taxonomy(self::POST_CATEGORIES_TYPE, self::POST_TYPE, array(
			'label' => esc_html__('Pages Be Builder Categories', PAGES_BE_BUILDER_LANG_DOMAIN),
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

	public static function get_by_page_id($pro_id){
		$query = new \WP_Query([
			'post_type' => self::POST_TYPE,
			'meta_query' => array(
				array(
					'key' => self::POST_META_SYNC,
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
		$currentUser = \DIVI\Includes\Core\User::get_current();
        $currentUserId="";
        if( !empty($currentUser->errors['403'][0])){
            $currentUserId="";
        } else {
            $currentUserId=$currentUser['id'];
        }
		$post_content = $this->sanitize_output($post_content);
		$data = [
			'product_code' => 'PBB Code ' . uniqid(),
			'product_title' => $post->post_title,
			'product_description' => $post_content,
			'product_price' => '',
			'product_category' => '',
			'user_id' => $currentUserId,
			'product_lang' => 'vi',
			'product_relative_lang' => 'vi',
			'product_status' => $post->post_status,
		];
		$postarr_product = $_POST['product'] ?? [];
		if( !empty($postarr_product) ){
			$data['product_title'] = $postarr_product['product_title'];
			#$data['product_price'] = (int)core_convert_number_to_syntax($postarr_product['product_price']);
			#$data['address'] = $postarr_product['address'];
			#$data['product_category'] = $postarr_product['product_category'];
			#$data['product_slug'] = sanitize_title($postarr_product['product_slug']);
			$data['product_slug'] = $post->post_name;
			$data['product_seo_keywords'] = $postarr_product['product_seo_keywords'];
			$data['product_seo_description'] = $postarr_product['product_seo_description'];
			$data['product_gallery'] = implode(',', $postarr_product['product_gallery']);
			if( is_null($data['product_gallery']) ){
				$data['product_gallery'] = "";
			}
		}
		unset($data['product_price']);
		unset($data['product_category']);
		if( !$update ){
			$response = \DIVI\Includes\Core\Pages::add($data);
			if( !is_wp_error($response) ){
				update_post_meta($post_ID, self::POST_META_SYNC, $response['id']);
			}
		}else{
			$data['id'] = get_post_meta($post_ID, self::POST_META_SYNC, true);
			$response = \DIVI\Includes\Core\Pages::update($data);
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
