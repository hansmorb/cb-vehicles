<?php

namespace CBVehicles\API\GBFS;

use CommonsBooking\Repository\Item;

class StationStatus
{
	/**
	 * Adds the vehicle types available at the current station to the API.
	 *
	 * @param \WP_REST_Response $response
	 * @return \WP_REST_Response
	 */
	public static function addVehicleTypes(\WP_REST_Response $response) {
		$data = $response->get_data();
		foreach ( $data->data->stations as &$station ) {
			$items = 			array_filter(
				Item::getByLocation( $station->station_id, true ),
				fn( $item ) => $item->isCurrentlyFreeAtLocation( $station->station_id ) && ! $item->getMeta( COMMONSBOOKING_METABOX_PREFIX . 'api_exclude' ) == 'on'
			);
			//there are no vehicle_types with multiple assignments, every item has its own vehicle_type with the same id as the item
			$vehicleTypes = array_map(fn($item) => (object) [
				'vehicle_type_id' => $item->getCloakedId(),
				'count'           => 1
			],$items);
			$station->vehicle_types_available = array_values($vehicleTypes); //removes the index: Starts array at 0 again, when items were filtered
		}
		return new \WP_REST_Response($data);
	}
}
