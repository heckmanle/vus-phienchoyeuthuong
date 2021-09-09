<?php
namespace SME\Includes\Core;

class User
{
	private static $auth_cookie_name = 'auth';
	private static $token;
	private static $field_default = [
		'email',
		'name',
		'phone',
		'id',
		'address',
		'birthdate',
		'role_title',
		'avatar',
		'note',
        'department',
		'direct_management' => [
			'email',
			'name',
			'phone',
			'id',
			'address',
			'birthdate',
			'role_title',
			'avatar',
			'note',
			'status',
		],
		'status',
		'last_login'
	];
	public static function check_account($username){
		global $system_api;

		$response = $system_api->re_query('POST', 'checkAccount', [
			'params' => [
				'username' => $username,
			]
		]);
		return $response;
	}

	public static function check_username_password($username, $password){
		global $system_api;
		$response = $system_api->re_query('POST', 'login', [
			'params' => [
				'username' => $username,
				'password' => $password,
			],
			'fields' => [
				'token','id'
			]
		]);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response['login'];
	}

	public static function login($username, $password){
		global $system_api;
		$response = $system_api->re_query('POST', 'login', [
			'params' => [
				'username' => $username,
				'password' => $password,
			],
			'fields' => [
				'token','id'
			]
		]);
		if( !is_wp_error($response) ){
			$system_api->set_token_cookie($response['login'], true); // update true for cookie saving..
			do_action('app/login_success', $response['login']);
		}
		return $response;
	}

	public static function get_current(){
		global $system_api;
		$response = $system_api->re_query('get', 'profile', [
			'fields' => [
				'email',
				'name',
				'phone',
				'id',
				'address',
				'birthdate',
				'role_title',
				'avatar',
				'note',
				'roles' => [
					'role_name',
					'id',
				],
				'department',
				'direct_management' => [
					'email',
					'name',
					'phone',
					'id',
					'address',
					'birthdate',
					'role_title',
					'avatar',
					'note',
					'status',
				],
				'status',
			]
		], true);
		if( is_wp_error($response) ){
			return $response;
		}
		$response = (array)$response['profile'];
		return $response;
	}

	public static function is_user_login(){
		$user = self::get_current();
		return !is_wp_error($user) && !empty($user) ? true : false;
	}

	public static function get_phone_by_email($email){
		global $system_api;
		$response = $system_api->re_query('POST', 'getPhoneByEmail', [
			'params' => [
				'email' => $email,
			],
			'fields' => [
				'phone',
				'email'
			]
		]);
		if( is_wp_error($response) ){
			return $response;
		}
		$response = $response['getPhoneByEmail'];
		return $response;
	}

	public static function send_email_code($email){
		global $system_api;
		$response = $system_api->re_query('POST', 'sendEmailCode', [
			'params' => [
				'email' => $email,
			]
		]);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response;
	}

	public static function check_code($email, $code){
		global $system_api;
		$response = $system_api->re_query('POST', 'checkCode', [
			'params' => [
				'email' => $email,
				'code'  => $code,
			]
		]);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response;
	}

	public static function verify_phone_number($code, $sessionInfo){
		global $system_api;
		$response = $system_api->re_query('POST', 'verifyPhoneNumber', [
			'params' => compact('code', 'sessionInfo')
		]);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response;
	}

	public static function get_session_info($phone, $sessionInfo){
		global $system_api;
		$response = $system_api->re_query('POST', 'getSessionInfo', [
			'params' => compact('phone', 'sessionInfo')
		]);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response;
	}

	public static function set_password_forgot_by_email($email, $password, $confirm_password){
		global $system_api;
		$response = $system_api->re_query('POST', 'setPasswordForgot', [
			'params' => compact('email', 'password', 'confirm_password')
		]);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response;
	}

	public static function set_password_forgot_by_phone($phone, $code, $password, $confirm_password){
		global $system_api;
		$response = $system_api->re_query('POST', 'setPasswordForgotPhone', [
			'params' => compact('phone', 'code', 'password', 'confirm_password')
		]);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response;
	}

