<?php


$page = isset($_GET['view']) ? $_GET['view'] : '';
$dir = BOOKINGSERVICES_MODULE_DIR . '/frontend/pages/children/';
$file_name = $dir . $page . '.php';
if( file_exists($file_name) && is_readable($file_name) ) {
	if( $page != 'print' ) {
		get_header();
		get_sidebar('left');
	}
	require_once $file_name;
	if( $page != 'print' ) {
		get_footer();
	}
}

