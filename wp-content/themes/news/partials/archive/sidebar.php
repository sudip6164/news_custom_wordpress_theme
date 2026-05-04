<?php
/**
 * List page sidebar: search + category nav (Canvas blog list demo).
 *
 * @package news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$posts_page_id = (int) get_option( 'page_for_posts' );
if ( 'posts' === get_option( 'show_on_front' ) ) {
	$all_categories_url = home_url( '/' );
	$all_categories_active = is_front_page() && ! is_paged();
} else {
	$all_categories_url    = $posts_page_id ? get_permalink( $posts_page_id ) : home_url( '/' );
	$all_categories_active = $posts_page_id && is_home() && ! is_paged();
}

$current_category_id = is_category() ? (int) get_queried_object_id() : 0;

$categories = get_categories(
	array(
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>

<div class="widget widget-search">
	<form role="search" method="get" class="input-group" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<input class="form-control" type="search" name="s" placeholder="Search" aria-label="Search" value="<?php echo esc_attr( get_search_query() ); ?>" autocomplete="off">
		<button class="btn btn-outline-secondary uil uil-search" type="submit" aria-label="Search"></button>
	</form>
</div>

<div class="widget widget-nav mt-md-5">
	<ul class="nav">
		<li class="nav-item<?php echo $all_categories_active ? ' active' : ''; ?>">
			<a class="nav-link" href="<?php echo esc_url( $all_categories_url ); ?>">All Categories</a>
		</li>
		<?php foreach ( $categories as $cat ) : ?>
			<?php
			$cat_id    = (int) $cat->term_id;
			$is_active = $current_category_id === $cat_id;
			?>
			<li class="nav-item<?php echo $is_active ? ' active' : ''; ?>">
				<a class="nav-link" href="<?php echo esc_url( get_category_link( $cat_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
