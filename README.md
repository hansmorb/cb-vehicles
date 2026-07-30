# CB-Vehicles #
**Contributors:** hansmorb
**Tags:** booking, bikes, cargobikes, vehicles, calendar
**Requires at least:** 5.9
**Tested up to:** 7.0
**Requires PHP:** 8.1
**Stable tag:** 0.1.1
**License:** GPLv2 or later
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html

An extension for CommonsBooking (>=2.11) to facilitate cargobike, bicycle, bicycle trailer and other vehicle rentals.

## Description ##

This plugin provides you with additional fields to manage your fleet of vehicles in CommonsBooking.
You can set the type of vehicle and add technical specifications. From these fields, you can
- automatically generate a beautiful frontend overview for the users
- provide the data using the GBFS API
- include them in your booking and e-mail templates

Includes support for trailer hitches, so you can find a suitable bike for your trailer and vice versa.
This plugin only works with the latest version of CommonsBooking (>=2.11) and is not compatible with older versions.

## Installation ##

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

## Development ##

1. Clone the the repository
2. Run `npm install` and `composer install`

**Update translations**

Run `grunt makepot`

## Screenshots ##

### Frontend example ###
![Example how the item information is displayed in the frontend (the technical information box is expandable)](http://ps.w.org/cb-vehicles/screenshots/demo_010_english.png)

### Backend example ###
![This is how that same information is configured using the WP backend.](http://ps.w.org/cb-vehicles/demo_010_backend_english.png)


## Changelog ##

### 0.1.1 ###
fix: gbfs startday offset was not ignored for StationStatus route
fix: default options not set upon activation

### 0.1.0 ###
Initial release: Support generating frontend templates and GBFS vehicle_types endpoints from metadata