	public static function users($search = '', $status = '', $offset = 0, $limit = 0){
		global $system_api;
		$params = [];
		if( !empty($search) ){
			$params['search'] = $search;
		}
		if( !empty($status) ){
			$params['status'] = $status;
		}
		if( !empty($offset) ){
			$params['offset'] = $offset;
		}
		if( !empty($limit) ){
			$params['limit'] = $limit;
		}
		$fields = self::$field_default;
		$fields['roles'] = [
			'role_name', 'id'
		];
		$fields[] = 'registered';
		$args = [
			'fields' => $fields
		];
		if( !empty($params) ){
			$args['params'] = $params;
		}
		$response = $system_api->re_query('GET', 'users', $args, true);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response['users'];
	}

	public static function get_roles(){
		global $system_api;
		$response = $system_api->re_query('GET', 'roles', [
			'fields' => [
				'id',
				'role_name'
			],
		], true);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response['roles'];
	}

	public static function get_user_by_id($id){
		global $system_api;
		self::$field_default['roles'] = [
			'role_name',
			'id',
		];

		$response = $system_api->re_query('GET', 'user', [
			'params' => [
				'id' => $id
			],
			'fields' => self::$field_default,
		], true);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response['user'];
	}

	public static function has_role($user = null, $role){
		if( empty($user) ){
			$user = self::get_current();
		}
		$roles = isset($user['roles']) ? $user['roles'] : [];
		if( !empty($roles) ){
			if( !is_array($role) ){
				$role = [$role];
			}
			$role = array_map('mb_strtolower', $role);
			foreach ($roles as $k => $item){
				if( in_array(mb_strtolower($item['role_name']), $role) ){
					return true;
				}
			}
		}
		return false;
	}

	public static function add_user($data){
	    var_dump('----0');
		$defaults = [
			'id' => '',
			'email' => '',
			'name' => '',
			'phone' => '',
			'address' => '',
			'birthdate' => '',
			'role_title' => '',
			'avatar' => '',
			'note' => '',
			'roles' => '',
            'department' => '',
			'direct_management' => '',
			'status' => '',
			'password' => '',
		];

		$data = wp_parse_args($data, $defaults);
		extract($data);
		$fields = [
			'email',
			'name',
			'phone',
			'address',
			'birthdate',
			'role_title',
			'avatar',
			'note',
			'roles',
            'department',
			'direct_management',
			'status',
		];
		if( !empty($data['password']) ){
			$fields[] = 'password';
		}

		if( !empty($data['id']) ){
            var_dump('----0-ID');
			$users = self::users();
			if( !empty($users) ){
				$filter = array_filter($users, function($item) use($email, $id){
					return $email == $item['email'] && $id != $item['id'];
				});
				if( $filter ){
					return new \WP_Error(401, __('Email đã được sử dụng'));
				}else{
					//unset($fields[0]);
				}
				$filter = array_filter($users, function($item) use($phone, $id){
					return $phone == $item['phone'] && $id != $item['id'];
				});
				if( $filter ){
					return new \WP_Error(401, __('Điện thoại đã được sử dụng'));
				}else{
					//unset($fields[2]);
				}
			}
			$func = 'updateUser';
			$fields[] = 'id';
		}else{
            var_dump('----0-NOT-ID');
			$func = 'addUser';
			$check_account = self::check_account($email);
			if( true === $check_account ){
				return new \WP_Error(401, __('Email đã được sử dụng'));
			}
			$check_account = self::check_account($phone);
			if( true === $check_account ){
				return new \WP_Error(401, __('Điện thoại đã được sử dụng'));
			}
		}
        var_dump('----1xxxx');
		$compact = compact($fields);
		global $system_api;

        var_dump($compact."||");

		$response = $system_api->re_query('POST', $func, [
			'params' => $compact,
			'fields' => self::$field_default,
		], true);

        var_dump($response);

		if( is_wp_error($response) ){
			$response = self::convert_message_error($response);
			return $response;
		}
		return $response[$func];
	}

	public static function delete_user($user_id){
		global $system_api;
		$response = $system_api->re_query('POST', 'deleteUser', [
			'params' => compact('user_id'),
		], true);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response['deleteUser'];
	}

	private static function convert_message_error($error){
		$message = $error->get_error_message();
		if( preg_match('/(email_1 dup key:)/i', $message) ){
			$response = new \WP_Error(409, __('Email đã bị được sử dụng.'));
		}
		if( preg_match('/(phone_1 dup key:)/i', $message) ){
			$response = new \WP_Error(409, __('Điện thoại đã bị được sử dụng.'));
		}
		return $response;
	}
}

