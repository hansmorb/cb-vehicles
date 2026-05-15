<?php

namespace CBVehicles\WordPress\CustomPostType;

class Item {

	/**
	 * Gets the CMB2 metaboxes that the plugin will append to the Item post type
	 * types are the https://cmb2.io/docs/field-types
	 * This is passed directly into $cmb->add_field, so all parameters of CMB2 fields apply
	 *
	 * GBFS fields have the same id as the key in the GBFS api
	 *
	 * @return Mixed[]
	 */
	public static function getMetaboxes(): array {
		return [
			//GBFS field (required)
			[
				'name'       => esc_html__( 'Form factor (required)', 'cb-vehicles' ),
				'desc'       => esc_html__('The item\'s general form factor.', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'form_factor',
				'type'       => 'select',
				'options'	 => self::getFormFactors(),
				'show_option_none' => true,
				'default'     => 'none'
			],
			//GBFS field (required)
			[
				'name'       => esc_html__( 'Propulsion type (required)', 'cb-vehicles' ),
				'desc'       => esc_html__( 'The primary propulsion type of the vehicle.', 'cb-vehicles' ),
				'id' 	     => CB_VEHICLES_METABOX_PREFIX . 'propulsion_type',
				'type'       => 'select',
				'options'	 => self::getPropulsionTypes(),
				'show_option_none' => true,
				'default'     => 'none'
			],
			//GBFS field: Conditionally required when a motor is installed
			[
				'name'       => esc_html__( 'Range (meters) (required when using a motor)' , 'cb-vehicles' ),
				'desc'       => esc_html__( 'The range the vehicle can travel with a full charge / tank.', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'max_range_meters',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1',
				)
			],
			//GBFS field (optional)
			[
				'name'       => esc_html__( 'Wheel count' , 'cb-vehicles' ),
				'desc'       => esc_html__( 'Number of wheels this vehicle has' , 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'wheel_count',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1', //non-negative integer
				),
			],
			//GBFS field (optional)
			[
				'name'       => esc_html__( 'Max permitted speed (km/h)', 'cb-vehicles' ),
				'desc'       => esc_html__( 'The maximum speed in kilometers per hour this vehicle is permitted to reach in accordance with local permit and regulations. ', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'max_permitted_speed',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1', //non-negative integer
				)
			],
			//GBFS field (optional)
			[
				'name'       => esc_html__( 'Cargo volume capacity (l)', 'cb-vehicles' ),
				'desc'       => esc_html__( 'Cargo volume available in the vehicle, expressed in liters. For cars, it corresponds to the space between the boot floor, including the storage under the hatch, to the rear shelf in the trunk.', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'cargo_volume_capacity',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1', //non-negative integer
				)
			],
			//GBFS field (optional)
			[
				'name'       => esc_html__( 'Cargo load capacity (kg)', 'cb-vehicles' ),
				'desc'       => esc_html__( 'The capacity of the vehicle cargo space (excluding passengers), expressed in kilograms.' , 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'cargo_load_capacity',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1', //non-negative integer
				)
			],
			//optional
			[
				'name'      => esc_html__( 'Empty weight (kg)', 'cb-vehicles' ),
				'desc'      => esc_html__ ('The weight of the vehicle when it is empty.', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'empty_weight',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1'
				)
			],
			//optional
			[
				'name'       => esc_html__( 'Length (cm)', 'cb-vehicles' ),
				'desc'       => esc_html__ ('The minimum length of the vehicle. For trailers, the length with the trailer drawbar folded in.', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'length',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1'
				)
			],
			//optional
			[
				'name'       => esc_html__( 'Width (cm)', 'cb-vehicles' ),
				'desc'       => esc_html__ ('The width of the vehicle at its widest point.', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'width',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1'
				)
			],
			//optional
			[
				'name'       => esc_html__( 'Loading area length (cm)', 'cb-vehicles' ),
				'desc'       => esc_html__( 'The length of just the loading area', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'loading_area_length',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1'
				)
			],
			//optional
			[
				'name'       => esc_html__( 'Loading area width (cm)', 'cb-vehicles' ),
				'desc'       => esc_html__( 'The width of just the loading area', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'loading_area_width',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1'
				)
			],
			//optional (only for bicycle,cargo_bicycle)
			[
				'name'       => esc_html__( 'Adjustable seat post?', 'cb-vehicles' ),
				'desc'       => esc_html__ ('Is the seat post adjustable?', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'seat_post_adjustable',
				'type'       => 'checkbox'
			],
			//optional (only for bicycle,cargo_bicycle)
			[
				'name'       => esc_html__( 'Handlebar adjustable?', 'cb-vehicles' ),
				'desc'       => esc_html__ ('Is the handlebar adjustable?', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'handlebar_adjustable',
				'type'       => 'checkbox'
			],
			//optional, right now only for bicycle, cargo_bicyle and bicycle_trailer
			[
				'name' => esc_html__('Hitches', 'cb-vehicles' ),
				'desc' => esc_html__ ('The hitches attached to the vehicle.', 'cb-vehicles' ),
				'id'   => CB_VEHICLES_METABOX_PREFIX . 'hitches',
				'type' => 'group',
				'repeatable' => true,
				'options' => array(
					'group_title' => esc_html__( 'Hitch {#}', 'cb-vehicles' ),
					'add_button'  => esc_html__( 'Add hitch', 'cb-vehicles' ),
					'remove_button' => esc_html__( 'Remove hitch', 'cb-vehicles' ),
				),
				'fields' => array(
						array(
						'name'       => esc_html__( 'Hitch type', 'cb-vehicles' ),
						'desc'       => esc_html__ ('What kind of trailer hitch is used by the vehicle? This is used to find compatible trailers for the item.', 'cb-vehicles' ),
						'id'         => CB_VEHICLES_METABOX_PREFIX . 'hitch_make',
						'type'       => 'select',
						'show_option_none' => true,
						'default'   => 'none',
						'options'    => self::getHitches()
						),
						//optional, only for bicycle, cargo_bicycle. Can hide trailers, when empty_weight + cargo_load_capacity exceeds capacity
						array (
							'name'       => esc_html__( 'Hitch maximum towing capacity (kg)', 'cb-vehicles' ),
							'desc'       => esc_html__ ('The maximum towing capacity that the hitch is rated for in kilograms.', 'cb-vehicles' ),
							'id'         => CB_VEHICLES_METABOX_PREFIX . 'hitch_capacity',
							'type'       => 'text_small',
							'attributes' => array(
								'type'    => 'number',
								'pattern' => '\d*',
								'min'     => '1',
							)
						)
				)
			],
			//GBFS field(optional)
			[
				'name'       => esc_html__( 'Make', 'cb-vehicles' ),
				'desc'       => esc_html__ ('The name of the vehicle manufacturer', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'make',
				'type'       => 'text'
			],
			//GBFS field(optional)
			[
				'name'       => esc_html__( 'Model', 'cb-vehicles' ),
				'desc'       => esc_html__ ('The name of the vehicle model', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'model',
				'type'       => 'text'
			],
			//optional
			[
				'name'       => esc_html__( 'Gallery', 'cb-vehicles' ),
				'desc'       => esc_html__ ('Additional pictures of the vehicle. Will be displayed in a gallery.', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'gallery',
				'type'       => 'file_list',
				'query_args' => array(
					'type' => 'image',
				)
			],
			//GBFS field (optional)
			//cb only supports roundtrip stations as of now
			/*
			[
				'name'       => self::esc__( 'Return constraint'),
				'desc'       => self::esc__( 'The conditions for returning the vehicle at the end of the rental.'),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'return_constraint',
				'type'       => 'select',
				'default'    => 'roundtrip_station',
				'show_option_none' => false,
				'options'	 => array(
					'free_floating' => self::esc__( 'Free Floating'),
					'roundtrip_station' => self::esc__( 'Round trip Station'),
					'any_station' => self::esc__( 'Any Station'),
					'hybrid' => self::esc__( 'Hybrid')
				),
			],
			*/
			//optional
			[
				'name'       => esc_html__( 'Owner' , 'cb-vehicles' ),
				'desc'       => esc_html__ ('Who is the owner of the vehicle? Some owners want their name displayed, do this here. USually company or sponsor.', 'cb-vehicles' ), //TODO: Reword
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'owner',
				'type'       => 'text'
			],
			//optional
			[
				'name'       => esc_html__( 'Owner logo' , 'cb-vehicles' ),
				'desc'       => esc_html__ ('An image representative of the lessor. This is usually a company logo. It will be displayed right alongside the name of the owner.', 'cb-vehicles' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'owner_image',
				'type'       => 'file',
				'options'    => array(
					'url' => false, //hide the text input for the url
				),
				'query_args' => array(
					// only allow GIF, JPG, or PNG images
					 'type' => array(
					     'image/gif',
					     'image/jpeg',
					     'image/png',
					),
				),
				'preview_size' => 'small'
			]
		];

	}

	/**
	 * @return array
	 */
	public static function getPropulsionTypes(): array
	{
		return array(
			'human' => esc_html__('Human powered', 'cb-vehicles' ),
			'electric_assist' => esc_html__('Electric assist', 'cb-vehicles' ),
			'electric' => esc_html__('Electric (throttle)', 'cb-vehicles' ),
			'combustion' => esc_html__('Gasoline Combustion Engine', 'cb-vehicles' ),
			'combustion_diesel' => esc_html__('Diesel Combustion Engine', 'cb-vehicles' ),
			'hybrid' => esc_html__('Hybrid (Combustion engine / electric motor', 'cb-vehicles' ),
			'plug_in_hybrid' => esc_html__('Plug-in hybrid', 'cb-vehicles' ),
			'hydrogel_fuel_cell' => esc_html__('Hydrogel fuel cell', 'cb-vehicles' )
		);
	}

	/**
	 * @return array
	 */
	public static function getFormFactors(): array
	{
		return array(
			'cargo_bicycle' => esc_html__('Cargo bike', 'cb-vehicles' ),
			'bicycle' => esc_html__('Bicycle', 'cb-vehicles' ),
			'bicycle_trailer' => esc_html__('Bicycle trailer', 'cb-vehicles' ), //not part of the GBFS standard, will report as other
			'adaptive_bicycle' => esc_html__('Adaptive bike', 'cb-vehicles' ), //not part of the GBFS standard, will report as other
			'moped' => esc_html__('Moped', 'cb-vehicles' ),
			'scooter_standing' => esc_html__('Scooter with rider standing up', 'cb-vehicles' ),
			'scooter_seated' => esc_html__('Scooter with rider seated', 'cb-vehicles' ),
			'car' => esc_html__('Car', 'cb-vehicles' ),
			'other' => esc_html__('Other', 'cb-vehicles' )
		);
	}

	/**
	 * @return array
	 */
	public static function getHitches(): array
	{
		return array(
			'aevon' => 'Aevon®',
			'burley' => 'Burley®',
			'burley_travoy' => 'Burley Travoy®',
			'bob' => 'Bob®',
			'croozer' => 'Croozer®',
			'croozer_old' => esc_html__('Croozer® (pre 2016)', 'cb-vehicles' ),
			'extrawheel' => 'Extrawheel®',
			'hamax' => 'Hamax®',
			'haerry' => 'Haerry',
			'hebie' => 'Hebie®',
			'leggero' => 'Leggero®',
			'qeridoo' => 'Qeridoo®',
			'thule' => 'Thule®',
			'tout_terrain' => 'Tout Terrain®',
			'used' => 'USED® (Carry Freedom)',
			'weber' => 'Weber®'
		);
	}
}
