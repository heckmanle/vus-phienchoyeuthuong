<?php


namespace SME\Includes\Core;


class Booking
{
	public static function get_registration_forms($flag = '', $status = '', $events = ''){
		global $system_api;
		$params = [];
		if( !empty($flag) ){
			$params['flag'] = $flag;
		}
		if( !empty($status) ){
			$params['status'] = $status;
		}
		if( !empty($events) ){
			$params['events'] = $events;
		}
		$response = $system_api->re_query(
			'GET',
			'registrationForms',
			[
				'params' => $params,
				'fields' => [
					'id',
					'client_type',
					'into_money',
					'client' => [
						'id', 'name', 'registered'
					],
					'vat',
					'payment_type',
					'discount',
					'booth',
					'votes',
					'status',
					'date',
					'list_of_equipment' => [
						'product' => [
							'id',
							'product_code',
							'product_title',
							'product_price',
							'product_unit',
						],
						'quantity',
						'into_money',
						'image',
					],
					'author' => [
						'id',
						'name'
					],
					'events' => [
						'id',
						'title',
						'photos',
						'feature_image',
						'title',
						'description',
					],
					'total_ticket',
					'per_charge',
					'image_confirm',
					'description',
					'flag',
				]
			], true

		);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response['registrationForms'];
	}
}
