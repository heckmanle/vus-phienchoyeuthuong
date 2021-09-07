<?php


namespace REP\Components\Widgets;

use REP\Includes\Product;

class ProductGallery extends \WP_Widget
{
	public function __construct()
	{
		$id_base = 'rep-product-gallery';
		$name = __('Product Gallery', REAL_ESTATE_PRODUCTS_LANG_DOMAIN);
		$widget_options = array();
		$control_options = array();
		parent::__construct($id_base, $name, $widget_options, $control_options);
	}

	public function widget( $args, $instance ){
		$html = '';
		if( is_user_logged_in() ) {
			global $current_user;
			wp_enqueue_style('bootstrap');
			wp_enqueue_style('lightgallery');
			wp_enqueue_script('lightgallery');
			wp_enqueue_script('widget-pro-gallery', REAL_ESTATE_PRODUCTS_PUBLIC_URL . 'js/widgets/pro-gallery.js', ['jquery']);
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
			$html_items = $html_light = '';
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
					$pro_gallery = $response['product_gallery'];
					if( $pro_gallery ){
						$pro_gallery = explode(',', $pro_gallery);
						$count = count($pro_gallery);
						foreach ($pro_gallery as $key => $it){
							$html_light .= '<a href="' . $it . '" class="progi-' . ($key + 1) . '"><img src="' . $it . '"></a>';
                        }
						if( $count == 1 ){
							$html_items .= '<div class="col-12"><div class="pro-gallery-item" data-cls="progi-1"><a href="' . $pro_gallery[0] . '"><img src="' . $pro_gallery[0] . '"/></a></div></div>';
						}elseif($count == 2){
							$html_items .= '<div class="col-6"><div class="pro-gallery-item" data-cls="progi-1"><a href="' . $pro_gallery[0] . '"><img src="' . $pro_gallery[0] . '"/></a></div></div>';
							$html_items .= '<div class="col-6"><div class="pro-gallery-item" data-cls="progi-2"><a href="' . $pro_gallery[1] . '"><img src="' . $pro_gallery[1] . '"/></a></div></div>';
						}elseif($count == 3){
							$html_items .= '<div class="col-4"><div class="pro-gallery-item" data-cls="progi-1"><a href="' . $pro_gallery[0] . '"><img src="' . $pro_gallery[0] . '"/></a></div></div>';
							$html_items .= '<div class="col-4"><div class="pro-gallery-item" data-cls="progi-2"><a href="' . $pro_gallery[1] . '"><img src="' . $pro_gallery[1] . '"/></a></div></div>';
							$html_items .= '<div class="col-4"><div class="pro-gallery-item" data-cls="progi-3"><a href="' . $pro_gallery[2] . '"><img src="' . $pro_gallery[2] . '"/></a></div></div>';
						}elseif($count == 4){
							$html_items .= '<div class="col-6"><div class="pro-gallery-item" data-cls="progi-1"><a href="' . $pro_gallery[0] . '"><img src="' . $pro_gallery[0] . '"/></a></div></div>';
							$html_items .= '<div class="col-6"><div class="pro-gallery-item" data-cls="progi-2"><a href="' . $pro_gallery[1] . '"><img src="' . $pro_gallery[1] . '"/></a></div></div>';
							$html_items .= '<div class="col-6"><div class="pro-gallery-item" data-cls="progi-3"><a href="' . $pro_gallery[2] . '"><img src="' . $pro_gallery[2] . '"/></a></div></div>';
							$html_items .= '<div class="col-6"><div class="pro-gallery-item" data-cls="progi-4"><a href="' . $pro_gallery[3] . '"><img src="' . $pro_gallery[3] . '"/></a></div></div>';
						}elseif($count > 4){
						    $more = $count - 5;
							$more_html = '';
						    if( $more > 0 ){
						        $more_html = '<span class="prog-more" more="' . $more . '+"></span>';
                            }
							$html_items = '
								    <div class="col-md-6">
								        <div class="row">
								            <div class="col-md-6">
								                <div class="pro-gallery-item" data-cls="progi-2"><a href="' . $pro_gallery[1] . '"><img src="' . $pro_gallery[1] . '"/></a></div>
                                            </div>
								            <div class="col-md-6">
								                <div class="pro-gallery-item" data-cls="progi-3"><a href="' . $pro_gallery[2] . '"><img src="' . $pro_gallery[2] . '"/></a></div>
                                            </div>
                                        </div>
								        <div class="row">
								            <div class="col-md-6">
								                <div class="pro-gallery-item" data-cls="progi-4"><a href="' . $pro_gallery[3] . '"><img src="' . $pro_gallery[3] . '"/></a></div>
                                            </div>
								            <div class="col-md-6">
								                <div class="pro-gallery-item" data-cls="progi-5"><a href="' . $pro_gallery[4] . '"><img src="' . $pro_gallery[4] . '"/>' . $more_html . '</a></div>
                                            </div>
                                        </div>
                                    </div>
								    <div class="col-md-6">
								        <div class="pro-gallery-item" data-cls="progi-1"><a href="' . $pro_gallery[0] . '"><img src="' . $pro_gallery[0] . '"/></a></div>
								    </div>
								    ';
						}
					}
				}
			}
			$html = '<div class="row pro-gallery-items">' . $html_items . '</div><div id="pro-lightgallery-js-' . $args['widget_id'] . '" style="display: none">' . $html_light . '</div>';
			add_filter('app/product/the_content', function ($post_content, $post_ID, $post) use ($args){
				$html = "
				<script>
					(function( $ ) {
					'use strict';
					$('#" . $args['widget_id'] . " .pro-gallery-item').click(function (ev){
						ev.preventDefault();
						let {cls} = $(this).data();
						$('#pro-lightgallery-js-" . $args['widget_id'] . " .' + cls + ' img').trigger('click');
						ev.stopPropagation();
					});
					lightGallery(document.getElementById('pro-lightgallery-js-" . $args['widget_id'] . "'));
				})(jQuery); 
			</script>
			";
				$ssg = '	<link rel="stylesheet" href="' . REAL_ESTATE_PRODUCTS_PUBLIC_URL . 'libs/lightgallery/css/lightgallery.css">
				<script href="' . REAL_ESTATE_PRODUCTS_PUBLIC_URL . 'libs/lightgallery/js/lightgallery.js"></script>';
				$ss = '<link rel="stylesheet" href="' . REAL_ESTATE_PRODUCTS_PUBLIC_URL . 'css/styles.css">
				<link rel="stylesheet" href="' . REAL_ESTATE_PRODUCTS_PUBLIC_URL . 'css/responsive.css">';
				preg_match('/(js\/lightgallery\.js)/i', $post_content, $matches);
				if( !empty($matches) ){
					$ssg = '';
				}
				$style_url = '' . REAL_ESTATE_PRODUCTS_PUBLIC_URL . 'css/styles.css';
				$style_url = preg_replace('/https?:\/\//i', '', $style_url);
				$regex = preg_quote($style_url);
				$regex = str_replace('/', '\/', $regex);
				preg_match('/(' . $regex . ')/i', $post_content, $matches2);
				if( $matches2 ){
					$ss = '';
				}
				$post_content = '
				' . $ssg . '
				' . $ss . '
				' . $post_content . $html;
				return $post_content;
			}, 100, 3);
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