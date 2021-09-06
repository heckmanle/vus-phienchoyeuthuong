<?php

namespace GS\Includes;

class AJAX {

    /**
     * Ajax actions.
     *
     * Holds all the register ajax action.
     *
     * @since 2.0.0
     * @access private
     *
     * @var array
     */
    private $ajax_actions = [];

    /**
     * Ajax response data.
     *
     * Holds all the response data for all the ajax requests.
     *
     * @since 2.0.0
     * @access private
     *
     * @var array
     */
    private $response_data = [];

    protected static $_instances = [];

    function __construct()
    {
        add_action('wp_ajax_gs_handle_ajax', [$this, 'handle_ajax_request']);
    }

    /**
     * Class name.
     *
     * Retrieve the name of the class.
     *
     * @since 1.7.0
     * @access public
     * @static
     */
    public static function class_name() {
        return get_called_class();
    }

    public static function instance() {
        $class_name = static::class_name();
        if ( empty( static::$_instances[ $class_name ] ) ) {
            static::$_instances[ $class_name ] = new static();
        }

        return static::$_instances[ $class_name ];
    }

    function process_action($callback, $data){
        try{
            $result = call_user_func($callback, $data, $this);
            if( $result === false ){
                $this->add_response_data('', true);
            }elseif(is_wp_error($result)){
                $message = $result->get_error_messages();
                $message = implode("<br>", $message);
                $code = $result->get_error_code();
                $data = apply_filters('gs/ajax/insert_data_errors', []);
                $this->add_response_data($message, false, $data, $code)
                    ->send_error($code, $message);
            }else{
                $this->add_response_data(__('Success', GLOBAL_SETTINGS_LANG_DOMAIN), true, $result);
            }
        }catch (\Exception $e){
            $this->add_response_data($e->getMessage(), false, [], $e->getCode());
        }

        $this->send_success();
    }

    function handle_ajax_request(){
        $data = wp_slash($_REQUEST);
        $action = $data['func'];
        if( is_user_logged_in() ){
	        do_action('gs/ajax/register_actions', $this);
            $ajax_actions = isset($this->ajax_actions[$action]) ? $this->ajax_actions[$action] : [];
        }
        if( empty($ajax_actions) ){
            $this->add_response_data( false, __( 'Action not found.', GLOBAL_SETTINGS_LANG_DOMAIN ), [], 400 )
                ->send_error(400);
        }
        $this->process_action($ajax_actions['callback'], $data);
    }

    public function verify_request($id){
        if( !$this->verify_request_nonce($id) ){
            $message = __('Time has expired' ,GLOBAL_SETTINGS_LANG_DOMAIN );
            $this->add_response_data($message, false, [], 400)
                ->send_error(400);
        }
    }

    public function handle_ajax_action($tag, $callback){
        if ( ! did_action( 'gs/ajax/register_actions' ) ) {
            _doing_it_wrong( __METHOD__, esc_html( sprintf( 'Use `%s` hook to register ajax action.', 'gs/ajax/register_actions' ) ), '1.0.0' );
        }
        $this->ajax_actions[$tag] = compact('tag', 'callback');
    }

    public function register_ajax_action($tag, $callback){
        if( is_user_logged_in() ){
            $this->handle_ajax_action($tag, $callback);
        }
    }

    function verify_request_nonce($action){
        return ! empty( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], $action );
    }

    function send_success(){
        $this->send_response_json(201);
        wp_die( '', '', [ 'response' => null ] );
    }

    function send_error($code = null, $message = ''){
        $this->send_response_json($code, $message);
        wp_die( '', '', [ 'response' => null ] );
    }

    function add_response_data($message, $success, $data = [], $code = 201){
        $this->response_data = [
            'success' => $success,
            'code' => $code,
            'data' => $data,
            'message' => $message
        ];
        return $this;
    }

    function send_response_json( $status_code = null, $description = '' ) {
        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        if ( null !== $status_code ) {
            status_header( $status_code, __('Error') );
            if ( ! $description ) {
                $description = get_status_header_desc( $status_code );
            }
        }
        if( !empty($description) ) {
            $description = json_encode($description);
            @header("Xhr-Message: {$description}");
        }
        echo wp_json_encode( $this->response_data, JSON_PRETTY_PRINT );
    }
}
new \GS\Includes\AJAX();