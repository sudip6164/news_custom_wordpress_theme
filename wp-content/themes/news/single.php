<?php
/**
 * Single post template (demo-blog-single structure, without related posts section).
 *
 * @package news
 */

get_header();
?>

<section id="content">
	<div class="content-wrap pt-5" style="overflow: visible;">
		<div class="container">
			<div class="single-post mb-0">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
							<div class="row justify-content-center">
								<div class="col-lg-6">
									<div class="entry-title">
										<?php
										$cats = get_the_category();
										if ( ! empty( $cats[0] ) && $cats[0] instanceof WP_Term ) :
											$cat = $cats[0];
											?>
											<div class="entry-categories"><a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a></div>
										<?php endif; ?>
										<h2><?php the_title(); ?></h2>
									</div>
								</div>
							</div>

							<div class="d-flex justify-content-center mt-2">
								<div class="entry-meta">
									<ul>
										<li><?php echo esc_html( get_the_date( 'jS F Y' ) ); ?></li>
										<li>By <?php the_author_posts_link(); ?></li>
									</ul>
								</div>
							</div>

							<?php if ( has_post_thumbnail() ) : ?>
								<?php $full = get_the_post_thumbnail_url( get_the_ID(), 'full' ); ?>
								<div class="entry-image mt-5">
									<a href="<?php echo esc_url( $full ? $full : get_permalink() ); ?>" data-lightbox="image">
										<?php the_post_thumbnail( 'large', array( 'class' => 'rounded', 'alt' => '' ) ); ?>
									</a>
								</div>
							<?php endif; ?>

							<div class="entry-content">
								<div class="row">
									<div class="col-lg-2 media-content">
										<div class="entry-title text-start">
											<h4><?php the_title(); ?></h4>
										</div>
										<div>
											<h5 class="mb-2">Share this Post:</h5>
											<div>
												<a href="#" class="social-icon si-small rounded-circle text-light border-0 bg-facebook"><i class="fa-brands fa-facebook-f"></i><i class="fa-brands fa-facebook-f"></i></a>
												<a href="#" class="social-icon si-small rounded-circle text-light border-0 bg-x-twitter"><i class="fa-brands fa-x-twitter"></i><i class="fa-brands fa-x-twitter"></i></a>
												<a href="#" class="social-icon si-small rounded-circle text-light border-0 bg-pinterest"><i class="fa-brands fa-pinterest-p"></i><i class="fa-brands fa-pinterest-p"></i></a>
												<a href="#" class="social-icon si-small rounded-circle text-light border-0 bg-rss"><i class="fa-solid fa-rss"></i><i class="fa-solid fa-rss"></i></a>
											</div>
										</div>
									</div>

									<div class="col-lg-1"></div>

									<div class="text-content col-lg-6">
										<?php the_content(); ?>

										<?php
										$tags = get_the_tags();
										if ( $tags ) :
											?>
											<div class="line"></div>
											<h4 class="mb-3">Related Tags</h4>
											<div class="tagcloud">
												<?php foreach ( $tags as $tag ) : ?>
													<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>

										<div class="clear"></div>

										<?php comments_template(); ?>
									</div>
								</div>
							</div>
						</article>
					<?php endwhile; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
