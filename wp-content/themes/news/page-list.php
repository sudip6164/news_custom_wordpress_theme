<?php
/**
 * List page (Page slug: list).
 *
 * Full-width flexible rows named "banner", then #content with sidebar + all other flexible rows.
 *
 * @package news
 */

get_header();

$page_id = get_queried_object_id();

$banners_html = '';
$main_html    = '';

if ( function_exists( 'have_rows' ) && have_rows( 'flexible_sections', $page_id ) ) {
	while ( have_rows( 'flexible_sections', $page_id ) ) {
		the_row();
		$layout = get_row_layout();
		if ( ! is_string( $layout ) || '' === $layout ) {
			continue;
		}
		$slug = str_replace( '_', '-', $layout );
		ob_start();
		get_template_part( 'partials/flexible-content/' . $slug );
		$chunk = ob_get_clean();
		if ( 'banner' === $layout ) {
			$banners_html .= $chunk;
		} else {
			$main_html .= $chunk;
		}
	}
}

echo $banners_html;
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
					$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );
					$sort  = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'latest';
					$order = function_exists( 'news_get_sort_query_args' ) ? news_get_sort_query_args( $sort ) : array(
						'orderby' => 'date',
						'order'   => 'DESC',
					);

					$list_query = new WP_Query(
						array(
							'post_type'           => 'post',
							'post_status'         => 'publish',
							'posts_per_page'      => (int) get_option( 'posts_per_page' ),
							'paged'               => $paged,
							'ignore_sticky_posts' => true,
							'orderby'             => $order['orderby'],
							'order'               => $order['order'],
						)
					);

					get_template_part(
						'partials/archive/posts-main-column',
						null,
						array(
							'heading' => 'All Posts',
							'query'   => $list_query,
						)
					);
					?>
				</div>
			</div>
			<?php if ( '' !== $main_html ) : ?>
				<div class="mt-5">
					<?php echo $main_html; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
