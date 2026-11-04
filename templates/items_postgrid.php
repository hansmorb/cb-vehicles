<?php
/**
 * CBVehicles Collection of items to display that are compatible with the current item (determined through attached hitch)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $post;
$item = new \CommonsBooking\Model\Item( $post->ID );

$prefix = CB_VEHICLES_METABOX_PREFIX;

echo $item->ID;

?>