<?php


namespace REP\Components\Widgets;

use DIVI\Includes\Core\ProductCategory;
use REP\Includes\Product;

class ProductCategories extends \WP_Widget
{
	public function __construct()
	{
		$id_base = 'rep-product-categories';
		$name = __('Product Categories', REAL_ESTATE_PRODUCTS_LANG_DOMAIN);
		$widget_options = array();
		$control_options = array();
		parent::__construct($id_base, $name, $widget_options, $control_options);
	}

	public function widget( $args, $instance ){
		$html = '';
		if( is_user_logged_in() ) {
			global $current_user;
			$column = isset($instance['column']) && abs($instance['column']) > 0 ? $instance['column'] : 3;
			$text_more = isset($instance['text_more']) ? __($instance['text_more'], REAL_ESTATE_PRODUCTS_LANG_DOMAIN) : __('Read more', REAL_ESTATE_PRODUCTS_LANG_DOMAIN);
			if( $column == 4 ){
				$column = 12 / 3;
            }elseif($column == 3){
			    $column = 12 / 4;
            }else{
			    $column = 12 / $column;
            }
			$limit = isset($instance['limit']) ? $instance['limit'] : 0;
			$expert_length = isset($instance['expert_length']) ? $instance['expert_length'] : '';
			$product_categories = $instance['product_categories'] ?? [];
			$products = \DIVI\Includes\Core\Product::products();
			if( $product_categories ) {
				$products = array_filter($products, function ($it) use ($product_categories) {
					return array_filter($it['product_category'], function ($_it) use ($product_categories) {
						return in_array($_it['id'], $product_categories);
					}) && $it['product_status'] == 'publish';
				});
			}
			$html_more = '';
			if( count($products) > $limit ){
                $html_more = '<div class="pcf-more"><a href="#">' . $text_more . '</a></div>';
			}
			$html = '';
			if( $products ){
			    $col = 12 / $column;
				$index = 1;
			    $domain = defined('FRONTEND_URL') ? FRONTEND_URL : '';
			    foreach ($products as $item){
			        if( $index > $limit )
			            break;
					$product_gallery = '';
			        if( !empty($item['product_gallery']) && !is_null($item['product_gallery']) ) {
						$item['product_gallery'] = explode(',', $item['product_gallery']);
						$product_gallery = $item['product_gallery'][0];
					}
			        $excerpt = $item['product_excerpt'];
			        if( $expert_length ){
			            $excerpt = wp_trim_words($excerpt, $expert_length);
                    }
					$html .= '
					<div class="col-md-' . $col . ' pfc-product-item">
					    <a class="product-thumbnail d-block" href="' .  $domain . $item['product_slug'] . '">
					        <img src="' . $product_gallery . '" class="w-100" />
					    </a>
					    <a class="d-block" href="' .  $domain . $item['product_slug'] . '">
					        ' . $item['product_title'] . '
					    </a>
					    <div class="product-excerpt">
					        ' . $excerpt . '
					    </div>
					</div>
					';
					$index++;
                }
				if( $html ){
					$html = '<div class="product-for-categories"><div class="row">' . $html . '</div>' . $html_more . '</div>';
                }
            }
		}
		echo $html;
	}

	public function form($instance)
	{
		$column = 3;
		$limit = 3;
		$expert_length = 20;
		$product_categories = [];
		$text_more = __('Read more', REAL_ESTATE_PRODUCTS_LANG_DOMAIN);
		if ( isset( $instance[ 'product_categories' ] ) ) {
			$product_categories = $instance[ 'product_categories' ];
		}
		if ( isset( $instance[ 'limit' ] ) ) {
			$limit = $instance[ 'limit' ];
		}
		if ( isset( $instance[ 'expert_length' ] ) ) {
			$expert_length = $instance[ 'expert_length' ];
		}
		if ( isset( $instance[ 'text_more' ] ) ) {
			$text_more = $instance[ 'text_more' ];
		}
		if ( isset( $instance[ 'column' ] ) ) {
			$column = $instance[ 'column' ];
		}
		$get_categories = ProductCategory::productCategories();
		?>
		<p class="form-group w-100">
			<label for="<?php echo $this->get_field_id( 'product_categories' ); ?>"><?php _e( 'Categories:' ); ?></label>
			<select multiple id="<?php echo $this->get_field_id( 'product_categories' ); ?>" name="product_categories[]">
				<?php
				foreach ($get_categories as $obj){ ?>
					<option value="<?php echo $obj['id'] ?>" <?php echo in_array($obj['id'], $product_categories) ? 'selected' : ''; ?>><?php echo $obj['cate_title'] ?></option>
					<?php
				}
				?>
			</select>
		</p>
        <p class="form-group w-100">
            <label for="<?php echo $this->get_field_id( 'column' ); ?>"><?php _e( 'Column:' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'column' ); ?>" name="<?php echo $this->get_field_name( 'column' ); ?>">
				<?php foreach ([1, 2, 3, 4, 6] as $col){ ?>
                    <option value="<?php echo $col ?>" <?php echo $column == $col ? 'selected' : ''; ?>><?php echo $col ?></option>
				<?php } ?>
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
        <p>
            <label for="<?php echo $this->get_field_id( 'text_more' ); ?>"><?php _e( 'Text more:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'text_more' ); ?>" name="<?php echo $this->get_field_name( 'text_more' ); ?>" type="text" value="<?php echo esc_attr( $text_more ); ?>" />
        </p>
        <p>
        <script>
            jQuery(function ($){
                $('#widget-rep-product-categories-2-product_categories, #widget-rep-product-categories-2-column').select2();
            });
        </script>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['product_categories'] = $_POST['product_categories'] ?? [];
		$instance['column'] = ( ! empty( $new_instance['column'] ) ) ? strip_tags( $new_instance['column'] ) : 3;
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? absint( $new_instance['limit'] ) : '';
		$instance['expert_length'] = ( ! empty( $new_instance['expert_length'] ) ) ? absint( $new_instance['expert_length'] ) : '';
		return $instance;
	}
}