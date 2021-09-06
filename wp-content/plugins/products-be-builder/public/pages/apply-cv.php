<?php
global $rep_product;
$product_title = $rep_product['product_title'] ?? '';
$product_slug = $rep_product['product_slug'] ?? '';
$product_category = $rep_product['product_category'] ?? [];
if( $product_category ){
    $product_category = array_shift($product_category);
}
$cate_title = $product_category['cate_title'] ?? '';
$product_address_list = \DIVI\Includes\Core\GlobalSettings::publishGlobalSetting('product-address-list');
if( is_wp_error($product_address_list) || empty($product_address_list) ){
	$product_address_list = [];
}else{
	$product_address_list = explode(',', $product_address_list['value']);
	$product_address_list = array_map('trim', $product_address_list);
}

$logo = get_custom_logo();

do_action('wp_head');
wp_enqueue_script('real-estate-products-apply-cv');
?>

<div class="apply-cv-wrapper">
	<div class="container">
        <a href="<?php echo site_url('/job/' . $product_slug); ?>" class="backtojob"><?php _e('Xem lại JD', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) ?></a>
		<form class="form-apply-cv" method="post" action="<?php echo REAL_ESTATE_PRODUCTS_AJAX_URL; ?>">
            <input type="hidden" name="action" value="rep_handle_ajax">
            <input type="hidden" name="func" value="rep_post_cv">
			<table class="table table-borderless">
				<thead>
					<tr>
						<th colspan="2" class="text-right align-middle">
							<?php echo $logo; ?>
						</th>
						<th class="align-middle" height="100">
							<h1 class="text-uppercase mb-0"><?php _e('Ứng tuyển', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></h1>
						</th>
					</tr>
                    <tr>
                        <th colspan="100%" style="height: 100px;"></th>
                    </tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-uppercase font-weight-bold align-top text-black" rowspan="3"><?php _e('Công việc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td class="text-uppercase font-weight-bold text-right"><?php _e('Vị trí', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td class="text-uppercase font-weight-bold text-black">
                            <?php echo $product_title; ?>
                        </td>
					</tr>
					<tr>
						<td class="text-uppercase font-weight-bold text-right"><?php _e('Cấp bậc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td class="text-uppercase font-weight-bold text-black">
							<?php echo $cate_title; ?>
                        </td>
					</tr>
					<tr>
						<td class="text-uppercase font-weight-bold text-right"><?php _e('Chọn nơi<br>làm việc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td class="text-uppercase font-weight-bold">
                            <div class="row">
                            <?php
                            if( $product_address_list ){
                                foreach ($product_address_list as $item){
                                    echo '
                                    <div class="col-md-5">
                                        <label class="switch mb-0 check-item d-flex align-items-center">
                                            <input class="checkbox-status check" autocomplete="off" type="checkbox" value="' . $item . '">
                                            <span class="checkbox-slider fa"></span>
                                            <span class="pl-2 text">' . $item . '</span>
                                        </label>
                                    </div>
                                    ';
                                }
                            }
                            ?>
                            </div>
                        </td>
					</tr>
                    <tr>
                        <td colspan="100%"><hr></td>
                    </tr>
					<tr>
						<td class="text-uppercase font-weight-bold align-top text-black" rowspan="5"><?php _e('Giới thiệu', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td class="text-uppercase font-weight-bold text-right"><?php _e('Họ & tên', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td><input class="form-control" name="full_name" data-is-validation="true" required data-msg-required="<?php _e('Họ & tên không được để trống', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) ?>"></td>
					</tr>
					<tr>
						<td class="text-uppercase font-weight-bold text-right"><?php _e('Ngày sinh', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td class="">
                            <input class="form-control" name="elv[0][birth_of_day]" value="" data-is-validation="true" required data-msg-required="<?php _e('Ngày sinh không được để trống', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) ?>">
                        </td>
					</tr>
					<tr>
						<td class="text-uppercase font-weight-bold text-right"><?php _e('Email', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td class="">
                            <input type="email" class="form-control" name="elv[0][email]" value="" data-is-validation="true" required data-msg-required="<?php _e('Email không được để trống', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) ?>">
                        </td>
					</tr>
					<tr>
						<td class="text-uppercase font-weight-bold text-right"><?php _e('Số điện thoại', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td class="">
                            <input class="form-control" name="elv[0][phone_number]" value="" data-is-validation="true" required data-msg-required="<?php _e('Số điện thoại không được để trống', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) ?>">
                        </td>
					</tr>
					<tr>
						<td class="text-uppercase font-weight-bold text-right"><?php _e('Nơi ở hiện tại', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
						<td class="">
                            <input class="form-control" name="elv[0][address]" value="" data-is-validation="true" required data-msg-required="<?php _e('Nơi ở hiện tại không được để trống', REAL_ESTATE_PRODUCTS_LANG_DOMAIN) ?>">
                        </td>
					</tr>
                    <tr>
                        <td colspan="100%"><hr></td>
                    </tr>
                    <tr>
                        <td class="text-uppercase font-weight-bold align-top text-black elv-main" rowspan="3"><?php _e('Học vấn', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td class="text-uppercase font-weight-bold text-right"><?php _e('Tốt nghiệp', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td>
                            <input class="form-control" name="elv[0][graduate]" value="">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-uppercase font-weight-bold text-right"><?php _e('Trường', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td class="text-uppercase font-weight-bold">
                            <input class="form-control" name="elv[0][school]" value="">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-uppercase font-weight-bold text-right"><?php _e('Chuyên nghành', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td class="text-uppercase font-weight-bold">
                            <input class="form-control" name="elv[0][specialized]" value="">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="100%"><a class="btn-apply-action-add" data-rowspan="3" data-type="elv" href="#"><?php _e('Thêm học vấn', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></a></td>
                    </tr>
                    <tr>
                        <td colspan="100%"><hr></td>
                    </tr>
                    <tr>
                        <td class="text-uppercase font-weight-bold align-top text-black exp-main" rowspan="4"><?php _e('Kinh nghiệm<br>làm việc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td class="text-uppercase font-weight-bold text-right"><?php _e('Thời gian', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td>
                            <div class="row">
                                <div class="col-md-5">
                                    <input class="form-control" name="exp_skill[0][start_date]" value="">
                                </div>
                                <div class="col-md-2 text-center text-uppercase d-flex justify-content-center align-items-center">
									<?php _e('Đến', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?>
                                </div>
                                <div class="col-md-5">
                                    <input class="form-control" name="exp_skill[0][end_date]" value="">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-uppercase font-weight-bold text-right"><?php _e('Nơi làm việc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td class="text-uppercase font-weight-bold">
                            <input class="form-control" name="exp_skill[0][workplace]" value="">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-uppercase font-weight-bold text-right"><?php _e('Vị trí', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td class="text-uppercase font-weight-bold">
                            <input class="form-control" name="exp_skill[0][position]" value="">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-uppercase font-weight-bold text-right"><?php _e('Mức lương', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td class="text-uppercase font-weight-bold">
                            <div class="">
                                <input data-type="currency" class="form-control" name="exp_skill[0][salary]" value="">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="100%"><a class="btn-apply-action-add" data-rowspan="4" data-type="exp" href="#"><?php _e('Thêm kinh nghiệm làm việc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></a></td>
                    </tr>
                    <tr>
                        <td colspan="100%"><hr></td>
                    </tr>
                    <tr>
                        <td class="text-uppercase font-weight-bold align-top text-black" rowspan="3"><?php _e('CV', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td class="text-uppercase font-weight-bold text-right"><?php _e('Tải CV', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
                        <td>
                            <div class="d-md-flex align-items-center">
                                <label class="material-label-upload mr-3">
                                    <span class="icon"><?php _e('', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></span>
                                    <span class="text"><?php _e('Tải CV', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></span>
                                    <input type="file" name="file_cv">
                                </label>
                                <span class="font-italic"><?php _e('(Định dạng doc, docx, pdf tối đa 3M)', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></span>
                            </div>
                            <div class="file-cv-progress-bar d-none">
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="file-cv-name d-none mt-3"></div>
                        </td>
                    </tr>
				</tbody>
                <tfoot>
                    <tr>
                        <td colspan="100%"><hr></td>
                    </tr>
                    <tr>
                        <td colspan="100%" class="text-center" height="100">
                            <button type="submit" class="btn btn-primary"><?php  _e('Ứng tuyển', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></button>
                        </td>
                    </tr>
                </tfoot>
			</table>
		</form>
	</div>
    <script id="tpl-exp-item" type="text/html">
        <tr class="item-exp-{{ idx }}">
            <td colspan="100%">
                <div class="line"></div>
            </td>
        </tr>
        <tr class="item-exp-{{ idx }}">
            <td class="text-right" colspan="100%"><a class="btn-apply-action-remove" data-target=".item-exp-{{ idx }}" data-type="exp" href="#"><?php _e('Xoá', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></a></td>
        </tr>
        <tr class="item-exp-{{ idx }}">
            <td class="text-uppercase font-weight-bold text-right"><?php _e('Thời gian', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
            <td>
                <div class="row">
                    <div class="col-md-5">
                        <input class="form-control" name="exp_skill[{{ idx }}][start_date]" value="">
                    </div>
                    <div class="col-md-2 text-center text-uppercase d-flex justify-content-center align-items-center">
						<?php _e('Đến', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?>
                    </div>
                    <div class="col-md-5">
                        <input class="form-control" name="exp_skill[{{ idx }}][end_date]" value="">
                    </div>
                </div>
            </td>
        </tr>
        <tr class="item-exp-{{ idx }}">
            <td class="text-uppercase font-weight-bold text-right"><?php _e('Nơi làm việc', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
            <td class="text-uppercase font-weight-bold">
                <input class="form-control" name="exp_skill[{{ idx }}][workplace]" value="">
            </td>
        </tr>
        <tr class="item-exp-{{ idx }}">
            <td class="text-uppercase font-weight-bold text-right"><?php _e('Vị trí', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
            <td class="text-uppercase font-weight-bold">
                <input class="form-control" name="exp_skill[{{ idx }}][position]" value="">
            </td>
        </tr>
        <tr class="item-exp-{{ idx }}">
            <td class="text-uppercase font-weight-bold text-right"><?php _e('Mức lương', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
            <td class="text-uppercase font-weight-bold">
                <div class="">
                    <input data-type="currency" class="form-control" name="exp_skill[{{ idx }}][salary]" value="">
                </div>
            </td>
        </tr>
    </script>
    <script id="tpl-elv-item" type="text/html">
        <tr class="item-elv-{{ idx }}">
            <td colspan="100%">
                <div class="line"></div>
            </td>
        </tr>
        <tr class="item-elv-{{ idx }}">
            <td class="text-right" colspan="100%"><a class="btn-apply-action-remove" data-target=".item-elv-{{ idx }}" data-type="elv" href="#"><?php _e('Xoá', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></a></td>
        </tr>
        <tr class="item-elv-{{ idx }}">
            <td class="text-uppercase font-weight-bold text-right"><?php _e('Tốt nghiệp', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
            <td>
                <input class="form-control" name="elv[{{ idx }}][graduate]" value="">
            </td>
        </tr>
        <tr class="item-elv-{{ idx }}">
            <td class="text-uppercase font-weight-bold text-right"><?php _e('Trường', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
            <td class="text-uppercase font-weight-bold">
                <input class="form-control" name="elv[{{ idx }}][school]" value="">
            </td>
        </tr>
        <tr class="item-elv-{{ idx }}">
            <td class="text-uppercase font-weight-bold text-right"><?php _e('Chuyên nghành', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></td>
            <td class="text-uppercase font-weight-bold">
                <input class="form-control" name="elv[{{ idx }}][specialized]" value="">
            </td>
        </tr>
    </script>
</div>
<?php
do_action('wp_footer');