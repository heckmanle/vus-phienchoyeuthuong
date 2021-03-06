<?php

namespace Authentication\Includes\Core;

use SME\API\API;
use SME\Includes\AJAX;
use SME\Includes\Core\User;

class Authentication
{
	public $page;
	public $pages = [];
	public $page_children = [];

	public $api = null;

	public function __construct()
	{
		add_action('init', [$this, 'init']);
		add_action('app/login_success', [$this, 'login_success']);
		add_action('app/logout', [$this, 'logout']);
		$this->hook_page_template();
		add_action('app/ajax/register_nopriv_actions', [$this, 'register_nopriv_actions']);
		add_action('app/ajax/register_actions', [$this, 'register_nopriv_actions']);
	}

	public function init(){
		$this->page = get_page_by_path(__('Authentication', AUTHENTICATION_LANG_DOMAIN));
		$this->init_page_children();
	}

	public function login_success($response){
		if( !is_user_logged_in() ) {
			$credentials = array(
				'user_login' => 'webmaster',
				'user_password' => '12345678@Ab!.',
				'remember' => true
			);

			$user = wp_signon($credentials, false);

			if (is_wp_error($user)) {
				echo $user->get_error_message();
			} else {
				$user_id = $user->ID;
			}
		}
	}

	public function logout(){
		$user_id = get_current_user_id();

		wp_destroy_current_session();
		wp_clear_auth_cookie();
		wp_set_current_user( 0 );
	}

	public function hook_page_template(){
		add_filter( 'page_template', [$this, 'page_template'] );
		add_filter( 'theme_page_templates', [$this, 'theme_page_templates'] );
		add_filter( 'template_include', [$this, 'template_include'] );
	}

	public function init_page_children(){
		$page_children['sub'] = [];
		$this->pages = $page_children;
	}

	public function page_template($page_template){
		return $page_template;
	}

