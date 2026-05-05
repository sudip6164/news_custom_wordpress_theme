<?php
/**
 * One card in the list grid (Canvas blog categories demo).
 *
 * @package news
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cats = get_the_category();
$is_list_page = is_page_template( 'page-list.php' ) || is_page( 'list' );

?>

<div class="col-md-4">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="entry-image mb-3">
				<a href="<?php the_permalink(); ?>">
					<?php
					the_post_thumbnail(
						'medium_large',
						array(
							'alt'   => '',
							'style' => 'width:100%;height:240px;object-fit:cover;',
						)
					);
					?>
				</a>
			</div>
		<?php endif; ?>

		<div class="entry-title title-sm">
			<?php if ( ! empty( $cats[0] ) && $cats[0] instanceof WP_Term ) : ?>
				<?php $c = $cats[0]; ?>
				<div class="entry-categories"><a href="<?php echo esc_url( get_category_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a></div>
			<?php endif; ?>
			<h3>
				<a
					href="<?php the_permalink(); ?>"
					class="color-underline stretched-link<?php echo $is_list_page ? ' news-line-clamp-3' : ''; ?>"
				>
					<?php the_title(); ?>
				</a>
			</h3>
		</div>

		<div class="entry-meta">
			<ul>
				<li><?php echo esc_html( get_the_date() ); ?></li>
			</ul>
		</div>
	</article>
</div>
