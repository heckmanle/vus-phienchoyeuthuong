<?php
global $post;
$_keyword = $_GET['keyword'] ?? '';
$_address = $_GET['address'] ?? '';
$_cate = $_GET['cate'] ?? '';
$_keyword = trim($_keyword);
$_address = trim($_address);
$_cate = trim($_cate);
$products = \DIVI\Includes\Core\Product::products();
if( is_wp_error($products) ){
    $products = [];
}
$products = array_filter($products, function ($item) {
    return $item['product_status'] == 'publish';
});
$product_address_list = \DIVI\Includes\Core\GlobalSettings::publishGlobalSetting('product-address-list');
if( is_wp_error($product_address_list) || empty($product_address_list) ){
	$product_address_list = [];
}else{
	$product_address_list['value'] = explode(',', $product_address_list['value']);
	$product_address_list = array_map(function ($item){
	    $item = trim($item);
	    return ['value' => $item, 'amount' => 0];
    }, $product_address_list['value']);
}
$product_categories = \DIVI\Includes\Core\ProductCategory::productCategories();
//add_filter('get_pagenum_link', function($result, $pagenum){
//	global $wp_rewrite;
//	$request = site_url('/job/') . user_trailingslashit( $wp_rewrite->pagination_base . '/' . $pagenum, 'paged' );
//	$result = $request;
//	return $result;
//}, 100, 2);

get_header();
wp_enqueue_script('real-estate-products-script');
wp_enqueue_style('real-estate-products-pagination');


