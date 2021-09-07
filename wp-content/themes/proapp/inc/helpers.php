<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 11/28/18
 * Time: 9:28 AM
 */

function esc_json_attr($data) {
    $data = esc_attr( str_replace('"', '&#34;', json_encode($data) ) );
    return $data;
}

function esc_json_attr_e($data) {
    echo esc_json_attr($data);
}

if(!function_exists('remove_script')){
    function remove_script($content){
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content);
    }
}

if( !function_exists( 'array_group_by' ) ){
    /***
     * @param $array
     * @param $key
     * @return array|bool
     */
    function array_group_by( $array, $key ){
        if( !is_string( $key ) && !is_float( $key ) && !is_int( $key ) && !is_callable( $key ) ){
            trigger_error( "array_group_by: key not exists in array", E_USER_ERROR );
            return false;
        }
        $func = ( !is_string( $key ) && is_callable( $key ) ? $key : null );
        $group = [];
        foreach ( $array as $k => $v ){
            $_key = null;
            if( is_callable( $func ) ){
                call_user_func( $func, $v );
            }elseif( is_object( $v ) && isset( $v->{$key} ) ){
                $_key = $v->{$key};
            } elseif( isset( $v[$key] ) ){
                $_key = $v[$key];
            }
            if( $_key === null )
                continue;
            $group[$_key][] = $v;
        }
        if( func_num_args() > 2 ){
            $args = func_get_args();
            foreach ( $group as $k => $value ){
                $params = array_merge( [$value], array_slice( $args, 2, func_num_args() ) );
                $group[$k] = call_user_func_array('array_group_by', $params );
            }
        }
        return $group;
    }
}
/**
 * Send a JSON response back to an Ajax request.
 *
 * @since 3.5.0
 * @since 4.7.0 The `$status_code` parameter was added.
 *
 * @param mixed $response    Variable (usually an array or object) to encode as JSON,
 *                           then print and die.
 * @param int   $status_code The HTTP status code to output.
 */
function send_response_json( $response, $status_code = null, $description = '' ) {
    @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
    if ( null !== $status_code ) {
        status_header( $status_code, $description );
        if ( ! $description ) {
            $description = get_status_header_desc( $status_code );
        }
    }
    if( !empty($description) ) {
        $description = json_encode($description);
        @header("Xhr-Message: {$description}");
    }
    echo wp_json_encode( $response, JSON_PRETTY_PRINT );
    exit;
}

if( !function_exists('array_depth') ) {
    /**
     * @param array $array
     * @return int
     */
    function array_depth(array $array)
    {
        $max_depth = 1;

        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = array_depth($value) + 1;

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }

        return $max_depth;
    }
}

if( !function_exists('array_depth_by_key') ) {
    /**
     * @param array $array
     * @return int
     */
    function array_depth_by_key(array $array, $key)
    {
        $max_depth = 1;
        if( is_array($array) && !empty($array) ) {
            $arr = isset($array[$key]) ? $array[$key] : $array;
            foreach ($arr as $value) {
                if (is_array($value) && isset($value[$key])) {
                    $depth = array_depth_by_key($value, $key) + 1;
                    if ($depth > $max_depth) {
                        $max_depth = $depth;
                    }
                }
            }
        }
        return $max_depth;
    }
}

function site_array_count_child($array, $parent = 0){
    $temp = [];$count = 0;
    foreach( $array as $key => $value ){
        if($value->parent == $parent){
            $temp[] = $value;
            unset($array[$key]);
        }
    }
    if($temp) {
        foreach ($temp as $key => $value) {
            $id = $value->id;
            if (!empty($array)) {
                $count++;
                $count += site_array_count_child( $array, $id );
            }
        }
    }
    return $count;
}

if( !function_exists('site_make_array_depth_for_parent') ){
    function site_make_array_depth_for_parent($array = [],$parent = 0, $name_key_parent = 'parent', $name_key_id = 'id'){
        $temp = [];$output = [];
        if( is_array($array) && !empty($array) ){
            foreach ($array as $k => $item){
                if( is_object($item) && isset($item->{$name_key_parent}) ){
                    $this_parent = $item->{$name_key_parent};
                }elseif(isset($item[$name_key_parent])){
                    $this_parent = $item[$name_key_parent];
                }else{
                    $this_parent = null;
                }
                if( $this_parent == null )
                    continue;
                if( $this_parent == $parent ){
                    $temp[] = $item;
                    unset($array[$k]);
                }
            }
            if( !empty($temp) ){
                foreach ($temp as $k => $item){
                    if( is_object($item) && isset($item->{$name_key_id}) ){
                        $_id = $item->{$name_key_id};

                    }elseif(isset($item[$name_key_id])){
                        $_id = $item[$name_key_id];
                    }else{
                        $_id = null;
                    }
                    if( is_object($item) && isset($item->{$name_key_parent}) ){
                        $_parent = $item->{$name_key_parent};

                    }elseif(isset($item[$name_key_parent])){
                        $_parent = $item[$name_key_parent];
                    }else{
                        $_parent = null;
                    }
                    if( $_id == null || $_parent == null)
                        continue;
                    $output[$_id]['value'] = $item;
                    if( !empty($array) ) {
                        $child = site_make_array_depth_for_parent($array, $_id, $name_key_parent, $name_key_id);
                        if( !empty($child) ){
                            $output[$_id]['child'] = $child;
                        }
                    }
                }
            }
        }
        return $output;
    }
}

function site_format_date($date, $separator = "-") {
    return preg_replace('#^(\d{4})(\-|\/)(\d{1,2})(\-|\/)(\d{1,2})#', "$1{$separator}$3{$separator}$5", $date);
}
function site_format_dmy_to_ymd( $date, $separator = "-" ){
    return preg_replace('#^(\d{1,2})(\-|\/)(\d{1,2})(\-|\/)(\d{4})#', "$5{$separator}$3{$separator}$1", $date);
}

function site_format_ymd_to_dmy( $date, $separator = "-" ){
    return preg_replace('#^(\d{4})(\-|\/)(\d{1,2})(\-|\/)(\d{1,2})#', "$5{$separator}$3{$separator}$1", $date);
}

