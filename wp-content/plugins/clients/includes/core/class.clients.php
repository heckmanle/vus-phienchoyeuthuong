<?php
namespace ClassClients\Includes\Core;
use SME\Frames\Frames;
use SME\Includes\AJAX;

class Clients extends Frames{

    public $list_branch = [
        'heavy_industry' => 'Công nghiệp nặng',
        'light_industry' => 'Công nghiệp nhẹ',
        'hardware' => 'Ngũ kim',
    ];

    public $client_type = [
        'organizers' => 'Ban tổ chức',
        'contractors' => 'Nhà thầu',
        'client' => 'Khách hàng'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->init_hook();
    }

    function init_hook(){
        add_action('app/ajax/register_nopriv_actions', [$this, 'register_nopriv_actions']);
    }

    function register_nopriv_actions(AJAX $ajax){
        $ajax->register_ajax_action('handle_clients', [$this, 'handle_clients']);
        $ajax->register_ajax_action('get_client', [$this, 'get_client']);
        $ajax->register_ajax_action('delete_clients', [$this, 'delete_clients']);
        $ajax->register_ajax_action('import_clients', [$this, 'import_clients']);
        $ajax->register_ajax_action('get_user', [$this, 'get_user']);
    }

    function get_user($params){
        $user_id = isset($params['user_id']) ? $params['user_id'] : '';
        $is_user = $this->get_user_by_id($user_id);
        return ['user' => $is_user];
    }

    function get_user_by_id($user_id){
        global $system_api;
        $user = [];
        $query = $system_api->query(
            'GET',
            'user',
            [
                'params' => ['id' => $user_id],
                'fields' => [
                    'id',
                    'name',
                    'phone',
                    'email ',
                    'role_title',
                    'roles { role_name }',
                    'birthdate',
                ]
            ], true
        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $user = $query->data->user;
        }
        return $user;
    }

