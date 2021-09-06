<?php
namespace Includes\Core\ClassUsersmanagement;
use SME\Frames\Frames;
class Usersmanagement extends Frames {

    public function __construct()
    {
        parent::__construct();
        $this->init_hook();
    }
    function init_hook(){
    }

    function get_list(){
        global $system_api;
        $respone = [];
        $status_code = 404;

        $query = $system_api->query(
            'GET',
            'users',
            [
                'fields' =>
                    ['id', 'name', 'phone', 'email','role_title', 'address', 'department', 'roles {id, role_name} ', 'avatar','registered', 'status' ]

            ], true

        );
        if(is_wp_error($query) || isset($query->errors)){
            $respone = new \WP_Error(403, __("Access deny"));
            $status_code = 403;
        }else {
            $respone = ['message' => 'success', 'users_list' => $query->data->users];
            $status_code = 201;
        }

        return json_encode($respone, $status_code);
    }

}