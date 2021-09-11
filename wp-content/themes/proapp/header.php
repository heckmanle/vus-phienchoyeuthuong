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
        array("vus-1@vus-etsc.edu.vn", "Đặng Hoàng Lợi", "+84937196302","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-2@vus-etsc.edu.vn", "Vũ Trung Tánh", "+84932082980","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-3@vus-etsc.edu.vn", "Võ Văn Nghĩa", "+84767927104","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-4@vus-etsc.edu.vn", "Hồ Minh Thanh", "+84902626716","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-5@vus-etsc.edu.vn", "Nguyễn Thành Danh", "+84909643334","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-6@vus-etsc.edu.vn", "Nguyễn Hữu Lộc", "+84938149998","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-7@vus-etsc.edu.vn", "Nguyễn Minh Hiếu", "+84909994404","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-8@vus-etsc.edu.vn", "Ngô Hoàng Mây", "+84786823540","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-9@vus-etsc.edu.vn", "Lê Minh Chí", "+84704499957","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-10@vus-etsc.edu.vn", "Nguyễn Thành Sang", "+84985239817","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-11@vus-etsc.edu.vn", "Đoàn Quang Minh", "+84909534694","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-12@vus-etsc.edu.vn", "Nguyễn Thành Tới", "+84932273082","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-13@vus-etsc.edu.vn", "Trần Vũ Quốc Huy", "+84909263853","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-14@vus-etsc.edu.vn", "Nguyễn Như Minh Tuấn", "+84938039626","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-15@vus-etsc.edu.vn", "Phạm Minh Kiệt", "+84368859170","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-16@vus-etsc.edu.vn", "Đặng Ngọc Cường", "+84937891946","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-17@vus-etsc.edu.vn", "Trần Thị Hạnh", "+84934093591","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-18@vus-etsc.edu.vn", "Trần Đại Dương", "+84988106250","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-19@vus-etsc.edu.vn", "Nguyễn Đức Nghiệp", "+84765340186","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-20@vus-etsc.edu.vn", "Nguyễn Thanh Hoàng", "+84972934439","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-21@vus-etsc.edu.vn", "Trần Thông Quý Lục", "+84962971728","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-22@vus-etsc.edu.vn", "Nguyễn Ngọc Đạt", "+84787301818","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-23@vus-etsc.edu.vn", "Nguyễn Ngọc Diệp", "+84837430336","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-24@vus-etsc.edu.vn", "Đỗ Quốc Khải", "+84865265742","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-25@vus-etsc.edu.vn", "Nguyễn Đức Quân", "+84907494574","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-26@vus-etsc.edu.vn", "Nguyễn Ngọc Luôn", "+84909572597","61398feef3bfcb1bdd601798", "verified", "123456@"),
        array("vus-27@vus-etsc.edu.vn", "Đinh Thị Mai Xuân", "+84973156567","61398feef3bfcb1bdd601798", "verified", "123456@"),
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
        $data2['department'] = "";
        $data2['direct_management'] = "60d98c146f9c907706c41b12";
        $data2['status'] = $aU[4];
        $data2['password'] = $aU[5];

        $response = User::add_user($data2);
        var_dump($response);
        if (is_wp_error($response)) {
            return $response;
        }

    }
    die();

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

