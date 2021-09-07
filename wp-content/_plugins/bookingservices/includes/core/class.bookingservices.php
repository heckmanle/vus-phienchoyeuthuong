<?php
namespace ClassBookingServices\Includes\Core;

use SME\Frames\Frames;
use SME\Inc\TemplateTWIG;
use SME\Inc\UploadFile;
use SME\Includes\AJAX;
use SME\Includes\Core\Client;
use SME\Includes\Core\Event;
use SME\Includes\Core\User;

class BookingServices extends Frames{
    public function __construct()
    {
        parent::__construct();
        $this->init_hook();
    }

    function init_hook(){
        add_action('app/ajax/register_nopriv_actions', [$this, 'register_nopriv_actions']);
    }

    function register_nopriv_actions(AJAX $ajax){
        $ajax->register_ajax_action('register_bookingservices', [$this, 'register_bookingservices']);
        $ajax->register_ajax_action('accuracy_information', [$this, 'accuracy_information']);
        $ajax->register_ajax_action('get_once_bookingservices', [$this, 'get_once']);
        $ajax->register_ajax_action('delete_bookingservices', [$this, 'delete_bookingservices']);
    }

    function send_email_booking($id = '', $to = '', $title = '', $body = ''){
        if(!empty($to)){
            global $system_api;
            $sendmail = $system_api->re_query(
                'POST',
                'sendEmailNotification',
                [
                    'params' => ['id' => $id, 'title' => $title, 'body' => $body, 'to' => $to],
                ], true
            );
            if(is_wp_error($sendmail) ){
                return true;
            }else {
                return true;
            }
        }else{
            return false;
        }
    }

