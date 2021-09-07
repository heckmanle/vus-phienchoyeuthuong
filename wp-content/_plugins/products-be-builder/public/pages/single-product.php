<?php
global $rep_product;
add_filter('pre_get_document_title', function ($title) use($rep_product){
	$title .= ' ' . $rep_product['product_title'];
	return $title;
}, 100);
$apply_cv = $_GET['apply'] ?? '';
if( $apply_cv == 'cv' ){
	include_once REAL_ESTATE_PRODUCTS_DIR . 'public/pages/apply-cv.php';
}else {
	get_header();
	echo $rep_product['product_description'];
	get_footer();
}