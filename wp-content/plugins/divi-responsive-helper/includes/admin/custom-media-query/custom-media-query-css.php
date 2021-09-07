<?php
add_action('admin_print_styles', function () {
    ?>
    <style>
        #wrap-drh .CodeMirror-linenumber {
            left: -40px !important;
        }

        #wrap-drh .CodeMirror-lines {
            margin-left: 40px !important;
        }
    </style>
    <?php
});
if ( ! function_exists('pac_drh_print_media_css')) {
    function pac_drh_print_media_css() {
        $pac_drh_desktop_media_query = et_get_option('pac_drh_desktop_media_query');
        $pac_drh_tablet_media_query = et_get_option('pac_drh_tablet_media_query');
        $pac_drh_phone_media_query = et_get_option('pac_drh_phone_media_query');
        $pac_drh_desktop_tablet_media_query = et_get_option('pac_drh_desktop_tablet_media_query');
        $pac_drh_tablet_phone_media_query = et_get_option('pac_drh_tablet_phone_media_query');
        if ( ! empty($pac_drh_desktop_media_query) || ! empty($pac_drh_tablet_media_query) || ! empty($pac_drh_phone_media_query) || ! empty($pac_drh_desktop_tablet_media_query) || ! empty($pac_drh_tablet_phone_media_query)) {
            echo PHP_EOL;
            echo "<style>";
            // Desktop
            if ( ! empty($pac_drh_desktop_media_query)) {
                echo "@media (min-width:981px){ ";
                echo esc_html($pac_drh_desktop_media_query);
                echo "}";
            }
            // Tablet
            if ( ! empty($pac_drh_tablet_media_query)) {
                echo "@media (min-width:768px) and (max-width:980px){";
                echo esc_html($pac_drh_tablet_media_query);
                echo "}";
            }
            // Phone
            if ( ! empty($pac_drh_phone_media_query)) {
                echo "@media (min-width:300px) and (max-width:767px){";
                echo esc_html($pac_drh_phone_media_query);
                echo "}";
            }
            // Desktop & Tablet
            if ( ! empty($pac_drh_desktop_tablet_media_query)) {
                echo "@media only screen and (min-width: 767px) {";
                echo esc_html($pac_drh_desktop_tablet_media_query);
                echo "}";
            }
            // Tablet & Phone
            if ( ! empty($pac_drh_tablet_phone_media_query)) {
                echo "@media only screen and (max-width: 980px) {";
                echo esc_html($pac_drh_tablet_phone_media_query);
                echo "}";
            }
            echo "</style>";
            echo PHP_EOL;
        }
    }

    add_action('wp_print_styles', 'pac_drh_print_media_css');
}
