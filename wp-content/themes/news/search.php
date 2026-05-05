<?php
/**
 * Search results template.
 *
 * @package news
 */

get_header();

$search_query = get_search_query();
?>

<section id="content">
	<div class="content-wrap pt-0 pt-sm-6">
		<div class="container">
			<div class="row gutter-50">
				<div class="col-lg-3 cat-widgets position-sticky h-100" style="top: 234px;">
					<?php get_template_part( 'partials/archive/sidebar' ); ?>
				</div>
				<div class="col-lg-9">
					<?php
					if ( have_posts() ) {
						get_template_part(
							'partials/archive/posts-main-column',
							null,
							array(
								'heading' => sprintf(
									/* translators: %s: search query text. */
									esc_html__( 'Search Results for: %s', 'news-wp' ),
									$search_query
								),
							)
						);
					} else {
						?>
						<h3><?php echo esc_html( sprintf( __( 'No results found for: %s', 'news-wp' ), $search_query ) ); ?></h3>
						<p><?php esc_html_e( 'Try another keyword.', 'news-wp' ); ?></p>

						<form role="search" method="get" class="input-group mt-4 mb-0" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<input class="form-control" type="search" name="s" placeholder="<?php esc_attr_e( 'Search', 'news-wp' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'news-wp' ); ?>" value="<?php echo esc_attr( $search_query ); ?>" autocomplete="off">
							<button class="btn btn-outline-secondary uil uil-search" type="submit" aria-label="<?php esc_attr_e( 'Search', 'news-wp' ); ?>"></button>
						</form>
						<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
