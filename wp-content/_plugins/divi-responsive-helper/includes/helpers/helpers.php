<?php
if ( ! function_exists('pac_drh_is_post_page')) {
    function pac_drh_is_post_page() {
        global $pagenow;
        if ('post.php' === $pagenow) {
            return true;
        }

        return false;
    }
}
// phpcs:disable
if ( ! function_exists('pac_drh_write_log')) {
    function pac_drh_write_log($log, $delete = false, $file_name = '') {
        if (empty($file_name)) {
            $file_name = debug_backtrace()[1]['function'];
        }
        if (file_exists(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$file_name.'.log') && $delete == true) {
            unlink(WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$file_name.'.log');
        }
        ini_set('error_log', WP_CONTENT_DIR.DIRECTORY_SEPARATOR.$file_name.'.log');
        if (is_array($log) || is_object($log)) {
            error_log(print_r($log, true));
        } else {
            error_log($log);
        }
    }
}
if ( ! function_exists('pac_drh_dd')) {
    function pac_drh_dd($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit;
    }
}
// phpcs:enable