    function delete_bookingservices($params){
        $bookingservices = isset($params['id']) ? $params['id'] : [];
        global $system_api;
        $data_success = [];
        $data_false = [];
        if(!empty($bookingservices)){
            foreach ($bookingservices as $id){
                $delete = $system_api->query(
                    'POST',
                    'deleteRegistrationForm',
                    [
                        'params' => ['id' => $id],
                    ], true
                );
                if(is_wp_error($delete) || isset($delete->errors)){
                    $data_false[] = $id;
                }else {
                    $data_success[] = $id;
                }
            }
	        $get_list = json_decode($this->get_list(), true);
	        $booking_list = $get_list['booking_list'];
	        $booking_list = self::get_row_array($booking_list);
	        return ['list_data' => $booking_list];
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

	public static function get_row_array($list){
    	global $currentUser;
		$output = [];
		if( !empty($list) ){
			foreach ($list as $item){
				$data = [];
				$data_send = [
					'func' => 'get_once_bookingservices',
					'action' => "handle_ajax",
					'id' => $item['id'],
				];
				if( \SME\Includes\Core\User::has_role($currentUser, ['admin']) ) {
					$data[] = "<input type=\"checkbox\" class=\"check-row\" name=\"bookingservices[]\" value=\"{$item['id']}\">";
				}
				$data[] = "<a href=\"javascript:;\" data-send=\"" . esc_json_attr($data_send) . "\" class=\"btn-icon-split btn-sm opendetail get-once\" data-toggle=\"modal\" data-target=\"#modal-booking-service\">
                                                        " . $item["votes"] . "
                                                    </a>";
				$data[] = $item["author"]["name"];
				$data[] = $item["client"]["name"];
				if($item['status'] == "draft") {
					$status_name = "Chờ xác nhận";
					$ctatus="bg-warning";
					$percenStatus='68%';
				}
				if($item['status'] == "pending-draft") {
					$status_name = "Chờ triển khai";
					$ctatus="";
					$percenStatus='80%';
				}
				if($item['status'] == "approve") {
					$status_name = "Đã xác nhận";
					$ctatus="bg-success";
					$percenStatus='68%';
				}
				if($item['status']== "done") {
					$ctatus="bg-success bg-done";
					$status_name = "Hoàn tất";
					$percenStatus='100%';
				}
				$data[] = "<div class=\"progress \">
                                                        <div class=\"progress-bar " . $ctatus . "\" role=\"progressbar\" style=\"width: " . $percenStatus . "; padding-left:6px;\" aria-valuenow=\"80\" aria-valuemin=\"0\" aria-valuemax=\"100\">
                                                            " . $status_name . "
                                                        </div>
                                                    </div>";
				$data[] = $item["date"];
				$output[] = $data;
			}
		}
		return $output;
	}

    function get_once($params){
        global $system_api;
        $booking = [];
        $id = isset($params['id']) ? $params['id'] : '';
        $query = $system_api->re_query(
            'GET',
            'registrationForm',
            [
                'params' => ['id' => $id],
                'fields' => [
                    'id',
                    'date',
                    'votes',
                    'booth',
                    'discount',
                    'vat',
                    'payment_type',
                    'into_money',
                    'per_charge',
                    'client_type',
                    'status',
                    'image_confirm',
                    'client' => [
                    	'id',
	                    'avatar',
	                    'address',
	                    'phone',
	                    'name',
	                    'client_id',
                        'client_type',
                        'contact_person_information' => [
                            'name',
                            'phone',
                        ],
                    ],
                    'events' => [
                    	'id',
	                    'title',
	                    'start_date',
	                    'location_diagram' => [
		                    'area' => [
			                    'location',
			                    'status',
			                    'did'
		                    ],
	                    ],
                    ],
                    'author' => [
                    	'name',
	                    'email',
	                    'id',
	                    'address',
	                    'phone'
                    ],
                    'list_of_equipment' => [
	                    'product' => [
		                    'id',
		                    'product_code',
		                    'product_title',
		                    //'product_price',
                            'product_pay',
		                    'product_unit',
	                    ],
	                    'quantity',
	                    'into_money',
	                    'image',
                    ],
                ]
            ], true
        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $booking = $query['registrationForm'];
        }
        return $booking;
    }

    function accuracy_information($params){
        $respone = [];
        global $system_api;
        if(!empty($params)) {
            $data['id'] = isset($params['id']) ? $params['id'] : '';
            $data['status'] = 'pending-draft';

            $product_field = $system_api->parseFields(['product_title']);
            $list_of_equipment_fields = $system_api->parseFields(['quantity', 'product'.$product_field, 'into_money']);
            $client_fields = $system_api->parseFields(['name', 'phone', 'email']);
            $event_field = $system_api->parseFields(['title']);

            $update = $system_api->query(
                'POST',
                'updateRegistrationForm',
                [
                    'params' => $data,
                    'fields' => [
                        'id',
                        'booth',
                        'votes',
                        'date',
                        'vat',
                        'into_money',
                        'payment_type',
                        'discount',
                        'list_of_equipment'.$list_of_equipment_fields,
                        'client'.$client_fields,
                        'events'.$event_field,
                        ]
                ], true
            );
            if(is_wp_error($update) || isset($update->errors)){
                $respone = new \WP_Error(403, __("Xác thực thất bại"));
            }else {

                $data_return = $update->data->updateRegistrationForm;
                $to = $data_return->client->email;
                $body['votes'] = $data_return->votes;
                $body['date'] = $data_return->date;
                $body['vat'] = $data_return->vat;
                $body['discount'] = $data_return->discount;
                $body['event_title'] = $data_return->events->title;
                $body['client_name'] = $data_return->client->name;
                $body['into_money'] = $data_return->into_money;
                $body['payment_type'] = $data_return->payment_type;

                foreach ($body as $key => $item){
                    $body[$key] = '<p>'.$item.'</p>';
                }

                $sendmail = $this->send_email_booking($to, 'Test send mail phieu dang ky', implode(' ', $body));

                $respone = ['message' => 'success', 'data' => $data_return];
            }
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

    function register_bookingservices($params){
        global $system_api, $core_inventory;
        $list_of_equipment = [];
	    $get_products = $core_inventory->get_list();
	    $get_products = array_group_by($get_products, 'id');
	    $get_products = array_map(function ($it){ return array_shift($it); },$get_products);

	    $get_events = Event::get_events();
	    $start_date_event = $get_events[0]['start_date'];
	    $get_events = array_group_by($get_events, 'id');
	    $get_events = array_map(function ($it){ return array_shift($it); },$get_events);

	    //$event = $get_events[$params['events']];

	    $get_clients_original = Client::clients();

	    $get_clients = array_group_by($get_clients_original, 'id');
	    $get_clients = array_map(function ($it){ return array_shift($it); }, $get_clients);

	    $id = isset($params['id']) && !empty($params['id']) ? $params['id'] : '';
	    $total = 0;
	    $vote_notification = '';
        if(!empty($params)) {
            $products = isset($params['products']) ? $params['products'] : [];
            if (!empty($products)) {
                foreach ($products as $key => $product) {
                	$_id = isset($product['id']) ? $product['id'] : '';
                	$quantity = isset($product['quantity']) ? core_convert_number_to_syntax($product['quantity']) : 1;
                	//$into_money = isset($get_products[$_id]) ? $get_products[$_id]->product_price : 0;
                    $into_money = isset($get_products[$_id]) ? $get_products[$_id]->product_pay : 0;
	                $list_of_equipment[] = [
		                'quantity' => (int)$quantity,
		                'product' => $_id,
		                'into_money' => (float)$into_money,
	                ];
	                $total += $into_money * (int)$quantity;
                }
            }

            $status = 'draft';
            if( array_key_exists('rollback', $params) ){
	            $status = 'approve';
            }elseif(array_key_exists('completed', $params)){
            	$status = 'done';
            }

            $flag = 'booking';
            extract($params);
            $date = isset($params['date']) ? $params['date'] : current_time('d/m/Y');
            $data = compact(['client', 'events', 'date', 'votes', 'booth', 'discount', 'vat', 'payment_type', 'status', 'list_of_equipment', 'flag', 'per_charge', 'total_ticket']);


	        $data['vat'] = isset($data['vat']) ? (float)$data['vat'] : 0.0;
	        $data['discount'] = isset($data['discount']) ? (float)$data['discount'] : 0.0;

	        $total = $total + ($total * $data['vat'] / 100) - ($total * $data['discount'] / 100);
            $data['into_money'] = $total;

//            $data['per_charge'] = '0';
//            $data['total_ticket'] = $total;

            $function_handle = 'addRegistrationForm';
	        $currentUser = User::get_current();
            if(!empty($id)){
            	unset($data['list_of_equipment']);
                $function_handle = "updateRegistrationForm";
                $get_once = $this->get_once(['id' => $id]);
                $vote_notification = $get_once['votes'];
                if( !empty($get_once) ){
                	$data['client'] = $get_once['client']['id'];
                	$data['events'] = $get_once['events']['id'];
                	$data['discount'] = $get_once['discount'];
                	$data['vat'] = $get_once['vat'];
                }
                $data['id'] = $id;
                if( User::has_role($currentUser, ['hh', 'admin', 'kt secc']) ){
	                $image = '';
                	if( isset($_FILES['image_upload']) ){
		                $mimes = [
			                'jpg|jpeg|jpe' => 'image/jpeg',
			                'gif' => 'image/gif',
			                'png' => 'image/png',
			                'bmp' => 'image/bmp',
		                ];
		                if( !empty($_FILES['image_upload']) ) {
			                $file = new UploadFile($_FILES['image_upload'], $mimes);
			                $image = $file->save_file();
			                if (is_wp_error($image)) {
				                return $image;
			                }
		                }else{
			                $image = isset($params['image_confirm']) ? $params['image_confirm'] : '';
		                }
	                }
                	$data['image_confirm'] = $image;
                }
            }else{
	            //$data['date'] = current_time('d/m/Y');
	            $random_votes = get_option('booking_random_votes', 0);
	            $random_votes = $random_votes + 1;
	            $data['votes'] = $random_votes < 1000 ? "PO-000{$random_votes}" : "PO-{$random_votes}";
                $vote_notification = $data['votes'];
	            if( empty($data['events']) ){
		            return new \WP_Error(401, __('Chọn tên triển lãm'));
	            }
	            if( empty($data['client']) ){
		            return new \WP_Error(401, __('Chọn khách hàng'));
	            }
	            if( !isset($get_events[$data['events']]) ){
		            return new \WP_Error(401, __('Không tìm thấy triển lãm.'));
	            }
	            if( !\SME\Includes\Core\User::has_role($currentUser, ['sale', 'admin']) ) {
		            $list_client = array_filter($get_clients_original, function ($item) use ($currentUser) {
			            $contact_person_informations = array_column($item['contact_person_informations'], 'id');
			            $contact_person_informations = array_values($contact_person_informations);

			            return in_array($currentUser['id'], $contact_person_informations);
		            });
	            }else{
	            	$list_client = $get_clients_original;
	            }
	            $list_client_id = array_column($list_client, 'id');
	            if( !in_array($data['client'], $list_client_id) ){
		            return new \WP_Error(401, __('Không tìm thấy khách hàng.'));
	            }
            }

            $date_time = strtotime(site_format_dmy_to_ymd($date));
            $event = $get_events[$data['events']];
            $event_start_date = $event['start_date'];
            $event_start_date_time = strtotime(site_format_dmy_to_ymd($event_start_date));
			$data['per_charge'] = 0;
            if( $event_start_date_time - $date_time <= WEEK_IN_SECONDS * 2 && $event_start_date_time - $date_time > WEEK_IN_SECONDS ){
                $data['per_charge'] = 10;
            }elseif($event_start_date_time - $date_time <= WEEK_IN_SECONDS && $event_start_date_time - $date_time > DAY_IN_SECONDS ){
                $data['per_charge'] = 20;
            }elseif( $event_start_date_time - $date_time == DAY_IN_SECONDS ){
                $data['per_charge'] = 30;
            }

            if(isset($get_clients[$data['client']]['client_type'])){
                $data['client_type'] = $get_clients[$data['client']]['client_type'];
            }
			$data['per_charge'] = "{$data['per_charge']}";
            $insert = $system_api->re_query(
                'POST',
                $function_handle,
                [
                    'params' => $data,
                    'fields' => [
                        'id',
                        'votes',
                        'booth',
                        'list_of_equipment' => [
	                        'product' => [
	                        	'id',
		                        'product_code',
		                        'product_title',
		                        'product_unit',
                                'product_pay',
	                        ],
							'quantity',
							'into_money',
                        ],
                        'client' => [
	                        'name', 'phone', 'id'
                        ],
                    ]
                ], true
            );
            if (is_wp_error($insert) || isset($insert->errors)) {
                $respone = new \WP_Error(403, __("Cập nhật thất bại"));
            } else {
            	if( $function_handle === 'addRegistrationForm' ) {
            		$this->mail_booking($insert[$function_handle]);
            		$this->send_notification('Yêu cầu xác nhận - Phiếu đăng ký ' . $vote_notification, ['sale']);
		            update_option('booking_random_votes', $random_votes);
	            }
            	if(array_key_exists('rollback', $params) || array_key_exists('completed', $params) ){
            		$action = array_key_exists('completed', $params) ? 'completed' : 'rollback';
		           /* $args['note'] = isset($params['note']) ? $params['note'] : '';
		            $args['mailto'] = isset($params['mailto']) ? $params['mailto'] : '';*/

		            if(array_key_exists('rollback', $params)){
		                $args['mailto'] = ['hh', 'kt secc'];
                        $this->mail_booking($insert[$function_handle], $action, $args);
                        $this->send_notification('Xác nhận - Phiếu đăng ký ' . $vote_notification, ['hh', 'kt secc']);
                    }elseif(array_key_exists('completed', $params)){
                        $args['mailto'] = ['sale', 'btc'];
                        $this->mail_booking($insert[$function_handle], $action, $args);
                        $this->send_notification('Hoàn thành - Phiếu đăng ký ' . $vote_notification, ['sale"', '"btc']);
                    }
	            }

                $respone = ['message' => 'success', 'data' => $insert[$function_handle]];
            }
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

    function token_fcm_of_user(){
        global $system_api;
        $token_fcm = '';
        $query = $system_api->query(
            'GET',
            'profile',
            [
                'fields' => [
                    'token_fcm'
                ]
            ], true

        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $token_fcm = $query->data->profile->token_fcm;
        }
        return $token_fcm;
    }

    function get_users_by_role_name($role_name){
        global $system_api;
        $users = [];
        $query = $system_api->query(
            'GET',
            'users',
            [
                'params' => ['role_name' => $role_name],
                'fields' => [
                    'email'
                ]
            ], true

        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $users = $query->data->users;
        }
        return $users;
    }

    function send_notification($body, $role = []){
        if(!empty($role)) {
            global $system_api;
            /*$id_receiver = $system_api->get_id_cookie();
            $fcm_tokens = $this->token_fcm_of_user();
            if (!empty($fcm_tokens)) {
                $fcm_tokens = implode(',', $fcm_tokens);
            }else{
                $fcm_tokens = '';
            }*/
            $title_notification = "SME";
            $sendnotification = $system_api->query_for_body(
                'POST',
                'sendNotificationToDepartment',
                [
                    'params' => [
                        'title' => $title_notification,
                       /* 'fcm_tokens' => (object)['data' => '"'.$fcm_tokens.'"', 'array' => true],*/
                        'body' => $body,
                       /* 'id_receiver' => (object)['data' => '"'.$id_receiver.'"',
                        'array' => true],*/
                        'to' => (object)['data' => '"'.implode(',', $role).'"', 'array' => true]],
                    'fields' => [
                        'errors'
                    ]
                ], true
            );
            if(is_wp_error($sendnotification) ){
                return true;
            }else {
                return true;
            }
        }else{
            return false;
        }
    }

    public function mail_booking($data, $action = '', $args = []){
	    $users = \SME\Includes\Core\User::users();
    	if( empty($action) || $action == 'completed' ) {
		    $roles = ['sale'];
	    }else{
		    $note = isset($args['note']) ? $args['note'] : '';
		    $mailto = isset($args['mailto']) ? $args['mailto'] : '';
		    $users = array_filter($users, function ($it) use ($mailto) {
			    return $it['id'] == $mailto;
		    });
    		$data['note'] = $note;
		    $roles = ['hh', 'kt secc'];
	    }
	    $emails = "";
    	if(!empty($users)){
		    $get_users = array_filter($users, function ($it) use ($roles) {
			    return \SME\Includes\Core\User::has_role($it, $roles);
		    });
		    if( !empty($get_users) ) {
			    $emails = array_column($get_users, 'email');
			    $emails = implode(', ', $emails);
		    }
	    }
    	if( $action == 'completed' ){
    	    $data_mail = [];
    	    foreach ($args['mailto'] as $item){
                $data_mail[] = array_filter($users, function ($it) use ($item) {
                    return \SME\Includes\Core\User::has_role($it, $item);
                });
            }
    	    if(!empty($data_mail)){
    	        foreach ($data_mail as $user_data){
    	            foreach ($user_data as $datum){
                        $mailto_completed[] = $datum['email'];
                    }
                }
    	        $emails .= implode(', ', $mailto_completed);
            }else {
                global $currentUser;
                $emails .= ", {$currentUser['email']}";
            }
	    }
	    if( !empty($emails) ){
	    	$title = $action == 'completed' ? __('YÊU CẦU HOÀN TẤT') : __('XÁC NHẬN YÊU CẦU');
		    $title = "#{$data['votes']} " . $title;
		    ob_start();
		    require_once BOOKINGSERVICES_MODULE_DIR . 'template/email/booking-send.twig';
		    $message = ob_get_clean();
		    $body = TemplateTWIG::compileTemplateTwig($message, $data);
		    $body = str_replace('"', "'", $body);
		    $body = str_replace("\n", "", $body);
		    $this->send_email_booking($data['id'], $emails, $title, $body);
	    }
    }

    function get_list(){
        global $system_api;

        $respone = [];
        $status_code = 404;

        $query = $system_api->re_query(
            'GET',
            'registrationForms',
            [
                'params' => [],
                'fields' => [
                    'id',
	                'into_money',
	                'client' => [
		                'id', 'name'
	                ],
	                'booth',
	                'votes',
	                'status',
	                'date',
	                'author' => [
	                	'id',
		                'name'
	                ],
                    'events' => [
                        'title'
                    ],
                    'flag',
                ]
            ], true

        );
            if (is_wp_error($query) || isset($query->errors)) {
                $respone = new \WP_Error(403, __("Access deny"));
                $status_code = 403;
            } else {
                $respone = ['message' => 'success', 'booking_list' => $query['registrationForms']];
                $status_code = 201;
            }
            //var_dump($query['registrationForms']);
            return json_encode($respone, $status_code);

    }
}
