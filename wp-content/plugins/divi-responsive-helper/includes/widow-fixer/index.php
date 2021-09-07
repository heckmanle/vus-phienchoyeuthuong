<?php
add_action('wp_footer', 'pac_drh_init_widow');
add_action('in_admin_footer', 'pac_drh_init_widow');
function pac_drh_init_widow() {
    if (is_admin()) {
        if (function_exists('get_current_screen')) {
            if ( ! in_array(get_current_screen(), ['edit', 'toplevel_page_et_divi_options', 'divi_page_et_theme_builder'], true)) {
                return;
            }
        }
    }
    $exclude = true;
    if ( ! is_admin()) {
        $exclude = false;
        $exclude_pages = ! empty(et_get_option('pac_drh_pages_widow_fixer')) ? et_get_option('pac_drh_pages_widow_fixer') : [];
        if ( ! in_array(get_the_ID(), $exclude_pages)) {
            $exclude = true;
        }
    }
    // check if Widow Fixer is enabled
    if ('on' == et_get_option('pac_drh_enable_widow_fixer') && $exclude) { ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                <?php if('on' === et_get_option('pac_drh_widow_fixer_headings')) : ?>
                setTimeout(function () {
                    jQuery("h1,h2,h3,h4,h5,h6").dwfWidow(<?php echo esc_js(et_get_option('pac_drh_widow_fixer_heading_select') - 1); ?>);
                }, 30);
                <?php endif; ?>
                <?php if('on' === et_get_option('pac_drh_enable_paragraph_widow_fixer')) : ?>
                // exclude woo-commerce add product and edit product pages
                if (!(jQuery("body.wp-admin.post-new-php.post-type-product").length) || !(jQuery("body.wp-admin.post-php.post-type-product").length)) {
                    setTimeout(function () {
                        jQuery("p").dwfWidow(<?php echo esc_js(et_get_option('pac_drh_widow_fixer_paragraph_select') - 1); ?>);
                    }, 30)
                }
                <?php endif; ?>
            });
        </script>
        <style type="text/css">
            h1, h2, h3, h4, h5, h6 {
                overflow-wrap: normal;
            }
        </style>
    <?php }
}

?>
