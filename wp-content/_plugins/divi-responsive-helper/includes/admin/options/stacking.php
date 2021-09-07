<?php
add_filter('et_pb_all_fields_unprocessed_et_pb_row', 'pac_drh_add_staking_toggle');
add_filter('et_pb_all_fields_unprocessed_et_pb_row_inner', 'pac_drh_add_staking_toggle');
add_filter('et_pb_all_fields_unprocessed_et_pb_column', 'pac_drh_add_staking_order');
if ( ! function_exists('pac_drh_add_staking_toggle')) {
    function pac_drh_add_staking_toggle($fields_unprocessed) {
        $fields = [];
        $fields['pac_drh_enable_stacking'] = [
            'label' => esc_html__('Enable Column Stacking Order', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Default Column Stacking Order', 'Divi'),
                'pac_drh_stack_row pac_drh_stack_tab_mob' => esc_html__('Enable Column Stacking Order on both Tablet and Phone', 'Divi'),
                'pac_drh_stack_row pac_drh_stack_tab_only' => esc_html__('Enable Column Stacking Order on Tablet only', 'Divi'),
                'pac_drh_stack_row pac_drh_stack_mob_only' => esc_html__('Enable Column Stacking Order on Phone only', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Choose which Column Stacking Order option to use.', 'Divi'),
            'tab_slug' => 'custom_css',
            'toggle_slug' => 'classes',
        ];

        return array_merge($fields_unprocessed, $fields);
    }
}
if ( ! function_exists('pac_drh_add_staking_order')) {
    function pac_drh_add_staking_order($fields_unprocessed) {
        $fields = [];
        $fields['pac_drh_add_staking_order_tab'] = [
            'label' => esc_html__('Stacking Order on Tablet', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Stacking Order on Tablet', 'Divi'),
                'pac_drh_order_tab_one' => esc_html__('One', 'Divi'),
                'pac_drh_order_tab_two' => esc_html__('Two', 'Divi'),
                'pac_drh_order_tab_three' => esc_html__('Three', 'Divi'),
                'pac_drh_order_tab_four' => esc_html__('Four', 'Divi'),
                'pac_drh_order_tab_five' => esc_html__('Five', 'Divi'),
                'pac_drh_order_tab_six' => esc_html__('Six', 'Divi'),
                'pac_drh_order_tab_seven' => esc_html__('Seven', 'Divi'),
                'pac_drh_order_tab_eight' => esc_html__('Eight', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select a number to designate the order in which the columns will stack on Tablet.', 'Divi'),
            'tab_slug' => 'custom_css',
            'toggle_slug' => 'classes',
        ];
        $fields['pac_drh_add_staking_order_mob'] = [
            'label' => esc_html__('Stacking Order on Phone', 'Divi'),
            'type' => 'select',
            'options' => [
                'off' => esc_html__('Stacking Order on Phone', 'Divi'),
                'pac_drh_order_mob_one' => esc_html__('One', 'Divi'),
                'pac_drh_order_mob_two' => esc_html__('Two', 'Divi'),
                'pac_drh_order_mob_three' => esc_html__('Three', 'Divi'),
                'pac_drh_order_mob_four' => esc_html__('Four', 'Divi'),
                'pac_drh_order_mob_five' => esc_html__('Five', 'Divi'),
                'pac_drh_order_mob_six' => esc_html__('Six', 'Divi'),
                'pac_drh_order_mob_seven' => esc_html__('Seven', 'Divi'),
                'pac_drh_order_mob_eight' => esc_html__('Eight', 'Divi'),
            ],
            'default' => 'off',
            'description' => esc_html__('Select a number to designate the order in which the columns will stack on Phone.', 'Divi'),
            'tab_slug' => 'custom_css',
            'toggle_slug' => 'classes',
        ];

        return array_merge($fields_unprocessed, $fields);
    }
}