$limit = 8;
$html_pro_item = '';
$args = [
    'prev_next' => false,
];
#$pagination = core_get_pagination(count($products), $limit, '', $args);
if( $products ){

    $i = 0;
    $pro_url = site_url('/job');
    foreach ($products as $k_p => $item){
        $product_properties = $item['product_properties'] ?? [];
        $product_title = $item['product_title'] ?? '';
        $address = $item['address'] ?? '';
        $product_slug = $item['product_slug'] ?? '';
        $product_category = $item['product_category'] ?? [];
        $product_gallery = $item['product_gallery'] ?? [];
        $product_thumbnail = REAL_ESTATE_PRODUCTS_PUBLIC_URL . '/images/no-image.png';
        if( $product_gallery ){
            $product_thumbnail = $product_gallery[0] ? $product_gallery[0] : $product_thumbnail;
        }
		$html_property_item = '';
		$pro_url_detail = $pro_url . '/' . $product_slug;
		$link_apply = add_query_arg(['apply' => 'cv'], $pro_url_detail);
		$products[$k_p]['product_thumbnail'] = $product_thumbnail;
		$products[$k_p]['link'] = $pro_url_detail;
		$products[$k_p]['link_apply'] = $link_apply;
        $_pro_prop = [];
        if( !empty($product_properties) ){
            foreach ($product_properties as $prop_item){
                $prop_item = explode(',', $prop_item);
                $prop_item = array_map('trim', $prop_item);
                if( count($prop_item) < 3 )
                    continue;
                $html_property_item .= '
                <div class="col-md-6">
                    <span class="prop-icon"><img src="' . $prop_item[0] . '" width="12"></span>
                    <span class="prop-value">' . $prop_item[1] . '</span>
                    <span class="prop-unit">' . $prop_item[2] . '</span>
                </div>
                ';
				$_pro_prop[] = [
                    'icon' => $prop_item[0],
                    'value' => $prop_item[1],
                    'unit' => $prop_item[2],
                ];
            }
			$products[$k_p]['product_properties'] = $_pro_prop;
        }
        if( $i < $limit ){
            $html_pro_item .= '
            <div class="col-md-12 col-lg-6">
                <div class="pro-item pro-item-mode-1">
                    <div class="pro-thumbnail">
                        <a href="' . $pro_url_detail . '" class="d-block" style="background-image: url(' . $product_thumbnail . ');"></a>
                    </div>
                    <div class="pro-content">
                        <a class="pro-title" href="' . $pro_url_detail . '">' . $product_title . '</a>
                        <div class="pro-properties">
                            <div class="d-md-flex justify-content-between">
                                <div class="pro-properties-items">
                                    <div class="row">
                                        ' . $html_property_item . '
                                    </div>
                                </div>
                                <div class="pro-action-apply-wrapper">
                                    <div class="d-flex align-items-center justify-content-end hg-100">
                                        <a href="' . $link_apply . '" class="pro-apply">' . __('Ứng tuyển', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) . '</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
        if( !empty($product_categories) ){
            foreach ($product_categories as $key => $pro_cate){
                $filter = array_filter($product_category, function ($it) use ($pro_cate) {
                    return $it['id'] == $pro_cate['id'];
                });
                if( !array_key_exists('amount', $pro_cate) ){
                    $product_categories[$key]['amount'] = 0;
                }
                if( $filter ){
					$product_categories[$key]['amount']++;
                }
            }
        }
        if( !empty($product_address_list) ){
            foreach ($product_address_list as $key => $add_item){
                if( $address == $add_item['value'] ){
					$product_address_list[$key]['amount']++;
                }
            }
        }
        $i++;
    }
}

?>
    <script>
        const REP_IMAGE_LOADING = {
            message: '<div class="ball-clip-rotate-multiple"> <div></div><div></div></div>',
        };
        const REP_AJAX_URL = "<?php echo REAL_ESTATE_PRODUCTS_AJAX_URL; ?>";
    </script>
<div class="products-wrapper">
    <?php the_content(); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="rep-sav-sidebar">
                    <div class="repss-item">
                        <h3 class="font-weight-bold">
                            <img class="ico-left" src="<?php echo REAL_ESTATE_PRODUCTS_PUBLIC_URL;?>/images/icon-bg-jobs-fillter.svg" width="18">
                            <?php _e('Tìm nâng cao', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?>
                        </h3>
                    </div>
                    <div class="repss-item ress-address">
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th colspan="100%" class="p-0">
                                    <h4 class="font-weight-bold"><?php _e('Nơi làm việc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></h4>
                                </th>
                            </tr>
                            </thead>
							<?php
							if( $product_address_list ){
								foreach ($product_address_list as $item){
								    $checked = $_address == $item['value'] ? 'checked' : '';
									echo sprintf('
                                <tr>
                                    <td class="p-0 pt-1 pb-1 align-top" width="30"><label class="switch mb-0 check-item">
                                                <input class="checkbox-status check" %s autocomplete="off" type="checkbox" value="%s">
                                                <span class="checkbox-slider fa"></span>
                                            </label></td>
                                    <td class="p-0 pt-1 pb-1 font-weight-bold">%s</td>
                                    <td class="p-0 pt-1 pb-1 align-top font-weight-bold">%d</td>
                                </tr>
                                ', $checked, $item['value'], $item['value'], $item['amount']);
								}
							}
							?>
                        </table>
                    </div>
                    <div class="repss-item ress-cate">
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th colspan="100%" class="p-0">
                                    <h4 class="font-weight-bold"><?php _e('Cấp bậc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></h4>
                                </th>
                            </tr>
                            </thead>
							<?php
							if( $product_categories ){
								foreach ($product_categories as $item){
									$checked = $_cate == $item['id'] ? 'checked' : '';
									echo sprintf('
                                <tr>
                                    <td class="p-0 pt-1 pb-1 align-top" width="30"><label class="switch mb-0 check-item">
                                                <input class="checkbox-status check" %s autocomplete="off" type="checkbox" value="%s">
                                                <span class="checkbox-slider fa"></span>
                                            </label></td>
                                    <td class="p-0 pt-1 pb-1 font-weight-bold">%s</td>
                                    <td class="p-0 pt-1 pb-1 align-top font-weight-bold">%d</td>
                                </tr>
                                ', $checked, $item['id'], $item['cate_title'], $item['amount']);
								}
							}
							?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="pro-search mb-3 d-flex align-items-center py-1">
                    <div>
                        <div class="d-flex">
                            <a href="javascript:;" class="view-mode" data-mode="0">
                                <i class="fas fa-list"></i>
                            </a>
                            <a href="javascript:;" class="view-mode active" data-mode="1">
                                <i class="fas fa-th-large"></i>
                            </a>
                        </div>
                    </div>
                    <div class="pro-search-box">
                        <input type="search" class="pro-search-keyword form-control" placeholder="<?php _e('Nhập một công việc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?>" value="<?php echo $_keyword; ?>">
                        <button id="pro-search-action" type="button" class="btn btn-default">
                            <i class="fas fa-search"></i>
                            <span class="ml-2"><?php _e('Tìm', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></span>
                        </button>
                    </div>
                </div>
                <div class="page-pro-list">
                    <div class="row page-pro-list-wrapper">
						<?php echo $html_pro_item; ?>
                    </div>
					<?php #echo $pagination; ?>
                    <div class="blockUI" style="display:none"></div>
                    <div class="blockUI blockOverlay" style="z-index: 1000; border: medium none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgb(0, 0, 0); opacity: 0.6; cursor: wait; position: absolute;"></div>
                    <div class="blockUI blockMsg blockElement" style="z-index: 1011; position: absolute; padding: 0px; margin: 0px; width: 30%; top: 0px; left: 322px; text-align: center; color: rgb(0, 0, 0); border: 3px solid rgb(170, 170, 170); background-color: rgb(255, 255, 255); cursor: wait;"><div class="ball-clip-rotate-multiple"> <div></div><div></div></div></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/html" id="tpl-pro-item-1">
        <div class=" col-md-6">
            <div class="pro-item pro-item-mode-1">
                <div class="pro-thumbnail">
                    <a href="{{ link }}" class="d-block" style="background-image: url({{ product_thumbnail }});"></a>
                </div>
                <div class="pro-content">
                    <a class="pro-title" href="{{ link }}">{{ product_title }}</a>
                    <div class="pro-properties">
                        <div class="d-md-flex justify-content-between">
                            <div class="pro-properties-items">
                                <div class="row">
                                    <# _.each(product_properties, function(item){ #>
                                    <div class="col-md-6">
                                        <span class="prop-icon"><img src="{{ item.icon }}" width="12"></span>
                                        <span class="prop-value">{{ item.value }}</span>
                                        <span class="prop-unit">{{ item.unit }}</span>
                                    </div>
                                    <# }) #>
                                </div>
                            </div>
                            <div class="pro-action-apply-wrapper">
                                <div class="d-flex align-items-center justify-content-end hg-100">
                                    <a href="{{ link_apply }}" class="pro-apply"><?php _e('Ứng tuyển', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/html" id="tpl-pro-item-0">
        <div class="col-12">
            <div class="pro-item pro-item-mode-0">
                <div class="d-md-flex">
                    <div class="pro-thumbnail">
                        <a href="{{ link }}" class="d-block" style="background-image: url({{ product_thumbnail }});"></a>
                    </div>
                    <div class="pro-content">
                        <div class="d-md-flex align-items-center justify-content-between pro-group-title-apply">
                            <a class="pro-title" href="{{ link }}">{{ product_title }}</a>
                            <div class="pro-action-apply-wrapper">
                                <div class="d-flex align-items-center justify-content-end hg-100">
                                    <a href="{{ link_apply }}" class="pro-apply"><?php _e('Ứng tuyển', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="pro-properties">
                            <div class="row">
                                <# _.each(product_properties, function(item){ #>
                                <div class="col-lg-3 col-md-12 col-sm-6">
                                    <span class="prop-icon"><img src="{{ item.icon }}" width="12"></span>
                                    <span class="prop-value">{{ item.value }}</span>
                                    <span class="prop-unit">{{ item.unit }}</span>
                                </div>
                                <# }) #>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
</div>
<?php
wp_localize_script('real-estate-products-script', 'REP_LIST', [
    'products' => array_values($products),
    'limit' => $limit,
    'keyword' => $_keyword,
    'address' => $_address,
    'cate' => $_cate,
]);
get_footer();