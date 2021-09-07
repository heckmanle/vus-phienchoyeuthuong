<?php
if ( ! function_exists('pac_drh_add_class_to_row')) {
    function pac_drh_add_class_to_row($output, $render_slug, $module) {
        if (et_fb_is_enabled()) {
            return $output;
        }
        if ('et_pb_row' !== $render_slug) {
            return $output;
        }
        if (is_array($output)) {
            return $output;
        }
        $pac_drh_enable_stacking = isset($module->props['pac_drh_enable_stacking']) ? $module->props['pac_drh_enable_stacking'] : 'off';
        if ('off' !== $pac_drh_enable_stacking) {
            $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row '.$module->props['pac_drh_enable_stacking'], $output);
        }
        $div_columns = explode(",", $module->props['column_structure']);
        $column_structure = preg_replace("/[0-9]_/", "", end($div_columns));
        $tablet_columns = [
            'pac_drh_tab_col_one' => 1,
            'pac_drh_tab_col_two' => 2,
            'pac_drh_tab_col_three' => 3,
            'pac_drh_tab_col_four' => 4,
            'pac_drh_tab_col_five' => 5,
            'pac_drh_tab_col_six' => 6,
        ];
        $phone_columns = [
            'pac_drh_phone_col_one' => 1,
            'pac_drh_phone_col_two' => 2,
            'pac_drh_phone_col_three' => 3,
            'pac_drh_phone_col_four' => 4,
            'pac_drh_phone_col_five' => 5,
            'pac_drh_phone_col_six' => 6,
        ];
        // Tablet
        $pac_drh_column_numbers_tablet = isset($module->props['pac_drh_column_numbers_tablet']) ? $module->props['pac_drh_column_numbers_tablet'] : 'off';
        if ('off' !== $pac_drh_column_numbers_tablet) {
            $col_on_tab = $tablet_columns[$pac_drh_column_numbers_tablet];
            if ('pac_drh_tab_col_two' === $pac_drh_column_numbers_tablet && ($column_structure % $col_on_tab) === 1) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_tab_fix_2_last_1_col '.$pac_drh_column_numbers_tablet, $output);
            } elseif ('pac_drh_tab_col_three' === $pac_drh_column_numbers_tablet && ($column_structure % $col_on_tab) === 1) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_tab_fix_3_last_1_col '.$pac_drh_column_numbers_tablet, $output);
            } elseif ('pac_drh_tab_col_three' === $pac_drh_column_numbers_tablet && ($column_structure % $col_on_tab) === 2) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_tab_fix_3_last_2_col '.$pac_drh_column_numbers_tablet, $output);
            } elseif ('pac_drh_tab_col_four' === $pac_drh_column_numbers_tablet && ($column_structure % $col_on_tab) === 1) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_tab_fix_4_last_1_col '.$pac_drh_column_numbers_tablet, $output);
            } elseif ('pac_drh_tab_col_four' === $pac_drh_column_numbers_tablet && ($column_structure % $col_on_tab) === 2) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_tab_fix_4_last_2_col '.$pac_drh_column_numbers_tablet, $output);
            } elseif ('pac_drh_tab_col_five' === $pac_drh_column_numbers_tablet && ($column_structure % $col_on_tab) === 1) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_tab_fix_5_last_1_col '.$pac_drh_column_numbers_tablet, $output);
            } else {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row '.$pac_drh_column_numbers_tablet, $output);
            }
        }
        // Phone
        $pac_drh_column_numbers_phone = isset($module->props['pac_drh_column_numbers_phone']) ? $module->props['pac_drh_column_numbers_phone'] : 'off';
        if ('off' !== $pac_drh_column_numbers_phone) {
            $col_on_phone = $phone_columns[$pac_drh_column_numbers_phone];
            if ('pac_drh_phone_col_two' === $pac_drh_column_numbers_phone && ($column_structure % $col_on_phone) === 1) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_phone_fix_2_last_1_col '.$pac_drh_column_numbers_phone, $output);
            } elseif ('pac_drh_phone_col_three' === $pac_drh_column_numbers_phone && ($column_structure % $col_on_phone) === 1) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_phone_fix_3_last_1_col '.$pac_drh_column_numbers_phone, $output);
            } elseif ('pac_drh_phone_col_three' === $pac_drh_column_numbers_phone && ($column_structure % $col_on_phone) === 2) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_phone_fix_3_last_2_col '.$pac_drh_column_numbers_phone, $output);
            } elseif ('pac_drh_phone_col_four' === $pac_drh_column_numbers_phone && ($column_structure % $col_on_phone) === 1) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_phone_fix_4_last_1_col '.$pac_drh_column_numbers_phone, $output);
            } elseif ('pac_drh_phone_col_four' === $pac_drh_column_numbers_phone && ($column_structure % $col_on_phone) === 2) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_phone_fix_4_last_2_col '.$pac_drh_column_numbers_phone, $output);
            } elseif ('pac_drh_phone_col_five' === $pac_drh_column_numbers_phone && ($column_structure % $col_on_phone) === 1) {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row pac_drh_phone_fix_5_last_1_col '.$pac_drh_column_numbers_phone, $output);
            } else {
                $output = preg_replace('/\bet_pb_row\b/', 'et_pb_row '.$pac_drh_column_numbers_phone, $output);
            }
        }

        return $output;
    }
}
add_filter('et_module_shortcode_output', 'pac_drh_add_class_to_row', 10, 3);