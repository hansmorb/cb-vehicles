<?php

namespace CBVehicles\API\GBFS;

use CommonsBooking\Repository\Item;

class VehicleStatus
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
			$currentRange = self::getCurrentRange(Item::getByCloakedId($vehicle->vehicle_id));
			if ($currentRange !== null) {
				$vehicle->current_range_meters = $currentRange;
			}
		}
		return new \WP_REST_Response($data);
	}

	/**
	 * This field is required, when the propulsion type is anything else than 'human'.
	 * Since there is no way to track this in CB as of right now, we are always assuming,
	 * that the vehicle is fully charged (max range).
	 *
	 * @param \CommonsBooking\Model\Item $item
	 * @return ?int
	 */
	public static function getCurrentRange(\CommonsBooking\Model\Item $item): ?int
	{
		if (VehicleTypes::getPropulsionType($item) == 'human') {
			return null;
		}
		return VehicleTypes::getMaxRange($item);
	}
}
