<?php
/**
 * Renders the ACF Flexible Content field `flexible_sections` for the current Page.
 *
 * @package news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="container">

	<?php if ( function_exists( 'have_rows' ) && have_rows( 'flexible_sections' ) ) : ?>
		<?php
		while ( have_rows( 'flexible_sections' ) ) :
			the_row();
			$layout = get_row_layout();
			if ( ! is_string( $layout ) || '' === $layout ) {
				continue;
			}
			// ACF layout names use underscores; partial files use hyphens (e.g. featured_highlights → featured-highlights).
			$slug = str_replace( '_', '-', $layout );
			get_template_part( 'partials/flexible-content/' . $slug );
		endwhile;
		?>
	<?php endif; ?>

</div>
