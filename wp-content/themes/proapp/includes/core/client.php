<?php


namespace SME\Includes\Core;


class Client
{

	public static function clients(){
		global $system_api;
		$fields = [
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
			'contact_person_informations' => [
				'id',
				'name',
				'email',
				'phone',
				'address',
			],
            'contact_person_information' => [
				'name',
				'phone',
			],
			'status',
			'author' => [
				'id',
				'name',
				'email',
				'phone',
				'address',
			]
		];
		$response = $system_api->re_query('GET', 'clients', [
			'params' => [],
			'fields' => $fields,
		], true);
		if( is_wp_error($response) ){
			return $response;
		}
		return $response['clients'];
	}
}
