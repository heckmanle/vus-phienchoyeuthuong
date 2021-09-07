<?php
$page = isset($_GET['view']) ? $_GET['view'] : '';
$dir = USERSMANAGEMENT_MODULE_DIR . '/frontend/pages/children/';
$file_name = $dir . $page . '.php';
if( file_exists($file_name) && is_readable($file_name) ) {
	wp_enqueue_script( 'usersmanagement-script', USERSMANAGEMENT_MODULE_URL . 'frontend/js/usersmanagement-public.js', array( 'jquery', 'backbone', 'underscore', 'cropper-js', 'range-js' ), USERSMANAGEMENT_VERSION_ENQUEUE, true );
    require_once $file_name;
}
