<?php
if ( ! function_exists('pac_drh_add_class_to_column')) {
    function pac_drh_add_class_to_column($output, $render_slug, $module) {
        if (et_fb_is_enabled()) {
            return $output;
        }
        if ('et_pb_column' !== $render_slug) {
            return $output;
        }
        if (is_array($output)) {
            return $output;
        }
        $pac_drh_add_staking_order_tab = isset($module->props['pac_drh_add_staking_order_tab']) ? $module->props['pac_drh_add_staking_order_tab'] : 'off';
        $pac_drh_add_staking_order_mob = isset($module->props['pac_drh_add_staking_order_mob']) ? $module->props['pac_drh_add_staking_order_mob'] : 'off';
        if ('off' !== $pac_drh_add_staking_order_tab) {
            /* $output = preg_replace('/class="et_pb_column/', 'class="et_pb_column '.$module->props['pac_drh_add_staking_order_tab'], $output);*/
            $output = preg_replace('/\bet_pb_column\b/', 'et_pb_column '.$module->props['pac_drh_add_staking_order_tab'], $output);
        }
        if ('off' !== $pac_drh_add_staking_order_mob) {
            /*$output = preg_replace('/class="et_pb_column/', 'class="et_pb_column '.$module->props['pac_drh_add_staking_order_mob'], $output)*/;
            $output = preg_replace('/\bet_pb_column\b/', 'et_pb_column '.$module->props['pac_drh_add_staking_order_mob'], $output);
        }

        return $output;
    }
}
add_filter('et_module_shortcode_output', 'pac_drh_add_class_to_column', 10, 3);