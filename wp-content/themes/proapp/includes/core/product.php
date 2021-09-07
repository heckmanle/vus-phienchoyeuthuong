<?php


namespace SME\Includes\Core;


class Product
{
	public static function products($params = []){
		global $system_api;
		$fields = [
			'id',
			'product_code',
			'product_title',
			'product_unit',
			'product_price',
			'product_gallery',
			'product_slug',
			'address',
			'product_seo_keywords',
			'product_seo_description',
			'product_seo_link',
			'product_properties',
			'product_category' => [
				'id',
				'cate_title'
			],
			'product_number',
			'product_pay',
			'product_status'
		];
		$offset = $params['offset'] ?? 0;
		$limit = $params['limit'] ?? 0;
		$product_category = $params['product_category'] ?? '';
		$product_store = $params['product_store'] ?? '';
		$user_id = $params['user_id'] ?? '';
		$search = $params['search'] ?? '';
		$long = $params['long'] ?? 0;
		$lat = $params['lat'] ?? 0;
		$radius = $params['radius'] ?? 0;
		$long = (double)$long;
		$lat = (double)$lat;
		$radius = (double)$radius;
		$_params = [];
		$compact = compact('offset', 'limit', 'product_category', 'product_store', 'user_id', 'search', 'long', 'lat', 'radius');
		foreach ($compact as $key => $value){
			if( $value ){
				$_params[$key] = $value;
			}
		}
		$response = $system_api->re_query('GET', 'products', [
			'params' => $_params,
			'fields' => $fields,
		]);
		if( is_wp_error($response) )
			return $response;
		return $response['products'];
	}
}