function site_is_date( $date ) {
    $date = site_format_dmy_to_ymd($date);
    $regex = '/^(\d{4})(\-|\/)(\d{1,2})(\-|\/)(\d{1,2})$/';
    $result = preg_match( $regex, $date, $matches );
    if ( $result ) {
        $result = checkdate( $matches[3], $matches[5], $matches[1] );
    }

    return $result;
}

function site_format_HIS($str, $start = 0, $length = 5){
    return substr($str, $start, $length);
}

function roman_numerals_lookups() {
    $lookup = [
        'N'     => 1000000,
        'QN'    =>  900000,
        'O'     =>  500000,
        'QO'    =>  400000,
        'Q'     =>  100000,
        'RQ'    =>   90000,
        'P'     =>   50000,
        'RP'    =>   40000,
        'R'     =>   10000,
        'MR'    =>    9000,
        'S'     =>    5000,
        'MS'    =>    4000,
        'M'     =>    1000,
        'CM'    =>     900,
        'D'     =>     500,
        'CD'    =>     400,
        'C'     =>     100,
        'XC'    =>      90,
        'L'     =>      50,
        'XL'    =>      40,
        'X'     =>      10,
        'IX'    =>       9,
        'V'     =>       5,
        'IV'    =>       4,
        'I'     =>       1
    ];
    return $lookup;
}

function convert_roman_2_number($romanNumber) {
    $romanNumber = "{$romanNumber}";
    $lookup = roman_numerals_lookups();
    $result = 0;

    $len = strlen($romanNumber);
    while($len > 0) {
        foreach($lookup as $roman => $value){
            $l = strlen($roman);
            $num = substr($romanNumber, 0, $l);
            if( $num == $roman ) {
                $result += $value;
                $romanNumber = substr($romanNumber, $l);
                break;
            }
        }
        $len = strlen($romanNumber);
    }

    return $result;
}

function convert_number_2_roman($integer) {
// Convert the integer into an integer (just to make sure)
    $integer = abs(intval($integer));
    $result = '';

    // Create a lookup array that contains all of the Roman numerals.
    $lookup = roman_numerals_lookups();

    foreach($lookup as $roman => $value){
        // Determine the number of matches
        $matches = intval($integer / $value);

        // Add the same number of characters to the string
        $result .= str_repeat($roman, $matches);

        // Set the integer to be the remainder of the integer and the value
        $integer = $integer % $value;
    }

    // The Roman numeral should be built, return it
    return $result;
}


function getColorColumnNode( $key ){
    $colors = [
        '#008fd5',
        '#ee3f3f',
        '#7fc45d',
        '#fea055',
        '#c3a614',
    ];
    if( isset( $key ) ){
        if( array_key_exists( $key, $colors ) ){
            return $colors[$key];
        }else{
            return false;
        }
    }
    return $colors;
}

/**
 * get background color of node this
 * @param $create_by_node
 * @return string
 */
function getColorStar( $create_by_node ){
    if( $create_by_node == CORES_NODE_START ){
        $classNode = "color-company";
    }elseif( $create_by_node == CORES_NODE_MIDDLE ){
        $classNode = "color-department";
    }else{
        $classNode = "";
    }
    return $classNode;
}

function getColor4Column(){
    $color = [
        'finance' => '#008fd5',
        'customer' => '#ee3f3f',
        'operate' => '#7fc45d',
        'development' => '#fea055',
    ];
    return $color;
}

function renderDatasetChart($array_data = []){
    $finance = isset($array_data['finance']) ? $array_data['finance'] : 0;
    $customer = isset($array_data['customer']) ? $array_data['customer'] : 0;
    $operate = isset($array_data['operate']) ? $array_data['operate'] : 0;
    $development = isset($array_data['development']) ? $array_data['development'] : 0;
    $bgColor = getColor4Column();
    $data = [ (int)$finance, (int)$customer, (int)$operate, (int)$development ];
    $dataSet = [
        'labels' => [
            __('Tài chính', TPL_DOMAIN_LANG),
            __('Khách hàng', TPL_DOMAIN_LANG),
            __('Vận hành', TPL_DOMAIN_LANG),
            __('Phát triển', TPL_DOMAIN_LANG),
        ],
        'datasets' => [[
            'data' => $data,
            'backgroundColor' => array_values( $bgColor ),
        ]]
    ];
    return $dataSet;
}

function Gradient($HexFrom, $HexTo, $ColorSteps) {
    if( $ColorSteps == 1 ){
        $ColorSteps = 1.1;
    }

    $FromRGB['r'] = hexdec(substr($HexFrom, 0, 2));
    $FromRGB['g'] = hexdec(substr($HexFrom, 2, 2));
    $FromRGB['b'] = hexdec(substr($HexFrom, 4, 2));

    $ToRGB['r'] = hexdec(substr($HexTo, 0, 2));
    $ToRGB['g'] = hexdec(substr($HexTo, 2, 2));
    $ToRGB['b'] = hexdec(substr($HexTo, 4, 2));

    $StepRGB['r'] = ($FromRGB['r'] - $ToRGB['r']) / ($ColorSteps - 1);
    $StepRGB['g'] = ($FromRGB['g'] - $ToRGB['g']) / ($ColorSteps - 1);
    $StepRGB['b'] = ($FromRGB['b'] - $ToRGB['b']) / ($ColorSteps - 1);

    $GradientColors = array();

    for($i = 0; $i <= $ColorSteps; $i++) {
        $RGB['r'] = floor($FromRGB['r'] - ($StepRGB['r'] * $i));
        $RGB['g'] = floor($FromRGB['g'] - ($StepRGB['g'] * $i));
        $RGB['b'] = floor($FromRGB['b'] - ($StepRGB['b'] * $i));

        $HexRGB['r'] = sprintf('%02x', ($RGB['r']));
        $HexRGB['g'] = sprintf('%02x', ($RGB['g']));
        $HexRGB['b'] = sprintf('%02x', ($RGB['b']));

        $GradientColors[] = "#" . implode(NULL, $HexRGB);
    }
    $GradientColors = array_filter($GradientColors, "len");
    return $GradientColors;
}

