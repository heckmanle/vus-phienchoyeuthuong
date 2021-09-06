<?php

namespace Clients\Includes\Core;
class Clients{

    public $page;
    public $pages = [];
    public $page_children = [];

    public function __construct()
    {
        add_action('init', [$this, 'init']);
        $this->hook_page_template();
    }

    public function init(){
        $this->page = get_page_by_path(__('Clients', CLIENTS_LANG_DOMAIN));
        $this->init_page_children();
    }

    function get_list(){
        global $system_api;
        $more_category = $system_api->parseFields(['cate_title']);
        $more_inventory = $system_api->parseFields(['inv_title', 'id']);
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
                    'product_category'.$more_category,
                    'product_inventory'.$more_inventory]
            ], true
        );
        if(!is_wp_error($query) || !empty($query)){
            $result = $query->data->products;
        }
        return $result;
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

                if( 'page-clients.php' === $_wp_page_template ){
                    $template = CLIENTS_MODULE_DIR . 'frontend/pages/page-clients.php';
                }
            }
        }
        return $template;
    }

    public function theme_page_templates($templates){
        $templates[CLIENTS_PAGE] = __('Clients', CLIENTS_LANG_DOMAIN);
        return $templates;
    }



}