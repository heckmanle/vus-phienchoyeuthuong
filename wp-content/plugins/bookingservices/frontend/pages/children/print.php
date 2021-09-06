<?php
/**
 * Created by PhpStorm.
 * User: Richard
 * Date: 25/12/2020
 * Time: 21:33
 */
global $core_bookingservices_class;
$id = isset($_GET['id']) ? $_GET['id'] : '';
$trigger_print = isset($_GET['print']) ? true : false;
$get_data = $core_bookingservices_class->get_once(['id' => $id]);

$data = [
	'booking' => $get_data,
	'trigger_print' => $trigger_print,
	'styles' => [
		THEME_URL . '/style.css',
		THEME_URL . '/main.css',
		THEME_URL . '/layout-print.css',
	]
];
if( !empty($get_data) ) {
	ob_start();
	include_once BOOKINGSERVICES_MODULE_DIR . 'template/print/print-booking.twig';
	$template = ob_get_clean();
	$html = \SME\Inc\TemplateTWIG::compileTemplateTwig($template, $data);
}else{
	$html = '<h3 class="my-4 text-center">' . __('Phiếu chưa được khởi tạo') . '</h3>';
}
echo $html;

?>
