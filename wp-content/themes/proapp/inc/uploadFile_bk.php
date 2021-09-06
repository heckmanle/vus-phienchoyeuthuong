<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 1/14/20
 * Time: 11:33 AM
 */

namespace Cores\Inc;

use \WP_Error;

class UploadFile
{
    protected  $file;
    protected $folder;
    private $uploads;
    private $file_path;
    private $file_url;
    private $files_in_directory = [];
    private $allowed_mime_types = [
        // Image formats.
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/png',
        'bmp' => 'image/bmp',
        'tiff|tif' => 'image/tiff',
        'ico' => 'image/x-icon',
        // Text formats.
        'csv' => 'text/csv',
        // custom ppt pptx
        'ppt' => 'application/vnd.ms-powerpoint,',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
        // MS Office formats.
        'doc' => 'application/msword',
        'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
        'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
        'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
        'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
    ];
    private $settings = [
        'save_database' => true,
        'file_sizes' => 1024 * 1024 * 10,
    ];
    protected $file_type = [];

    public function __construct($file, $file_name = '', $folder = '', $allowed_mime_types = [], $settings = [])
    {
        $this->file = $file;


        $this->set_settings($settings);
        $this->set_allowed_mime_types($allowed_mime_types);
        $this->set_file_name($file_name);

        if( !empty($folder) ){
            add_filter('upload_dir', [$this, 'preupload_dir'], 100);
            $this->folder = $folder;
        }
        $uploads = wp_upload_dir();
        $this->uploads = $uploads;
    }

    function preupload_dir($uploads){
        $uploads['basedir'] .= "/" . $this->folder;
        $uploads['baseurl'] .= "/" . $this->folder;
        $uploads['path'] = $uploads['basedir'];
        $uploads['url'] = $uploads['baseurl'];
        $this->files_in_directory = glob($this->uploads['basedir'] . "/*");
        return $uploads;
    }

    function set_allowed_mime_types($mime_types){
        if( !empty($mime_types) ) {
            $this->allowed_mime_types = array_filter($mime_types, function ($key) {
                return isset($this->allowed_mime_types[$key]);
            }, ARRAY_FILTER_USE_KEY);
        }
    }

    function set_settings($settings = []){
        $this->settings = wp_parse_args($settings, $this->settings);
    }

    function get_upload_dir(){
        return $this->uploads;
    }

    function check_file_type(){
        $check_file_type = wp_check_filetype($this->file['name'], $this->allowed_mime_types);
        if( !$check_file_type['type'] ){
            $message = __('Sorry, you are not allowed to upload files.');
            return new WP_Error(403, $message);
        }
        return $check_file_type;
    }

    function set_file_name($file_name){
        if( empty($file_name) ){
            $file_name = preg_replace('/\.[^.]+$/', '', basename($this->file['name']));
        }else{
            $file_name = trim($file_name);
        }
        $file_name = $file_name . '-' . substr(uniqid(), -3, 3);
        $this->file['name'] = preg_replace('/^(.*)(\.\S+)$/i', "{$file_name}$2", $this->file['name']);
        // custom remove printable
        //$this->file['name'] = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $this->file['name']);
    }

    function set_empty_folder(){
        if( !empty($this->files_in_directory) ){
            foreach($this->files_in_directory as $file){
                if( is_file($file) ){
                    unlink($file);
                }
            }
        }
    }

    function set_file_path($path){
        $this->file_path = $path;
    }


    function set_file_url($url){
        $this->file_url = $url;
    }

    function handle_upload(){
        if( !empty($this->file) ) {
            $errorFiles = $this->file['error'];
            $sizeFiles = $this->file['size'];
            $fileName = $this->file['name'];
            $check_file_type = $this->check_file_type();
            if( is_wp_error($check_file_type) ){
                return $check_file_type;
            }
            if ($errorFiles == UPLOAD_ERR_OK && $sizeFiles <= $this->settings['file_sizes']) {
                if (!function_exists('wp_handle_upload')) {
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                }
                $uploadedfile = $this->file;
                $upload_overrides = array('test_form' => false, 'mimes' => $this->allowed_mime_types);

                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
                if( $movefile && isset($movefile['error']) && !empty($movefile['error']) ){
                    $msg = $movefile['error'];
                    $httpCode = 410;
                    return new WP_Error($httpCode, $msg);
                }
                $this->set_file_path($movefile['file']);
                $this->set_file_url($movefile['url']);
                if( $this->settings['save_database'] ){
                    $_wp_attached_file = str_replace($this->uploads['basedir'] . "/", "", $movefile['file']);
                    $attachment = array(
                        'guid' => $movefile['url'],
                        'post_mime_type' => $movefile['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', basename($fileName)),
                        'post_content' => '',
                        'post_status' => 'inherit',
                        'meta_input' => ['_wp_attached_file' => $_wp_attached_file],
                    );
                    $attach_id = wp_insert_attachment($attachment, $movefile['file'], 0);
                    return $attach_id;
                }else{
                    $this->set_empty_folder();
                    return $this->get_file_url();
                }
            }
        }
        /**
         * Send error
         */
        $httpCode = 401;
        $message = __('File is empty. Please upload something more substantial.');
        return new WP_Error($httpCode, $message);
    }

    function get_file_path(){
        return $this->file_path;
    }

    function get_file_url(){
        return $this->file_url;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        remove_filter('upload_dir', [$this, 'preupload_dir'], 100);
    }

}