function render_color_chart($percentItem){
    if( !empty($percentItem) ) {
        $colorStep = round($percentItem, 1);
        if( $percentItem == 1 ){
            $colorStep = 1.1;
        }
        $gradient_1 = Gradient("ff1300", "ffe100", $colorStep);
        $gradient_2 = Gradient("ffe100", "84e91c", $colorStep);
        $gradient_3 = Gradient("84e91c", "84e91c", $colorStep);

    }else{
        $gradient_1 = [];
        $gradient_2 = [];
        $gradient_3 = [];
    }
    $backgroundColor = array_merge($gradient_1, $gradient_2, $gradient_3);
    if( $percentItem == 1 ){
        $backgroundColor = array_merge($gradient_1, $gradient_2, [$gradient_3[0]]);
    }
    return $backgroundColor;
}

function len($val){
    return (strlen($val) == 7 ? true : false );
}

if( !function_exists('isValidIpAddress') ) {
    function isValidIpAddress($ip)
    {
        $flags = FILTER_FLAG_IPV4 |
            FILTER_FLAG_IPV6 |
            FILTER_FLAG_NO_PRIV_RANGE |
            FILTER_FLAG_NO_RES_RANGE;
        if (filter_var($ip, $flags) === false) {
            return false;
        }
        return true;
    }
}


if( !function_exists('get_real_ip') ) {
    function get_real_ip() {
        static $ipAddress;
        if( !empty($ipAddress) ) {
            // Check for shared internet/ISP IP
            if ( !empty($_SERVER['HTTP_CLIENT_IP']) && isValidIpAddress($_SERVER['HTTP_CLIENT_IP']) ) {
                $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
                return $ipAddress;
            }
            // Check for IPs passing through proxies
            if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                // Check if multiple IP addresses exist in var
                $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                foreach ($iplist as $ip) {
                    if (isValidIpAddress($ip) ) {
                        $ipAddress = $ip;
                        return $ipAddress;
                    }
                }
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED']) && isValidIpAddress($_SERVER['HTTP_X_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
            return $ipAddress;
        }
        if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && isValidIpAddress($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
            return $ipAddress;
        } else if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && isValidIpAddress($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (!empty($_SERVER['HTTP_FORWARDED']) && isValidIpAddress($_SERVER['HTTP_FORWARDED'])) {
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        } else {
            // Return unreliable IP address since all else failed
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        return $ipAddress;
    }
}

/**
 * @param $startDate
 * @param $endDate
 * @return bool|WP_Error
 */
function check_start_date_and_end_date($startDate, $endDate, $message = ''){
    /**
     * Kiểm tra ngày kết thúc và ngày bắt đầu
     * end_date > start_date
     */
    if( !empty($startDate) && !empty($endDate) ){
        if( empty($message) ){
            $message = __("Select an end date to be greater than the start date" ,TPL_DOMAIN_LANG );
        }
        if( strtotime($endDate) - strtotime($startDate) < 0 ){
            $httpCode = 470;
            $error = new WP_Error($httpCode, $message);
            return $error;
        }
    }else{
        $message = __("Please enter a start and end date" ,TPL_DOMAIN_LANG );
        $httpCode = 471;
        $error = new WP_Error($httpCode, $message);
        return $error;
    }
    return true;
}

function custom_paginate_links( $args = '' ) {
    global $wp_query, $wp_rewrite;

    // Setting up default values based on the current URL.
    $pagenum_link = html_entity_decode( get_pagenum_link() );
    $url_parts    = explode( '?', $pagenum_link );

    // Get max pages and current page out of the current query, if available.
    $total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
    $current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

    // Append the format placeholder to the base URL.
    $pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';

    // URL base depends on permalink settings.
    $format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
    $format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';

    $defaults = array(
        'base'               => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
        'format'             => $format, // ?page=%#% : %#% is replaced by the page number
        'total'              => $total,
        'current'            => $current,
        'aria_current'       => 'page',
        'show_all'           => false,
        'prev_next'          => true,
        'prev_text'          => __( '&laquo; Previous' ),
        'next_text'          => __( 'Next &raquo;' ),
        'end_size'           => 1,
        'mid_size'           => 2,
        'type'               => 'plain',
        'add_args'           => array(), // array of query args to add
        'add_fragment'       => '',
        'before_page_number' => '',
        'after_page_number'  => '',
    );

    $args = wp_parse_args( $args, $defaults );

    if ( ! is_array( $args['add_args'] ) ) {
        $args['add_args'] = array();
    }

    // Merge additional query vars found in the original URL into 'add_args' array.
    if ( isset( $url_parts[1] ) ) {
        // Find the format argument.
        $format = explode( '?', str_replace( '%_%', $args['format'], $args['base'] ) );
        $format_query = isset( $format[1] ) ? $format[1] : '';
        wp_parse_str( $format_query, $format_args );

        // Find the query args of the requested URL.
        wp_parse_str( $url_parts[1], $url_query_args );

        // Remove the format argument from the array of query arguments, to avoid overwriting custom format.
        foreach ( $format_args as $format_arg => $format_arg_value ) {
            unset( $url_query_args[ $format_arg ] );
        }

        $args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $url_query_args ) );
    }

    // Who knows what else people pass in $args
    $total = (int) $args['total'];
    if ( $total < 2 ) {
        return;
    }
    $current  = (int) $args['current'];
    $end_size = (int) $args['end_size']; // Out of bounds?  Make it the default.
    if ( $end_size < 1 ) {
        $end_size = 1;
    }
    $mid_size = (int) $args['mid_size'];
    if ( $mid_size < 0 ) {
        $mid_size = 2;
    }
    $add_args = $args['add_args'];
    $r = '';
    $page_links = array();
    $dots = false;

    if ( $args['prev_next'] && $current && 1 < $current ) :
        $link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
        $link = str_replace( '%#%', $current - 1, $link );
        if ( $add_args )
            $link = add_query_arg( $add_args, $link );
        $link .= $args['add_fragment'];
        /**
         * Filters the paginated links for the given archive pages.
         *
         * @since 3.0.0
         *
         * @param string $link The paginated link URL.
         */
        $page_links[] = '<a class="prev page-numbers" data-paged="' . ($current - 1) . '" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['prev_text'] . '</a>';
    endif;
    for ( $n = 1; $n <= $total; $n++ ) :
        if ( $n == $current ) :
            $page_links[] = "<span aria-current='" . esc_attr( $args['aria_current'] ) . "' class='page-numbers current'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</span>";
            $dots = true;
        else :
            if ( $args['show_all'] || ( $n <= $end_size || ( $current && $n >= $current - $mid_size && $n <= $current + $mid_size ) || $n > $total - $end_size ) ) :
                $link = str_replace( '%_%', 1 == $n ? '' : $args['format'], $args['base'] );
                $link = str_replace( '%#%', $n, $link );
                if ( $add_args )
                    $link = add_query_arg( $add_args, $link );
                $link .= $args['add_fragment'];

                /** This filter is documented in wp-includes/general-template.php */
                $page_links[] = "<a class='page-numbers' data-paged='" . ($n) . "' href='" . esc_url( apply_filters( 'paginate_links', $link ) ) . "'>" . $args['before_page_number'] . number_format_i18n( $n ) . $args['after_page_number'] . "</a>";
                $dots = true;
            elseif ( $dots && ! $args['show_all'] ) :
                $page_links[] = '<span class="page-numbers dots">' . __( '&hellip;' ) . '</span>';
                $dots = false;
            endif;
        endif;
    endfor;
    if ( $args['prev_next'] && $current && $current < $total ) :
        $link = str_replace( '%_%', $args['format'], $args['base'] );
        $link = str_replace( '%#%', $current + 1, $link );
        if ( $add_args )
            $link = add_query_arg( $add_args, $link );
        $link .= $args['add_fragment'];

        /** This filter is documented in wp-includes/general-template.php */
        $page_links[] = '<a class="next page-numbers" data-paged="' . ($current + 1) . '" href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '">' . $args['next_text'] . '</a>';
    endif;
    switch ( $args['type'] ) {
        case 'array' :
            return $page_links;

        case 'list' :
            $r .= "<ul class='page-numbers'>\n\t<li>";
            $r .= join("</li>\n\t<li>", $page_links);
            $r .= "</li>\n</ul>\n";
            break;

        default :
            $r = join("\n", $page_links);
            break;
    }
    return $r;
}

