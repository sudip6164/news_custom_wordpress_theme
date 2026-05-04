<?php
/**
 * Pages: same flexible builder as the static front page when assigned to Page post type.
 *
 * @package news
 */

get_header();
?>

<section id="content">
	<div class="content-wrap pt-5">
		<?php get_template_part( 'partials/flexible-sections' ); ?>
	</div>
</section>

<?php
get_footer();
