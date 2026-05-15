<?php

namespace CBVehicles\API\GBFS;

class VehicleAvailability
{
	/**
	 * Adds a vehicle type to an existing WP_REST_Response so that it can be added into the existing API
	 *
	 * @param \WP_REST_Response $response
	 * @return \WP_REST_Response
	 */
	public static function addVehicleTypes(\WP_REST_Response $response) {
		$data = $response->get_data();
		foreach ( $data->data->vehicles as &$vehicle ) {
			$vehicle->vehicle_type_id = $vehicle->vehicle_id;
		}
		return new \WP_REST_Response($data);
	}
}
