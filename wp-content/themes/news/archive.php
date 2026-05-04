<?php
/**
 * Taxonomy archives: banner, sidebar, post grid (Canvas list demo).
 *
 * @package news
 */

get_header();

$banner_title       = '';
$banner_description = '';
$banner_bg_url      = '';

$acf_term_key = null;
if ( is_category() ) {
	$acf_term_key = 'category_' . get_queried_object_id();
} elseif ( is_tag() ) {
	$acf_term_key = 'post_tag_' . get_queried_object_id();
} elseif ( is_tax() ) {
	$term = get_queried_object();
	if ( $term instanceof WP_Term ) {
		$acf_term_key = $term->taxonomy . '_' . $term->term_id;
	}
}

if ( $acf_term_key && function_exists( 'get_field' ) ) {
	$t = get_field( 'banner_title', $acf_term_key );
	if ( is_string( $t ) && '' !== $t ) {
		$banner_title = $t;
	}

	$d = get_field( 'banner_description', $acf_term_key );
	if ( is_string( $d ) && '' !== $d ) {
		$banner_description = $d;
	}

	$bg_raw = get_field( 'banner_background', $acf_term_key );
	if ( is_string( $bg_raw ) && '' !== $bg_raw ) {
		$banner_bg_url = $bg_raw;
	} elseif ( is_numeric( $bg_raw ) ) {
		$img = wp_get_attachment_image_url( (int) $bg_raw, 'full' );
		$banner_bg_url = $img ? $img : '';
	} elseif ( is_array( $bg_raw ) ) {
		if ( ! empty( $bg_raw['url'] ) ) {
			$banner_bg_url = (string) $bg_raw['url'];
		} elseif ( ! empty( $bg_raw['ID'] ) ) {
			$img = wp_get_attachment_image_url( (int) $bg_raw['ID'], 'full' );
			$banner_bg_url = $img ? $img : '';
		}
	}
}

if ( '' === $banner_title ) {
	$archive_title = get_the_archive_title( '', '', false );
	if ( is_string( $archive_title ) && '' !== $archive_title ) {
		$banner_title = wp_strip_all_tags( $archive_title );
	}
}

if ( '' === $banner_description ) {
	$desc = get_the_archive_description();
	if ( is_string( $desc ) && '' !== $desc ) {
		$banner_description = $desc;
	}
}

$section_classes = 'page-title page-title-center';
$inline_styles   = array();

if ( '' !== $banner_bg_url ) {
	$section_classes .= ' text-white py-5';
	$inline_styles[] = 'background-image: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url(' . esc_url( $banner_bg_url ) . ')';
	$inline_styles[] = 'background-size: cover';
	$inline_styles[] = 'background-position: center center';
}
?>

<section class="<?php echo esc_attr( $section_classes ); ?>"<?php echo $inline_styles ? ' style="' . esc_attr( implode( '; ', $inline_styles ) ) . '"' : ''; ?>>
	<div class="container">
		<div class="page-title-row">
			<div class="page-title-content mw-sm">
				<?php if ( '' !== $banner_title ) : ?>
					<h1><?php echo esc_html( $banner_title ); ?></h1>
				<?php endif; ?>
				<?php if ( '' !== $banner_description ) : ?>
					<span><?php echo wp_kses_post( $banner_description ); ?></span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<section id="content">
	<div class="content-wrap pt-0 pt-sm-6">
		<div class="container">
			<div class="row gutter-50">
				<div class="col-lg-3 cat-widgets position-sticky h-100" style="top: 234px;">
					<?php get_template_part( 'partials/archive/sidebar' ); ?>
				</div>
				<div class="col-lg-9">
					<?php
					$grid_heading = '';
					if ( is_category() ) {
						$grid_heading = sprintf( 'All %s Posts', single_cat_title( '', false ) );
					} elseif ( is_tag() ) {
						$grid_heading = sprintf( 'All %s Posts', single_tag_title( '', false ) );
					} elseif ( is_tax() ) {
						$term = get_queried_object();
						if ( $term instanceof WP_Term ) {
							$grid_heading = sprintf( 'All %s Posts', $term->name );
						}
					} else {
						$t = get_the_archive_title( '', '', false );
						$grid_heading = is_string( $t ) ? wp_strip_all_tags( $t ) : '';
					}
					get_template_part(
						'partials/archive/posts-main-column',
						null,
						array( 'heading' => $grid_heading )
					);
					?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
