<?php

namespace Usersmanagement\Includes\Core;

use SME\Inc\UploadFile;
use SME\Includes\AJAX;
use SME\Includes\Core\User;

class Usersmanagement
{
    public $page;
    public $pages = [];
    public $page_children = [];

    public function __construct()
    {
        add_action('init', [$this, 'init']);
	    add_action('app/ajax/register_nopriv_actions', [$this, 'register_nopriv_actions']);
	    add_action('app/ajax/register_actions', [$this, 'register_nopriv_actions']);
        $this->hook_page_template();
    }

    public function init(){
        $this->page = get_page_by_path(__('Usersmanagement', USERSMANAGEMENT_LANG_DOMAIN));
        $this->init_page_children();
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

                if( 'page-usersmanagement.php' === $_wp_page_template ){
                    $template = USERSMANAGEMENT_MODULE_DIR . 'frontend/pages/page-usersmanagement.php';
                }
            }
        }
        return $template;
    }

    public function theme_page_templates($templates){
        $templates[USERSMANAGEMENT_PAGE] = __('Usersmanagement', USERSMANAGEMENT_LANG_DOMAIN);
        return $templates;
    }
    function register_nopriv_actions(AJAX $ajax){
		$ajax->register_ajax_action('handle_user', [$this, 'handle_user']);
		$ajax->register_ajax_action('usermng_delete', [$this, 'handle_delete']);
	}

	 function handle_user($data, $ajax){
		$ajax->verify_request('handle_user');
		$file_upload = isset($_FILES['file_upload']) ? $_FILES['file_upload'] : [];
		$avatar = '';
		$user = [];
		if( isset($data['uid']) && !empty($data['uid']) ){
			$user = User::get_user_by_id($data['uid']);
			if( is_wp_error($user) ){
				return $user;
			}
			$avatar = $user['avatar'];
			$data['id'] = $data['uid'];
		}
		if( !empty($file_upload) ){
			$mimes = [
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif' => 'image/gif',
				'png' => 'image/png',
				'bmp' => 'image/bmp',
			];
			$file = new UploadFile($file_upload, $mimes);
			$avatar = $file->save_file();
			if( is_wp_error($avatar) ){
				return $avatar;
			}
		}
        $data['department'] = $data['department'] ?? '';
		$data['direct_management'] = $data['manager'];
		$data['roles'] = $data['role'];

		$data['avatar'] = $avatar;
		if( array_key_exists('status', $data) ){
			$data['status'] = 'verified';
		}else{
			$data['status'] = 'not verified';
		}
		$phone_code = isset($data['phone_code']) ? trim($data['phone_code']) : '';

		$phone = isset($data['phone']) ? trim($data['phone']) : '';

		if( empty($phone) ){
			return new \WP_Error(409, __('Số điện thoại không được để trống'));
		}

		$phone = preg_replace('/^(0)/', '', $phone);

		$data['phone'] = "{$phone_code}{$phone}";
		$password = isset($data['password']) ? $data['password'] : '';
		$confirm_password = isset($data['confirm_password']) ? $data['confirm_password'] : '';
		if( !empty($password) && $password != $confirm_password ){
			return new \WP_Error(409, __('Nhập lại mật khẩu phải trùng với mật khẩu'));
		}

		$response = User::add_user($data);
		if( is_wp_error($response) ){
			return $response;
		}
		$link = get_the_permalink($this->page);
		return ['_wp_http_referer' => add_query_arg(['view' => 'profile', 'uid' => $response['id']], $link)];

	}

	public function handle_delete($data, $ajax){
		$ajax->verify_request('usermng_delete');
		$id = isset($data['id']) ? $data['id'] : [];
		if( !empty($id) && is_array($id) ){
			foreach ($id as $uid){
				$response = User::delete_user($uid);
			}
		}
		$users = \SME\Includes\Core\User::users();
		$users = self::get_row_array($users);
		return ['message' => __('Thành công'), 'users' => $users];

	}

	public static function get_row_array($list){

    	$output = [];
    	if( !empty($list) ){
		    $page_user = get_page_by_path(__('Usersmanagement', USERSMANAGEMENT_LANG_DOMAIN));
		    $link = get_the_permalink($page_user);
    		foreach ($list as $item){
			    $role_name = '';
			    if(count($item["roles"]) > 0){
                    $role_name = $item["roles"][0]["role_name"];
                }
			    if($item["avatar"] != null) {
				   $avatar = '<img class="rounded-circle" src="' . $item["avatar"] . '" alt="" width="39" height="39">';
			    }else{
				    $avatar = '<img class="rounded-circle" src="' . USERSMANAGEMENT_MODULE_URL . 'images/default.jpg" alt="" width="39" height="39">';
			    }
			    $statusName="Người dùng mới";
			    if($item["status"] == "verified") {
				    $statusName="Đang hoạt động";
			    } else {
				    $statusName="Ngưng hoạt động";
			    }
			    $data = [];
			    $profile_link = add_query_arg(['view' => 'profile', 'uid' => $item['id']], $link);
    			$data[] = "<input type=\"checkbox\" class=\"check-row\" name=\"uid[]\" value=\"{$item['id']}\">";
			    $data[] = "
                    <a href=\"{$profile_link}\">
                        <div class=\"widget-content p-0\">
                            <div class=\"widget-content-wrapper\">
                                <div class=\"widget-content-left mr-3\">
                                    <div class=\"widget-content-left\">
                                        {$avatar}
                                    </div>
                                </div>
                                <div class=\"widget-content-left flex2\">
                                    <div class=\"widget-heading user_name\">{$item["name"]}</div>
                                    <div class=\"widget-subheading opacity-7\">
                                        {$role_name}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
			    ";

                $data[] = $item["department"];
			    $data[] = $item["phone"];
			    $data[] = $item["email"];
			    $data[] = $item["address"];
			    $data[] = date("d/m/Y", ceil($item["registered"] / 1000) );
			    $data[] = $statusName;
			    $output[] = $data;
		    }
	    }
    	return $output;
	}
}
