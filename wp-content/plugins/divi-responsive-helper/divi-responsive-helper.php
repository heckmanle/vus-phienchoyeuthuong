<?php
/*
Plugin Name: Divi Responsive Helper
Plugin URI:  https://www.peeayecreative.com/product/divi-responsive-helper
Description: Quickly make Divi responsive and easily adjust design settings on all devices in the Divi Builder!
Version:     2.0.4
Author:      Pee-Aye Creative
Author URI:  https://www.peeayecreative.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Divi Responsive Helper is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Divi Responsive Helper is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Divi Responsive Helper. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

-----------------------------------------------------------
*/
if ( ! defined('ABSPATH')) {
    exit;
}
define('PAC_DRH_VERSION', '2.0.4');
define('PAC_DRH_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
define('PAC_DRH_URL', untrailingslashit(plugin_dir_url(__FILE__)));
if ( ! function_exists('get_plugins')) {
    require_once ABSPATH.'wp-admin/includes/plugin.php';
}
if (file_exists(PAC_DRH_PATH.'/includes/helpers/helpers.php')) {
    require_once(PAC_DRH_PATH.'/includes/helpers/helpers.php');
}
$pac_drh_update_file = PAC_DRH_PATH.'/plugin-update-checker/plugin-update-checker.php';
if (file_exists($pac_drh_update_file)) {
    require $pac_drh_update_file;
    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker('https://www.peeayecreative.com/update-server/?action=get_metadata&slug=divi-responsive-helper', __FILE__, 'Divi-responsive-helper');
}
if ( ! function_exists('pac_drh_check_is_divi_theme')) {
    function pac_drh_check_is_divi_theme() {
        $active_theme = wp_get_theme()->get('Name');
        $parent_theme = wp_get_theme()->get('Template');
        $divi_builder = is_plugin_active('divi-builder/divi-builder.php');
        $divi_ghoster = is_plugin_active('divi-ghoster/divi-ghoster.php');
        if ('divi' == strtolower($active_theme) || 'divi' == strtolower($parent_theme) || 'extra' == strtolower($active_theme) || 'extra' == strtolower($parent_theme) || $divi_builder || $divi_ghoster) {
            return true;
        }
        deactivate_plugins(__FILE__);
        wp_die('<p>'.sprintf(esc_attr__('The Divi Responsive Helper plugin only works with Divi Theme, Extra Theme or Divi Builder plugin only. Your current active theme is %2$s %1$s %3$s. The plugin will be deactivated and you may return to your WordPress dashboard.',
                'Divi'), esc_html($active_theme), '<b>', '</b>').'</p> <a href="'.esc_url(admin_url('index.php')).'">'.esc_attr__('Go Back', 'Divi').'</a>');
    }

    add_action('init', 'pac_drh_check_is_divi_theme');
    register_activation_hook(__FILE__, 'pac_drh_check_is_divi_theme');
}
if ( ! function_exists('pac_drh_plugin_action_links')) {
    function pac_drh_plugin_action_links($actions) {
        $actions[] = sprintf('<a href="%s">'.esc_html__('Settings', 'Divi').' </a>.', esc_url(admin_url('admin.php?page=et_divi_options#wrap-drh')));

        return $actions;
    }
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'pac_drh_plugin_action_links');
if ( ! function_exists('pac_drh_if_user_logged_in')) {
    function pac_drh_if_user_logged_in() {
        function pac_drh_enqueue_scripts() {
            wp_enqueue_style('pac_drh_public', plugins_url('/includes/public/css/style.css', __FILE__), false, PAC_DRH_VERSION);
            if (is_user_logged_in() === true) {
                $enable_auto_responsive = et_get_option('pac_drh_enable_auto_responsive');
                if ('on' === $enable_auto_responsive) {
                    wp_enqueue_script('pac_drh_vb_responsive', plugins_url('/includes/admin/js/vb_responsive.js', __FILE__), [], PAC_DRH_VERSION, true);
                    wp_localize_script('pac_drh_vb_responsive', 'pac_drh_vb_obj', ['responsiveTabs' => $enable_auto_responsive]);
                }
                if ('on' === et_get_option('pac_drh_enable_presets') || 'on' === et_get_option('pac_drh_enable_custom_preview')) {
                    wp_enqueue_script('pac_drh_vb_responsive_buttons', plugins_url('/includes/admin/js/responsive.js', __FILE__), ['jquery'], PAC_DRH_VERSION, true);
                    $pac_drh_custom_presets = [
                        'pac_drh_phone_preset_one' => et_get_option('pac_drh_phone_preset_one'),
                        'pac_drh_phone_preset_two' => et_get_option('pac_drh_phone_preset_two'),
                        'pac_drh_phone_preset_three' => et_get_option('pac_drh_phone_preset_three'),
                        'pac_drh_tablet_preset_one' => et_get_option('pac_drh_tablet_preset_one'),
                        'pac_drh_tablet_preset_two' => et_get_option('pac_drh_tablet_preset_two'),
                        'pac_drh_tablet_preset_three' => et_get_option('pac_drh_tablet_preset_three'),
                        'pac_drh_desktop_preset_one' => et_get_option('pac_drh_desktop_preset_one'),
                        'pac_drh_desktop_preset_two' => et_get_option('pac_drh_desktop_preset_two'),
                        'pac_drh_desktop_preset_three' => et_get_option('pac_drh_desktop_preset_three'),
                    ];
                    wp_localize_script('pac_drh_vb_responsive_buttons', 'pac_drh_custom_presets', $pac_drh_custom_presets);
                    wp_enqueue_style('pac_drh_responsive_settings_vb_css', plugins_url('/includes/admin/css/style.css', __FILE__));
                }
            }
            if ('on' === et_get_option('pac_drh_enable_widow_fixer')) {
                wp_enqueue_script('pac_drh_dwf_widow', plugins_url('/includes/admin/js/pac_drh_dwf_widow.js', __FILE__), [], PAC_DRH_VERSION, true);
            }
            if ('on' === et_get_option('pac_drh_enable_col_stacking')) {
                wp_enqueue_style('pac_drh_stacking', plugins_url("/includes/public/css/stacking.css", __FILE__), [], PAC_DRH_VERSION);
            }
            if ('on' === et_get_option('pac_drh_enable_number_of_columns')) {
                wp_enqueue_style('pac_drh_col_numbering_css', plugins_url("/includes/public/css/column-numbering.css", __FILE__), [], PAC_DRH_VERSION);
            }
            if ('on' === et_get_option('pac_drh_enable_blog_number_of_columns')) {
                wp_enqueue_style('pac_drh_et_pb_blog_css', plugins_url("/includes/public/css/et_pb_blog.css", __FILE__), [], PAC_DRH_VERSION);
            }
            if ('on' === et_get_option('pac_drh_enable_portfolio_number_of_columns')) {
                wp_enqueue_style('pac_drh_et_pb_portfolio_css', plugins_url("/includes/public/css/et_pb_portfolio.css", __FILE__), [], PAC_DRH_VERSION);
            }
            if ('on' === et_get_option('pac_drh_enable_gallery_number_of_columns')) {
                wp_enqueue_style('pac_drh_et_pb_gallery_css', plugins_url("/includes/public/css/et_pb_gallery.css", __FILE__), [], PAC_DRH_VERSION);
            }
            if ('on' === et_get_option('pac_drh_enable_shop_number_of_columns')) {
                wp_enqueue_style('pac_drh_et_pb_shop_css', plugins_url("/includes/public/css/et_pb_shop.css", __FILE__), [], PAC_DRH_VERSION);
            }
            if ('on' === et_get_option('pac_drh_enable_mobile_parallax')) {
                wp_enqueue_script('pac_drh_mobile_parallax', plugins_url("/includes/misc/mobile-parallax.js", __FILE__), [], PAC_DRH_VERSION, true);
            }
        }

        add_action('wp_enqueue_scripts', 'pac_drh_enqueue_scripts');
    }
}
add_action('init', 'pac_drh_if_user_logged_in');
// Admin Scripts
if ( ! function_exists('pac_drh_admin_enqueue_scripts')) {
    function pac_drh_admin_enqueue_scripts($hook) {
        $active_theme = wp_get_theme()->get('Name');
        $divi_ghoster = is_plugin_active('divi-ghoster/divi-ghoster.php');
        $allowed_pages = [
            'edit.php',
            'toplevel_page_et_divi_options',
            'divi_page_et_theme_builder',
            'extra_page_et_theme_builder',
            'toplevel_page_et_extra_options',
        ];
        if ($divi_ghoster) {
            $divi_ghoster_options = get_option('agsdg_settings');
            $divi_ghoster_theme_slug = isset($divi_ghoster_options['theme_slug']) ? $divi_ghoster_options['theme_slug'] : '';
            $allowed_pages = array_merge($allowed_pages, ["toplevel_page_et_".$divi_ghoster_theme_slug."_options"]);
        }
        if ( ! in_array($hook, $allowed_pages, true)) {
            return;
        }
        wp_enqueue_script('pac_drh_dashboard', plugins_url('/includes/admin/js/dashboard_settings.js', __FILE__));
        $enable_auto_responsive = et_get_option('pac_drh_enable_auto_responsive');
        if ('on' === $enable_auto_responsive) {
            wp_enqueue_script('pac_drh_vb_responsive', plugins_url('/includes/admin/js/vb_responsive.js', __FILE__), [], PAC_DRH_VERSION, true);
            wp_localize_script('pac_drh_vb_responsive', 'pac_drh_vb_obj', ['responsiveTabs' => $enable_auto_responsive]);
        }
        if ('on' === et_get_option('pac_drh_enable_presets') || 'on' === et_get_option('pac_drh_enable_custom_preview')) {
            wp_enqueue_script('pac_drh_vb_responsive_buttons', plugins_url('/includes/admin/js/responsive.js', __FILE__), ["jquery"], PAC_DRH_VERSION, true);
            $pac_drh_custom_presets = [
                'pac_drh_phone_preset_one' => et_get_option('pac_drh_phone_preset_one'),
                'pac_drh_phone_preset_two' => et_get_option('pac_drh_phone_preset_two'),
                'pac_drh_phone_preset_three' => et_get_option('pac_drh_phone_preset_three'),
                'pac_drh_tablet_preset_one' => et_get_option('pac_drh_tablet_preset_one'),
                'pac_drh_tablet_preset_two' => et_get_option('pac_drh_tablet_preset_two'),
                'pac_drh_tablet_preset_three' => et_get_option('pac_drh_tablet_preset_three'),
                'pac_drh_desktop_preset_one' => et_get_option('pac_drh_desktop_preset_one'),
                'pac_drh_desktop_preset_two' => et_get_option('pac_drh_desktop_preset_two'),
                'pac_drh_desktop_preset_three' => et_get_option('pac_drh_desktop_preset_three'),
            ];
            wp_localize_script('pac_drh_vb_responsive_buttons', 'pac_drh_custom_presets', $pac_drh_custom_presets);
            wp_enqueue_style('pac_drh_responsive_settings_vb_css', plugins_url('/includes/admin/css/style.css', __FILE__));
        }
        wp_enqueue_script('pac_drh_custom_media_query', plugins_url('/includes/admin/custom-media-query/custom-media-query.js', __FILE__), [], PAC_DRH_VERSION, true);
    }
}
add_action('admin_enqueue_scripts', 'pac_drh_admin_enqueue_scripts');
add_action('admin_enqueue_scripts', 'pac_drh_enqueue_scripts');
// Include Required Features
if ( ! function_exists('pac_drh_init_plugin')) {
    function pac_drh_init_plugin() {
        if ( ! function_exists('pac_drh_init_settings')) {
            require_once(PAC_DRH_PATH.'/includes/admin/drh/settings.php');
        }
        if ( ! function_exists('pac_drh_epanel_tabs')) {
            require_once(PAC_DRH_PATH.'/includes/admin/options/options_panel.php');
        }
        if (('on' === et_get_option('pac_drh_enable_number_of_columns') || 'on' === et_get_option('pac_drh_enable_col_stacking')) && (file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_row.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_row.php');
        }
        if ('on' === et_get_option('pac_drh_enable_col_stacking') && (file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_column.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_column.php');
        }
        if ('on' === et_get_option('pac_drh_enable_gallery_number_of_columns') && (file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_gallery.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_gallery.php');
        }
        if ('on' === et_get_option('pac_drh_enable_shop_number_of_columns') && (file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_shop.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_shop.php');
        }
        if ('on' === et_get_option('pac_drh_enable_blog_number_of_columns') && (file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_blog.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_blog.php');
        }
        if ('on' === et_get_option('pac_drh_enable_portfolio_number_of_columns') && (file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_portfolio.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_portfolio.php');
        }
        if ('on' === et_get_option('pac_drh_enable_blurb_settings') && (file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_blurb.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_blurb.php');
        }
        if ('on' === et_get_option('pac_drh_enable_tabs_layout_settings') && (file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_tabs.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_tabs.php');
        }
        if ('on' == et_get_option('pac_drh_enable_widow_fixer') && (file_exists(PAC_DRH_PATH.'/includes/widow-fixer/index.php'))) {
            require_once(PAC_DRH_PATH.'/includes/widow-fixer/index.php');
        }
        if ('on' == et_get_option('pac_drh_enable_responsive_menu') && file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_menu.php') && file_exists(PAC_DRH_PATH.'/includes/lib/Mobile_Detect.php')) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_menu.php');
        }
        if ('on' === et_get_option('pac_drh_enable_col_stacking') && (file_exists(PAC_DRH_PATH.'/includes/admin/options/stacking.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/options/stacking.php');
        }
        if ('on' === et_get_option('pac_drh_enable_number_of_columns') && (file_exists(PAC_DRH_PATH.'/includes/admin/options/col_numbers.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/options/col_numbers.php');
        }
        if ('on' === et_get_option('pac_drh_enable_text_sizes') && (file_exists(PAC_DRH_PATH.'/includes/admin/options/text_sizes_css.php'))) {
            require_once(PAC_DRH_PATH.'/includes/admin/options/text_sizes_css.php');
        }
        if ('on' === et_get_option('pac_drh_enable_show_hide_menu_item') && file_exists(PAC_DRH_PATH.'/includes/admin/wp-menu/menu.php')) {
            require_once(PAC_DRH_PATH.'/includes/admin/wp-menu/menu.php');
        }
        if (file_exists(PAC_DRH_PATH.'/includes/admin/custom-media-query/custom-media-query-css.php')) {
            require_once(PAC_DRH_PATH.'/includes/admin/custom-media-query/custom-media-query-css.php');
        }
        if (file_exists(PAC_DRH_PATH.'/includes/admin/options/update_customizer_options.php')) {
            require_once(PAC_DRH_PATH.'/includes/admin/options/update_customizer_options.php');
        }
        if (file_exists(PAC_DRH_PATH.'/includes/admin/modules/et_pb_row_layout.php')) {
            require_once(PAC_DRH_PATH.'/includes/admin/modules/et_pb_row_layout.php');
        }
        if (file_exists(PAC_DRH_PATH.'/includes/misc/misc.php')) {
            require_once(PAC_DRH_PATH.'/includes/misc/misc.php');
        }
    }
}
add_action('init', 'pac_drh_init_plugin');
