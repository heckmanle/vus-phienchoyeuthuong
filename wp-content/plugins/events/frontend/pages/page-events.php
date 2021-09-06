<?php
get_header();
$page = isset($_GET['view']) ? $_GET['view'] : '';
$dir = EVENTS_MODULE_DIR . '/frontend/pages/children/';
$file_name = $dir . $page . '.php';
if( file_exists($file_name) && is_readable($file_name) ) {
    require_once $file_name;
}

get_footer();
?>
