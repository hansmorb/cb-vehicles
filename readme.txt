=== CommonsBooking - Vehicles ===
Contributors: hansmorb
Tags: booking, bikes, cargobikes, vehicles, calendar
Requires at least: 5.9
Tested up to: 7.9
Requires PHP: 8.1
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

An extension for CommonsBooking (>=2.11) to facilitate cargobike, bicycle, bicycle trailer and other vehicle rentals.

== Description ==

This plugin provides you with additional fields to manage your fleet of vehicles in CommonsBooking.
You can set the type of vehicle and add technical specifications. From these fields, you can
- automatically generate a beautiful frontend overview for the users
- provide the data using the GBFS API
- include them in your booking and e-mail templates

Includes support for trailer hitches, so you can find a suitable bike for your trailer and vice versa.
This plugin only works with the latest version of CommonsBooking (>=2.11) and is not compatible with older versions.

== Installation ==

Install this plugin from the WordPress plugin directory as usual.
In case you should only have a zip file of the plugin, proceed as follows:

### Uploading zip WordPress Dashboard

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `cb-vehicles.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

== Screenshots ==

1. Example how the item information is displayed in the frontend (the technical information box is expandable)

2. This is how that same information is configured using the WP backend.

== Changelog ==

= 0.1.0 =
Initial release: Support generating frontend templates and GBFS vehicle_types endpoints from metadata
