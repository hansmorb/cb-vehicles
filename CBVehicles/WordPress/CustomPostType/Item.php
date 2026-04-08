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
				'name'       => self::esc__( 'Form factor' ),
				'desc'       => self::esc__('The item\'s general form factor. ' ),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'form_factor',
				'type'       => 'select',
				'options'	 => array(
					'cargo_bicycle' => self::esc__( 'Cargo bike' ),
					'bicycle' => self::esc__( 'Bicycle' ),
					'bicycle_trailer' => self::esc__( 'Bicycle trailer' ), //not part of the GBFS standard, will report as other
					'adaptive_bicycle' => self::esc__( 'Adaptive bike' ), //not part of the GBFS standard, will report as other
					'moped' => self::esc__( 'Moped' ),
					'scooter_standing' => self::esc__( 'Scooter with rider standing up' ),
					'scooter_seated' => self::esc__( 'Scooter with rider seated'),
					'car'              => self::esc__( 'Car' ),
					'other' => self::esc__( 'Other' )
				),
				'show_option_none' => false,
				'default'     => 'cargo_bicycle'
			],
			//GBFS field (required)
			[
				'name'       => self::esc__( 'Propulsion type'),
				'desc'       => self::esc__( 'The primary propulsion type of the vehicle.'),
				'id' 	     => CB_VEHICLES_METABOX_PREFIX . 'propulsion_type',
				'type'       => 'select',
				'options'	 => array(
					'human' => self::esc__( 'Human powered'),
					'electric_assist' => self::esc__( 'Electric assist'),
					'electric' => self::esc__( 'Electric (throttle)'),
					'combustion' => self::esc__( 'Gasoline Combustion Engine'),
					'combustion_diesel' => self::esc__( 'Diesel Combustion Engine'),
					'hybrid' => self::esc__( 'Hybrid (Combustion engine / electric motor'),
					'plug_in_hybrid' => self::esc__( 'Plug-in hybrid'),
					'hydrogel_fuel_cell' => self::esc__( 'Hydrogel fuel cell')
				),
				'show_option_none' => false,
				'default'     => 'human'
			],
			//GBFS field: Conditionally required when a motor is installed
			[
				'name'       => self::esc__( 'Range (meters)' ),
				'desc'       => self::esc__( 'The range the vehicle can travel with a full charge / tank.'),
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
				'name'       => self::esc__( 'Wheel count' ),
				'desc'       => self::esc__( 'Number of wheels this vehicle has' ),
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
				'name'       => self::esc__( 'Max permitted speed (km/h)'),
				'desc'       => self::esc__( 'The maximum speed in kilometers per hour this vehicle is permitted to reach in accordance with local permit and regulations. '),
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
				'name'       => self::esc__( 'Cargo volume capacity (l)'),
				'desc'       => self::esc__( 'Cargo volume available in the vehicle, expressed in liters. For cars, it corresponds to the space between the boot floor, including the storage under the hatch, to the rear shelf in the trunk.'),
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
				'name'       => self::esc__( 'Cargo load capacity (kg)'),
				'desc'       => self::esc__( 'The capacity of the vehicle cargo space (excluding passengers), expressed in kilograms.' ),
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
				'name'      => self::esc__( 'Empty weight (kg)'),
				'desc'      => self::esc__ ('The weight of the vehicle when it is empty.'),
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
				'name'       => self::esc__( 'Length (cm)'),
				'desc'       => self::esc__ ('The minimum length of the vehicle. For trailers, the length with the trailer drawbar folded in.'),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'length',
				'type'       => 'text_small',
				'attributes' => array(
					'type'    => 'number',
					'pattern' => '\d*',
					'min'     => '1'
				)
			],
			[
				'name'       => self::esc__( 'Width (cm)'),
				'desc'       => self::esc__ ('The width of the vehicle at its widest point.'),
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
				'name'       => self::esc__( 'Loading area length (cm)'),
				'desc'       => self::esc__( 'The length of just the loading area'),
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
				'name'       => self::esc__( 'Loading area width (cm)'),
				'desc'       => self::esc__( 'The width of just the loading area'),
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
				'name'       => self::esc__( 'Adjustable seat post?'),
				'desc'       => self::esc__ ('Is the seat post adjustable?'),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'seat_post_adjustable',
				'type'       => 'checkbox'
			],
			//optional (only for bicycle,cargo_bicycle)
			[
				'name'       => self::esc__( 'Handlebar adjustable?'),
				'desc'       => self::esc__ ('Is the handlebar adjustable?'),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'handlebar_adjustable',
				'type'       => 'checkbox'
			],
			//optional, right now only for bicycle, cargo_bicyle and bicycle_trailer
			[
				'name' => self::esc__('Hitches'),
				'desc' => self::esc__ ('The hitches attached to the vehicle.'),
				'id'   => CB_VEHICLES_METABOX_PREFIX . 'hitches',
				'type' => 'group',
				'repeatable' => true,
				'options' => array(
					'group_title' => self::esc__( 'Hitch {#}'),
					'add_button'  => self::esc__( 'Add hitch'),
					'remove_button' => self::esc__( 'Remove hitch'),
				),
				'fields' => array(
						array(
						'name'       => self::esc__( 'Hitch type'),
						'desc'       => self::esc__ ('What kind of trailer hitch is used by the vehicle?'),
						'id'         => CB_VEHICLES_METABOX_PREFIX . 'hitch_make',
						'type'       => 'select',
						'options'    => array(
							'aevon'  => self::esc__( 'Aevon®' ),
							'burley' => self::esc__( 'Burley®' ),
							'burley_travoy' => self::esc__( 'Burley Travoy®' ),
							'bob'     => self::esc__( 'Bob®' ),
							'croozer' => self::esc__( 'Croozer®' ),
							'croozer_old' => self::esc__( 'Croozer® (pre 2016)' ),
							'extrawheel' => self::esc__( 'Extrawheel®' ),
							'hamax'     => self::esc__( 'Hamax®' ),
							'haerry'     => self::esc__( 'Haerry' ),
							'hebie'     => self::esc__( 'Hebie®' ),
							'leggero' => self::esc__( 'Leggero®' ),
							'qeridoo'   => self::esc__( 'Qeridoo®' ),
							'thule'     => self::esc__('Thule®'),
							'tout_terrain' => self::esc__('Tout Terrain®'),
							'used'          => self::esc__('USED® (Carry Freedom)'),
							'weber'         => self::esc__('Weber®')
						)
						),
						//optional, only for bicycle, cargo_bicycle. Can hide trailers, when empty_weight + cargo_load_capacity exceeds capacity
						array (
							'name'       => self::esc__( 'Hitch maximum towing capacity (kg)'),
							'desc'       => self::esc__ ('The maximum towing capacity that the hitch is rated for in kilograms.'),
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
				'name'       => self::esc__( 'Make'),
				'desc'       => self::esc__ ('The name of the vehicle manufacturer'),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'make',
				'type'       => 'text'
			],
			//GBFS field(optional)
			[
				'name'       => self::esc__( 'Model'),
				'desc'       => self::esc__ ('The name of the vehicle model'),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'model',
				'type'       => 'text'
			],
			//optional
			[
				'name'       => self::esc__( 'Gallery'),
				'desc'       => self::esc__ ('Additional pictures of the vehicle. Will be displayed in a gallery.'),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'gallery',
				'type'       => 'file_list',
				'query_args' => array(
					'type' => 'image',
				)
			],
			//GBFS field (optional)
			//TODO: hidden, because cb only supports roundtrip stations as of now
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
			[
				'name'       => self::esc__( 'Owner' ),
				'desc'       => self::esc__ ('Who is renting out the vehicle?'),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'owner',
				'type'       => 'text'
			],
			[
				'name'       => self::esc__( 'Owner logo' ),
				'desc'       => self::esc__ ('An image representative of the lessor.'),
				'id'         => CB_VEHICLES_METABOX_PREFIX . 'owner_image',
				'type'       => 'file',
				'options'    => array(
					'url' => false, //hide the text input for the url
				),
				'query_args' => array(
					// only allow gif, jpg, or png images
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

	private static function esc__ ( $string ): string
	{
		return esc_html__( $string, CB_VEHICLES_TRANSLATION_DOMAIN );
	}
}
