<?php
namespace Includes\Core\ClassEvents;
use Cores\Inc\UploadFile;
use SME\Frames\Frames;
use SME\Inc\TemplateTWIG;
use SME\Includes\AJAX;
class Events extends Frames {

    public function __construct()
    {
        parent::__construct();
        $this->init_hook();
        //$this->render_table_preview();
    }

    function init_hook(){
        add_action('app/ajax/register_nopriv_actions', [$this, 'register_nopriv_actions']);
    }

    function register_nopriv_actions(AJAX $ajax){
        $ajax->register_ajax_action('handle_event', [$this, 'handle_event']);
        $ajax->register_ajax_action('delete_events', [$this, 'delete_events']);
        $ajax->register_ajax_action('get_list', [$this, 'get_list']);
        $ajax->register_ajax_action('import_file_booking', [$this, 'import_file_booking']);
        $ajax->register_ajax_action('handle_send_mail', [$this, 'handle_send_mail']);
        $ajax->register_ajax_action('get_table_preview_booking', [$this, 'get_table_preview_booking']);
    }

    function handle_send_mail($params){

        $event_id = isset($params['event_id']) ? $params['event_id'] : '';
        $event = $this->get_event_by_id($event_id);
        if(empty($event)){
            return new \WP_Error(403, "Không tìm thấy sự kiện");
        }

        $booking_main = $this->get_booking_main_of_event($event_id);
        if(empty($booking_main)){
            return new \WP_Error(403, "Không tìm thấy thiết bị đăng ký của sự kiện");
        }

        $object = isset($params['object']) ? $params['object'] : '';
        if(empty($object)){
            return new \WP_Error(403, "Không tìm thấy đối tượng để gửi thông báo");
        }
        $email_send = [];
        foreach ($object as $item){
            if($item == 'btc'){
                global $core_clients_class;
                $client_of_event = $core_clients_class->get_client_by_id($event->client->id);
                if(empty($client_of_event)){
                    return new \WP_Error(403, "Không tìm thấy ban tổ chức của sự kiện");
                }
                if(!empty($client_of_event->contact_person_informations)){
                    foreach ($client_of_event->contact_person_informations as $user_of_client){
                        $email_send[] = $user_of_client->email;
                    }
                }
            }else {
                $users = $this->get_users_by_role_name($item);
                if (!empty($users)) {
                    foreach ($users as $user) {
                        $email_send[] = $user->email;
                    }
                }
            }
        }
        $content = isset($params['content']) ? $params['content'] : 'Không có';

        $booking = $this->get_booking_client_of_event($event_id);
        if(empty($booking)){
            return new \WP_Error(403, "Không tìm thấy thiết bị đăng ký của sự kiện");
        }
        global $core_inventory;
        $table = $this->render_table_for_send_mail_2($event_id);
        $data = [
            'table' => $table,
            'content' => $content
        ];
        ob_start();
        require_once EVENTS_MODULE_DIR . 'templates/email/send_mail_booking_main.twig';
        $message = ob_get_clean();
        $body = TemplateTWIG::compileTemplateTwig($message, $data);
        $body = str_replace('"', "'", $body);
        $body = str_replace("\n", "", $body);
        $to = implode(',', $email_send);
        $title = 'Danh sách đăng ký thiết bị chính của ' . $event->title;
        $send_email = $this->send_email_booking_main($to, $title, $body);
        if(!$send_email){
            return new \WP_Error(403, "Thông báo thất bại");
        }

        //send notification
        $this->send_notification($content . ' - ' . $event->title, $object);

        return ['message' => 'Thành công'];
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
                        'to' => (object)['data' => '"'.implode('","', $role).'"', 'array' => true]],
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

