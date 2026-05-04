<?php get_header(); ?>

<section id="content">
	<div class="content-wrap pt-5" style="overflow: visible;">
		<div class="container">

			<?php if ( function_exists( 'have_rows' ) && have_rows( 'home_sections' ) ) : ?>
				<?php while ( have_rows( 'home_sections' ) ) : the_row(); ?>

					<?php if ( 'featured_highlights' === get_row_layout() ) : ?>
						<?php get_template_part( 'partials/flexible-content/featured-highlights' ); ?>
					<?php elseif ( 'newsletter' === get_row_layout() ) : ?>
						<?php get_template_part( 'partials/flexible-content/newsletter' ); ?>
					<?php endif; ?>

				<?php endwhile; ?>
			<?php endif; ?>

		</div>
	</div>
</section>

<?php get_footer(); ?>