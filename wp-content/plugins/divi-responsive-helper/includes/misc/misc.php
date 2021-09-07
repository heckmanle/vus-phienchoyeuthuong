<?php
//  Close Menu Icon Phone
if ( ! function_exists('pac_drh_change_phone_menu_icon')) {
    function pac_drh_change_phone_menu_icon() {
        $pac_drh_enable_open_mobile_icon = et_get_option('pac_drh_enable_open_mobile_icon');
        $pac_drh_menu_to_mobile_menu = esc_html(et_get_option('pac_drh_menu_to_mobile_menu'));
        if ('on' === $pac_drh_enable_open_mobile_icon || ! empty($pac_drh_menu_to_mobile_menu)) {
            echo PHP_EOL;
            echo "<style>";
            if ('on' === $pac_drh_enable_open_mobile_icon) {
                $processed_icon = et_pb_process_font_icon('M');
                echo ".mobile_nav.opened .mobile_menu_bar:before {content: '".esc_attr($processed_icon)."';}";
            }
            if ( ! empty($pac_drh_menu_to_mobile_menu)) {
                echo "@media only screen and (max-width:".esc_attr($pac_drh_menu_to_mobile_menu)."){#et_mobile_nav_menu{display:block!important;margin-bottom:10px;#margin-top:5px}#top-menu-nav{display:none!important}.et_pb_menu__menu{display:none!important}.et_mobile_nav_menu{display:block!important}.et_pb_fullwidth_menu .et_mobile_nav_menu,.et_pb_menu .et_mobile_nav_menu{float:none;margin:0 6px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center}.et_pb_fullwidth_menu--style-left_aligned .et_pb_menu__wrap,.et_pb_menu--style-left_aligned .et_pb_menu__wrap{-webkit-box-pack:end;-ms-flex-pack:end;justify-content:flex-end}ul.et_mobile_menu{list-style-type:none!important;padding:0!important}}";
            }
            echo "</style>";
            echo PHP_EOL;
        }
    }

    add_action('wp_print_scripts', 'pac_drh_change_phone_menu_icon');
}
// Hide Divi Responsive Tabs
if ( ! function_exists('pac_drh_hide_responsive_tabs')) {
    function pac_drh_hide_responsive_tabs() {
        echo PHP_EOL;
        echo "<style>";
        echo ".et-bfb-responsive-view-controls,.et-fb-responsive-view-controls{display:none}.et-db #et-boc .et-l #et-fb-app-frame{position:absolute!important;top:3%!important;padding-top:0!important}";        echo "</style>";
        echo PHP_EOL;
    }
}
if ('on' === et_get_option('pac_drh_enable_hide_responsive_view')) {
    add_action('admin_footer', 'pac_drh_hide_responsive_tabs');
    add_action('wp_footer', 'pac_drh_hide_responsive_tabs');
}
// Mix Css
if ( ! function_exists('pac_drh_inline_css')) {
    function pac_drh_inline_css() {
        $enable_prevent_horizontal_scroll = trim(et_get_option('pac_drh_enable_prevent_horizontal_scroll'));
        $enable_remove_animation = trim(et_get_option('pac_drh_enable_remove_animation'));
        $pac_drh_back_top_visibility = trim(et_get_option('pac_drh_back_top_visibility'));
        if ('on' === $enable_prevent_horizontal_scroll || 'on' === $enable_remove_animation || 'off' !== $pac_drh_back_top_visibility) {
            echo PHP_EOL;
            echo "<style>";
            // Horizontal Scroll
            if ('on' === $enable_prevent_horizontal_scroll) {
                echo "#page-container{overflow-x: hidden;}";
            }
            // Animations
            if ('on' === $enable_remove_animation) {
                echo "@media (min-width:300px) and (max-width:767px){.et_animated{opacity:1!important}.et_pb_section *{-o-transition-property:none!important;-moz-transition-property:none!important;-webkit-transition-property:none!important;transition-property:none!important;-o-transform:none!important;-moz-transform:none!important;-ms-transform:none!important;-webkit-transform:none!important;transform:none!important;-webkit-animation:none!important;-moz-animation:none!important;-o-animation:none!important;animation:none!important}}";
            }
            // Back To Top Button Visibility
            $visibility_on_desktop = "@media (min-width: 980px) {.et_pb_scroll_top {display: none !important;}}";
            $visibility_on_tablet = "@media (min-width: 768px) and (max-width: 980px){.et_pb_scroll_top {display: none !important;}}";
            $visibility_on_phone = "@media (max-width: 767px) and (min-width: 0px) {.et_pb_scroll_top {display: none !important;}}";
            if ('desktop' === $pac_drh_back_top_visibility) {
                echo esc_html($visibility_on_tablet);
                echo esc_html($visibility_on_phone);
            } elseif ('tablet' === $pac_drh_back_top_visibility) {
                echo esc_html($visibility_on_desktop);
                echo esc_html($visibility_on_phone);
            } elseif ('phone' === $pac_drh_back_top_visibility) {
                echo esc_html($visibility_on_desktop);
                echo esc_html($visibility_on_tablet);
            } elseif ('desktop_tablet' === $pac_drh_back_top_visibility) {
                echo esc_html($visibility_on_phone);
            } elseif ('tablet_phone' === $pac_drh_back_top_visibility) {
                echo esc_html($visibility_on_desktop);
            } elseif ('desktop_phone' === $pac_drh_back_top_visibility) {
                echo esc_html($visibility_on_tablet);
            }
            echo "</style>";
            echo PHP_EOL;
        }
    }
}
add_action('admin_print_styles', 'pac_drh_inline_css');
add_action('wp_print_styles', 'pac_drh_inline_css');
// Mobile Custom Logo
if ( ! function_exists('pac_drh_mobile_logo')) {
    function pac_drh_mobile_logo() {
        $pac_drh_mobile_logo = et_get_option('pac_drh_mobile_logo');
        echo PHP_EOL;
        echo "<style>";
        echo "#logo {display: none;} @media (min-width:300px) and (max-width:767px){.pac_drh_desktop_logo{display:none!important}.pac_drh_mobile_logo{display:inline-block!important}}@media (min-width:768px) and (max-width:980px){.pac_drh_desktop_logo{display:none!important}.pac_drh_mobile_logo{display:inline-block!important}}@media (min-width:981px){.pac_drh_desktop_logo{display:inline-block!important}.pac_drh_mobile_logo{display:none!important}}";
        echo "</style>";
        echo PHP_EOL;
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var pac_drh_logo_selector = '#logo';
                jQuery(pac_drh_logo_selector).css('display', 'block');
                if (jQuery(pac_drh_logo_selector).length > 0) {
                    jQuery(pac_drh_logo_selector).addClass('pac_drh_desktop_logo');
                    if (jQuery('.pac_drh_desktop_logo').length > 0) {
                        jQuery('.logo_container a').append('<img src="<?php esc_attr_e($pac_drh_mobile_logo); ?>" alt="<?php esc_attr_e(get_bloginfo('name')); ?>" id="logo" class="pac_drh_mobile_logo" data-height-percentage="<?php esc_attr_e(et_get_option('logo_height',
                            '54'));?>" />');
                    }
                }

            });
        </script>
        <?php
        echo PHP_EOL;
    }
}
if ('' !== et_get_option('pac_drh_mobile_logo')) {
    add_action('wp_footer', 'pac_drh_mobile_logo');
}
// Mobile Header Bar Color
if ( ! function_exists('pac_drh_mobile_header_color')) {
    function pac_drh_mobile_header_color() {
        echo PHP_EOL;
        echo sprintf('<meta name="theme-color" content="%s" />', esc_attr(et_get_option('pac_drh_mobile_header_color')));
        echo PHP_EOL;
    }
}
if ('' !== et_get_option('pac_drh_mobile_header_color')) {
    add_action('wp_head', 'pac_drh_mobile_header_color');
}
// Mobile Pinch Zooming
if ( ! function_exists('pac_drh_mobile_pinch_zoom')) {
    function pac_drh_mobile_pinch_zoom() {
        echo PHP_EOL;
        echo '<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=0.1, maximum-scale=10.0">';
        echo PHP_EOL;
    }
}
if ('on' === et_get_option('pac_drh_enable_mobile_pinch_zoom')) {
    remove_action('wp_head', 'et_add_viewport_meta');
    add_action('wp_head', 'pac_drh_mobile_pinch_zoom');
}