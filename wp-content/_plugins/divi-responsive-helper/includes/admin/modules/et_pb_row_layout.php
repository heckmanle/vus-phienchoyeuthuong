<?php
if ( ! function_exists('pac_drh_row_layout_fields')) {
    function pac_drh_row_layout_fields($fields_unprocessed) {
        if ( ! empty(et_get_option('pac_drh_row_width_desktop'))) {
            $fields_unprocessed['width']['default'] = et_get_option('pac_drh_row_width_desktop');
        }
        if ( ! empty(et_get_option('pac_drh_row_max_width_desktop'))) {
            $fields_unprocessed['max_width']['default'] = et_get_option('pac_drh_row_max_width_desktop');
        }
        if ( ! empty(et_get_option('pac_drh_row_max_width_tablet'))) {
            $fields_unprocessed['max_width_tablet']['default'] = et_get_option('pac_drh_row_max_width_tablet');
        }
        if ( ! empty(et_get_option('pac_drh_row_width_tablet'))) {
            $fields_unprocessed['width_tablet']['default'] = et_get_option('pac_drh_row_width_tablet');
        }
        if ( ! empty(et_get_option('pac_drh_row_width_phone'))) {
            $fields_unprocessed['width_phone']['default'] = et_get_option('pac_drh_row_width_phone');
        }
        if ( ! empty(et_get_option('pac_drh_row_max_width_phone'))) {
            $fields_unprocessed['max_width_phone']['default'] = et_get_option('pac_drh_row_max_width_phone');
        }

        return $fields_unprocessed;
    }
}
add_filter('et_pb_all_fields_unprocessed_et_pb_row', 'pac_drh_row_layout_fields');
if ( ! function_exists('pac_drh_row_layout_output')) {
    function pac_drh_row_layout_output($output, $render_slug, $module) {
        if (et_fb_is_enabled()) {
            return $output;
        }
        if ('et_pb_row' !== $render_slug) {
            return $output;
        }
        if (is_array($output)) {
            return $output;
        }
        // Desktop Row Width
        $epanel_desktop_width = et_get_option('pac_drh_row_width_desktop');
        $module_desktop_width = $module->props['width'];
        if ($module_desktop_width === $epanel_desktop_width) {
            $desktop_width = $epanel_desktop_width;
            ET_Builder_Element::set_style($render_slug, [
                'selector' => '%%order_class%%.et_pb_row',
                'declaration' => sprintf('width: %s; ', $desktop_width),
                'media_query' => ET_Builder_Element::get_media_query('min_width_981'),
            ]);
        }
        $epanel_desktop_max_width = et_get_option('pac_drh_row_max_width_desktop');
        $module_desktop_max_width = $module->props['max_width'];
        if ($module_desktop_max_width === $epanel_desktop_max_width) {
            $desktop_max_width = $epanel_desktop_max_width;
            if ( ! empty($desktop_max_width)) {
                ET_Builder_Element::set_style($render_slug, [
                    'selector' => '%%order_class%%.et_pb_row',
                    'declaration' => sprintf('max-width: %s; ', $desktop_max_width),
                    'media_query' => ET_Builder_Element::get_media_query('min_width_981'),
                ]);
            }
        }
        // Tablet Row Width
        $epanel_tablet_width = et_get_option('pac_drh_row_width_tablet');
        $module_tablet_width = $module->props['width_tablet'];
        if ($module_tablet_width === $epanel_tablet_width) {
            $tablet_width = $epanel_tablet_width;
            ET_Builder_Element::set_style($render_slug, [
                'selector' => '%%order_class%%.et_pb_row',
                'declaration' => sprintf('width: %s; ', $tablet_width),
                'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
            ]);
        }
        $epanel_tablet_max_width = et_get_option('pac_drh_row_max_width_tablet');
        $module_tablet_max_width = $module->props['max_width_tablet'];
        if ($module_tablet_max_width === $epanel_tablet_max_width) {
            $tablet_max_width = $epanel_tablet_max_width;
            if ( ! empty($tablet_max_width)) {
                ET_Builder_Element::set_style($render_slug, [
                    'selector' => '%%order_class%%.et_pb_row',
                    'declaration' => sprintf('max-width: %s; ', $tablet_max_width),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
                ]);
            }
        }
        // Phone Row Width
        $epanel_phone_width = et_get_option('pac_drh_row_width_phone');
        $module_phone_width = $module->props['width_phone'];
        if ($module_phone_width === $epanel_phone_width) {
            $phone_width = $epanel_phone_width;
            ET_Builder_Element::set_style($render_slug, [
                'selector' => '%%order_class%%.et_pb_row',
                'declaration' => sprintf('width: %s; ', $phone_width),
                'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
            ]);
        }
        $epanel_phone_max_width = et_get_option('pac_drh_row_max_width_phone');
        $module_phone_max_width = $module->props['max_width_phone'];
        if ($module_phone_max_width === $epanel_phone_max_width) {
            $phone_max_width = $epanel_phone_max_width;
            if ( ! empty($phone_max_width)) {
                ET_Builder_Element::set_style($render_slug, [
                    'selector' => '%%order_class%%.et_pb_row',
                    'declaration' => sprintf('max-width: %s; ', $phone_max_width),
                    'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
                ]);
            }
        }

        return $output;
    }
}
add_filter('et_module_shortcode_output', 'pac_drh_row_layout_output', 10, 3);
