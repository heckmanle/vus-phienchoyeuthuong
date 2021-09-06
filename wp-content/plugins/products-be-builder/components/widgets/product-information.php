<?php


namespace REP\Components\Widgets;

use REP\Includes\Product;

class ProductInformation extends \WP_Widget
{
	public function __construct()
	{
		$id_base = 'rep-product-information';
		$name = __('Product Information', REAL_ESTATE_PRODUCTS_LANG_DOMAIN);
		$widget_options = array();
		$control_options = array();
		parent::__construct($id_base, $name, $widget_options, $control_options);
	}

	public function widget( $args, $instance ){
		$html = '';
		if( is_admin() ){
			$current_page = $_POST['current_page'] ?? [];
			$post_type = $_POST['post_type'] ?? '';
			$id = $current_page['id'] ?? 0;
			if( $post_type == Product::PRODUCT_TYPE ){
				$pro_id = get_post_meta($id, Product::PRODUCT_META_SYNC, true);
			}
		}else{
			global $post;
			if( $post->post_type == Product::PRODUCT_TYPE ){
				$pro_id = get_post_meta($post->ID, Product::PRODUCT_META_SYNC, true);
			}
		}
		if( !empty($pro_id) ){
			$fields = [
				'id',
				'product_code',
				'product_title',
				'product_unit',
				'product_price',
				'product_category' => [
					'id',
					'cate_title'
				],
				'product_description',
				'product_excerpt',
				'address',
				'product_number',
				'product_pay',
				'product_status',
				'product_gallery',
				'updated',
				'created',
				'user_id' => [
					'id',
					'avatar',
					'name',
					'email',
					'phone',
					'registered',
				],
			];
			$response = \DIVI\Includes\Core\Product::get_by_id($pro_id, $fields);
			if( !is_wp_error($response) ){
				$user = $response['user_id'];
				$user_avatar = $user['avatar'] ? $user['avatar'] : REAL_ESTATE_PRODUCTS_PUBLIC_URL . 'images/default_profile.png';
				$excerpt = $response['product_excerpt'];
				$html = '
				<div class="row">
					<div class="col-md-7">
						<h3 class="wdpinfo-property-title">' . __('Property informations', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) . '</h3>
						' . $excerpt . '
					</div>
					<div class="col-md-5">
						<div class="d-md-flex justify-content-end">
							<div class="wdpinfo-author">
							<div class="wdpinfo-author-avatar mb-3">
								<a href="javascript:;" class="m-auto">
									<img src="' . $user_avatar . '" class="w-100">
								</a>
							</div>
							<div class="wdpinfo-author-name text-center">
								<div class="wdpinfo-author-full-name d-flex align-items-center justify-content-center">
									<span class="pr-2">' . $user['name'] . '</span>
									<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M0 11C0 4.92487 4.92487 0 11 0C17.0751 0 22 4.92487 22 11C22 17.0751 17.0751 22 11 22C4.92487 22 0 17.0751 0 11Z" fill="#27AE60"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M16.3107 7.04297C16.7012 7.43349 16.7012 8.06666 16.3107 8.45718L10.1036 14.9572C9.71307 15.3477 9.0799 15.3477 8.68938 14.9572L5.68938 11.9572C5.29885 11.5667 5.29885 10.9335 5.68938 10.543C6.0799 10.1524 6.71307 10.1524 7.10359 10.543L9.39648 12.8359L14.8965 7.04297C15.287 6.65244 15.9202 6.65244 16.3107 7.04297Z" fill="white"/>
</svg>
								</div>
								<div class="wdpinfo-author-join pb-2">Agent • Joined 2020</div>
							</div>
							<div class="wdpinfo-author-contact d-flex align-items-center justify-content-center">
								<span class="pr-3 wdpinfo-author-contact-title">' . __('Contact Agent', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) . '</span>
								<a href="javascript:;" class="wdpinfo-author-icon-message wdpinfo-author-group-icon-item">
									<svg width="15" height="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M11.0703 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V12.9297C20.8233 13.6104 19.4571 14 18 14C13.5817 14 10 10.4183 10 6C10 4.54285 10.3896 3.17669 11.0703 2Z" fill="white"/>
</svg>
									<span class="count-message">1</span>
								</a>
							</div>
							<div class="mt-4 text-center">
								<a href="javascript:;" class="wdpinfo-book d-inline-block">' . __('Book', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) . '</a>
							</div>
						</div>
						</div>
					</div>
				</div>
				';
			}
		}

		echo $html;
	}

	public function form($instance)
	{

	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}
}