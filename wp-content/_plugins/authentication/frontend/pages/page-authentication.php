<?php

use DIVI\Includes\Core\User;

$page = isset($_GET['view']) ? $_GET['view'] : 'login-email';
$dir = AUTHENTICATION_MODULE_DIR . '/frontend/pages/children/';
$file_name = $dir . $page . '.php';
if( User::is_user_login() ){
	$url = apply_filters('app/authentication/login_success_url', home_url());
	wp_redirect($url);
	exit;
}
if( file_exists($file_name) && is_readable($file_name) ) {
	wp_enqueue_script('authentication', AUTHENTICATION_MODULE_URL . '/frontend/js/authentication.js', ['jquery', 'backbone', 'underscore', 'firebase', 'recaptcha'], '', true);
    require_once $file_name;
}