	public function template_include($template){

		if (is_page()) {
			$meta = get_post_meta(get_the_ID());
			if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) {

				$_wp_page_template = $meta['_wp_page_template'][0];

				if( 'page-authentication.php' === $_wp_page_template ){
					$template = AUTHENTICATION_MODULE_DIR . 'frontend/pages/page-authentication.php';
				}
			}
		}
		return $template;
	}

	public function theme_page_templates($templates){
		$templates[AUTHENTICATION_PAGE] = __('Authentication', AUTHENTICATION_LANG_DOMAIN);
		return $templates;
	}

	public function register_nopriv_actions(AJAX $ajax){
		$ajax->register_ajax_action('authentication_login_email', [$this, 'authentication_login_email']);
		$ajax->register_ajax_action('authentication_check_password', [$this, 'authentication_check_password']);
		$ajax->register_ajax_action('authentication_login', [$this, 'authentication_login']);
		$ajax->register_ajax_action('authentication_send_code', [$this, 'ajax_authentication_send_code']);
		$ajax->register_ajax_action('authentication_forgotpass', [$this, 'ajax_authentication_forgotpass']);
		$ajax->register_ajax_action('authentication_verify_code', [$this, 'ajax_authentication_verify_code']);
		$ajax->register_ajax_action('authentication_reset_password', [$this, 'ajax_authentication_reset_password']);
        $ajax->register_ajax_action('get_publishGlobalSetting_logo', [$this, 'get_publishGlobalSetting_logo']);
	}

    public function get_publishGlobalSetting_logo(){
        global $system_api;

        $respone = [];
        $status_code = 404;
        $query = $system_api->query(
            'GET',
            'publishGlobalSetting',
            [
                'params' => ['key' => 'web_logo'],
                'fields' => [
                    'id',
                    'value'
                ]
            ], true

        );

        if(is_wp_error($query) || isset($query->errors)){
            $respone = new \WP_Error(403, __("Access deny"));
            $status_code = 403;
        }else {
            $respone = [
                'message' => 'success',
                'publishGlobalSetting' => $query->data->publishGlobalSetting,
            ];
            $status_code = 201;
        }

        return $respone;
    }

	public function authentication_login_email($data, $ajax){
		$ajax->verify_request('authentication_login_email');
		$username = isset($data['user_name']) ? $data['user_name'] : '';
		if( empty($username) ){
			return new \WP_Error(401, __('Vui l??ng nh???p ??i???n tho???i ho???c email'));
		}
		$response = User::check_account($username);
		if( is_wp_error($response) ){
			return $response;
		}
		$link = get_the_permalink($this->page);
		$_SESSION['username_type'] = is_email($username) ? 'email' : 'phone_number';
		$_SESSION['username'] = $username;
		setcookie('username', $username, time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
		return ['_wp_http_referer' => add_query_arg(['view' => 'login-password'], $link)];
	}

	public function authentication_check_password($data, $ajax){
		$ajax->verify_request('authentication_check_password');
		$password = isset($data['password']) ? $data['password'] : '';
		if( empty($password) ){
			return new \WP_Error(401, __('Vui l??ng nh???p m???t kh???u'));
		}
		$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
		$response = User::check_username_password($username, $password);
		if( is_wp_error($response) ){
			return $response;
		}
		if( isset($response['id']) ){
			$_SESSION['password'] = $data['password'];
			if( $_SESSION['username_type'] == 'email' ){
				$_SESSION['email'] = $_SESSION['username'];
			}else{
				$_SESSION['phone_number'] = $_SESSION['username'];
			}
		}
		$link = get_the_permalink($this->page);
		return ['_wp_http_referer' => add_query_arg(['view' => 'login-verifycode'], $link)];
	}

	public function ajax_authentication_send_code($data, $ajax){
		$type = isset($data['type']) ? $data['type'] : 'email';
		if( 'email' == $type ){
			$response = User::send_email_code($_SESSION['email']);
		}else{
			$_SESSION['verificationID'] = isset($data['verificationID']) ? $data['verificationID'] : '';
			$response = User::get_session_info($_SESSION['phone_number'], $_SESSION['verificationID']);
		}
		if( is_wp_error($response) ){
			return $response;
		}
	}

	public function authentication_login($data, $ajax){
		$ajax->verify_request('authentication_login');
		$password = isset($data['password']) ? $data['password'] : '';
		$two_fa = \DIVI\Includes\Core\GlobalSettings::publishGlobalSetting('2FA');
		if( !is_wp_error($two_fa) ){
			$two_fa = $two_fa['value'] ?? 'off';
		}else{
			$two_fa = 'off';
		}
		if( $two_fa == 'on' ) {
			if (isset($_SESSION['password'])) {
				$password = $_SESSION['password'];
			}
		}
		if( empty($password) ){
			return new \WP_Error(401, __('Vui l??ng nh???p m???t kh???u'));
		}
		if( $two_fa == 'on' ) {
			$code = isset($data['code']) ? $data['code'] : '';
			if (empty($code)) {
				return new \WP_Error(401, __('Vui l??ng nh???p m?? x??c nh???n'));
			}
			$type = isset($_SESSION['username_type']) ? $_SESSION['username_type'] : 'email';
			if ('email' == $type) {
				$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
				$response = User::check_code($email, $code);
			} else {
				$response = User::verify_phone_number($code, $_SESSION['verificationID']);
			}
			if (is_wp_error($response)) {
				return $response;
			}
		}
		$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
		$response = User::login($username, $password);
		if( is_wp_error($response) ){
			return $response;
		}
		unset($_SESSION['username']);
		unset($_SESSION['username_type']);
		unset($_SESSION['password']);
		$url = apply_filters('app/authentication/login_success_url', home_url());
		return ['_wp_http_referer' => $url];
	}

	public function ajax_authentication_forgotpass($data, $ajax){
		$type = isset($data['type']) ? $data['type'] : 'email';
		if( 'email' == $type ){
			$response = User::send_email_code($_SESSION['email']);
		}else{
			$_SESSION['verificationID'] = isset($data['verificationID']) ? $data['verificationID'] : '';
			$response = User::get_session_info($_SESSION['phone_number'], $_SESSION['verificationID']);
		}
		if( is_wp_error($response) ){
			return $response;
		}
		$_SESSION['forgot_type'] = $type;
		$link = get_the_permalink($this->page);
		return ['_wp_http_referer' => add_query_arg(['view' => 'login-verifycode'], $link)];
	}

	public function ajax_authentication_verify_code($data, $ajax){
		$ajax->verify_request('authentication_verify_code');
		$code = isset($data['code']) ? $data['code'] : '';
		if( empty($code) ){
			return new \WP_Error(401, __('Vui l??ng nh???p m?? x??c nh???n'));
		}
		$type = isset($_SESSION['forgot_type']) ? $_SESSION['forgot_type'] : 'email';
		if( 'email' == $type ) {
			$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
			$response = User::check_code($email, $code);
		}else{
			$response = User::verify_phone_number($code, $_SESSION['verificationID']);
		}
		if( is_wp_error($response) ){
			return $response;
		}
		$_SESSION['code'] = $code;
		$link = get_the_permalink($this->page);
		return ['_wp_http_referer' => add_query_arg(['view' => 'reset-password'], $link)];
	}

	public function ajax_authentication_reset_password($data, $ajax){
		$ajax->verify_request('authentication_reset_password');
		$password = isset($data['password']) ? $data['password'] : '';
		$re_password = isset($data['re_password']) ? $data['re_password'] : '';
		if( strlen($password) < 8 ){
			return new \WP_Error(401, __('M???t kh???u ??t nh???t 8 k?? t??? bao g???m ch??? v?? s???'));
		}
		if( $password != $re_password ){
			return new \WP_Error(401, __('Nh???p l???i m???t kh???u ph???i tr??ng v???i m???t kh???u'));
		}
		$type = isset($_SESSION['forgot_type']) ? $_SESSION['forgot_type'] : 'email';
		if( 'email' == $type ) {
			$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
			$response = User::set_password_forgot_by_email($email, $password, $re_password);
		}else{
			$phone = isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : '';
			$code = isset($_SESSION['code']) ? $_SESSION['code'] : '';
			$response = User::set_password_forgot_by_phone($phone, $code, $password, $re_password);
		}
		if( is_wp_error($response) ){
			return $response;
		}
		unset($_SESSION['username']);
		unset($_SESSION['email']);
		unset($_SESSION['forgot_type']);
		unset($_SESSION['phone_number']);
		unset($_SESSION['code']);
		$link = get_the_permalink($this->page);
		return ['_wp_http_referer' => add_query_arg(['view' => 'login-email'], $link)];
	}

}
