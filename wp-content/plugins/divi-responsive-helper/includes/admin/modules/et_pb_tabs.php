<?php

if ( ! function_exists('pac_drh_add_class_to_tab')) {
    function pac_drh_add_class_to_tab($output, $render_slug, $module) {
        if (et_fb_is_enabled()) {
            return $output;
        }
        if ('et_pb_tabs' !== $render_slug) {
            return $output;
        }
        if (is_array($output)) {
            return $output;
        }
        $pac_drh_tabs_layout_tablet = isset($module->props['pac_drh_tabs_layout_tablet']) ? $module->props['pac_drh_tabs_layout_tablet'] : 'off';
        if ('off' !== $pac_drh_tabs_layout_tablet) {
            $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module '.$pac_drh_tabs_layout_tablet, $output);
        }
        $pac_drh_tabs_layout_phone = isset($module->props['pac_drh_tabs_layout_phone']) ? $module->props['pac_drh_tabs_layout_phone'] : 'off';
        if ('off' !== $pac_drh_tabs_layout_phone) {
            $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module '.$pac_drh_tabs_layout_phone, $output);
        }

        return $output;
    }
}
add_filter('et_module_shortcode_output', 'pac_drh_add_class_to_tab', 10, 3);
// Tabs Layout Settings
if ( ! function_exists('pac_drh_tabs_layout_options')) {
    function pac_drh_tabs_layout_options($fields_unprocessed) {
        $fields = [];
        $fields['pac_drh_tabs_layout_tablet'] = [
            'label' => esc_html__('Layout On Tablet', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_tabs_vertical_tab' => esc_html__('Vertical', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Choose a tab layout to use on Tablet.', 'Divi'),
            'tab_slug' => 'advanced',
        ];
        $fields['pac_drh_tabs_layout_phone'] = [
            'label' => esc_html__('Layout On Phone', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_tabs_horizontal_phone' => esc_html__('Horizontal', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Choose a tab layout to use on Phone.', 'Divi'),
            'tab_slug' => 'advanced',
        ];

        return array_merge($fields_unprocessed, $fields);
    }
}
add_filter('et_pb_all_fields_unprocessed_et_pb_tabs', 'pac_drh_tabs_layout_options');
// Add Inline Css
if ( ! function_exists('pac_drh_tabs_css')) {
    function pac_drh_tabs_css() {
        echo PHP_EOL;
        echo "<style>";
        /* Tablet */
        echo "@media (min-width:768px) and (max-width:980px){.pac_drh_tabs_vertical_tab .et_pb_tabs_controls li{float:none;border-right:none;border-bottom:1px solid #d9d9d9;display:block}}";
        /* Phone */
        echo "@media (min-width:300px) and (max-width:767px){.pac_drh_tabs_horizontal_phone ul.et_pb_tabs_controls{display:flex;flex-wrap:wrap}.pac_drh_tabs_horizontal_phone .et_pb_tabs_controls li{flex-grow:1}.pac_drh_tabs_horizontal_phone .et_pb_tabs_controls li a{display:block}}";
        echo "</style>";
        echo PHP_EOL;
    }
}
add_action('admin_print_styles', 'pac_drh_tabs_css');
add_action('wp_print_styles', 'pac_drh_tabs_css');