<?php
$title       = get_sub_field( 'banner_title' );
$description = get_sub_field( 'banner_description' );
$bg_raw      = get_sub_field( 'banner_background' );

$title       = is_string( $title ) ? $title : '';
$description = is_string( $description ) ? $description : '';

$bg_url = '';
if ( is_string( $bg_raw ) && '' !== $bg_raw ) {
	$bg_url = $bg_raw;
} elseif ( is_numeric( $bg_raw ) ) {
	$u = wp_get_attachment_image_url( (int) $bg_raw, 'full' );
	$bg_url = $u ? $u : '';
} elseif ( is_array( $bg_raw ) ) {
	if ( ! empty( $bg_raw['url'] ) ) {
		$bg_url = (string) $bg_raw['url'];
	} elseif ( ! empty( $bg_raw['ID'] ) ) {
		$u = wp_get_attachment_image_url( (int) $bg_raw['ID'], 'full' );
		$bg_url = $u ? $u : '';
	}
}

if ( '' === $title && '' === $description && '' === $bg_url ) {
	return;
}

$section_classes = 'page-title page-title-center mb-5';
$inline_styles   = array();

if ( '' !== $bg_url ) {
	$section_classes .= ' text-white py-5';
	$inline_styles[] = 'background-image: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url(' . esc_url( $bg_url ) . ')';
	$inline_styles[] = 'background-size: cover';
	$inline_styles[] = 'background-position: center center';
}
?>

<section class="<?php echo esc_attr( $section_classes ); ?>"<?php echo $inline_styles ? ' style="' . esc_attr( implode( '; ', $inline_styles ) ) . '"' : ''; ?>>

	<div class="page-title-row">
		<div class="page-title-content mw-sm">
			<?php if ( '' !== $title ) : ?>
				<h1><?php echo esc_html( $title ); ?></h1>
			<?php endif; ?>
			<?php if ( '' !== $description ) : ?>
				<span><?php echo wp_kses_post( $description ); ?></span>
			<?php endif; ?>
		</div>
	</div>

</section>
