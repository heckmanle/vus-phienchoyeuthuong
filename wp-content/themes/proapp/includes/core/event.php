<?php


namespace SME\Includes\Core;


class Event
{
	public static $fields = [
		'id',
		'title',
		'feature_image',
		'description',
		'client' => [
			'id',
			'client_type',
			'client_id',
			'name',
			'phone',
			'email',
			'address',
			'website',
			'avatar',
			'tax_code',
			'billing_address',
			'note',
			'branch',
			'registered',
			'contact_person_information' => [
				'name',
				'title',
				'gender',
				'birthdate',
				'phone',
				'email',
			],
			'status',
		],
		'manager' => [
			'name',
			'id',
			'phone',
			'email',
		],
		'author' => [
			'name',
			'id',
			'phone',
			'email',
		],
		'status',
		'start_date',
		'end_date',
		'location_diagram' => [
			'area' => [
				'location',
				'status',
				'did'
			],
		],
		'photos'
	];
	public static function get_events(){
		global $system_api;
		$response = $system_api->re_query('GET', 'getEvents', [
			'params' => [],
			'fields' => self::$fields,
		], true);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response['getEvents'];
	}

	public static function get_event($id){
		global $system_api;
		$response = $system_api->re_query('GET', 'getEvent', [
			'params' => [
				'id' => $id
			],
			'fields' => self::$fields,
		], true);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response['getEvent'];
	}
}
