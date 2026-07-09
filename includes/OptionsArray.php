<?php
if ( ! defined( 'ABSPATH' ) ) exit;

return array(
	'cb_vehicles' => array(
		'title'        => esc_html__( 'CB Vehicles', 'cb-vehicles' ),
		'id'           => 'cb_vehicles',
		'field_groups' => array(
			'general' => array(
				'title'  => esc_html__( 'General', 'cb-vehicles' ),
				'id'     => 'general',
				'fields' => array(
					array(
						'name' => esc_html__( 'Show frontend templates', 'cb-vehicles' ),
						'desc' => esc_html__( 'When enabled, the information you defined for each item will be nicely displayed in a list on the item page.', 'cb-vehicles' ),
						'id'   => 'frontend_enabled',
						'type' => 'checkbox',
					),
				),
			),
			'gbfs_defaults' => array(
				'title'        => esc_html__( 'GBFS Default Vehicle Types', 'cb-vehicles' ),
				'desc'         => esc_html__('When publishing additional vehicle information to the GBFS API, some information is required, otherwise the data published will be considered invalid. Therefore, you can set default values here, that will be taken when the values are not defined at the item level. Items can also be hidden from the API, when the checkbox is set in the corresponding item.','cb-vehicles'),
				'id'           => 'gbfs_api',
				'fields'       => array(
					array(
						'name' => esc_html__( 'Default form factor', 'cb-vehicles' ),
						'id'   => 'default_form_factor',
						'desc' => esc_html__( 'The default form factor that is used when none is defined at the item level.', 'cb-vehicles' ),
						'type' => 'select',
						'options' => \CBVehicles\WordPress\CustomPostType\Item::getFormFactors(),
						'show_option_none' => false,
						'default' => 'cargo_bicycle'
					),
					array(
						'name' => esc_html__( 'Default propulsion type', 'cb-vehicles' ),
						'id'   => 'default_propulsion_type',
						'desc' => esc_html__('The default propulsion type that is used when none is defined at the item level.', 'cb-vehicles' ),
						'type' => 'select',
						'options' => \CBVehicles\WordPress\CustomPostType\Item::getPropulsionTypes(),
						'show_option_none' => false,
						'default' => 'human'
					),
					array(
						'name' => esc_html__('Default max range (meters)', 'cb-vehicles' ),
						'id'   => 'default_max_range',
						'desc' => esc_html__('When a motor is defined in the propulsion type, a max range also has to be given.','cb-vehicles'),
						'type'       => 'text_small',
						'attributes' => array(
							'type'    => 'number',
							'pattern' => '\d*',
							'min'     => '1',
						),
						'default' => 20000
					)
				)
			)
		),
	),
);
