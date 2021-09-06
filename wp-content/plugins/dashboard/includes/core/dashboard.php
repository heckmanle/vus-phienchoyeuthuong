<?php

namespace Dashboard\Includes\Core;

class Dashboard
{
    public $page;
    public $pages = [];
    public $page_children = [];

    public function __construct()
    {
        add_action('init', [$this, 'init']);
        add_filter('app/authentication/login_success_url', [$this, 'login_success_url']);
        $this->hook_page_template();
    }

    public function init(){
        $this->page = get_page_by_path(__('Dashboard', DASHBOARD_LANG_DOMAIN));
        $this->init_page_children();
    }

    public function login_success_url($url){
    	if( !empty($this->page) ){
    		return get_the_permalink($this->page);
	    }
    	return $url;
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

                if( 'page-dashboard.php' === $_wp_page_template ){
                    $template = DASHBOARD_MODULE_DIR . 'frontend/pages/page-dashboard.php';
                }
            }
        }
        return $template;
    }

    public function theme_page_templates($templates){
        $templates[DASHBOARD_PAGE] = __('Dashboard', DASHBOARD_LANG_DOMAIN);
        return $templates;
    }



}
