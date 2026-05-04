<?php
/**
 * Flexible section: Advertisement (demo-blog ad-banner block).
 *
 * Expected ACF sub fields:
 * - advertisement_label (text, optional)
 * - advertisement_url (url/text, optional)
 * - advertisement_image (image, required for visible banner)
 * - advertisement_open_new_tab (true/false, optional)
 *
 * @package news
 */

$label        = get_sub_field( 'advertisement_label' );
$url          = get_sub_field( 'advertisement_url' );
$image_raw    = get_sub_field( 'advertisement_image' );
$open_new_tab = (bool) get_sub_field( 'advertisement_open_new_tab' );

$label = is_string( $label ) && '' !== trim( $label ) ? $label : 'Advertisement';
$url   = is_string( $url ) ? trim( $url ) : '';

$image_url = '';
$image_alt = 'Ad Image';

if ( is_string( $image_raw ) && '' !== trim( $image_raw ) ) {
	$image_url = trim( $image_raw );
} elseif ( is_numeric( $image_raw ) ) {
	$image_url = (string) wp_get_attachment_image_url( (int) $image_raw, 'full' );
	$alt = get_post_meta( (int) $image_raw, '_wp_attachment_image_alt', true );
	if ( is_string( $alt ) && '' !== trim( $alt ) ) {
		$image_alt = $alt;
	}
} elseif ( is_array( $image_raw ) ) {
	if ( ! empty( $image_raw['url'] ) ) {
		$image_url = (string) $image_raw['url'];
	}
	if ( ! empty( $image_raw['alt'] ) && is_string( $image_raw['alt'] ) ) {
		$image_alt = $image_raw['alt'];
	}
}

if ( '' === $image_url ) {
	return;
}

$link_attrs = '';
if ( $open_new_tab ) {
	$link_attrs = ' target="_blank" rel="noopener noreferrer"';
}
?>

<div class="section">
	<div class="container">
		<div class="ad-banner">
			<small class="mb-2 d-block"><?php echo esc_html( $label ); ?></small>
			<?php if ( '' !== $url ) : ?>
				<a href="<?php echo esc_url( $url ); ?>"<?php echo $link_attrs; ?>>
					<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
				</a>
			<?php else : ?>
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
			<?php endif; ?>
		</div>
	</div>
</div>
