<?php
/**
 * Toolbar + 3-column post grid (+ pagination). Uses main query unless $args['query'] is a WP_Query.
 *
 * @package news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_pmc       = isset( $args ) && is_array( $args ) ? $args : array();
$heading    = isset( $_pmc['heading'] ) ? (string) $_pmc['heading'] : '';
$custom_q   = isset( $_pmc['query'] ) && $_pmc['query'] instanceof WP_Query ? $_pmc['query'] : null;
$before     = isset( $_pmc['before'] ) ? (string) $_pmc['before'] : '';

$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

?>

<?php echo $before; ?>

<div class="d-flex mb-4">
	<div class="flex-grow-1">
		<?php if ( '' !== $heading ) : ?>
			<h3><?php echo esc_html( $heading ); ?></h3>
		<?php endif; ?>
	</div>
	<div>
		<div class="btn-group">
			<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Most Popular</button>
			<div class="dropdown-menu">
				<a class="dropdown-item" href="#">Latest Posts</a>
				<a class="dropdown-item" href="#">Most Comments</a>
			</div>
		</div>
	</div>
</div>

<div class="row col-mb-50 posts-md">
	<?php
	if ( $custom_q ) {
		if ( $custom_q->have_posts() ) {
			while ( $custom_q->have_posts() ) {
				$custom_q->the_post();
				get_template_part( 'partials/archive/content', 'post-card' );
			}
			wp_reset_postdata();
		}
	} elseif ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'partials/archive/content', 'post-card' );
		}
	}
	?>
</div>

<?php if ( $custom_q && $custom_q->max_num_pages > 1 ) : ?>
	<nav class="navigation pagination mt-4" aria-label="Posts">
		<?php
		echo wp_kses_post(
			paginate_links(
				array(
					'total'   => $custom_q->max_num_pages,
					'current' => $paged,
					'type'    => 'list',
				)
			)
		);
		?>
	</nav>
<?php elseif ( ! $custom_q ) : ?>
	<?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
<?php endif; ?>
