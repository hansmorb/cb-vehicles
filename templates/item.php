<?php
/**
 * CBVehicles Item Single Template
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $post;
$item = new \CommonsBooking\Model\Item( $post->ID );

$prefix = CB_VEHICLES_METABOX_PREFIX;

$form_factor         = get_post_meta( $post->ID, $prefix . 'form_factor', true );
$propulsion_type     = get_post_meta( $post->ID, $prefix . 'propulsion_type', true );
$max_range_meters    = get_post_meta( $post->ID, $prefix . 'max_range_meters', true );
$wheel_count         = get_post_meta( $post->ID, $prefix . 'wheel_count', true );
$max_permitted_speed = get_post_meta( $post->ID, $prefix . 'max_permitted_speed', true );
$cargo_volume        = get_post_meta( $post->ID, $prefix . 'cargo_volume_capacity', true );
$cargo_load          = get_post_meta( $post->ID, $prefix . 'cargo_load_capacity', true );
$empty_weight        = get_post_meta( $post->ID, $prefix . 'empty_weight', true );
$length              = get_post_meta( $post->ID, $prefix . 'length', true );
$width               = get_post_meta( $post->ID, $prefix . 'width', true );
$loading_area_length = get_post_meta( $post->ID, $prefix . 'loading_area_length', true );
$loading_area_width  = get_post_meta( $post->ID, $prefix . 'loading_area_width', true );
$seat_post_adj       = get_post_meta( $post->ID, $prefix . 'seat_post_adjustable', true );
$handlebar_adj       = get_post_meta( $post->ID, $prefix . 'handlebar_adjustable', true );
$hitches             = get_post_meta( $post->ID, $prefix . 'hitches', true );
$make                = get_post_meta( $post->ID, $prefix . 'make', true );
$model               = get_post_meta( $post->ID, $prefix . 'model', true );
$gallery             = get_post_meta( $post->ID, $prefix . 'gallery', true );
$owner               = get_post_meta( $post->ID, $prefix . 'owner', true );
$owner_image         = get_post_meta( $post->ID, $prefix . 'owner_image', true );

$form_factor_labels = \CBVehicles\WordPress\CustomPostType\Item::getFormFactors();
$propulsion_labels  = \CBVehicles\WordPress\CustomPostType\Item::getPropulsionTypes();
$hitch_labels       = \CBVehicles\WordPress\CustomPostType\Item::getHitches();
?>

<?php if ( $owner ) : ?>
    <p><i>
        <?php printf(
            /* translators: %s: owner name */
            esc_html__( 'Provided by %s', 'cb-vehicles' ),
            esc_html( $owner )
        ); ?>
    </i>
    <?php if ( $owner_image ) : ?>
    <img src="<?php echo esc_url( $owner_image ); ?>"
         alt="<?php echo esc_attr( $owner ); ?>"
         style="max-height:50px;">
    <?php endif; ?>
</p>
<?php endif; ?>

<?php
$gallery_slides = [];

$thumbnail_id  = get_post_thumbnail_id( $post->ID );
$thumbnail_url = get_the_post_thumbnail_url( $post->ID );

if ( $thumbnail_url ) {
    $gallery_slides[] = [
        'url' => $thumbnail_url,
        'alt' => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ),
    ];
}

if ( ! empty( $gallery ) ) {
    foreach ( $gallery as $attachment_id => $attachment_url ) {
        $gallery_slides[] = [
            'url' => $attachment_url,
            'alt' => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $attachment_id ),
        ];
    }
}
?>

<?php if ( ! empty( $gallery_slides ) ) : ?>
    <div class="slideshow-container">
        <?php foreach ( $gallery_slides as $slide ) : ?>
            <div class="itemgallery fade">
                <img src="<?php echo esc_url( $slide['url'] ); ?>"
                     alt="<?php echo esc_attr( $slide['alt'] ); ?>"
                     style="width:100%">
            </div>
        <?php endforeach; ?>

        <?php if ( count( $gallery_slides ) > 1 ) : ?>
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        <?php endif; ?>
    </div>

    <?php if ( count( $gallery_slides ) > 1 ) : ?>
        <div style="text-align:center">
            <?php for ( $i = 1; $i <= count( $gallery_slides ); $i++ ) : ?>
                <span class="dot" onclick="currentSlide(<?php echo esc_attr( $i ); ?>)"></span>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php
