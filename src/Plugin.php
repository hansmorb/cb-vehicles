<?php
/**
 * Initialize the plugin.
 *
 * @package CB_Vehicles
 */

namespace CBVehicles;

use CBVehicles\API\GBFS\StationStatus;
use CBVehicles\API\GBFS\VehicleAvailability;
use CBVehicles\API\GBFS\VehicleStatus;
use CBVehicles\API\GBFS\VehicleTypes;
use CBVehicles\WordPress\CustomPostType\Item;
use CommonsBooking\Settings\Settings;
use CommonsBooking\Wordpress\Options\OptionsTab;
use CommonsBooking\Wordpress\Options\AdminOptions;
use function PHPUnit\Framework\stringContains;

/**
 * The main class of the plugin.
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class Plugin {

	/**
	 * The single instance of the class.
	 *
	 *
	 * @var \CBVehicles\Plugin|null
	 */
	private static ?Plugin $instance = null;

	/**
	 * Init all the things.
	 */
	public static function init() {
		$plugin = self::get_instance();

		//initialize item metaboxes
		add_filter( 'commonsbooking_custom_metadata', function ( $metadata )
		{
			if (! array_key_exists('item',$metadata)  || ! is_array( $metadata['item'])) {
				return array('item' => Item::getMetaboxes());
			}
			else {
				return array('item' => array_merge($metadata['item'], Item::getMetaboxes()));
			}
		});
		//initialize backend settings page
		add_action( 'init', function () use ($plugin) {
			$options_array = include CB_VEHICLES_PATH . '/includes/OptionsArray.php';
			foreach ( $options_array as $tab_id => $tab ) {
				new OptionsTab( $tab_id, $tab );
			}
		},40);

		/**
		 * We want to ensure that new translations are available directly after update
		 * so we load the local translation first if its available, otherwise we use the load_plugin_textdomain
		 * to load from the global WordPress translation file.
		 */
		$locale                  = get_locale();
		$locale_translation_file = CB_VEHICLES_PLUGIN_DIR . 'languages/' . CB_VEHICLES_TRANSLATION_DOMAIN . '_' .  $locale . '.mo';
		add_action( 'init', function () use ($locale_translation_file) {
			if ( file_exists( $locale_translation_file ) ) {
				load_textdomain( CB_VEHICLES_TRANSLATION_DOMAIN, $locale_translation_file );
			} else {
				load_plugin_textdomain( CB_VEHICLES_TRANSLATION_DOMAIN, false, CB_VEHICLES_PLUGIN_DIR . 'languages' );
			}
		});

		//Register VehicleTypes over rest Route before CB can jump in with their own (priority 5)
		add_action(
			'rest_api_init',
		function () {
			$route = new VehicleTypes();
			$route->register_routes();
		},5);

		//add vehicletypes to other routes
		add_filter( 'rest_request_after_callbacks', function( $response, $handler, $request) {
			if (str_ends_with($request->get_route(), 'station_status.json')) {
				return StationStatus::addVehicleTypes($response);
			}
			elseif (str_ends_with($request->get_route(), 'vehicle_status.json')) {
				return VehicleStatus::addVehicleTypes($response);
			}
			elseif (str_ends_with($request->get_route(), 'vehicle_availability.json')) {
				return VehicleAvailability::addVehicleTypes($response);
			}
			else {
				return $response;
			}
		},10,3);

		if (Settings::getOption('commonsbooking_options_cb_vehicles', 'frontend_enabled' ) == "on") {
			add_action('commonsbooking_before_item-single', function () {
				//Accordion scripts
				wp_enqueue_script(
					CB_VEHICLES_TRANSLATION_DOMAIN . '_accordion_js',
					CB_VEHICLES_PLUGIN_URL . '/assets/js/accordion.js',
					[],
					CB_VEHICLES_VERSION,
					true
				);
				//Accordion styles
				wp_enqueue_style(
					CB_VEHICLES_TRANSLATION_DOMAIN . '_accordion_css',
					CB_VEHICLES_PLUGIN_URL . '/assets/css/accordion.css',
					[],
					CB_VEHICLES_VERSION
				);

				//Gallery styles
				wp_enqueue_style(
					CB_VEHICLES_TRANSLATION_DOMAIN . '_gallery_css',
					CB_VEHICLES_PLUGIN_URL . '/assets/css/gallery.css',
					[],
					CB_VEHICLES_VERSION
				);

				//Gallery scripts
				wp_enqueue_script(
					CB_VEHICLES_TRANSLATION_DOMAIN . '_gallery_js',
					CB_VEHICLES_PLUGIN_URL . '/assets/js/gallery.js',
					[],
					CB_VEHICLES_VERSION
				);

				//The icon font
				wp_enqueue_style(
					CB_VEHICLES_TRANSLATION_DOMAIN . '_fontello',
					CB_VEHICLES_PLUGIN_URL . '/assets/icons/css/fontello.css',
					[],
					CB_VEHICLES_VERSION
				);

				include CB_VEHICLES_PATH . '/templates/item.php';
			});
		}
	}

	/**
	 * Return the instance.
	 * Make sure that the instance is created only once.
	 *
	 * @return \CBVehicles\Plugin
	 */
	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * The actions to be performed on plugin activation.
	 *
	 * @return void
	 */
	public static function activate() {
		AdminOptions::setOptionsDefaultValues();
	}

	/**
	 * The actions to be performed on plugin deactivation.
	 *
	 * @return void
	 */
	public static function deactivate() {
		// Run plugin deactivation code here.
	}
}
