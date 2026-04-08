<?php
/**
 * Plugin Name:     CommonsBooking - Vehicles
 * Plugin URI:      https://github.com/hansmorb/cb-vehicles
 * Description:     An extension for CommonsBooking (>=2.9.2) to facilitate vehicle rental.
 * Author:          hansmorb
 * Author URI:      https://profiles.wordpress.org/hansmorb/
 * Text Domain:     cb-vehicles
 * Domain Path:     /languages
 * Requires Plugins: commonsbooking
 * Version:         0.1.0
 *
 * @package         Cb_vehicles
 */

namespace CBVehicles;

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

define( 'CB_VEHICLES_VERSION', '0.1.0' );
define( 'CB_VEHICLES_PATH', __DIR__ );
define( 'CB_VEHICLES_METABOX_PREFIX', '_cbvehicles_' );
define( 'CB_VEHICLES_TRANSLATION_DOMAIN', 'commonsbooking_vehicles' );
define( 'CB_VEHICLES_URL', plugins_url( '', __FILE__ ) );
define( 'CB_VEHICLES_PLUGIN_FILE', __FILE__ );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

class CBVehicles {

	public static function plugins_loaded() {
		load_plugin_textdomain(
			'cb-vehicles',
			false,
			basename( CB_VEHICLES_PATH ) . '/languages'
		);
		$plugin = Plugin::init();
	}

	public static function activation_hooks() {
		register_activation_hook( CB_VEHICLES_PLUGIN_FILE, array( Plugin::class, 'activate' ) );
		register_deactivation_hook( CB_VEHICLES_PLUGIN_FILE, array( Plugin::class, 'deactivate' ) );
	}
}

add_action(
	'plugins_loaded',
	function () {
		CBVehicles::plugins_loaded();
	}
);

CBVehicles::activation_hooks();
