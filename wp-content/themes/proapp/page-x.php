<?php

/**
 * Template Name: Thông tin hỗ trợ
 */
$currentUser = \DIVI\Includes\Core\User::get_current();
if( !$currentUser || is_wp_error($currentUser) ){
    wp_redirect(site_url('/'));
    die;
}
$currentUserNote = $currentUser['note'] ?? '';
$products = \DIVI\Includes\Core\Product::products();
if( is_wp_error($products) ){
    $products = [];
}
get_header();
global $currentUser;
$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content" class="ttht-layout">

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! $is_page_builder_used ) : ?>

					<h1 class="entry-title main_title"><?php the_title(); ?></h1>
				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$alttext = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
					$thumbnail = get_thumbnail( $width, $height, $classtext, $alttext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $alttext, $width, $height );
				?>

				<?php endif; ?>

					<div class="entry-content">
					<?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

<?php endif; ?>
<div class="ttht-wrapper">
    <div class="container">
        <form id="form-ttht" action="<?php echo admin_url('admin-ajax.php'); ?>">
            <input type="hidden" name="action" value="handle_ajax">
            <input type="hidden" name="func" value="ttht_booking">
            <div class="table-responsive table-res-style-main mb-5">
                <?php
                if ( !wp_is_mobile() ) {

                ?>
                <table class="table table-style-main">
                    <thead>
                    <tr>
                        <th class="text-center th-col-1" style="font-size: 8px;">STT</th>
                        <th class="text-center th-col-2">TIÊU CHÍ</th>
                        <th class="text-center th-col-2">ĐỊNH NGHĨA</th>
                        <th class="text-center th-col-3">GIẢI THÍCH</th>
                        <th class="text-center th-col-4">SỐ LƯỢNG</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if( $products ){
                        $stt = 0;
                        $strSL = "";
                        $product_excerpt = "";
                        foreach ($products as $item){
                            $stt++;
                            $address = !empty($item['address']) && !is_null($item['address']) && $item['address'] != 'NULL' ? $item['address'] : '';

                            if($item['product_slug'] == "co-nguoi-benh") {
                                $product_excerpt = $item['product_excerpt'] . "<br/><textarea name='tbl_text_conguoibenh[{$item['id']}]' class='tbl_col_cngb'></textarea>";
                            } else {
                                $product_excerpt = $item['product_excerpt'];
                            }

                            if($item['product_seo_description'] == "N") {
                                $strSL = "<input type='text' value='0' name='tbl_sl[{$item['id']}]' class='tbl_col_sl text-center'>";
                            } else {
                                $strSL = $item['product_seo_description'];
                            }

                            echo sprintf('
                        <tr>
                            <td class="text-center">%d.</td>
                            <td class="text-uppercase text-center">%s</td>
                            <td class="text-center">%s</td>
                            <td class="text-center">%s</td>
                            <td class="text-center">%s</td>
                        </tr>
                        ', $stt, $item['product_title'], $product_excerpt , $address, $strSL);
                        }
                    }
                    ?>

                    </tbody>
                </table>
                <?php
                } else {
                 //   echo "Mobile..";
                    ?>
                <table class="table table-style-main">
                    <thead>
                    <tr>
                        <th class="text-center th-col-1" style="font-size: 8px;">STT</th>
                        <th class="text-center th-col-2">TIÊU CHÍ</th>
                        <th class="text-center th-col-4">SỐ LƯỢNG</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if( $products ){
                        $stt = 1;

                        $tbl_r = 0;
                        $strSL = "";
                        $product_excerpt = "";
                        foreach ($products as $item){

                            $address = !empty($item['address']) && !is_null($item['address']) && $item['address'] != 'NULL' ? $item['address'] : '';

                            if($item['product_slug'] == "co-nguoi-benh") {
                                $product_excerpt = $item['product_excerpt'] . "<br/><textarea name='tbl_text_conguoibenh[{$item['id']}]' class='tbl_col_cngb'></textarea>";
                            } else {
                                $product_excerpt = $item['product_excerpt'];
                            }

                            if($item['product_seo_description'] == "N") {
                                $strSL = "<span style='font-size: 13px;line-height: 17px !important;padding-top: 2px !important;display: flex;padding-bottom: 6px !important;'>{$address}</span><input type='text' value='0' name='tbl_sl[{$item['id']}]' class='tbl_col_sl text-center'>";
                            } else {
                                $strSL = $item['product_seo_description'];
                            }

                            $lbl_stt = "";
                            $product_title = "";
                            $stylex = "";
                            if($tbl_r != 0 && $tbl_r != 2 && $tbl_r != 4 && $tbl_r != 6 && $tbl_r != 8) {
                                $stt++;
                            } else {
                                $lbl_stt = $stt;
                                $product_title = $item['product_title'];
                                $stylex = "border-bottom: 1px solid #ddd;width: 100%;padding: 10px;text-transform: uppercase;";
                            }

                            //$item['product_title'], $product_excerpt , $address, $strSL);
                            ?>
                            <tr>
                                <td class="text-center" style="vertical-align:top"><?=$lbl_stt;?></td>
                                <td class="text-uppercase text-center" style="padding: 0;">
                                    <p style="<?=$stylex;?>"><?=$product_title;?></p>
                                    <p style="width: 100%;padding: 10px;text-transform: initial;"><?=$product_excerpt;?></p>
                                </td>
                                <td class="text-center" style="width: 113px;text-align: center !important;"><?=$strSL;?></td>
                            </tr>
                    <?php


                            $tbl_r++;
                        }
                    }
                    ?>

                    </tbody>
                </table>
                    <?php
                }

                ?>
            </div>
            <div class="form-group">
                <label class="switch switch-circle mb-0 check-item d-flex align-items-center">
                    <span class="mr-2"><?php _e('Bạn không thuộc tiêu chí trên', REAL_ESTATE_PRODUCTS_LANG_DOMAIN); ?></span>
                    <input class="checkbox-status check tick-is-empty" name="tick" autocomplete="off" type="checkbox" value="1">
                    <span class="checkbox-slider fa"></span>
                </label>
            </div>
            <div class="form-group">
                <label><?php _e('Chia sẻ thêm câu chuyện của bạn'); ?>: </label>
                <textarea class="form-control" name="store"></textarea>
            </div>
            <?php if($currentUser): ?>
                <?php //if( $currentUserNote != 'SUBMITTED'): ?>
                <div class="text-right">
                    <button class="btn btn-primary btn-submit-ttht" type="submit">
                        <?php _e('Cập nhật'); ?>
                    </button>
                </div>
                <?php //endif; ?>
            <?php else: ?>
            <div><?php _e('Bạn chưa được xác thực đăng nhập. Vui lòng') ?> <a href="<?php echo site_url('/authentication/') ?>" class="redirect-login btn btn-primary"><?php _e('Đăng nhập'); ?></a></div>
            <?php endif; ?>
        </form>
    </div>
</div>
    <div id="modal-step-1" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-close-inside modal-dialog-centered ">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-center mb-4"><?php _e('Bạn đang cần hỗ trợ'); ?>:</p>
                    <div class="form-group">
                        <label><?php _e('Combo thực phẩm'); ?></label>
                        <textarea id="step-1-note-0" class="form-control"><?php _e("Thông tin nhận Combo thực phẩm:\nHọ tên: \nĐịa chỉ nhận hàng: "); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><?php _e('TIỀN MẶT'); ?></label>
                        <textarea id="step-1-note-1" class="form-control"><?php _e("Vui lòng điền đầy đủ thông tin\nChủ tài khoản: \nSố tài khoản: \nNgân hàng: "); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><?php _e('KHÁC'); ?></label>
                        <textarea id="step-1-note-2" class="form-control" placeholder="<?php _e('[Vui lòng ghi rõ]'); ?>"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default btn-primary modal-btn-action "><?php _e('Hoàn tất') ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-step-2" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-close-inside modal-dialog-centered ">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <h3 class="title text-center"><?php _e('XÁC NHẬN THÔNG TIN') ?></h3>
                    <div class="step2-info"></div>
                    <div class="notification d-none mt-4"></div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-default btn-primary modal-btn-action "><?php _e('Xác nhận') ?></button>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-step-3" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-close-inside modal-dialog-centered ">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body text-center">
                    <div><?php _e('Cảm ơn bạn đã chia sẻ thông tin.') ?></div>
                    <div><?php _e('VUS sẽ liên hệ với bạn ngay lập tức để hỗ trợ.') ?></div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-is-empty" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-close-inside modal-dialog-centered ">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                    <h4 class="mb-0 error"><?php _e('Nhập số lượng tiêu chí chọn') ?></h4>
                </div>
            </div>
        </div>
    </div>
<script>
    const TTHT_IMAGE_LOADING = {
        message: '<div class="ball-clip-rotate-multiple"> <div></div><div></div></div>',
    };
    jQuery(function ($){
        function _nl2br (str) {
            if (typeof str === 'undefined' || str === null) {
                return '';
            }
            let breakTag = '<br>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        }
        const _getTextMessageByXHR = (xhr, errThrow) => {
            let textError = '';
            if( xhr.hasOwnProperty('responseJSON') && xhr.responseJSON.hasOwnProperty( 'message' ) && (xhr.responseJSON.message != '') ){
                textError = xhr.responseJSON.message;
            }else if( errThrow != '' ){
                textError = xhr.getResponseHeader('xhr-message') || errThrow;
                try{
                    textError = JSON.parse(textError);
                } catch(e){
                    textError = 'An error occurred, please try again';
                }
            }else{
                textError = 'An error occurred, please try again';
            }
            return textError;
        }
        const _renderAlert = (message, type = 'error') => {
            let classes_alert = '';
            switch (type){
                case 'success':
                    classes_alert = 'alert-success';
                    break;
                case 'warning':
                    classes_alert = 'alert-warning';
                    break;
                case 'error':
                default:
                    classes_alert = 'alert-danger';
                    break;
            }
            return '<div class="alert ' + classes_alert + ' alert-dismissible" role="alert">\n' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>\n' +
                '<div class="message-response notification">' +
                message +
                '</div>\n' +
                '</div>';
        }
        $('.btn-submit-ttht').click(function (ev){
            ev.preventDefault();
            let $this = $(this), $form = $this.closest('form'),
                $tbl_col_sl = $('.tbl_col_sl', $form), tick_is_empty = $('.tick-is-empty', $form).prop('checked'),
                point = 0;
            $tbl_col_sl.each(function (){
                let val = $(this).val();
                val = parseFloat(val);
                val = isNaN(val) ? 0 : val;
                point += val;
            });
            if( point === 0 && !tick_is_empty ){
                $('#modal-is-empty').modal();
            }else{
                $('#modal-step-1').modal();
            }
        });
        $('#modal-step-1 .modal-btn-action').click(function(ev){
            ev.preventDefault();
            $('#modal-step-1').modal('hide');
            let info = '';
            info = $('#step-1-note-1').val();
            info = info.replace("Vui lòng điền đầy đủ thông tin\n", '');
            info += "\n";
            info += $('#step-1-note-2').val();
            info += $('#step-1-note-0').val();
            info = _nl2br(info);
            $('#modal-step-2 .step2-info').html(info);
            $('#modal-step-2 .notification').html('').addClass('d-none');
            $('#modal-step-2').modal();
        });
        $('#modal-step-2 .modal-btn-action').click(function(ev) {
            ev.preventDefault();
            let $form = $('form#form-ttht');
            let options = {
                dataType: 'json',
                beforeSubmit: function(serialize, form, option) {
                    serialize.push({name: 'note_1', type: 'textarea', value: $('#step-1-note-1').val()});
                    serialize.push({name: 'note_2', type: 'textarea', value: $('#step-1-note-2').val()});
                    serialize.push({name: 'note_0', type: 'textarea', value: $('#step-1-note-0').val()});

                },
                beforeSend: function () {
                    $('body').block(TTHT_IMAGE_LOADING);
                },
                success: function(response, status, xhr){
                    $('#modal-step-2').modal('hide');
                    $('#modal-step-3').modal();
                },
                error: function(xhr, status, errThrow){
                    let textError = _getTextMessageByXHR(xhr, errThrow);
                    textError = _renderAlert(textError);
                    $('#modal-step-2 .notification').html(textError).removeClass('d-none');
                },
                complete: function(xhr, status){
                    $('body').unblock(TTHT_IMAGE_LOADING);
                    return;
                }
            };
            $form.ajaxSubmit(options);
        });
    });
</script>
</div> <!-- #main-content -->

<?php

get_footer();
