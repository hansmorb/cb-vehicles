<?php
/**
 * Initialize the plugin.
 *
 * @package CB_Vehicles
 */

namespace CBVehicles;

use CBVehicles\WordPress\CustomPostType\Item;
use CommonsBooking\Wordpress\Options\OptionsTab;

/**
 * The main class of the plugin.
 */
class Plugin {

	/**
	 * The single instance of the class.
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
