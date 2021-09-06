<?php


namespace POSTSBB\Components\Widgets;

use DIVI\Includes\Core\PostCategory;
use DIVI\Includes\Core\ProductCategory;
use REP\Includes\Product;

class PostCategoriesSlider extends \WP_Widget
{
	public function __construct()
	{
		$id_base = 'postsbb-categories-slider';
		$name = __('PostBB Categories Slider', REAL_ESTATE_PRODUCTS_LANG_DOMAIN);
		$widget_options = array();
		$control_options = array();
		parent::__construct($id_base, $name, $widget_options, $control_options);
	}

	public function widget( $args, $instance ){
		$html = '';
		if( is_user_logged_in() ) {
			global $current_user;
			$column = isset($instance['column']) && abs($instance['column']) > 0 ? $instance['column'] : 3;
			$spaceBetween = isset($instance['space_between']) && absint($instance['space_between']) > 0 ? $instance['space_between'] : 20;
			$pagination = $instance['pagination'] ?? 'yes';
			$navigation = $instance['navigation'] ?? 'yes';
			$autoplay = $instance['autoplay'] ?? 'yes';
			$limit = isset($instance['limit']) ? $instance['limit'] : 0;
			$expert_length = isset($instance['expert_length']) ? $instance['expert_length'] : '';
			$post_categories = $instance['post_categories'] ?? [];
			$products = \DIVI\Includes\Core\Post::posts();
			if( is_wp_error($products) )
			    return;
			wp_enqueue_style('bootstrap');
			wp_enqueue_style('swiper');
			wp_enqueue_script('swiper');
			$POSTSBB_WIDGET_POST_CATE_SLIDE =  [
				'widget_id' => $args['widget_id'],
				'slidesPerView' => $column,
				'spaceBetween' => $spaceBetween,
				'pagination' => $pagination == 'yes' ? [
					'el' => '.swiper-pagination',
					'clickable' => true
				] : '',
				'navigation' => $navigation == 'yes' ? [
					'nextEl' => '.swiper-button-next',
					'prevEl' => '.swiper-button-prev',
				] : '',
				'autoplay' => $autoplay == 'yes' ? ['delay' => 5000] : '',
			];
			if( $post_categories ) {
				$products = array_filter($products, function ($it) use ($post_categories) {
					return array_filter($it['post_category'], function ($_it) use ($post_categories) {
						return in_array($_it['id'], $post_categories);
					}) && $it['post_status'] == 'publish';
				});
			}
			$html = $pagination_html = $navigation_html = '';
			if( $products ){
				$index = 1;
			    $domain = defined('FRONTEND_URL') ? FRONTEND_URL : '';
			    foreach ($products as $item){
			        if( $index > $limit )
			            break;
					$post_gallery = '';
			        if( !empty($item['post_gallery']) && !is_null($item['post_gallery']) ) {
						$item['post_gallery'] = explode(',', $item['post_gallery']);
						$post_gallery = $item['post_gallery'][0];
					}
			        $excerpt = $item['post_excerpt'];
			        if( $expert_length ){
			            $excerpt = wp_trim_words($excerpt, $expert_length);
                    }
					$html .= '
					<div class="swiper-slide">
                        <div class="pfc-postsbb-item">
                            <a class="postsbb-thumbnail d-block" href="' .  $domain . $item['post_slug'] . '">
                                <img src="' . $post_gallery . '" class="w-100" />
                            </a>
                            <a class="d-block" href="' .  $domain . $item['post_slug'] . '">
                                ' . $item['post_title'] . '
                            </a>
                            <div class="postsbb-excerpt">
                                ' . $excerpt . '
                            </div>
                        </div>
					</div>
					';
					$index++;
                }
			    if( $pagination == 'yes' ){
					$pagination_html = '<div class="swiper-pagination"></div>';
                }
			    if( $navigation == 'yes' ){
					$navigation_html = '<div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>';
                }
				if( $html ){
					$html = '<div id="' . $args['widget_id'] . '" class="postsbb-for-categories-slider"><div class="swiper-container"><div class="swiper-wrapper">' . $html . '</div>' . $pagination_html . $navigation_html . '</div></div>';
                }
            }
		}
		echo " <script>
            jQuery(function ($){
                  new Swiper('#" . $POSTSBB_WIDGET_POST_CATE_SLIDE['widget_id'] . " .swiper-container', {
                        slidesPerView: 1,
                        spaceBetween: parseFloat(" . $POSTSBB_WIDGET_POST_CATE_SLIDE['spaceBetween'] . "),
                        pagination: " . $POSTSBB_WIDGET_POST_CATE_SLIDE['pagination'] . ",
                        navigation: " . $POSTSBB_WIDGET_POST_CATE_SLIDE['navigation'] . ",
                        autoplay: " . $POSTSBB_WIDGET_POST_CATE_SLIDE['autoplay'] . ",
                        loop: true,
                        breakpoints: {
                            1024: {
                                slidesPerView: " . $POSTSBB_WIDGET_POST_CATE_SLIDE['slidesPerView'] . "
                            },
                            768: {
                                slidesPerView: 2
                            },
                        }
                    });
            });
        </script>";
		add_filter('app/postsbb/the_content', function ($post_content, $post_ID, $post) use ($args){
			$ssg = '	<link rel="stylesheet" href="' . POSTS_BE_BUILDER_PUBLIC_URL . 'libs/swiper/css/swiper.min.css">
				<script href="' . POSTS_BE_BUILDER_PUBLIC_URL . 'libs/swiper/js/swiper.min.js"></script>';
			$ss = '<link rel="stylesheet" href="' . POSTS_BE_BUILDER_PUBLIC_URL . 'css/styles.css">
				<link rel="stylesheet" href="' . POSTS_BE_BUILDER_PUBLIC_URL . 'css/responsive.css">';
			preg_match('/(js\/swiper\.min\.js)/i', $post_content, $matches);
			if( !empty($matches) ){
				$ssg = '';
			}
			$style_url = '' . POSTS_BE_BUILDER_PUBLIC_URL . 'css/styles.css';
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
				' . $post_content;
			return $post_content;
		}, 100, 3);
		echo $html;
	}

