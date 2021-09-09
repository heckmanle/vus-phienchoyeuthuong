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

///////////////// TEST IMPORT ///////////
if(isset($_GET['import']) && $_GET['import'] == "OK") {

    $data2 = array();
    $arrayUsers = array(
        array("leluhien10@gmail.com", "Hien Test 10", "+84868101719", "60d98c136f9c907706c41b0d", "verified", "123456@"),
    );


    foreach ($arrayUsers as $aU) {

        //$data['department'] = ;
        $data2['id'] = "";
        $data2['email'] = $aU[0];
        $data2['name'] = $aU[1];
        $data2['phone'] = $aU[2];
        $data2['address'] = "";
        $data2['birthdate'] = "";
        $data2['role_title'] = "";
        $data2['avatar'] = "";
        $data2['note'] = "";
        $data2['roles'] = $aU[3];
        $data2['department'] = [];
        $data2['direct_management'] = "60d98c146f9c907706c41b12"; //Quản lý trực tiếp
        $data2['status'] = $aU[4];
        $data2['password'] = $aU[5];

        $response = \DIVI\Includes\Core\User::add_user($data2);
        var_dump($response);
        if (is_wp_error($response)) {
            return $response;
        }

    }

}//end if
///////////////// END TEST IMPORT ///////////


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