if ($form_factor || $propulsion_type || $max_range_meters || $wheel_count || $empty_weight || $cargo_volume || $cargo_load || $width || $length || $loading_area_length || $loading_area_width || $seat_post_adj || $handlebar_adj || $hitches || $make || $model):
?>
<div class="cb-vehicles-top-infos">
    <ul style="list-style-type:none;">

        <?php if ( $propulsion_type ) : ?>
            <li>
                <i class="icon-flash"></i>
                <?php if ( $propulsion_type === 'electric_assist' && $max_permitted_speed ) : ?>
                    <?php printf(
                        /* translators: %s: speed in km/h */
                        esc_html__( 'Electric assist up to %s km/h', 'cb-vehicles' ),
                        esc_html( $max_permitted_speed )
                    ); ?>
                <?php elseif ( $propulsion_type === 'human' ) : ?>
                    <?php esc_html_e( 'No electric assist', 'cb-vehicles' ); ?>
                <?php else : ?>
                    <?php echo esc_html( $propulsion_labels[ $propulsion_type ] ?? $propulsion_type ); ?>
                    <?php if ( $max_permitted_speed ) : ?>
                        <?php printf(
                            /* translators: %s: speed in km/h */
                            esc_html__( '(max. %s km/h)', 'cb-vehicles' ),
                            esc_html( $max_permitted_speed )
                        ); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </li>
        <?php endif; ?>

        <?php if ( $cargo_load ) : ?>
            <li>
                <i class="icon-balance-scale"></i>
                <?php printf(
                    /* translators: %s: weight in kg */
                    esc_html__( 'Load capacity: %s kg', 'cb-vehicles' ),
                    esc_html( $cargo_load )
                ); ?>
            </li>
        <?php endif; ?>

    </ul>
</div>

<button type="button" class="accordion">
    <b><i class="icon-cog-alt"></i> <?php esc_html_e( 'Technical details', 'cb-vehicles' ); ?></b>
