<?php
namespace SME\System;

use SME\Includes\Core\User;

class System extends User {

    public function __construct()
    {
        //add_action( 'after_setup_theme', [$this, 'init_temp_admin'] );
        //add_filter('show_admin_bar', '__return_false');
        //add_action('after_setup_theme', [$this, 'remove_admin_bar']);
        //wp_logout();
        $this->init_class();
    }

    function remove_admin_bar() {
        if (!is_admin()) {
            show_admin_bar(false);
        }
    }

    function init_temp_admin(){
        $creds = array(
            'user_login'    => 'webmaster',
            'user_password' => '12345678@Ab!.',
            'remember'      => true
        );

        $user = wp_signon( $creds, false );

        if ( is_wp_error( $user ) ) {
            echo $user->get_error_message();
        }
    }

    function init_class(){
        require_once THEME_DIR . "/includes/frames.php";
        $components = apply_filters('app/add_components', []);
        if( !empty($components) ){
            foreach ($components as $key => $cp){
                if( class_exists($cp) ) {
                    $GLOBALS[$key] = new $cp;
                }
            }
        }
    }

}
