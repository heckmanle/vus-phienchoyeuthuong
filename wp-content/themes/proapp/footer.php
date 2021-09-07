<?php
if ( et_theme_builder_overrides_layout( ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ) || et_theme_builder_overrides_layout( ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ) ) {
    // Skip rendering anything as this partial is being buffered anyway.
    // In addition, avoids get_sidebar() issues since that uses
    // locate_template() with require_once.
    return;
}

/**
 * Fires after the main content, before the footer is output.
 *
 * @since 3.10
 */
do_action( 'et_after_main_content' );

if ( 'on' === et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

//if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>


        </div>

	</div> <!-- #page-container -->
    <footer>
        <img src="<?php echo THEME_URL; ?>/images/footer.svg">
    </footer>


	<?php wp_footer(); ?>
    <script>
        jQuery(function($){
            $('.et_pb_accordion .et_pb_toggle_open').addClass('et_pb_toggle_close').removeClass('et_pb_toggle_open');

            $('.et_pb_accordion .et_pb_toggle').click(function() {
                $this = $(this);
                setTimeout(function(){
                    $this.closest('.et_pb_accordion').removeClass('et_pb_accordion_toggling');
                },700);
            });
        });
    </script>
</body>
</html>