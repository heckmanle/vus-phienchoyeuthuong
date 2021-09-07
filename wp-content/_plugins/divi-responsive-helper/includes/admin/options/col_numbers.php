<?php
add_filter('et_pb_all_fields_unprocessed_et_pb_row', 'pac_drh_add_col_numbering_options');
add_filter('et_pb_all_fields_unprocessed_et_pb_row_inner', 'pac_drh_add_col_numbering_options');
if ( ! function_exists('pac_drh_add_col_numbering_options')) {
    function pac_drh_add_col_numbering_options($fields_unprocessed) {
        $fields = [];
        $fields['pac_drh_column_numbers_tablet'] = [
            'label' => esc_html__('Number of Columns on Tablet', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_tab_col_one' => esc_html__('One', 'Divi'),
                'pac_drh_tab_col_two' => esc_html__('Two', 'Divi'),
                'pac_drh_tab_col_three' => esc_html__('Three', 'Divi'),
                'pac_drh_tab_col_four' => esc_html__('Four', 'Divi'),
                'pac_drh_tab_col_five' => esc_html__('Five', 'Divi'),
                'pac_drh_tab_col_six' => esc_html__('Six', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select Number of Columns on Tablet', 'Divi'),
            'tab_slug' => 'custom_css',
            'toggle_slug' => 'classes',
        ];
        $fields['pac_drh_column_numbers_phone'] = [
            'label' => esc_html__('Number of Columns on Phone', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default', 'Divi'),
                'pac_drh_phone_col_one' => esc_html__('One', 'Divi'),
                'pac_drh_phone_col_two' => esc_html__('Two', 'Divi'),
                'pac_drh_phone_col_three' => esc_html__('Three', 'Divi'),
                'pac_drh_phone_col_four' => esc_html__('Four', 'Divi'),
                'pac_drh_phone_col_five' => esc_html__('Five', 'Divi'),
                'pac_drh_phone_col_six' => esc_html__('Six', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select Number of Columns on Phone', 'Divi'),
            'tab_slug' => 'custom_css',
            'toggle_slug' => 'classes',
        ];

        return array_merge($fields_unprocessed, $fields);
    }
}

