<?php


namespace REP\Components\Widgets;

use REP\Includes\Product;

class ProductFormSubmit extends \WP_Widget
{
	public function __construct()
	{
		$id_base = 'rep-product-form-submit';
		$name = __('Product Form Submit', REAL_ESTATE_PRODUCTS_LANG_DOMAIN);
		$widget_options = array();
		$control_options = array();
		parent::__construct($id_base, $name, $widget_options, $control_options);
	}

	public function widget( $args, $instance ){
		$html = '';
		$product_address_list = \DIVI\Includes\Core\GlobalSettings::publishGlobalSetting('product-address-list');
		$product_categories = \DIVI\Includes\Core\ProductCategory::productCategories();
		$form_action = $instance[ 'form_action' ] ?? site_url();
		if( is_wp_error($product_address_list) || empty($product_address_list) ){
			$product_address_list = [];
		}else{
			$product_address_list['value'] = explode(',', $product_address_list['value']);
			$product_address_list = array_map(function ($item){
				$item = trim($item);
				return $item;
			}, $product_address_list['value']);
		}
		$option_address = '<option value="">' . __('Nơi làm việc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) . '</option>';
		$option_cate = '<option value="">' . __('Cấp bậc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) . '</option>';
		if( $product_address_list ) {
			foreach ($product_address_list as $item) {
				$option_address .= sprintf('<option value="%s">%s</option>', $item, $item);
			}
		}
		if( is_wp_error($product_categories) )
			$product_categories = [];
		if( $product_categories ) {
			foreach ($product_categories as $item) {
				$option_cate .= sprintf('<option value="%s">%s</option>', $item['id'], $item['cate_title']);
			}
		}
		$html = '
		<div id="' . $args['widget_id'] . '" class="rep-widget-form-submit">
		<form action="' . $form_action . '" method="get">
			<div class="d-flex rep-wfs-content">
				<input class="keyword form-control" name="keyword" placeholder="' . __('Nhập một công việc...', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) . '">
				<div class="form-group w-100 mb-0">
					<select class="sl-address" name="address">
						' . $option_address . '
					</select>
				</div>
				<div class="form-group w-100 mb-0">
					<select class="sl-cates" name="cate">
						' . $option_cate . '
					</select>
				</div>
				<div class="filter-action">
				    <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                        <span class="ml-1">' . __('Tìm', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) . '</span>
                    </button>
                </div>
			</div>
		</form>
		</div>
		<script>
		    jQuery(function($){
		        $("#' . $args['widget_id'] . ' .sl-address" ).select2();
		        $("#' . $args['widget_id'] . ' .sl-cates" ).select2();
		    });
        </script>
		';
		echo $html;
	}

	public function form($instance)
	{
		$form_action = '';
		if ( isset( $instance[ 'form_action' ] ) ) {
			$form_action = $instance[ 'form_action' ];
		}
		?>
		<p class="form-group w-100">
			<label for="<?php echo $this->get_field_id( 'form_action' ); ?>"><?php _e( 'Action:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'form_action' ); ?>" name="<?php echo $this->get_field_name( 'form_action' ); ?>" type="text" value="<?php echo esc_attr( $form_action ); ?>" />
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['form_action'] = ( ! empty( $new_instance['form_action'] ) ) ? strip_tags( $new_instance['form_action'] ) : '';
		return $instance;
	}
}