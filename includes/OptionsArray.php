<?php

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
						'id'   => 'frontend_enabled',
						'type' => 'checkbox',
					),
				),
			),
		),
	),
);
