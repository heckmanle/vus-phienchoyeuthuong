<?php

/**
 * Template Name: Thông tin hỗ trợ
 */

$products = \DIVI\Includes\Core\Product::products();
if( is_wp_error($products) ){
    $products = [];
}
get_header();

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
        <form>
            <div class="table-responsive table-res-style-main mb-5">
                <table class="table table-style-main">
                    <thead>
                    <tr>
                        <th class="text-center th-col-1">STT</th>
                        <th class="text-center th-col-2">TIÊU CHÍ</th>
                        <th class="text-center th-col-2">ĐỊNH NGHĨA</th>
                        <th class="text-center th-col-3">TIÊU CHÍ</th>
                        <th class="text-center th-col-4">TICK ĐỂ CHỌN</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if( $products ){
                        $stt = 0;
                        foreach ($products as $item){
                            $stt++;
                            $address = !empty($item['address']) && !is_null($item['address']) && $item['address'] != 'NULL' ? $item['address'] : '';
                            echo sprintf('
                        <tr>
                            <td class="text-center">%d</td>
                            <td class="text-uppercase text-center">%s</td>
                            <td class="text-center">%s</td>
                            <td class="text-center">%s</td>
                            <td class="text-center">
                                <label class="switch switch-circle mb-0 check-item d-flex align-items-center justify-content-center">
                                    <input class="checkbox-status check" autocomplete="off" type="checkbox" value="%s">
                                    <span class="checkbox-slider fa"></span>
                                </label>
                            </td>
                        </tr>
                        ', $stt, $item['product_title'], $item['product_excerpt'], $address, $item['id']);
                        }
                    }
                    ?>

                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <label><?php _e('Chia sẻ thêm câu chuyện của bạn'); ?>: </label>
                <textarea class="form-control" name="store"></textarea>
            </div>
            <div class="text-right">
                <button class="btn btn-primary btn-submit-ttht<?php if(is_user_logged_in()) {echo ' btn-submit-not-logged';} ?>" type="submit">
                    <?php _e('Cập nhật'); ?>
                </button>
            </div>
        </form>
    </div>
</div>
<?php if( !is_user_logged_in() ): ?>
    <div id="modal-confirm-logged" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-close-inside modal-dialog-centered ">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                    <h4 class="modal-title" data-title="Bạn có chắc chắn xoá kinh nghiệm làm việc?">Bạn chưa được xác thực đăng nhập.</h4>
                </div>
                <div class="modal-body text-center">
                    <a href="<?php echo site_url('/authentication/') ?>" class="redirect-login btn btn-primary"><?php _e('Đăng nhập'); ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
    <div id="modal-step-1" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-close-inside modal-dialog-centered ">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-center"><?php _e('Bạn đang cần hỗ trợ'); ?>:</p>
                    <div class="form-group">
                        <label><?php _e('TIỀN MẶT'); ?></label>
                        <textarea id="step-1-note-1" class="form-control"><?php echo _e("Vui lòng điền đầy đủ thông tin\nChủ tài khoản:\nSố tài khoản:\nNgân hàng:"); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><?php _e('KHÁC'); ?></label>
                        <textarea id="step-1-note-2" class="form-control" placeholder="<?php _e('[Vui lòng ghi rõ]'); ?>"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-primary btn-yes font-weight-500 modal-btn-action "><?php _e('Hoàn tất') ?></button>
                </div>
            </div>
        </div>
    </div>
<script>
    jQuery(function ($){
        $('.btn-submit-ttht').click(function (ev){
            ev.preventDefault();
            let $this = $(this), $form = $this.closest('form');
            if( $this.hasClass('btn-submit-not-logged') ){
                $('#modal-confirm-logged').modal();
            }else{
                $('#modal-step-1').modal();
            }
        });
    });
</script>
</div> <!-- #main-content -->

<?php

get_footer();
