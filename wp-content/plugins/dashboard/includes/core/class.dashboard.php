<?php
namespace ClassDashboard\Includes\Core;
use SME\Frames\Frames;
use SME\Includes\AJAX;
use SME\Includes\Core\Event;

class Dashboard extends Frames{
    public function __construct()
    {
        parent::__construct();
        $this->init_hook();
    }

    function init_hook(){
        add_action('app/ajax/register_nopriv_actions', [$this, 'register_nopriv_actions']);
    }

    function register_nopriv_actions(AJAX $ajax){
        $ajax->register_ajax_action('get_data_event', [$this, 'get_data_event_ajax']);
        $ajax->register_ajax_action('get_location', [$this, 'get_location']);
    }

    function get_data_event_ajax($params){
        $event_id = isset($params['event_id']) ? $params['event_id'] : '';
        $data = $this->get_booking_of_event($event_id);
        $event = Event::get_event($event_id);
	    $area = [];
        if( !empty($event) ){
        	$area = $event['location_diagram']['area'];
        }
	    $booking_incurred = array_filter($data, function ($it){
        	return $it->flag != 'main';
        });
        $booking_done = array_filter($data, function ($it){
            return $it->status == 'done';
        });
        return ['booking' => $data, 'booking_incurred' => count($booking_incurred), 'booking_done' => count($booking_done), 'area' => count($area)];
    }

    function get_events(){
        global $system_api;
        $events = [];
        $query = $system_api->query(
            'GET',
            'getEvents',
            [
                'fields' => [
                    'id',
                    'title',
                    'status',
                ]
            ], true
        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $events = $query->data->getEvents;
        }
        return $events;
    }

    function get_booking_of_event($event_id){
        global $system_api;
        $booking = [];
        $more_event = $system_api->parseFields(['id', 'title']);

        $more_contact_person_information_client = $system_api->parseFields(['name', 'phone']);
        $more_client = $system_api->parseFields(['client_type', 'id', 'address', 'name', 'address', 'contact_person_information'.$more_contact_person_information_client]);

        $more_author = $system_api->parseFields(['name']);

        $more_product_list_of_equipment = $system_api->parseFields(['id', 'product_unit', 'product_code']);
        $more_list_of_equipment =  $system_api->parseFields(['quantity', 'into_money', 'product'.$more_product_list_of_equipment]);
        $query = $system_api->query(
            'GET',
            'registrationForms',
            [
                //'params' => ['events' => $event_id, 'status' => 'pending-draft'],
                'params' => ['events' => $event_id],
                'fields' => [
                    'id',
                    'date',
                    'votes',
                    'booth',
                    'discount',
                    'vat',
                    'flag',
                    'payment_type',
                    'into_money',
                    'status',
                    'events'.$more_event,
                    'client'.$more_client,
                    'author'.$more_author,
                    'list_of_equipment'.$more_list_of_equipment
                ]
            ], true
        );
        if(!is_wp_error($query) || !isset($query->errors)){
            $booking = $query->data->registrationForms;
        }
        return $booking;
    }

}
