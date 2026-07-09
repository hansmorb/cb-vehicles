<?php

namespace CBVehicles\API\GBFS;

use CommonsBooking\Repository\Item;
use CommonsBooking\Settings\Settings;

class VehicleTypes extends \CommonsBooking\API\GBFS\BaseRoute
{

	const SCHEMA_PATH = CB_VEHICLES_PLUGIN_DIR . 'includes/gbfs-json-schema/';
	protected $rest_base = 'vehicle_types.json';
	protected $schemaUrl = COMMONSBOOKING_PLUGIN_DIR . 'includes/gbfs-json-schema/vehicle_types.json';


	/**
	 * @param $item \CommonsBooking\Model\Item
	 * @param $request
	 * @return void
	 */
	public function prepare_item_for_response($item, $request)
	{

		//check for required fields, fill them with defaults if needed
		$propulsionType = $this->getPropulsionType($item);
		$formFactor = $this->getFormFactor($item);
		$maxRange = $this->getMaxRange($item);//condtiionally required, if propulsion type is a motor

		$preparedItem = new \stdClass();
		$preparedItem->vehicle_type_id = $item->getCloakedId();
		$preparedItem->form_factor = $formFactor; //Required, skip if null
		$preparedItem->propulsion_type = $propulsionType; //Required, skip if null
		//only conditionally required, when propulsion_type is something else than "human". Therefore, we may also skip the field
		if ($maxRange !== null) {
			$preparedItem->max_range_meters = $maxRange;
		}

		//optional fields:
		//$preparedItem->vehicle_image = 0; //TODO: URL to post image
		$wheelCount = $item->getMeta(CB_VEHICLES_METABOX_PREFIX . 'wheel_count');
		if ($wheelCount) {
			$preparedItem->wheel_count = intval($wheelCount);
		}
		$maxSpeed = $item->getMeta(CB_VEHICLES_METABOX_PREFIX . 'max_permitted_speed');
		if ($maxSpeed) {
			$preparedItem->max_permitted_speed = intval($maxSpeed);
		}
		$cargoVolume = $item->getMeta(CB_VEHICLES_METABOX_PREFIX . 'cargo_volume_capacity');
		if ($cargoVolume) {
			$preparedItem->cargo_volume_capacity = intval($cargoVolume);
		}
		$cargoLoadCapacity = $item->getMeta(CB_VEHICLES_METABOX_PREFIX . 'cargo_load_capacity');
		if ($cargoLoadCapacity) {
			$preparedItem->cargo_load_capacity = intval($cargoLoadCapacity);
		}
		$make = $item->getMeta(CB_VEHICLES_METABOX_PREFIX . 'make');
		if ($make) {
			$preparedItem->make = [
				(object)[
					'text' => $make,
					'language' => get_bloginfo('language'),
				],
			];
		}
		$model = $item->getMeta(CB_VEHICLES_METABOX_PREFIX . 'model');
		if ($model) {
			$preparedItem->model = [
				(object)[
					'text' => $model,
					'language' => get_bloginfo('language'),
				],
			];
		}

		//always set to fixed value
		$preparedItem->return_constraint = "roundtrip_station"; //this is the only type of station currently supported by CB

		return new \WP_REST_Response($preparedItem);
	}


	/**
	 * Gets the form factor of the vehicle in a format compatible with GBFS.
	 * Merges unsupported vehicle form factors in "other"
	 * @param $item \CommonsBooking\Model\Item
	 * @return string empty string if field does not exist
	 */
	private function getFormFactor($item): string
	{
		$supportedTypes = [
			"bicycle",
			"cargo_bicycle",
			"car",
			"moped",
			"scooter_standing",
			"scooter_seated"
		];
		$type = $item->getMeta(CB_VEHICLES_METABOX_PREFIX . 'form_factor');
		if (empty($type)) {
			$type = Settings::getOption('commonsbooking_options_cb_vehicles', 'default_form_factor');
		}
		foreach ($supportedTypes as $supportedType) {
			if ($supportedType === $type) {
				return $supportedType;
			}
		}
		return "other";
	}


	/**
	 * @param \CommonsBooking\Model\Item $item
	 * @return array|string
	 */
	public static function getPropulsionType(\CommonsBooking\Model\Item $item): string|array
	{
		$propulsionType = $item->getMeta(CB_VEHICLES_METABOX_PREFIX . 'propulsion_type');
		if (! $propulsionType ) {
			return Settings::getOption('commonsbooking_options_cb_vehicles', 'default_propulsion_type');
		}
		return $propulsionType;
	}

	/**
	 * This field is required, when the propulsion type is anything else than 'human'.
	 * In order to preserve validity of the feed, we fill in a value when it is not provided.
	 *
	 * @param \CommonsBooking\Model\Item $item
	 * @return ?int
	 */
	public static function getMaxRange(\CommonsBooking\Model\Item $item): ?int
	{
		$maxRange = $item->getMeta(CB_VEHICLES_METABOX_PREFIX . 'max_range_meters');
		if (self::getPropulsionType($item) == 'human') {
			return null;
		}

		if (! $maxRange ) {
			$maxRange = Settings::getOption('commonsbooking_options_cb_vehicles', 'default_max_range');
		}
		return intval($maxRange);
	}


	protected static function getListName(): string
	{
		return 'vehicle_types';
	}


	protected static function getRepository(): \CommonsBooking\Repository\PostRepository
	{
		// we iterate over posts with cb_item post type
		return new Item();
	}
}
