<?php
if ( ! function_exists('pac_drh_add_class_to_blurb')) {
    function pac_drh_add_class_to_blurb($output, $render_slug, $module) {
        if (et_fb_is_enabled()) {
            return $output;
        }
        if ('et_pb_blurb' !== $render_slug) {
            return $output;
        }
        if (is_array($output)) {
            return $output;
        }
        $pac_drh_blurb_layout_phone = isset($module->props['pac_drh_blurb_layout_phone']) ? $module->props['pac_drh_blurb_layout_phone'] : 'off';
        if ('off' !== $pac_drh_blurb_layout_phone) {
            $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module '.$pac_drh_blurb_layout_phone, $output);
        }

        return $output;
    }
}
add_filter('et_module_shortcode_output', 'pac_drh_add_class_to_blurb', 10, 3);
if ( ! function_exists('pac_drh_blurb_layout_options')) {
    function pac_drh_blurb_layout_options($fields_unprocessed) {
        $fields = [];
        $fields['pac_drh_blurb_layout_phone'] = [
            'label' => esc_html__('Blurb Image/Icon Position On Phone', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_ph_blurb_top_left' => esc_html__('Top Left', 'Divi'),
                'pac_drh_ph_blurb_top_center' => esc_html__('Top Center', 'Divi'),
                'pac_drh_ph_blurb_top_right' => esc_html__('Top Right', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Choose the image or icon position on Phone devices when the default placement is set to left.', 'Divi'),
            'tab_slug' => 'advanced',
            'toggle_slug' => 'icon_settings',
            'show_if' => [
                'icon_placement' => 'left'
            ]
        ];

        return array_merge($fields_unprocessed, $fields);
    }
}
add_filter('et_pb_all_fields_unprocessed_et_pb_blurb', 'pac_drh_blurb_layout_options');
if ( ! function_exists('pac_drh_blurb_css')) {
    function pac_drh_blurb_css() {
        echo PHP_EOL;
        echo "<style>";
        echo '@media all and (max-width:767px){.pac_drh_ph_blurb_top_left.et_pb_blurb_position_left .et_pb_main_blurb_image{display:block!important}.pac_drh_ph_blurb_top_left.et_pb_blurb_position_left .et_pb_blurb_container{padding-left:0;display:block!important}.pac_drh_ph_blurb_top_center.et_pb_blurb_position_left .et_pb_main_blurb_image{display:block!important;width:50%;margin:0 auto;margin-bottom:30px}.pac_drh_ph_blurb_top_center.et_pb_blurb_position_left .et_pb_blurb_container{padding-left:0;display:block!important}.pac_drh_ph_blurb_top_right.et_pb_blurb_position_left .et_pb_main_blurb_image{margin-left:auto;display:flex;flex-direction:row-reverse}.pac_drh_ph_blurb_top_right.et_pb_blurb_position_left .et_pb_blurb_container{padding-left:0;display:block!important}}';
        echo "</style>";
        echo PHP_EOL;
    }
}
add_action('admin_print_styles', 'pac_drh_inline_css');
add_action('wp_print_styles', 'pac_drh_blurb_css');