    function send_email_booking_main( $to = '', $title = '', $body = ''){
        if(!empty($to)){
            global $system_api;
            $sendmail = $system_api->re_query(
                'POST',
                'sendEmailNotification',
                [
                    'params' => ['title' => $title, 'body' => $body, 'to' => $to],
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

    function get_table_preview_booking($params){
        $event_id = isset($params['event_id']) ? $params['event_id'] : '';
        if(empty($event_id)){
            return new \WP_Error(403, "Không tìm thấy danh sách thiết bị đăng ký của sự kiện");
        }
        $table = $this->render_table_data($event_id);
        return ['table' => $table];
    }

    function add_booking_main_for_event($data){
        if(!empty($data)) {
            $id = $data['event_id'];
            $client = $data['client_id'];
            $booth = '';
            $into_money = 0;
            $votes = 'PO' . strtoupper(substr(uniqid(), -6, 6));
            $discount = 0;
            $vat = 0;
            $payment_type = '';
            $status = 'pending-draft';
            $flag = 'main';
            $events = $id;
            $date = date('d/m/Y', time());

            $data = compact(['client', 'events', 'date', 'votes', 'booth', 'discount', 'vat', 'payment_type', 'into_money', 'status', 'flag', 'total_ticket']);
            $data['into_money'] = isset($data['into_money']) ? (int)$data['into_money'] : 0;
            $data['vat'] = isset($data['vat']) ? (int)$data['vat'] : 0;
            $data['discount'] = isset($data['discount']) ? (int)$data['discount'] : 0;

            global $system_api;
            $client_fields = $system_api->parseFields(['name', 'phone']);
            $event_field = $system_api->parseFields(['title']);
            $insert = $system_api->query(
                'POST',
                'addRegistrationForm',
                [
                    'params' => $data,
                    'fields' => [
                        'id',
                        'votes',
                        'date',
                        'vat',
                        'flag',
                        'client' . $client_fields,
                        'events' . $event_field,
                    ]
                ], true
            );
            if (is_wp_error($insert) || isset($insert->errors)) {
                return new \WP_Error(403, __("Thêm thất bại do lỗi hệ thống"));
            } else {
                return $insert->data->addRegistrationForm;
            }
        }
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

    function add_list_booking($data){
        if(!empty($data)){
            global $system_api;
            $data_insert = ['data' => (object)$data];
            $insert = $system_api->query_for_body(
                'POST',
                'addRegistrationForms',
                [
                    'params' => $data_insert,
                ], true
            );
            if(is_wp_error($insert) || isset($insert->errors)){
                $respone = new \WP_Error(403, __("Thêm thất bại"));
            }else {
                $respone = $insert->data->addRegistrationForms;
            }
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

    function import_file_booking($params){

        $respone = [];

        $event_id = isset($params['event_id']) ? $params['event_id'] : '';
        if(empty($event_id)){
            return new \WP_Error(403, "Không tìm thấy sự kiện");
        }
        $event = $this->get_event_by_id($event_id);
        if(empty($event)){
            return new \WP_Error(403, "Không tìm thấy sự kiện");
        }

        $booking_main = $this->get_booking_main_of_event($event_id);
        if(!empty($booking_main)){
            return new \WP_Error(403, "Danh sách thiết bị chính đã được đăng ký");
        }

        $client_id = isset($event->client->id) ? $event->client->id : '';
        /*if(empty($client_id)){
            return new \WP_Error(403, "Không tìm thấy ban tổ chức của sự kiện");
        }*/

        $file = isset($_FILES['file_import']) ? $_FILES['file_import'] : '';
        if(empty($file)){
            return new \WP_Error(403, "Không tìm thấy file đăng ký");
        }

        $location = $this->get_location_of_event($event_id);
        if(empty($location)){
            return new \WP_Error(403, "Không tìm thấy sơ đồ vị trí cho sự kiện.");
        }
        $file_upload = new \SME\Inc\UploadFile($file, ['xla|xls|xlt|xlw' => 'application/vnd.ms-excel']);
        $fileImport = $file_upload->save_file();
        if(is_wp_error($fileImport)){
            return $fileImport;
        }
        $upload_dir = wp_get_upload_dir();
        $fileImport = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $fileImport);
        try {
            $checkFileType = wp_check_filetype($file['name']);
            /**
             * Load file type is excel
             */
            if (in_array($checkFileType['type'], ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
                $identify = \PHPExcel_IOFactory::identify($fileImport);
                $objReader = \PHPExcel_IOFactory::createReader($identify);
                $objPHPExcel = $objReader->load($fileImport);
            } elseif (in_array($checkFileType['type'], ['text/csv'])) {
                # load file type is csv
                $objReader = new \PHPExcel_Reader_CSV();
                $objPHPExcel = $objReader->load($fileImport);
            }

        } catch (Exception $e) {
            die(__("Error can not read file") . ' "' . pathinfo($fileImport, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
        $activeSheetIndex = $objPHPExcel->getActiveSheetIndex();
        $sheet = $objPHPExcel->getSheet($activeSheetIndex);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $head_original = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, true)[0];
        //$head = array_map('sanitize_title', $head_original);
        $head = array_flip($head_original);
        for ($row = 2; $row <= $highestRow; $row++) {
            // Lấy dữ liệu từng dòng và đưa vào mảng
            $temp = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, true)[0];
            $temp = array_map('trim', $temp);
            if(!empty($temp)){
                foreach ($head as $value=>$key){
                    $data[$row][$key] = $temp[$head[$value]];
                }
            }
        }
        $error_location = [];
        $key_location = 1;
        $key_client = 2;
        $start_product = 3;

        $key_accept_product = [];
        $key_accept_product = [];
        $key_accept_row = [];

        /**
         * kiem tra sản phẩm
         */
        if(empty($head_original)){
            return new \WP_Error(403, "Không tìm thấy sản phẩm");
        }
        global $core_inventory;
        $products = $core_inventory->get_list();

        for ($i = $start_product; $i <= count($head_original); $i++){
            $is_product = $is_product = $this->check_is_product($head_original[$i], $products);
            if($is_product){
                $key_accept_product[] = [
                    'key' => $i,
                    'product' => $is_product,
                ];
            }
        }
        if(empty($key_accept_product)){
            return new \WP_Error(403, "Không tìm thấy sản phẩm");
        }
        /**
         * kiểm tra vị trí và khách hàng có tồn tại hay k
         */
        foreach ($data as $key => $item){
            $is_location = $this->check_is_location($item[$key_location], $location->location_diagram->area);
            if(!$is_location){
                $error_location[] =  $item[$key_location];
            }else{
                $key_accept_row[] = $key;
            }
        }
        if(empty($key_accept_row)){
            return new \WP_Error(403, "Không tìm thấy dòng file hợp lệ");
        }

        $data_insert = [];

        global $core_clients_class;
        $clients = $core_clients_class->get_list();
        foreach ($key_accept_row as $row_accept){
            $row = $data[$row_accept];
            $is_client = $this->check_is_client($row[$key_client], $clients);
            if(!empty($is_client)){
                $client = $is_client->id;
            }else{
                //$client = $client_id;
                $insert_client = $core_clients_class->add_client(['name' => $row[$key_client]]);
                if(!is_wp_error($insert_client) && isset($insert_client['insert_id'])){
                    $client = $insert_client['insert_id'];
                }
            }
            if(!empty($client)) {
                $booth = $row[$key_location];
                $into_money = 0;
                $votes = 'PO' . strtoupper(substr(uniqid(), -6, 6));
                $discount = 0;
                $vat = 10;
                $payment_type = '';
                $status = 'pending-draft';
                $events = $event_id;
                $date = date('d/m/Y', time());
                $flag = '1';
                $list_of_equipment = [];
                $total_ticket = 0;
                $per_charge = 0;
                foreach ($key_accept_product as $product_accept) {
                    if((int)$row[$product_accept['key']] > 0) {
                        $list_of_equipment[] = [
                            'product' => $product_accept['product']->id,
                            'quantity' => (int)$row[$product_accept['key']],
                            'into_money' => $product_accept['product']->product_pay,
                        ];
                        if($product_accept['product']->product_pay > 0){
                            $total_ticket += $product_accept['product']->product_pay;
                        }
                    }else{
                        continue;
                    }
                }
                $price_of_vat = (int) $total_ticket * ( 1 + (_VAT * 0.01));
                $total_ticket = (string)$price_of_vat;
                $per_charge = (string)$per_charge;
                $list_of_equipment = (object)$list_of_equipment;
                $data_insert[] = compact(['client', 'events', 'date', 'votes', 'booth', 'discount', 'vat', 'payment_type', 'into_money', 'status', 'flag', 'total_ticket', 'per_charge', 'list_of_equipment']);
            }
        }
        $add_booking_main = $this->add_booking_main_for_event([
            'client_id' => $client_id,
            'event_id' => $event_id,
        ]);
        if(is_wp_error($add_booking_main)){
            return $add_booking_main;
        }
        $add_list_booking = $this->add_list_booking($data_insert);
        $table = $this->render_table_data($event_id);
        unlink($fileImport);
        return ['table' => $table, 'event_id' => $event_id];
    }

    function render_table_data($event_id){
        $table = '';
        $booking = $this->get_booking_client_of_event($event_id);
        $booking_client = array_filter($booking, function ($item){
           return ($item->flag != "main");
        });
        $header = [];
        foreach ($booking_client as $item){
            $products = $item->list_of_equipment;
            if(!empty($products)){
                foreach ($products as $product){
                    $header[$product->product->id] = $product->product->product_title;
                }
            }
        }
        $table_head = '
            <th class="" >STT</th>
            <th class="" >Vị trí</th>
            <th class="" >Khách hàng</th>
            ';
        foreach ($header as $item){
            $table_head .= "<th>{$item}</th>";
        }
        $table_body = '';
        $stt = 1;
        foreach ($booking_client as $item){
            $body = "<td>{$stt}</td><td>{$item->booth}</td><td>{$item->client->name}</td>";
            $products = $item->list_of_equipment;
            if(!empty($products)){
                foreach ($header as $key => $head){
                    $body_temp = "<td></td>";
                    foreach ($products as $product) {
                        if ($product->product->id == $key) {
                            $body_temp = "<td>{$product->quantity}</td>";
                        }
                    }
                    $body .= $body_temp;
                }
            }
            $body = "<tr>" . $body . "</tr>";
            $table_body .= $body;
            $stt++;
        }
        if(!empty($table_head) && !empty($table_body)){
            $table = '<div class="table-responsive"><table id="" class="mb-0 table table-hover table-striped table-bordered"><thead><tr>' . $table_head .'</tr></thead>' . '<tbody>'.$table_body.'</tbody></table></div>';
        }
        return $table;
    }

    function render_table_for_send_mail_2($event_id){
        $table = '';
        $booking = $this->get_booking_client_of_event($event_id);
        $booking_client = array_filter($booking, function ($item){
            return ($item->flag != "main");
        });
        $header = [];
        foreach ($booking_client as $item){
            $products = $item->list_of_equipment;
            if(!empty($products)){
                foreach ($products as $product){
                    $header[$product->product->id] = $product->product->product_title;
                }
            }
        }
        $table_head = '
            <th class="" >STT</th>
            <th class="" >Vị trí</th>
            <th class="" >Khách hàng</th>
            ';
        foreach ($header as $item){
            $table_head .= "<th>{$item}</th>";
        }
        $table_body = '';
        $stt = 1;
        foreach ($booking_client as $item){
            $body = "<td style='text-align: center'>{$stt}</td><td style='text-align: center'>{$item->booth}</td><td style='text-align: center'>{$item->client->name}</td>";
            $products = $item->list_of_equipment;
            if(!empty($products)){
                foreach ($header as $key => $head){
                    $body_temp = "<td></td>";
                    foreach ($products as $product) {
                        if ($product->product->id == $key) {
                            $body_temp = "<td style='text-align: center'>{$product->quantity}</td>";
                        }
                    }
                    $body .= $body_temp;
                }
            }
            $body = "<tr>" . $body . "</tr>";
            $table_body .= $body;
            $stt++;
        }
        if(!empty($table_head) && !empty($table_body)){
            $table = '<table id="" class="" border="1" cellspacing="0" width="100%" cellpadding="0"><thead><tr>' . $table_head .'</tr></thead>' . '<tbody>'.$table_body.'</tbody></table>';
        }
        return $table;
    }

    function check_is_client($client_name, $clients){
        if(!empty($clients) && !empty($client_name)){
            $client_name = trim(mb_strtolower($client_name, 'UTF-8'));
            foreach ($clients as $client){
                if($client_name == trim(mb_strtolower($client->name, 'UTF-8'))){
                    return $client;
                }
            }
        }
        return [];
    }

    function check_is_location($_location, $areas){
        if(!empty($areas)){
            foreach ($areas as $location){
                if($_location == $location->location){
                    return true;
                }
            }
        }
        return false;
    }

    function check_is_product($product_title, $products){
        if(!empty($products) && !empty($product_title)){
            $product_title = trim(mb_strtolower($product_title, 'UTF-8'));
            foreach ($products as $product){
                if($product_title == trim(mb_strtolower($product->product_title, 'UTF-8'))){
                    return $product;
                }
            }
        }
        return [];
    }

    function get_booking_client_of_event($event_id){
        global $system_api;
        $booking_client = [];
        $more_product_list_of_equipment = $system_api->parseFields(['id', 'product_unit', 'product_code', 'product_title']);
        $more_list_of_equipment =  $system_api->parseFields(['quantity', 'into_money', 'product'.$more_product_list_of_equipment]);
        $more_client = $system_api->parseFields(['name']);
        $query = $system_api->query(
            'GET',
            'registrationForms',
            [
                'params' => ['flag' => '1' ,'status' => 'pending-draft', 'events' => $event_id],
                'fields' => [
                    'id',
                    'booth',
                    'flag',
                    'client'.$more_client,
                    'list_of_equipment'.$more_list_of_equipment,
                ]
            ], true

        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $booking_client = $query->data->registrationForms;
        }
        return $booking_client;
    }

    function get_booking_main_of_event($event_id){
        global $system_api;
        $booking_main = [];
        $more_product_list_of_equipment = $system_api->parseFields(['id', 'product_unit', 'product_code']);
        $more_list_of_equipment =  $system_api->parseFields(['quantity', 'into_money', 'product'.$more_product_list_of_equipment]);
        $query = $system_api->query(
            'GET',
            'registrationForms',
            [
                'params' => ['flag' => 'main', 'status' => 'pending-draft', 'events' => $event_id],
                'fields' => [
                    'id',
                    'booth',
                    'list_of_equipment'.$more_list_of_equipment,
                ]
            ], true

        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $booking_main = $query->data->registrationForms;
        }
        return $booking_main;

    }

    function get_location_of_event($event_id){
        global $system_api;
        $location = [];
        $more_area = $system_api->parseFields(['location']);
        $more_location = $system_api->parseFields(['number_of_columns', 'number_of_lines', 'area'.$more_area]);
        $query = $system_api->query(
            'GET',
            'getEvent',
            [
                'params' => ['id' => $event_id],
                'fields' => [
                    'location_diagram'.$more_location,
                ]
            ], true

        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $location = $query->data->getEvent;
        }
        return $location;
    }

    function get_event_by_id($event_id){
        global $system_api;
        $location = [];
        $more_client = $system_api->parseFields(['id']);
        $query = $system_api->query(
            'GET',
            'getEvent',
            [
                'params' => ['id' => $event_id],
                'fields' => [
                    'client'.$more_client,
                    'title',
                    'description',
                    'status'
                ]
            ], true

        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $location = $query->data->getEvent;
        }
        return $location;
    }

    function get_list(){
        global $system_api;
        $respone = [];
        $status_code = 404;
        // get list events
        $query = $system_api->query(
            'GET',
            'getEvents',
            [
                'fields' => ['id','title','description','start_date', 'end_date', 'status', 'author{ id, name}','client { name, id }', 'manager {name}', 'location_diagram { id }' ]
            ], true

        );

        // get list locations
        $query_locations = $system_api->query(
            'GET',
            'locationDiagrams',
            [
                'fields' => ['id', 'name', 'created']
            ], true
        );

        // get list clients
        $query_clients = $system_api->query(
            'GET',
            'clients',
            [
                'fields' =>
                    ['id', 'name', 'phone', 'email','address', 'client_id', 'client_type', 'tax_code' ]

            ], true

        );
        // get list users
        $query_users = $system_api->query(
            'GET',
            'users',
            [
                'fields' =>
                    ['id', 'name', 'phone', 'email','address', 'roles { id, role_name }', 'status' ]

            ], true

        );


        if(is_wp_error($query) || isset($query->errors)){
            $respone = new \WP_Error(403, __("Access deny"));
            $status_code = 403;
        }else {
            $respone = [
                'message' => 'success',
                'events_list' => $query->data->getEvents,
                'locations_list' => $query_locations->data->locationDiagrams,
                'clients_list' => $query_clients->data->clients,
                'users_list' => $query_users->data->users
            ];
            $status_code = 201;
        }

        return json_encode($respone, $status_code);
    }

    function handle_event(){
        $params = $_POST;
        $id = !empty($params['id']) ? $params['id'] : '';
        extract($params);
        $data = compact(['event_name', 'event_desc', 'event_customer_role', 'event_manage_user', 'event_start', 'event_end', 'event_location', 'event_status']);

        if(!empty($id)){
            $data['id'] = $id;
            $this->update_event($data);
        }else{
            $respone = $this->add_event($data);
            if(is_wp_error($respone)){
                return $respone;
            }else{
                return $respone;
            }
        }
    }

    function add_event($data){
        $respone = [];
        $modaldata=[];
        global $system_api;
        if(!empty($data)){

            if(isset($data['event_name'])){
                $modaldata['title'] = $data['event_name'];
            }
            if(isset($data['event_desc'])){
                $modaldata['description'] = $data['event_desc'];
            }
            if(isset($data['event_customer_role'])){
                $modaldata['client'] = $data['event_customer_role'];
            }
            if(isset($data['event_manage_user'])){
                $modaldata['manager'] = $data['event_manage_user'];
            }
            if(isset($data['event_start'])){
                //$modaldata['start_date'] = (int)$data['event_start'];
                $modaldata['start_date'] = $data['event_start'];
            }
            if(isset($data['event_end'])){
                $modaldata['end_date'] = $data['event_end'];
            }
            if(isset($data['event_location'])){
                $modaldata['location_diagram'] = $data['event_location'];
            }
            if(isset($data['event_status'])){
                $modaldata['status'] = $data['event_status'];
            }

            $insert = $system_api->query(
                'POST',
                'addEvent',
                [
                    'params' => $modaldata,
                    'fields' => ['id']
                ], true
            );
            if(is_wp_error($insert) || isset($insert->errors)){
                $respone = new \WP_Error(403, __("Thêm thất bại"));
            }else {
                $respone = ['message' => 'success', 'insert_id' => $insert->data->addEvent->id];
            }
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

    function update_event($data){
        $respone = [];
        global $system_api;
        if(!empty($data)){
            $modaldata['id'] = $data['id'];
            if(isset($data['event_name'])){
                $modaldata['title'] = $data['event_name'];
            }
            if(isset($data['event_desc'])){
                $modaldata['description'] = $data['event_desc'];
            }
            if(isset($data['event_customer_role'])){
                $modaldata['client'] = $data['event_customer_role'];
            }
            if(isset($data['event_manage_user'])){
                $modaldata['manager'] = $data['event_manage_user'];
            }
            if(isset($data['event_start'])){
                //$modaldata['start_date'] = (int)$data['event_start'];
                $modaldata['start_date'] = $data['event_start'];
            }
            if(isset($data['event_end'])){
                $modaldata['end_date'] = $data['event_end'];
            }
            if(isset($data['event_location'])){
                $modaldata['location_diagram'] = $data['event_location'];
            }
            if(isset($data['event_status'])){
                $modaldata['status'] = $data['event_status'];
            }
            $update = $system_api->query(
                'POST',
                'updateEvent',
                [
                    'params' => $modaldata,
                    'fields' => ['id']
                ], true
            );
            if(is_wp_error($update) || isset($update->errors)){
                $respone = new \WP_Error(403, __("Cập nhật thất bại"));
            }else {
                $respone = ['message' => 'success', 'insert_id' => $update->data->updateEvent->id];
            }
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

    function delete_events($params){
        $events_set_delete = isset($params['events']) ? $params['events'] : [];
        $respone = [];
        global $system_api;
        $data_success = [];
        $data_false = [];
        if(!empty($events_set_delete)){
            foreach ($events_set_delete as $event_id){
                $delete = $system_api->query(
                    'POST',
                    'deleteEvent',
                    [
                        'params' => ['id' => $event_id]
                    ], true
                );

                if(is_wp_error($delete) || isset($delete->errors)){
                    $data_false[] = $event_id;
                }else {
                    $data_success[] = $event_id;
                }
            }
            $respone = ['data_false' => $data_false, 'data_success' => $data_success];
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

}