	public function form($instance)
	{
		$column = 3;
		$limit = 3;
		$expert_length = $space_between = 20;
		$post_categories = [];
		$pagination = $navigation = $autoplay = 'yes';
		if ( isset( $instance[ 'post_categories' ] ) ) {
			$post_categories = $instance[ 'post_categories' ];
		}
		if ( isset( $instance[ 'limit' ] ) ) {
			$limit = $instance[ 'limit' ];
		}
		if ( isset( $instance[ 'expert_length' ] ) ) {
			$expert_length = $instance[ 'expert_length' ];
		}
		if ( isset( $instance[ 'column' ] ) ) {
			$column = $instance[ 'column' ];
		}
		if ( isset( $instance[ 'space_between' ] ) ) {
			$space_between = $instance[ 'space_between' ];
		}
		if ( isset( $instance[ 'pagination' ] ) ) {
			$pagination = $instance[ 'pagination' ];
		}
		if ( isset( $instance[ 'navigation' ] ) ) {
			$navigation = $instance[ 'navigation' ];
		}
		if ( isset( $instance[ 'autoplay' ] ) ) {
			$autoplay = $instance[ 'autoplay' ];
		}
		$get_categories = PostCategory::postCategories();
		if( is_wp_error($get_categories) )
			$get_categories = [];
		$option_yes_no = ['yes' => __('Yes'), 'no' => __('No')];
		?>
		<p class="form-group w-100">
			<label for="<?php echo $this->get_field_id( 'post_categories' ); ?>"><?php _e( 'Categories:' ); ?></label>
			<select multiple id="<?php echo $this->get_field_id( 'post_categories' ); ?>" name="post_categories[]">
				<?php
				foreach ($get_categories as $obj){ ?>
					<option value="<?php echo $obj['id'] ?>" <?php echo in_array($obj['id'], $post_categories) ? 'selected' : ''; ?>><?php echo $obj['post_cate_title'] ?></option>
					<?php
				}
				?>
			</select>
		</p>
        <p class="form-group w-100">
            <label for="<?php echo $this->get_field_id( 'column' ); ?>"><?php _e( 'Column:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'column' ); ?>" name="<?php echo $this->get_field_name( 'column' ); ?>" type="number" min="1" value="<?php echo esc_attr( $column ); ?>" />
        </p>
        <p class="form-group w-100">
            <label for="<?php echo $this->get_field_id( 'space_between' ); ?>"><?php _e( 'Space between:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'space_between' ); ?>" name="<?php echo $this->get_field_name( 'space_between' ); ?>" type="number" min="0" value="<?php echo esc_attr( $space_between ); ?>" />
        </p>
        <p class="form-group w-100">
            <label for="<?php echo $this->get_field_id( 'pagination' ); ?>"><?php _e( 'Pagination:' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'pagination' ); ?>"  name="<?php echo $this->get_field_name( 'pagination' ); ?>">
				<?php
				foreach ( $option_yes_no as $key => $item){ ?>
                    <option value="<?php echo $key ?>" <?php echo $key == $pagination ? 'selected' : ''; ?>><?php echo $item ?></option>
					<?php
				}
				?>
            </select>
        </p>
        <p class="form-group w-100">
            <label for="<?php echo $this->get_field_id( 'navigation' ); ?>"><?php _e( 'Navigation:' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'navigation' ); ?>"  name="<?php echo $this->get_field_name( 'navigation' ); ?>">
				<?php
				foreach ( $option_yes_no as $key => $item){ ?>
                    <option value="<?php echo $key ?>" <?php echo $key == $navigation ? 'selected' : ''; ?>><?php echo $item ?></option>
					<?php
				}
				?>
            </select>
        </p>
        <p class="form-group w-100">
            <label for="<?php echo $this->get_field_id( 'navigation' ); ?>"><?php _e( 'Navigation:' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'navigation' ); ?>"  name="<?php echo $this->get_field_name( 'navigation' ); ?>">
				<?php
				foreach ( $option_yes_no as $key => $item){ ?>
                    <option value="<?php echo $key ?>" <?php echo $key == $navigation ? 'selected' : ''; ?>><?php echo $item ?></option>
					<?php
				}
				?>
            </select>
        </p>
        <p class="form-group w-100">
            <label for="<?php echo $this->get_field_id( 'autoplay' ); ?>"><?php _e( 'Autoplay:' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'autoplay' ); ?>"  name="<?php echo $this->get_field_name( 'autoplay' ); ?>">
				<?php
				foreach ( $option_yes_no as $key => $item){ ?>
                    <option value="<?php echo $key ?>" <?php echo $key == $autoplay ? 'selected' : ''; ?>><?php echo $item ?></option>
					<?php
				}
				?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="number" value="<?php echo esc_attr( $limit ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'expert_length' ); ?>"><?php _e( 'Expert length:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'expert_length' ); ?>" name="<?php echo $this->get_field_name( 'expert_length' ); ?>" type="number" value="<?php echo esc_attr( $expert_length ); ?>" />
        </p>
        <script>
            jQuery(function ($){
                $('#widget-postsbb-categories-slider-2-post_categories').select2();
            });
        </script>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['post_categories'] = $_POST['post_categories'] ?? [];
		$instance['column'] = ( ! empty( $new_instance['column'] ) ) ? strip_tags( $new_instance['column'] ) : 3;
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? absint( $new_instance['limit'] ) : '';
		$instance['expert_length'] = ( ! empty( $new_instance['expert_length'] ) ) ? absint( $new_instance['expert_length'] ) : '';
		return $instance;
	}
}