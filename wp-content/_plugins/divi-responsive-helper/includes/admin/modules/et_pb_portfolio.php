<?php
if ( ! function_exists('pac_drh_add_class_to_portfolio')) {
    function pac_drh_add_class_to_portfolio($output, $render_slug, $module) {
        if (et_fb_is_enabled()) {
            return $output;
        }
        if (is_array($output)) {
            return $output;
        }
        if ('et_pb_portfolio' === $render_slug || 'et_pb_fullwidth_portfolio' === $render_slug || 'et_pb_filterable_portfolio' === $render_slug) {
            $posts_number = $module->props['posts_number'];
            $include_categories = ! empty($module->props['include_categories']) ? explode(',', $module->props['include_categories']) : (array)null;
            // Prepare query arguments
            $query_args = [
                'posts_per_page' => (int)$posts_number,
                'post_type' => 'project',
                'post_status' => ['publish'],
                'perm' => 'readable',
            ];
            // phpcs:disable
            if ( ! empty($include_categories) and in_array("all", $include_categories) == false) {
                $query_args['tax_query'] = [
                    [
                        'taxonomy' => 'project_category',
                        'field' => 'id',
                        'terms' => $include_categories,
                        'operator' => 'IN',
                    ],
                ];
            }
            // phpcs:enable
            $the_query = new WP_Query($query_args);
            $total_post = $the_query->found_posts;
            if ($total_post > $posts_number) {
                $total_post = $posts_number;
            }
            $pac_drh_column_numbers_desktop = isset($module->props['pac_drh_column_numbers_desktop']) ? $module->props['pac_drh_column_numbers_desktop'] : 'off';
            $pac_drh_column_numbers_tablet = isset($module->props['pac_drh_column_numbers_tablet']) ? $module->props['pac_drh_column_numbers_tablet'] : 'off';
            $pac_drh_column_numbers_phone = isset($module->props['pac_drh_column_numbers_phone']) ? $module->props['pac_drh_column_numbers_phone'] : 'off';
            if ('off' !== $pac_drh_column_numbers_desktop) {
                if ($pac_drh_column_numbers_desktop === 'pac_drh_desktop_col_two' && (($total_post % 2) === 1)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_desktop_2_col_last_r_1_element_fix', $output);
                }
                if ($pac_drh_column_numbers_desktop === 'pac_drh_desktop_col_three' && (($total_post % 3) === 1)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_desktop_3_col_last_r_1_element_fix', $output);
                }
                if ($pac_drh_column_numbers_desktop === 'pac_drh_desktop_col_three' && (($total_post % 3) === 2)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_desktop_3_col_last_r_2_element_fix', $output);
                    // $output = preg_replace('/class="et_pb_portfolio_item\b/', 'class="et_pb_portfolio_item pac_drh_margin_left ', $output);
                }
                $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module '.$pac_drh_column_numbers_desktop, $output);
            }
            if ('off' !== $pac_drh_column_numbers_tablet) {
                if ($pac_drh_column_numbers_tablet === 'pac_drh_tab_col_two' && (($total_post % 2) === 1)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_tab_2_col_last_r_1_element_fix', $output);
                }
                if ($pac_drh_column_numbers_tablet === 'pac_drh_tab_col_four' && (($total_post % 4) === 1)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_tab_4_col_last_r_1_element_fix', $output);
                }
                if ($pac_drh_column_numbers_tablet === 'pac_drh_tab_col_four' && (($total_post % 4) === 2)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_tab_4_col_last_r_2_element_fix', $output);
                }
                if ($pac_drh_column_numbers_tablet === 'pac_drh_tab_col_four' && (($total_post % 4) === 3)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_tab_4_col_last_r_3_element_fix', $output);
                }
                $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module '.$pac_drh_column_numbers_tablet, $output);
            }
            if ('off' !== $pac_drh_column_numbers_phone) {
                if ($pac_drh_column_numbers_phone === 'pac_drh_ph_col_three' && (($total_post % 3) === 1)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_ph_3_col_last_r_1_element_fix', $output);
                }
                if ($pac_drh_column_numbers_phone === 'pac_drh_ph_col_three' && (($total_post % 3) === 2)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_ph_3_col_last_r_2_element_fix', $output);
                }
                if ($pac_drh_column_numbers_phone === 'pac_drh_ph_col_four' && (($total_post % 4) === 1)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_ph_4_col_last_r_1_element_fix', $output);
                }
                if ($pac_drh_column_numbers_phone === 'pac_drh_ph_col_four' && (($total_post % 4) === 2)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_ph_4_col_last_r_2_element_fix', $output);
                }
                if ($pac_drh_column_numbers_phone === 'pac_drh_ph_col_four' && (($total_post % 4) === 3)) {
                    $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module pac_drh_ph_4_col_last_r_3_element_fix', $output);
                }
                $output = preg_replace('/\bet_pb_module\b/', 'et_pb_module '.$pac_drh_column_numbers_phone, $output);
            }
        }

        return $output;
    }
}
add_filter('et_module_shortcode_output', 'pac_drh_add_class_to_portfolio', 10, 3);
if ( ! function_exists('pac_drh_add_portfolio_col_numbering_options')) {
    function pac_drh_add_portfolio_col_numbering_options($fields_unprocessed) {
        $fields = [];
        $fields['pac_drh_column_numbers_desktop'] = [
            'label' => esc_html__('Number of Portfolio Columns on Desktop', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_desktop_col_one' => esc_html__('One', 'Divi'),
                'pac_drh_desktop_col_two' => esc_html__('Two', 'Divi'),
                'pac_drh_desktop_col_three' => esc_html__('Three', 'Divi'),
                'pac_drh_desktop_col_four' => esc_html__('Four', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select the number of Portfolio columns to show on Desktop.', 'Divi'),
            'tab_slug' => 'advanced',
            'toggle_slug' => 'layout',
            'show_if' => [
                'fullwidth' => 'off',
            ],
        ];
        $fields['pac_drh_column_numbers_tablet'] = [
            'label' => esc_html__('Number of Portfolio Columns on Tablet', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_tab_col_one' => esc_html__('One', 'Divi'),
                'pac_drh_tab_col_two' => esc_html__('Two', 'Divi'),
                'pac_drh_tab_col_three' => esc_html__('Three', 'Divi'),
                'pac_drh_tab_col_four' => esc_html__('Four', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select the number of Portfolio columns to show on Tablet.', 'Divi'),
            'tab_slug' => 'advanced',
            'toggle_slug' => 'layout',
            'show_if' => [
                'fullwidth' => 'off',
            ],
        ];
        $fields['pac_drh_column_numbers_phone'] = [
            'label' => esc_html__('Number of Portfolio Columns on Phone', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_ph_col_one' => esc_html__('One', 'Divi'),
                'pac_drh_ph_col_two' => esc_html__('Two', 'Divi'),
                'pac_drh_ph_col_three' => esc_html__('Three', 'Divi'),
                'pac_drh_ph_col_four' => esc_html__('Four', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select the number of Portfolio columns to show on Phone.', 'Divi'),
            'tab_slug' => 'advanced',
            'toggle_slug' => 'layout',
            'show_if' => [
                'fullwidth' => 'off',
            ],
        ];

        return array_merge($fields_unprocessed, $fields);
    }
}
add_filter('et_pb_all_fields_unprocessed_et_pb_portfolio', 'pac_drh_add_portfolio_col_numbering_options');
add_filter('et_pb_all_fields_unprocessed_et_pb_filterable_portfolio', 'pac_drh_add_portfolio_col_numbering_options');


/*
if (strpos($output, 'et_pb_filterable_portfolio_grid') !== false) {

}*/