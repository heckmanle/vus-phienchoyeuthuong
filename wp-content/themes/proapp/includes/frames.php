<?php
namespace SME\Frames;
class Frames{

    public $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }


}