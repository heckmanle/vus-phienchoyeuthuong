<?php


namespace POSTSBB\Includes;

class PostsBeBuilder
{
	#CONST POST_TYPE = 'posts_be_builder';
	CONST POST_TYPE = 'post';
	CONST POST_CATEGORIES_TYPE = 'postsbb_categories';
	CONST POST_META_SYNC = '__postsbb_id_sync';
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
		$slug = esc_attr(__('posts-be-builder', PAGES_BE_BUILDER_LANG_DOMAIN));
		$tax = esc_attr(__('postsbb-categories', PAGES_BE_BUILDER_LANG_DOMAIN));

		$labels = array(
			'name' => esc_html__('Posts Be Builder', PAGES_BE_BUILDER_LANG_DOMAIN),
			'singular_name' => esc_html__('Posts Be Builder', PAGES_BE_BUILDER_LANG_DOMAIN),
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

	public static function get_by_post_id($pro_id){
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
		$post_content = apply_filters('app/postsbb/the_content', $post_content, $post_ID, $_post);
		$currentUser = \DIVI\Includes\Core\User::get_current();
		$post_content = $this->sanitize_output($post_content);
		$data = [
			'post_code' => '',
			'post_title' => $post->post_title,
			'post_description' => $post_content,
			'post_category' => '',
			#'author' => $currentUser['id'],
			#'post_lang' => 'vi',
			#'post_relative_lang' => 'vi',
			'post_status' => $post->post_status,
		];
		$postarr_product = $_POST['postsbb'] ?? [];
		if( !empty($postarr_product) ){
			$data['post_category'] = $postarr_product['post_category'];
			#$data['post_slug'] = sanitize_title($postarr_product['post_slug']);
			$data['post_slug'] = $post->post_name;
			$data['post_seo_keywords'] = $postarr_product['post_seo_keywords'];
			$data['post_seo_description'] = $postarr_product['post_seo_description'];
			$data['post_excerpt'] = strip_tags($postarr_product['post_excerpt']);
			$data['post_excerpt'] = htmlentities($data['post_excerpt']);
			$data['post_excerpt'] = nl2br($data['post_excerpt']);
			$data['post_excerpt'] = trim(preg_replace('/\s\s+/', '', $data['post_excerpt']));
			$data['post_excerpt'] = str_replace(str_split("\|"), "", $data['post_excerpt']);
			$data['post_gallery'] = $postarr_product['post_gallery'];
		}
		unset($data['post_price']);
		if( !$update ){
			$response = \DIVI\Includes\Core\Post::add($data);
			if( !is_wp_error($response) ){
				update_post_meta($post_ID, self::POST_META_SYNC, $response['id']);
			}
		}else{
			$data['id'] = get_post_meta($post_ID, self::POST_META_SYNC, true);
			$response = \DIVI\Includes\Core\Post::update($data);
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