</button>
<div class="panel">
    <ul style="list-style-type:none;">

        <?php if ( $form_factor ) : ?>
            <li>
                <i class="icon-info-circled"></i>
                <b><?php esc_html_e( 'Vehicle type:', 'cb-vehicles' ); ?></b>
                <?php echo esc_html( $form_factor_labels[ $form_factor ] ?? $form_factor ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $propulsion_type ) : ?>
            <li>
                <i class="icon-flash"></i>
                <b><?php esc_html_e( 'Propulsion type:', 'cb-vehicles' ); ?></b>
                <?php echo esc_html( $propulsion_labels[ $propulsion_type ] ?? $propulsion_type ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $max_range_meters ) : ?>
            <li>
                <i class="icon-road"></i>
                <b><?php esc_html_e( 'Range:', 'cb-vehicles' ); ?></b>
                <?php printf(
                    /* translators: %s: range in meters */
                    esc_html__( '%s m', 'cb-vehicles' ),
                    esc_html( $max_range_meters )
                ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $wheel_count ) : ?>
            <li>
                <i class="icon-circle-empty"></i>
                <b><?php esc_html_e( 'Wheel count:', 'cb-vehicles' ); ?></b>
                <?php echo esc_html( $wheel_count ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $empty_weight ) : ?>
            <li>
                <i class="icon-balance-scale"></i>
                <b><?php esc_html_e( 'Empty weight:', 'cb-vehicles' ); ?></b>
                <?php printf(
                    /* translators: %s: weight in kg */
                    esc_html__( '%s kg', 'cb-vehicles' ),
                    esc_html( $empty_weight )
                ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $cargo_volume ) : ?>
            <li>
                <i class="icon-truck"></i>
                <b><?php esc_html_e( 'Cargo volume:', 'cb-vehicles' ); ?></b>
                <?php printf(
                    /* translators: %s: volume in liters */
                    esc_html__( '%s l', 'cb-vehicles' ),
                    esc_html( $cargo_volume )
                ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $cargo_load ) : ?>
            <li>
                <i class="icon-balance-scale"></i>
                <b><?php esc_html_e( 'Cargo load capacity:', 'cb-vehicles' ); ?></b>
                <?php printf(
                    /* translators: %s: weight in kg */
                    esc_html__( '%s kg', 'cb-vehicles' ),
                    esc_html( $cargo_load )
                ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $width ) : ?>
            <li>
                <i class="icon-ruler"></i>
                <b><?php esc_html_e( 'Width:', 'cb-vehicles' ); ?></b>
                <?php printf(
                    /* translators: %s: width in cm */
                    esc_html__( '%s cm', 'cb-vehicles' ),
                    esc_html( $width )
                ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $length ) : ?>
            <li>
                <i class="icon-ruler"></i>
                <b><?php esc_html_e( 'Length:', 'cb-vehicles' ); ?></b>
                <?php printf(
                    /* translators: %s: length in cm */
                    esc_html__( '%s cm', 'cb-vehicles' ),
                    esc_html( $length )
                ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $loading_area_length || $loading_area_width ) : ?>
            <li>
                <i class="icon-ruler"></i>
                <b><?php esc_html_e( 'Loading area:', 'cb-vehicles' ); ?></b>
                <?php if ( $loading_area_length && $loading_area_width ) : ?>
                    <?php printf(
                        /* translators: 1: length in cm, 2: width in cm */
                        esc_html__( '%1$s cm × %2$s cm', 'cb-vehicles' ),
                        esc_html( $loading_area_length ),
                        esc_html( $loading_area_width )
                    ); ?>
                <?php elseif ( $loading_area_length ) : ?>
                    <?php printf(
                        /* translators: %s: length in cm */
                        esc_html__( '%s cm (length)', 'cb-vehicles' ),
                        esc_html( $loading_area_length )
                    ); ?>
                <?php else : ?>
                    <?php printf(
                        /* translators: %s: width in cm */
                        esc_html__( '%s cm (width)', 'cb-vehicles' ),
                        esc_html( $loading_area_width )
                    ); ?>
                <?php endif; ?>
            </li>
        <?php endif; ?>

        <?php if ( $seat_post_adj ) : ?>
            <li>
                <i class="icon-ruler"></i>
                <?php esc_html_e( 'Seat post adjustable', 'cb-vehicles' ); ?>
            </li>
        <?php endif; ?>

        <?php if ( $handlebar_adj ) : ?>
            <li>
                <i class="icon-ruler"></i>
                <?php esc_html_e( 'Handlebar adjustable', 'cb-vehicles' ); ?>
            </li>
        <?php endif; ?>

        <?php if ( ! empty( $hitches ) ) : ?>
            <li>
                <i class="icon-link"></i>
                <b><?php esc_html_e( 'Hitches:', 'cb-vehicles' ); ?></b>
                <?php
                $hitch_names = [];
                foreach ( $hitches as $hitch ) {
                    $hitch_make = $hitch[ $prefix . 'hitch_make' ] ?? '';
                    if ( $hitch_make ) {
                        $hitch_names[] = esc_html( $hitch_labels[ $hitch_make ] ?? $hitch_make );
                    }
                }
                echo esc_html( implode( ', ', $hitch_names ) );
                ?>
            </li>
        <?php endif; ?>

        <?php if ( $make || $model ) : ?>
            <li>
                <i class="icon-sticky-note-o"></i>
                <?php if ( $make && $model ) : ?>
                    <b><?php esc_html_e( 'Make / Model:', 'cb-vehicles' ); ?></b>
                    <?php echo esc_html( $make ) . ' ' . esc_html( $model ); ?>
                <?php elseif ( $make ) : ?>
                    <b><?php esc_html_e( 'Make:', 'cb-vehicles' ); ?></b>
                    <?php echo esc_html( $make ); ?>
                <?php else : ?>
                    <b><?php esc_html_e( 'Model:', 'cb-vehicles' ); ?></b>
                    <?php echo esc_html( $model ); ?>
                <?php endif; ?>
            </li>
        <?php endif; ?>

    </ul>
</div>
<?php endif; ?>