function core_attributes(array $attributes = []){
	if( !empty($attributes) ) {
		foreach ($attributes as $attribute => &$data) {
			$data = implode(' ', (array)$data);
			$data = $attribute . '="' . esc_html($data) . '"';
		}
	}
    return $attributes ? ' ' . implode(' ', $attributes) : '';
}

function core_upload_file($file, $mime_types = []){
    require_once THEME_DIR . '/inc/uploadFile.php';
    if( empty($mime_types) ){
        $mime_types = [
            'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
    }
    $uploadFile = new \Cores\Inc\UploadFile($file, '', '', $mime_types);
    return $uploadFile->handle_upload();
}

function core_compile_option($arr, $seleted = ''){
    $option = "";
    foreach ($arr as $key => $value){
        $is_select = $key == $seleted ? 'selected' : '';
        $option .= sprintf('<option value="%s" %s>%s</option>', $key, $is_select, $value);
    }
    return $option;
}

function core_get_query_str($url = null, $component = -1, $array = []){
	if( empty($url) )
		$url = $_SERVER['REQUEST_URI'];
    $query = parse_url($url, $component);
    if( isset($query['query']) && !empty($query['query']) ){
        wp_parse_str($query['query'], $array);
        return $array;
    }
    return false;
}

function core_get_query_str_page($url = '', $component = -1, $array = []){
	return core_get_query_str_param(CORES_PAGE, $url, $component, $array);
}
function core_get_query_str_param($param, $url = '', $component = -1, $array = []){
	$query_str = core_get_query_str($url, $component, $array);
	return isset($query_str[$param]) ? $query_str[$param] : '';
}

function core_convert_str_to_numberic($value){
    return preg_replace("/[^\d]/", "", $value);
}

if( ! function_exists( 'core_bg_size' ) )
{
    /**
     * Skin
     *
     * @return array
     */
    function core_bg_size(){
        return array(
            'auto' => __('Auto', TPL_DOMAIN_LANG),
            'contain' => __('Contain', TPL_DOMAIN_LANG),
            'cover' => __('Cover', TPL_DOMAIN_LANG),
        );
    }
}
if( ! function_exists( 'core_bg_repeat' ) )
{
    /**
     * Skin
     *
     * @return array
     */
    function core_bg_repeat(){
        return array(
            'repeat' => __('Repeat', TPL_DOMAIN_LANG),
            'repeat-x' => __('Repeat-x', TPL_DOMAIN_LANG),
            'repeat-y' => __('Repeat-y', TPL_DOMAIN_LANG),
            'no-repeat' => __('No repeat', TPL_DOMAIN_LANG),
            #'space' => __('Space', TPL_DOMAIN_LANG),
            #'round' => __('Round', TPL_DOMAIN_LANG),
        );
    }
}

function core_compile_background_to_style($background, $unit = 'px'){
    $default = [
        "bg_color" => "",
        "bg_image" => "",
        "bg_size" => "",
        "bg_repeat" => "",
        "bg_position_x" => "",
        "bg_position_y" => "",
    ];
    $background = wp_parse_args($background, $default);
    extract($background);
    $background_color = $background_image = $background_position = $background_size = $background_repeat = "";
    if( !empty($bg_color) ){
        $background_color = "background-color: {$bg_color};";
    }
    if( !empty($bg_image) ){
        $background_image = "background-image: url({$bg_image});";
    }
    if( !empty($bg_size) ){
        $background_size = "background-size: {$bg_size};";
    }
    if( !empty($bg_repeat) ){
        $background_repeat = "background-repeat: {$bg_repeat};";
    }
    if( !empty($bg_position_x) ){
        $background_position = "background-position-x: {$bg_position_x}{$unit};";
    }
    if( !empty($bg_position_y) ){
        $background_position .= "background-position-y: {$bg_position_y}{$unit};";
    }
    $style = $background_color . $background_image . $background_position . $background_size . $background_repeat;
    return $style;
}

function core_get_text_from($key = ''){
	$array = [
		'from' => __('Từ', TPL_DOMAIN_LANG),
		'above' => __('Trên', TPL_DOMAIN_LANG),
	];

	if( !empty($key) ){
		if( isset($array[$key]) ){
			return $array[$key];
		}else{
			return [];
		}
	}

	return $array;
}

function core_get_text_to($key = ''){
	$array = [
		'to' => __('Đến', TPL_DOMAIN_LANG),
		'under' => __('Dưới', TPL_DOMAIN_LANG),
	];

	if( !empty($key) ){
		if( isset($array[$key]) ){
			return $array[$key];
		}else{
			return [];
		}
	}

	return $array;
}

function core_get_contract_main_sub($key = ''){
	$contract = [
		'main_contract' => __('Hợp đồng chính', TPL_DOMAIN_LANG),
		'subcontract' => __('Hợp đồng phụ', TPL_DOMAIN_LANG),
	];
	if( !empty($key) ){
		if( isset($contract[$key]) ){
			return $contract[$key];
		}else{
			return false;
		}
	}
	return $contract;
}

function core_get_insurance($key = ''){
	$array = [
		'bhxh'              => __('BHXH', TPL_DOMAIN_LANG),
		'httt'              => __('HT-TT', TPL_DOMAIN_LANG),
		'tsod'              => __('TS-OĐ', TPL_DOMAIN_LANG),
		'tnbnn'             => __('BHTN-BNN', TPL_DOMAIN_LANG),
		'bhyt'              => __('BHYT', TPL_DOMAIN_LANG),
		'bhtn'              => __('BHTN', TPL_DOMAIN_LANG),
		'union_fees'        => __('Đoàn phí', TPL_DOMAIN_LANG),
	];
	$array = apply_filters('app/core_get_insurance', $array);
	if( !empty($key) ){
		if( isset($array[$key]) ){
			return $array[$key];
		}else{
			return '';
		}
	}
	return $array;
}

function hook_core_get_insurance_remove_social_insurance($array){
	if( isset($array['bhxh']) ){
		unset($array['bhxh']);
	}
	return $array;
}

function core_get_define_condition( $settings = [], $key = '' ){
	$conditions = [
		CONDITION_LESS                      => '<',
		CONDITION_LESS_THAN_EQUAL           => '≤',
		CONDITION_GREATER                   => '>',
		CONDITION_GREATER_THAN_EQUAL        => '≥',
		CONDITION_EQUAL                     => '=',
		CONDITION_DIFFERENT                 => '≠',
	];
	$defaults = [
		CONDITION_LESS                      => true,
		CONDITION_LESS_THAN_EQUAL           => true,
		CONDITION_GREATER                   => true,
		CONDITION_GREATER_THAN_EQUAL        => true,
		CONDITION_EQUAL                     => true,
		CONDITION_DIFFERENT                 => false,
	];
	$settings = wp_parse_args($settings, $defaults);

	foreach ($settings as $k => $value){
		if( !$value && isset($conditions[$k]) ){
			unset($conditions[$k]);
		}
	}

	if( !empty( $key ) ){
		if( array_key_exists( $key, $conditions ) ){
			return $conditions[$key];
		}else{
			return [];
		}
	}
	return $conditions;
}

function core_get_income_type($key = ''){
	$array = [
		//'total_income' => __('Tổng thu nhập', TPL_DOMAIN_LANG),
		'taxable_income' => __('TN chịu thuế', TPL_DOMAIN_LANG),
		'tax_income' => __('TN tính thuế ', TPL_DOMAIN_LANG),
	];
	if( !empty( $key ) ){
		if( array_key_exists( $key, $array ) ){
			return $array[$key];
		}else{
			return [];
		}
	}
	return $array;
}

function core_get_yes_no($key = ''){
	$array = [
		'yes' => __('Text Yes Have', TPL_DOMAIN_LANG),
		'no' => __('Text No Have', TPL_DOMAIN_LANG),
	];
	if( !empty($key) ){
		if( isset($array[$key]) ){
			return $array[$key];
		}else{
			return [];
		}
	}
	return $array;
}

function core_get_allowance_defaults($key = ''){
	$array = [
		'money' => [
			'title' => __('Allowance for money', TPL_DOMAIN_LANG),
			'tax' => '',
			'free_tax' => '',
		],
		'uniform' => [
			'title' => __('Uniform allowance', TPL_DOMAIN_LANG),
			'tax' => '',
			'free_tax' => '',
		],
		'housing' => [
			'title' => __('Housing allowance', TPL_DOMAIN_LANG),
			'tax' => '',
			'free_tax' => '',
		],
		'phone' => [
			'title' => __('Phone allowance', TPL_DOMAIN_LANG),
			'tax' => '',
			'free_tax' => '',
		],
		'transportation' => [
			'title' => __('Transportation allowance', TPL_DOMAIN_LANG),
			'tax' => '',
			'free_tax' => '',
		],
		'child_support' => [
			'title' => __('Nuôi con', TPL_DOMAIN_LANG),
			'tax' => '',
			'free_tax' => '',
		],
		'responsibility' => [
			'title' => __('Trách nhiệm', TPL_DOMAIN_LANG),
			'tax' => '',
			'free_tax' => '',
		],
	];

	if( !empty($key) ){
		if( isset($array[$key]) ){
			return $array[$key];
		}else{
			return [];
		}
	}
	return $array;
}

function core_convert_currency_to_number($number){
	if( empty($number) )
		return 0;
	$number = str_replace(THOUNSAND_SEP, '', $number);
	return $number;
}

function core_convert_decimal_to_number_syntax($number){
	$number = str_replace(DECIMAL_SEP, '.', $number);
	return $number;
}
function core_convert_number_syntax_to_decimal($number){
	$number = str_replace('.', DECIMAL_SEP, $number);
	return $number;
}

function core_convert_number_to_syntax($number){
	$number = core_convert_currency_to_number($number);
	$number = core_convert_decimal_to_number_syntax($number);
	return $number;
}

function core_convert_number_to_word( $number) {
	$number = (int) $number;

	if ( $number >= 10 ) {
		$string = "";
		$remainder = 0;
		$quotient = 0;

		if (  $number >= 1000000000 ) {
			$string = "tỷ";
			$remainder = $number % 1000000000;
			$quotient = $number / 1000000000 ;
		}
		else if ( $number >= 1000000 ) {
			$string = "triệu";
			$remainder = $number % 1000000;
			$quotient = $number / 1000000 ;
		}
		else if ( $number >= 1000 ) {
			$string = "ngàn";
			$remainder = $number % 1000;
			$quotient = $number / 1000 ;
		}
		else if ( $number >= 100 ) {
			$string = "trăm";
			$remainder = $number % 100;
			$quotient = $number / 100 ;

			if ( $remainder < 10 && $remainder > 0 ) {
				$string = "trăm lẻ";
			}
		}
		else if ($number >= 10) {
			$string = "mười";
			$remainder = $number % 10;
			$quotient = $number / 10 ;

			if ( $number >= 20 ) {
				$string = "mươi";
			}

			if ( $remainder == 5 ) {
				$string .= " lăm";
				$remainder = 0;
			}
			else if ( $remainder == 1 && $number > 20 ) {
				$string .= " mốt";
				$remainder = 0;
			}
		}

		$start = "";
		$end = "";

		if ( $quotient > 0 && $number >= 20 ) {
			$start = core_convert_number_to_word( $quotient ) . " ";
		}


		if ( $remainder > 0 ) {
			$end = " " . core_convert_number_to_word( $remainder );
		}

		return $start . $string . $end;
	}
	else if ( $number == 9 ) {
		return "chín";
	}
	else if ( $number == 8 ) {
		return "tám";
	}
	else if ($number == 7) {
		return "bảy";
	}
	else if ($number == 6) {
		return "sáu";
	}
	else if ($number == 5) {
		return "năm";
	}
	else if ($number == 4) {
		return "bốn";
	}
	else if ($number == 3) {
		return "ba";
	}
	else if ($number == 2) {
		return "hai";
	}
	else if ($number == 1) {
		return "một";
	}

	return "không";
}

function core_convert_number_to_format($number, $decimals = 0){
	$decimals = absint($decimals);
	if( $decimals > 0 ){
		$number = round($number, $decimals);
	}
	$number = number_format($number, $decimals, DECIMAL_SEP, THOUNSAND_SEP);
	$explode = explode(DECIMAL_SEP, $number);
	$end_explode = end($explode);
	if( (int)$end_explode === 0 ){
		$number = str_replace(DECIMAL_SEP . $end_explode, '', $number);
	}
	return $number;
}

function core_check_date($date){
	$date = site_format_dmy_to_ymd($date);
	return strtotime($date);
}

function core_check_start_end_date($start_date, $end_date, $is_empty_end_date = true){
	$start_date_ymd = site_format_dmy_to_ymd($start_date);
	$strtotime_start_date = core_check_date($start_date_ymd);

	if( !$strtotime_start_date ){
		return new \WP_Error(401, __('Nhập ngày bắt đầu đúng định dạng', TPL_DOMAIN_LANG));
	}
	if( !$is_empty_end_date ) {
		$end_date_ymd = site_format_dmy_to_ymd($end_date);
		$strtotime_end_date = core_check_date($end_date_ymd);
		if ( !$strtotime_end_date ) {
			return new \WP_Error(401, __('Nhập ngày kết thúc đúng định dạng', TPL_DOMAIN_LANG));
		}

		if ($strtotime_end_date <= $strtotime_start_date) {
			return new \WP_Error(401, __('Nhập ngày kết thúc phải lớn hơn ngày bắt đầu', TPL_DOMAIN_LANG));
		}
	}
	return true;
}

function core_is_json($string){
	return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

if( !function_exists('core_get_html_by_status') ) {
	function core_get_html_by_status($status)
	{
		$html_status = '';
		if ($status == STATUS_DRAFT) {
			$html_status = "<span class='site-status status-draft' data-toggle='tooltip' data-html='true' data-placement='top' title='" . __('Draft', TPL_DOMAIN_LANG) . "'><i class='icon-clipboard-edit'></i></span>";
		} elseif ($status == STATUS_APPROVED) {
			$html_status = "<span class='site-status status-approved' data-toggle='tooltip' data-html='true' data-placement='top' title='" . __('Đã trả lương', TPL_DOMAIN_LANG) . "'><i class='icon-check'></i></span>";
		} else {
			$html_status = "<span class='site-status status-warning' data-toggle='tooltip' data-html='true' data-placement='top' title='" . __('Chưa cập nhật lương mẫu', TPL_DOMAIN_LANG) . "'><i class=\"fal fa-exclamation-triangle\"></i></span>";
		}
		return $html_status;
	}
}

function core_explode_date($date){
	$separator = DATE_SEP;
	$length = strlen($date);
	if( $length > 10 )
		return false;

	if ( preg_match( '/^\d\d\d\d(\-|\/)\d\d(\-|\/)\d\d$/', $date ) ) {
		$_date = site_format_ymd_to_dmy($date, $separator);
	}elseif( preg_match( '/^\d\d\d\d(\-|\/)\d\d$/', $date ) ){
		$_date = preg_replace('#^(\d{4})(\-|\/)(\d{1,2})#', "$3{$separator}$1", $date);
	}elseif(preg_match( '/^\d\d(\-|\/)\d\d(\-|\/)\d\d\d\d$/', $date )){
		$_date = preg_replace('#^(\d{1,2})(\-|\/)(\d{1,2})(\-|\/)(\d{4})#', "$1{$separator}$3{$separator}$5", $date);
	}else{
		$_date = preg_replace('#^(\d{1,2})(\-|\/)(\d{4})#', "$1{$separator}$3", $date);
	}
	$_date = explode($separator, $_date);
	$output = [
		'day' => 0,
		'month' => 0,
		'year' => 0,
	];
	if( count($_date) > 2 ){
		$output['day'] = $_date[0];
		$output['month'] = $_date[1];
		$output['year'] = $_date[2];
	}else{
		$output['month'] = $_date[0];
		$output['year'] = $_date[1];
		unset($output['day']);
	}
	return $output;
}

function core_get_dates_by_start_end_date($start_date, $end_date, $excludes = []){
	$_start_date = site_format_dmy_to_ymd($start_date);
	$_end_date = site_format_dmy_to_ymd($end_date);
	$output = [];
	try {
		$_start_date = new DateTime($_start_date);
		$_end_date = new DateTime($_end_date);
	} catch (Exception $ex) {
		return [];
	}

	while ($_start_date <= $_end_date) {
		// find the timestamp value of start date
		$date = $_start_date->format('Y-m-d');
		if( !empty($excludes) ) {
			$filter = array_filter($excludes, function ($item) use ($date) {
				$d = site_format_dmy_to_ymd($item, '-');
				$d = str_replace('/', '-', $d);
				$d = substr($d, 0, 10);
				return $d == $date;
			});
			if( !$filter ){
				$output[] = $date;
			}
		}else{
			$output[] = $date;
		}
		$_start_date->modify('+1 day');
	}
	return $output;
}

function core_get_holiday_by_start_end_date($start_date, $end_date){
	$startDate = site_format_dmy_to_ymd($start_date);
	$endDate = site_format_dmy_to_ymd($end_date);
	$output = [];
	try {
		$startDate = new DateTime($startDate);
		$endDate = new DateTime($endDate);
	} catch (Exception $ex) {
		$message = __('Date not format', TPL_DOMAIN_LANG);
		return new \WP_Error(401, $message);
	}
	$settings_holiday = \Cores\Includes\Core\Settings::get_setting_holiday();
	while ($startDate <= $endDate) {
		// find the timestamp value of start date
		$timestamp = strtotime($startDate->format('d-m-Y'));

		// find out the day for timestamp and increase particular day
		#$weekDay = date('l', $timestamp);
		$weekDay = date('w', $timestamp);
		$string_week = convert_int_week_to_string_week($weekDay);
		$year = date('Y', $timestamp);
		$month = date('m', $timestamp);
		$day = date('d', $timestamp);

		/**
		 * Kiểm tra ngày lễ
		 */
		if (!empty($settings_holiday)) {
			$filter_holiday = array_filter($settings_holiday, function ($item) use ($day, $month, $year) {
				$date = preg_replace('#^(\d{1,2})(\-|\/)(\d{1,2})(\-|\/)(\d{4})#', '$3$4$1$2$5', $item['date']);
				$timestamp_holiday = strtotime($date);
				$day_holiday = date('d', $timestamp_holiday);
				$month_holiday = date('m', $timestamp_holiday);
				$year_holiday = date('Y', $timestamp_holiday);
				$condition = $day_holiday == $day && $month_holiday == $month && $year_holiday == $year;
				return $condition;
			});
			if( !empty($filter_holiday) ){
				$filter_holiday = array_shift($filter_holiday);
				$output[$timestamp] = $filter_holiday;
			}
		}
		// increase startDate by 1
		$startDate->modify('+1 day');
	}
	return $output;
}

function core_get_day_off_by_start_end_date($start_date, $end_date, $half_day = '', $option_half_day = ''){
	$startDate = site_format_dmy_to_ymd($start_date);
	$endDate = site_format_dmy_to_ymd($end_date);
	$time_start = strtotime($startDate);
	$time_end = strtotime($endDate);
	$time = $time_end - $time_start;
	$output = [];
	/**
	 * Lý do + thêm 1 ngày
	 * Vì chưa tính ngày hiện tại nên phải + thêm 1 ngày
	 */
	$time += 86400;

	try {
		$startDate = new DateTime($startDate);
		$endDate = new DateTime($endDate);
	} catch (Exception $ex) {
		$message = __('Date not format', TPL_DOMAIN_LANG);
		return new \WP_Error(401, $message);
	}
	$settings_working = get_option('settings_working', []);
	$settings_holiday = get_option('settings_holiday', []);
	while ($startDate <= $endDate) {
		// find the timestamp value of start date
		$timestamp = strtotime($startDate->format('d-m-Y'));

		// find out the day for timestamp and increase particular day
		#$weekDay = date('l', $timestamp);
		$weekDay = date('w', $timestamp);
		$string_week = convert_int_week_to_string_week($weekDay);
		$year = date('Y', $timestamp);
		$month = date('m', $timestamp);
		$day = date('d', $timestamp);
		/**
		 * Kiểm tra xem có tồn tại ngày thứ 7 hay chủ nhật không
		 * Nếu có thì kiểm tra xem trong cài đặt có cài đặt làm ngày thứ 7 hay chủ nhật không
		 */
		/**
		 * Kiểm tra ngày làm việc
		 */
		$filter_working = array_filter($settings_working, function ($key) use ($string_week) {
			$condition = $key == $string_week;
			return $condition;
		}, ARRAY_FILTER_USE_KEY);

		/**
		 * Kiểm tra ngày lễ
		 */
		$filter_holiday = [];
		if (!empty($settings_holiday)) {
			$filter_holiday = array_filter($settings_holiday, function ($item) use ($day, $month, $year) {
				$date = preg_replace('#^(\d{1,2})(\-|\/)(\d{1,2})(\-|\/)(\d{4})#', '$3$4$1$2$5', $item['date']);
				$timestamp_holiday = strtotime($date);
				$day_holiday = date('d', $timestamp_holiday);
				$month_holiday = date('m', $timestamp_holiday);
				$year_holiday = date('Y', $timestamp_holiday);
				$condition = $day_holiday == $day && $month_holiday == $month && $year_holiday == $year;
				return $condition;
			});
		}
		if (!empty($filter_working)) {
			if (empty($filter_holiday)) {
				$working = isset($filter_working[$string_week]) ? $filter_working[$string_week] : [];
				if (!empty($working)) {
					if ($working['status-working'] == 'off') {
						$output[$timestamp] = "{$year}/{$month}/{$day}";
					}
				}
			}else{
				$output[$timestamp] = "{$year}/{$month}/{$day}";
			}

		}
		// increase startDate by 1
		$startDate->modify('+1 day');
	}
	return $output;
}

function core_get_pagination($total, $post_per_page = 20, $class = '', $args = []){
	if( $post_per_page < $total){
		$big = 123456789;
		$pagination = '';
		$totalPage = ceil($total/$post_per_page);
		if( !empty(get_query_var('paged')) ){
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		}else{
			$paged = (get_query_var('page')) ? get_query_var('page') : 1;
		}
		$page_format = custom_paginate_links( array_merge(array(
			'base' => str_replace( $big, '%#%', get_pagenum_link( $big, false) ),
			'format' => '?paged=%#%',
			'current' => max( 1, $paged ),
			'total' => $totalPage,
			'type'  => 'array',
		), $args) );

		if( is_array($page_format) ) {
			$pagination = "<div class='clearfix'></div><nav class=\"{$class}\" aria-label=\"Page navigation\"><ul class=\"pagination font-inherit\">";
			$regex = "#>.*(Trang sau|Trang trước|Next|Previous)#i";
			foreach ( $page_format as $page ) {
				if( preg_match( $regex, $page, $match ) ){
					if( !empty( $match ) ){
						$page = str_replace( "{$match[1]}", '', $page );
						$page = trim( $page );
					}
				}
				$pagination .= "<li class='d-inline-block page-item'>{$page}</li>";
			}
			$pagination .= '</ul></nav>';
		}
	}else{
		$pagination = null;
	}
	return $pagination;
}

function core_render_pagination($current_url, $pagination){
	if(empty($pagination)){
		return '';
	}
	$options = [20, 50, 100, 200];
	$options = apply_filters('app/pagination/display', $options);
	$options_html = "";
	$limit = core_get_query_str_param('limit');
	$limit = !empty($limit) ? absint($limit) : 20;
	unset($_GET['limit']);
	$display_by_limit_url = add_query_arg($_GET, $current_url);
	if( !empty($options) && is_array($options) ) {
		foreach ($options as $k => $item) {
			$selected = $limit == $item ? 'selected' : '';
			$options_html .= "<option {$selected} value=\"{$item}\">{$item}</option>";
		}
		$html_display = '
			<div class="">
                <div class="input-group px-0 d-flex align-items-center">
                    <div class="input-group-prepend">
                        <span>' . __("Hiển thị số lượng dòng", TPL_DOMAIN_LANG) . '</span>
                    </div>
                    <div class="ml-3">
                        <select autocomplete="off" onchange="window.location.href = \'' . $display_by_limit_url . (!empty($_GET) ? "&" : "?") . 'limit=\' + this.value;" class="custom-select form-control display-limit-pagination">
                            ' . $options_html . '
                        </select>
                    </div>
                </div>
            </div>
			';
	}else{
		$html_display = '';
	}

	$html = '
		<div class="section-pagination">
			<div class="d-flex pb-3">
	            ' . $html_display . '
	            <div class="ml-3 d-flex  align-items-center">
	                ' . $pagination . '
	            </div>
	        </div>
        </div>';

	return $html;
}

function core_random_string($length) {
	$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$rand_string = '';

	for ($i = 0; $i < $length; $i++) {
		$rand_string .= $characters[ rand(0, strlen($characters)) ];
	}

	return $rand_string;
}

function core_rest_success_to_response($data){
	$response = new WP_REST_Response( ['data' => $data], 200 );
	return $response;
}
function core_rest_error_to_response( \WP_Error $error ) {
	$error_data = $error->get_error_data();

	if ( is_array( $error_data ) && isset( $error_data['status'] ) ) {
		$status = $error_data['status'];
	} else {
		$status = 500;
	}

	$errors = array();

	foreach ( (array) $error->errors as $code => $messages ) {
		foreach ( (array) $messages as $message ) {
			$errors[] = array(
				'code'    => $code,
				'message' => $message,
				'data'    => $error->get_error_data( $code ),
			);
		}
	}

	$data = $errors[0];
	if ( count( $errors ) > 1 ) {
		// Remove the primary error.
		array_shift( $errors );
		$data['additional_errors'] = $errors;
	}

	status_header($status, $data['message']);
	$response = new WP_REST_Response( $data, $status );

	return $response;
}

function core_rest_error($code, $message, $status){
	$error = new \WP_Error($code, $message, ['status' => $status]);
	return $error;
}

function core_rest_error_from_wp_error($response){
	$status = is_numeric($response->get_error_code()) ? $response->get_error_code() : 401;
	return core_rest_error($response->get_error_code(), $response->get_error_message(), $status);
}


/**
 * @param $date (MM/DD/YYYY | YYYY/MM/DD | typeof strtotime)
 * @param bool $short
 * @param string $format
 * @return false|string
 */
function core_livetimestamp( $date, $after_text = '', $short = true, $format = 'd/m/Y' ){
	if ( '0000-00-00 00:00:00' === $date ) {
		$h_time = '0000-00-00 00:00:00';
	}else {
		$m_time = $date;
		if (!is_numeric($date)) {
			$m_time = strtotime($date);
		}
		$current_time = current_time('timestamp');
		$time_diff = $current_time - $m_time;
		if ($time_diff > 0 && $time_diff < DAY_IN_SECONDS || $short) {
			$h_time = sprintf( __( '%s' ), human_time_diff( $m_time, $current_time ) );
			if( $time_diff === 0 ) {
				$h_time = __('Vừa xong');
			}elseif (!empty($after_text)) {
				$h_time .= ' ' . $after_text;
			}
		} else {
			$h_time = date( $format, $m_time );
		}
	}
	return $h_time;
}