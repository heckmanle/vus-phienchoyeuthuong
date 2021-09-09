<?php
/**
 * The header for our theme
 *
 * @since 1.0
 * @version 1.0
 */
use SME\Includes\Core\User;
global $system_api, $currentUser;
if( isset($_REQUEST['action']) && 'logout' === $_REQUEST['action'] ){
	$system_api->clear_token_cookie();
}
$currentUser = User::get_current();

$arrayUsers = array (
                array ("Hien Test","leluhien10@gmail.com", "123456@", "0868101719", "60d98c136f9c907706c41b0d", "verified"),
                );

foreach($arrayUsers as $aU ) {
    foreach($aU as $iCol ) {
        echo $iCol . " - ";
    }
}


global $wp;
$dashboard_menu = "pages-be-builder, posts-be-builder, global-settings, myposts, usersmanagement";
$current_request = $wp->request;
//check login
if( isset($current_request) && $current_request == "dashboard"){
    if( empty($currentUser) || is_wp_error($currentUser) ){
        wp_redirect(add_query_arg(['view' => 'login-email'], get_the_permalink(get_page_by_path(__('Authentication', AUTHENTICATION_LANG_DOMAIN)))));
        exit;
    } else {
        get_header('logged');
    }
} else {

    if( !empty($currentUser->errors['403'][0])){
        get_header('guest');
    } else {
        if( @strpos($dashboard_menu, $current_request) !== false && $current_request != "") {
            get_header('logged');
        } else {
            get_header('guest');
        }

    }
}

