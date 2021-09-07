<?php
require_once PAC_DRH_PATH."/includes/lib/Mobile_Detect.php";
if ( ! function_exists('pac_drh_enable_menu_responsive')) {
    function pac_drh_enable_menu_responsive($fields_unprocessed) {
        $fields_unprocessed['menu_id']['mobile_options'] = true;

        return $fields_unprocessed;
    }

    add_filter('et_pb_all_fields_unprocessed_et_pb_menu', 'pac_drh_enable_menu_responsive');
}
if ( ! function_exists('pac_drh_menu_db_handler')) {
    function pac_drh_menu_db_handler($data, $postarr) {
        $content = stripcslashes($data['post_content']);
        if (has_shortcode($content, 'et_pb_menu')) {
            $menu_id_tablet = null;
            $menu_id_phone = null;
            $pattern = '/\S+=(["\'])(?:(?=(\\\\?))\2.)*?\1/m';
            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER, 0);
            foreach ($matches as $r_match) {
                if (isset($r_match[0]) && ! empty($r_match[0])) {
                    if (strpos($r_match[0], 'menu_id_last_edited="on|desktop"') !== false || strpos($r_match[0], 'menu_id_last_edited="on|tablet"') !== false || strpos($r_match[0],
                            'menu_id_last_edited="on|phone"') !== false) {
                        foreach ($matches as $m_match) {
                            if (isset($m_match[0]) && ! empty($m_match[0])) {
                                if (strpos($m_match[0], 'menu_id_tablet') !== false) {
                                    $menu_id_tablet = (int)filter_var($m_match[0], FILTER_SANITIZE_NUMBER_INT);
                                }
                                if (strpos($m_match[0], 'menu_id_phone') !== false) {
                                    $menu_id_phone = (int)filter_var($m_match[0], FILTER_SANITIZE_NUMBER_INT);
                                }
                            }
                        }
                    }
                }
            }
            if ( ! is_null($menu_id_tablet)) {
                update_option('pac_drh_menu_tablet', $menu_id_tablet);
            } else {
                delete_option('pac_drh_menu_tablet');
            }
            if ( ! is_null($menu_id_phone)) {
                update_option('pac_drh_menu_phone', $menu_id_phone);
            } else {
                delete_option('pac_drh_menu_phone');
            }
        }

        return $data;
    }

    add_filter('wp_insert_post_data', 'pac_drh_menu_db_handler', 10, 2);
}
if ( ! function_exists('pac_drh_responsive_menu_filter')) {
    function pac_drh_responsive_menu_filter($menu_args) {
        $mobile_detect = new Mobile_Detect;
        $pac_drh_menu_tablet = get_option('pac_drh_menu_tablet');
        if ( ! empty($pac_drh_menu_tablet)) {
            if ($mobile_detect->isTablet()) {
                $menu_args['menu'] = intval($pac_drh_menu_tablet);

                return $menu_args;
            }
        }
        $pac_drh_menu_phone = get_option('pac_drh_menu_phone');
        if ( ! empty($pac_drh_menu_phone)) {
            if ($mobile_detect->isMobile()) {
                $menu_args['menu'] = intval($pac_drh_menu_phone);

                return $menu_args;
            }
        }

        return $menu_args;
    }

    add_filter('et_menu_args', 'pac_drh_responsive_menu_filter');
}
