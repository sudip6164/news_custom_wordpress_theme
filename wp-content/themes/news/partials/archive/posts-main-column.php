<?php
/**
 * Toolbar + 3-column post grid (+ pagination). Optional $args['after'] HTML after the grid.
 * Uses main query unless $args['query'] is a WP_Query.
 *
 * @package news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_pmc       = isset( $args ) && is_array( $args ) ? $args : array();
$heading    = isset( $_pmc['heading'] ) ? (string) $_pmc['heading'] : '';
$custom_q   = isset( $_pmc['query'] ) && $_pmc['query'] instanceof WP_Query ? $_pmc['query'] : null;
$after      = isset( $_pmc['after'] ) ? (string) $_pmc['after'] : '';

$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
$sort  = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'latest';
$sort  = in_array( $sort, array( 'latest', 'oldest', 'comments' ), true ) ? $sort : 'latest';

$sort_labels = array(
	'latest'   => 'Latest Posts',
	'oldest'   => 'Oldest Posts',
	'comments' => 'Most Comments',
);

$sort_links = array(
	'latest'   => add_query_arg( 'sort', 'latest' ),
	'oldest'   => add_query_arg( 'sort', 'oldest' ),
	'comments' => add_query_arg( 'sort', 'comments' ),
);

?>

<div class="d-flex mb-4">
	<div class="flex-grow-1">
		<?php if ( '' !== $heading ) : ?>
			<h3><?php echo esc_html( $heading ); ?></h3>
		<?php endif; ?>
	</div>
	<div>
		<div class="btn-group">
			<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?php echo esc_html( $sort_labels[ $sort ] ); ?></button>
			<div class="dropdown-menu">
				<a class="dropdown-item<?php echo 'latest' === $sort ? ' active' : ''; ?>" href="<?php echo esc_url( $sort_links['latest'] ); ?>">Latest Posts</a>
				<a class="dropdown-item<?php echo 'oldest' === $sort ? ' active' : ''; ?>" href="<?php echo esc_url( $sort_links['oldest'] ); ?>">Oldest Posts</a>
				<a class="dropdown-item<?php echo 'comments' === $sort ? ' active' : ''; ?>" href="<?php echo esc_url( $sort_links['comments'] ); ?>">Most Comments</a>
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
					'add_args'=> array(
						'sort' => $sort,
					),
				)
			)
		);
		?>
	</nav>
<?php elseif ( ! $custom_q ) : ?>
	<?php the_posts_pagination( array( 'mid_size' => 2, 'add_args' => array( 'sort' => $sort ) ) ); ?>
<?php endif; ?>

<?php if ( '' !== $after ) : ?>
	<div class="list-page-flexible-after mt-5">
		<?php echo $after; ?>
	</div>
<?php endif; ?>
