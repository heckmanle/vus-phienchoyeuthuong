<?php

if ( ! function_exists('pac_drh_add_class_to_gallery')) {
    function pac_drh_add_class_to_gallery($output, $render_slug, $module) {
        if (et_fb_is_enabled()) {
            return $output;
        }
        if ('et_pb_gallery' !== $render_slug) {
            return $output;
        }
        if (is_array($output)) {
            return $output;
        }
        if (strpos($output, 'et_pb_gallery_grid') === false) {
            return $output;
        }
        $gallery_ids = $module->props['gallery_ids'];
        $gallery_ids = count(explode(",", $gallery_ids));
        $pac_drh_column_numbers_desktop = isset($module->props['pac_drh_column_numbers_desktop']) ? $module->props['pac_drh_column_numbers_desktop'] : 'off';
        $pac_drh_column_numbers_tablet = isset($module->props['pac_drh_column_numbers_tablet']) ? $module->props['pac_drh_column_numbers_tablet'] : 'off';
        $pac_drh_column_numbers_phone = isset($module->props['pac_drh_column_numbers_phone']) ? $module->props['pac_drh_column_numbers_phone'] : 'off';
        if ('off' !== $pac_drh_column_numbers_desktop) {
            if ($pac_drh_column_numbers_desktop === 'pac_drh_desktop_col_three' & (($gallery_ids % 3) === 1)) {
                $output = preg_replace('/\bet_pb_module et_pb_gallery\b/', 'et_pb_module et_pb_gallery pac_drh_desktop_3_col_last_r_1_element_fix', $output);
            }
            $output = preg_replace('/\bet_pb_module et_pb_gallery\b/', 'et_pb_module et_pb_gallery '.$pac_drh_column_numbers_desktop, $output);
        }
        if ('off' !== $pac_drh_column_numbers_tablet) {
            if ($pac_drh_column_numbers_tablet === 'pac_drh_tab_col_four' & (($gallery_ids % 4) === 1)) {
                $output = preg_replace('/\bet_pb_module et_pb_gallery\b/', 'et_pb_module et_pb_gallery pac_drh_tab_4_col_last_r_1_element_fix', $output);
            }
            if ($pac_drh_column_numbers_tablet === 'pac_drh_tab_col_four' & (($gallery_ids % 4) === 2)) {
                $output = preg_replace('/\bet_pb_module et_pb_gallery\b/', 'et_pb_module et_pb_gallery pac_drh_tab_4_col_last_r_2_element_fix', $output);
            }
            $output = preg_replace('/\bet_pb_module et_pb_gallery\b/', 'et_pb_module et_pb_gallery '.$pac_drh_column_numbers_tablet, $output);
        }
        if ('off' !== $pac_drh_column_numbers_phone) {
            if ($pac_drh_column_numbers_phone === 'pac_drh_ph_col_three' & (($gallery_ids % 3) === 1)) {
                $output = preg_replace('/\bet_pb_module et_pb_gallery\b/', 'et_pb_module et_pb_gallery pac_drh_ph_3_col_last_r_1_element_fix', $output);
            }
            if ($pac_drh_column_numbers_phone === 'pac_drh_ph_col_four' & (($gallery_ids % 4) === 2)) {
                $output = preg_replace('/\bet_pb_module et_pb_gallery\b/', 'et_pb_module et_pb_gallery pac_drh_ph_4_col_last_r_2_element_fix', $output);
            }
            if ($pac_drh_column_numbers_phone === 'pac_drh_ph_col_four' & (($gallery_ids % 4) === 1)) {
                $output = preg_replace('/\bet_pb_module et_pb_gallery\b/', 'et_pb_module et_pb_gallery pac_drh_ph_4_col_last_r_1_element_fix', $output);
            }
            $output = preg_replace('/\bet_pb_module et_pb_gallery\b/', 'et_pb_module et_pb_gallery '.$pac_drh_column_numbers_phone, $output);
        }

        return $output;
    }
}
add_filter('et_module_shortcode_output', 'pac_drh_add_class_to_gallery', 10, 3);

if ( ! function_exists('pac_drh_add_gallery_col_numbering_options')) {
    function pac_drh_add_gallery_col_numbering_options($fields_unprocessed) {
        $fields = [];
        $fields['pac_drh_column_numbers_desktop'] = [
            'label' => esc_html__('Number of Image Columns on Desktop', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_desktop_col_one' => esc_html__('One', 'Divi'),
                'pac_drh_desktop_col_two' => esc_html__('Two', 'Divi'),
                'pac_drh_desktop_col_three' => esc_html__('Three', 'Divi'),
                'pac_drh_desktop_col_four' => esc_html__('Four', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select the number of image columns to show on Desktop.', 'Divi'),
            'tab_slug' => 'advanced',
            'toggle_slug' => 'layout',
            'show_if' => [
                'fullwidth' => 'off',
            ],
        ];
        $fields['pac_drh_column_numbers_tablet'] = [
            'label' => esc_html__('Number of Image Columns on Tablet', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_tab_col_one' => esc_html__('One', 'Divi'),
                'pac_drh_tab_col_two' => esc_html__('Two', 'Divi'),
                'pac_drh_tab_col_three' => esc_html__('Three', 'Divi'),
                'pac_drh_tab_col_four' => esc_html__('Four', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select the number of image columns to show on Tablet.', 'Divi'),
            'tab_slug' => 'advanced',
            'toggle_slug' => 'layout',
            'show_if' => [
                'fullwidth' => 'off',
            ],
        ];
        $fields['pac_drh_column_numbers_phone'] = [
            'label' => esc_html__('Number of Image Columns on Phone', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_ph_col_one' => esc_html__('One', 'Divi'),
                'pac_drh_ph_col_two' => esc_html__('Two', 'Divi'),
                'pac_drh_ph_col_three' => esc_html__('Three', 'Divi'),
                'pac_drh_ph_col_four' => esc_html__('Four', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select the number of image columns to show on Phone.', 'Divi'),
            'tab_slug' => 'advanced',
            'toggle_slug' => 'layout',
            'show_if' => [
                'fullwidth' => 'off',
            ],
        ];

        return array_merge($fields_unprocessed, $fields);
    }
}
add_filter('et_pb_all_fields_unprocessed_et_pb_gallery', 'pac_drh_add_gallery_col_numbering_options');