    function import_clients($params){

        $file = $_FILES['file'];
        if(empty($file)){
            return new \WP_Error(403, "Không tìm thấy mẫu file khách hàng");
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
        $head_original = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE)[0];
        $head_fields = $sheet->rangeToArray('A2:' . $highestColumn . '2', NULL, TRUE, FALSE)[0];

        $keys_column_client_type = 0;
        $keys_column_client_id = 1;
        $keys_column_name = 2;
        $keys_column_branch = 3;
        $keys_column_phone = 4;
        $keys_column_email = 5;
        $keys_column_address = 6;
        $keys_column_billing_address = 7;
        $keys_column_tax_code = 8;
        $keys_column_website = 9;
        $array_column_person_name = 10;
        $array_column_person_gender = 11;
        $keys_column_person_birthdate = 12;
        $keys_column_person_phone = 13;
        $keys_column_person_email = 14;
        $keys_column_person_title = 15;

        $start_client = 1;
        $start_person = 10;
        $data = [];

        for ($row = 3; $row <= $highestRow; $row++) {
            // Lấy dữ liệu từng dòng và đưa vào mảng
            $temp = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, true)[0];
            $temp = array_map('trim', $temp);
            $data_default = [
                'client_type' => $temp[$keys_column_client_type],
                'client_id' => $temp[$keys_column_client_id],
                'name' => $temp[$keys_column_name],
                'branch' => $temp[$keys_column_branch],
                'phone' => $temp[$keys_column_phone],
                'email' => $temp[$keys_column_email],
                'address' => $temp[$keys_column_address],
                'billing_address' => $temp[$keys_column_billing_address],
                'tax_code' => $temp[$keys_column_tax_code],
                'website' => $temp[$keys_column_website],
                'contact_person_information' => [
                    'name' => $temp[$array_column_person_name],
                    'gender' => $temp[$array_column_person_gender],
                    'birthdate' => $temp[$keys_column_person_birthdate],
                    'phone' => $temp[$keys_column_person_phone],
                    'email' => $temp[$keys_column_person_email],
                    'title' =>$temp[$keys_column_person_title],
                ],
            ];

        }

    }

    function get_client(){
        $id = isset($_POST['id']) ? $_POST['id'] : '';
        $result = $this->get_client_by_id($id);
        wp_send_json($result);
    }

    function get_client_by_id($id){
        global $system_api;
        $client = [];
        $more_author = $system_api->parseFields(['name']);
        $more_contact_person_information =$system_api->parseFields(['name', 'gender', 'birthdate', 'phone', 'email', 'title']);
        $more_contact_person_informations =$system_api->parseFields(['id', 'name', 'email']);
        $query = $system_api->query(
            'GET',
            'client',
            [
                'params' => ['id' => $id],
                'fields' => [
                    'id',
                    'client_type',
                    'client_id',
                    'name',
                    'phone',
                    'email',
                    'address',
                    'website',
                    'avatar',
                    'tax_code',
                    'billing_address',
                    'branch',
                    'registered',
                    'note',
                    'contact_person_information'.$more_contact_person_information,
                    'contact_person_informations' . $more_contact_person_informations,
                    'author'.$more_author,
                ]
            ], true
        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $client = $query->data->client;
        }
        return $client;

    }



    function delete_clients($params){
        $clients = isset($params['clients']) ? $params['clients'] : [];
        $respone = [];
        global $system_api;
        $data_success = [];
        $data_false = [];
        if(!empty($clients)){
            foreach ($clients as $client_id){
                $delete = $system_api->query(
                    'POST',
                    'deleteClient',
                    [
                        'params' => ['id' => $client_id],
                        'fields' => ['id']
                    ], true
                );

                if(is_wp_error($delete) || isset($delete->errors)){
                    $data_false[] = $client_id;
                }else {
                    $data_success[] = $client_id;
                }
            }
            $respone = ['data_false' => $data_false, 'data_success' => $data_success];
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

    function handle_clients($params){
        $id = !empty($params['id']) ? $params['id'] : '';
        $avatar_file = isset($_FILES['avatar_file']) && !empty($_FILES['avatar_file']) ? $_FILES['avatar_file'] : '';

        if(!empty($avatar_file['name']) && !empty($avatar_file['type']) && !empty($avatar_file['tmp_name']) ){
            $file_upload = new \SME\Inc\UploadFile($avatar_file, [
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png' => 'image/png',
            ]);
            $fileImport = $file_upload->save_file();
            if(is_wp_error($fileImport)){
                return $fileImport;
            }
            $upload_dir = wp_get_upload_dir();
            $fileImport = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $fileImport);
            $params['avatar'] = $fileImport;
        }
        $data = $this->convert_data($params);
        if(is_wp_error($data)){
            return $data;
        }
        if(!empty($id)){
            $data['id'] = $id;
            $this->update_client($data);
        }else{
            $respone = $this->add_client($data);
            if(is_wp_error($respone)){
                return $respone;
            }else{
                return $respone;
            }
        }
    }

    function convert_data($data){

        $client_type = isset($data['client_type']) ? $data['client_type'] : '';
//        if(empty($client_type)){
//            return new \WP_Error(403, "Không tìm thấy loại khách hàng");
//        }

        $client_id = isset($data['client_id']) ? $data['client_id'] : '';
//        if(empty($client_id)){
//            return new \WP_Error(403, "Không tìm thấy mã khách hàng");
//        }

        $name = isset($data['name']) ? $data['name'] : '';

        $branch = isset($data['branch']) ? $data['branch'] : '';
//        if(!array_key_exists($branch, $this->list_branch)){
//            return new \WP_Error(403, "Phân ngành không hợp lệ");
//        }

        $phone = isset($data['phone']) ? $data['phone'] : '';

        $email = isset($data['email']) ? $data['email'] : '';
        /*if(empty($email)){
            return new \WP_Error(403, "Không tìm thấy email");
        }*/

        $address = isset($data['address']) ? $data['address'] : '';
        $billing_address = isset($data['billing_address']) ? $data['billing_address'] : '';
        $tax_code = isset($data['tax_code']) ? $data['tax_code'] : '';
        $website = isset($data['website']) ? $data['website'] : '';
        $avatar = isset($data['avatar']) ? $data['avatar'] : '';
        $note = isset($data['note']) ? $data['note'] : '';

        $contact_person_information = isset($data['contact_person_information']) ? $data['contact_person_information'] : [];
        //$contact_person_information = (object)$contact_person_information;

        $contact_person_informations_data = [];
        $account_client = isset($data['account-client']) ? $data['account-client'] : '';
        if(!empty($account_client)){
            $data_check_match = [];
            foreach ($account_client as $item){
                if(!empty($item['user'])) {
//                    if (in_array($item, $data_check_match)) {
//                        return new \WP_Error(403, "Tài khoản khách hàng không được trùng khớp");
//                    }
                    $contact_person_informations_data[] = $item['user'];
                }
            }
        }
        if(!empty($contact_person_informations_data)){
            $contact_person_informations = [
                'data' => '"'.implode('","', $contact_person_informations_data).'"',
                'array' => true,
            ];
        }else{
            $contact_person_informations = [
                'data' => '',
                'array' => true,
            ];
        }
        $contact_person_informations = !empty($contact_person_informations) ? (object)$contact_person_informations : '';
        $data = compact(['client_type', 'client_id', 'name', 'branch', 'phone', 'email', 'address', 'billing_address', 'tax_code', 'website', 'note', 'contact_person_informations', 'contact_person_information', 'avatar']);
        return $data;
    }

    function add_client($data){
        $respone = [];
        $status_code = 404;
        global $system_api;
        if(!empty($data)){
            $insert = $system_api->query_for_body(
                'POST',
                'addClient',
                [
                    'params' => $data,
                    'fields' => ['id']
                ], true
            );
            if(is_wp_error($insert) || isset($insert->errors)){
                $respone = new \WP_Error(403, __("Thêm thất bại"));
            }else {
                $respone = ['message' => 'success', 'insert_id' => $insert->data->addClient->id];
            }
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

    function update_client($data){
        $respone = [];
        global $system_api;
        if(!empty($data)){
            $update = $system_api->query_for_body(
                'POST',
                'updateClient',
                [
                    'params' => $data,
                    'fields' => ['id']
                ], true
            );
            if(is_wp_error($update) || isset($update->errors)){
                $respone = new \WP_Error(403, __("Cập nhật thất bại"));
            }else {
                $respone = ['message' => 'success', 'insert_id' => $update->data->updateClient->id];
            }
        }else{
            $respone = new \WP_Error(403, __("Không tìm thấy dữ liệu"));
        }
        return $respone;
    }

    function get_list(){
        global $system_api;
        $more_author = $system_api->parseFields(['name']);
        $more_contact_person_information =$system_api->parseFields(['name', 'gender', 'birthdate', 'phone', 'email', 'title']);
        $result = [];
        $query = $system_api->query(
            'GET',
            'clients',
            [
                'fields' => [
                    'id',
                    'client_type',
                    'client_id',
                    'name',
                    'phone',
                    'email',
                    'address',
                    'avatar',
                    'tax_code',
                    'billing_address',
                    'branch',
                    'registered',
                    'author'.$more_author,
                    'contact_person_information'.$more_contact_person_information,
                ]
            ], true
        );
        if(!is_wp_error($query) || !empty($query)){
            $result = $query->data->clients;
        }
        return $result;
    }

    function convert_type(){

    }
}
?>