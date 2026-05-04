<?php
$featured_title    = get_sub_field( 'featured_title' );
$highlights_title  = get_sub_field( 'highlights_title' );
$section_style    = get_sub_field( 'section_style' ) ?: 'light';

$news_acf_first_sub = static function ( array $names ) {
	foreach ( $names as $name ) {
		$value = get_sub_field( $name );
		if ( null !== $value && '' !== $value && false !== $value ) {
			return $value;
		}
	}
	return null;
};

$news_normalize_pick_mode = static function ( $raw ) {
	if ( is_array( $raw ) ) {
		$raw = reset( $raw );
	}

	$mode = strtolower( trim( (string) $raw ) );
	if ( '' === $mode ) {
		return '';
	}

	if ( in_array( $mode, array( 'automatic', 'auto' ), true ) ) {
		return 'automatic';
	}

	if ( 'manual' === $mode ) {
		return 'manual';
	}

	return $mode;
};

$featured_mode_raw = $news_acf_first_sub(
	array(
		'featured_mode',
		'featured_pick_mode',
		'featured_source',
	)
);
$highlights_mode_raw = $news_acf_first_sub(
	array(
		'highlights_mode',
		'highlight_mode',
		'highlights_pick_mode',
	)
);

$featured_mode   = $news_normalize_pick_mode( $featured_mode_raw ) ?: 'manual';
$highlights_mode = $news_normalize_pick_mode( $highlights_mode_raw ) ?: 'manual';

$highlight_count  = (int) get_sub_field( 'highlight_count' );
$highlight_count  = $highlight_count > 0 ? $highlight_count : 4;
$exclude_featured = (bool) get_sub_field( 'exclude_featured' );

$featured_post  = null;
$highlight_posts = array();

/**
 * Resolve a category-ish ACF value into a term_id for WP_Query `cat`.
 *
 * @param mixed $value
 * @return int
 */
$news_resolve_cat_id = static function ( $value ) {
	if ( ! $value ) {
		return 0;
	}

	if ( is_numeric( $value ) ) {
		return (int) $value;
	}

	if ( $value instanceof WP_Term ) {
		return (int) $value->term_id;
	}

	if ( is_array( $value ) ) {
		$first = reset( $value );
		if ( $first instanceof WP_Term ) {
			return (int) $first->term_id;
		}
		if ( is_numeric( $first ) ) {
			return (int) $first;
		}
	}

	return 0;
};

$news_primary_category_markup = static function ( $post ) {
	if ( ! ( $post instanceof WP_Post ) ) {
		return '';
	}

	$cats = get_the_category( $post->ID );
	if ( empty( $cats ) || empty( $cats[0] ) || ! ( $cats[0] instanceof WP_Term ) ) {
		return '';
	}

	$cat = $cats[0];

	return '<div class="entry-categories"><a href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a></div>';
};

if ( 'manual' === $featured_mode ) {
	$featured_raw = get_sub_field( 'featured_post' );
	if ( $featured_raw instanceof WP_Post ) {
		$featured_post = $featured_raw;
	} elseif ( is_numeric( $featured_raw ) ) {
		$featured_post = get_post( (int) $featured_raw );
	}
} elseif ( 'automatic' === $featured_mode ) {
	$featured_args = array(
		'post_type'           => 'any',
		'post_type__not_in'   => array( 'attachment' ),
		'post_status'         => 'publish',
		'posts_per_page'      => 1,
		'ignore_sticky_posts' => true,
	);

	$front_page_id = (int) get_option( 'page_on_front' );
	if ( $front_page_id ) {
		$featured_args['post__not_in'] = array( $front_page_id );
	}

	$featured_query_category = $news_acf_first_sub(
		array(
			'featured_category',
			'featured_query_category',
		)
	);
	$featured_cat_id         = $news_resolve_cat_id( $featured_query_category );
	if ( $featured_cat_id ) {
		$featured_args['cat'] = $featured_cat_id;
	}

	$featured_posts = get_posts( $featured_args );
	if ( ! empty( $featured_posts ) ) {
		$featured_post = $featured_posts[0];
	}
}

if ( 'manual' === $highlights_mode ) {
	$selected = $news_acf_first_sub(
		array(
			'highlights_posts',
			'highlight_posts',
			'highlights_post',
			'highlight_post',
		)
	);

	$selected_ids = array();

	if ( is_string( $selected ) ) {
		$trimmed = trim( $selected );

		$maybe = maybe_unserialize( $trimmed );
		if ( is_array( $maybe ) ) {
			$selected = $maybe;
		} elseif ( is_string( $maybe ) && function_exists( 'acf_get_array' ) ) {
			$selected = acf_get_array( $maybe );
		} elseif ( '' !== $trimmed && isset( $trimmed[0] ) && ( '[' === $trimmed[0] || '{' === $trimmed[0] ) ) {
			$json = json_decode( $trimmed, true );
			$selected = is_array( $json ) ? $json : array_filter( array_map( 'trim', explode( ',', $trimmed ) ) );
		} else {
			$selected = array_filter( array_map( 'trim', explode( ',', $trimmed ) ) );
		}
	}

	if ( is_array( $selected ) ) {
		foreach ( $selected as $post_item ) {
			if ( $post_item instanceof WP_Post ) {
				$selected_ids[] = (int) $post_item->ID;
			} elseif ( is_array( $post_item ) ) {
				if ( isset( $post_item['ID'] ) && is_numeric( $post_item['ID'] ) ) {
					$selected_ids[] = (int) $post_item['ID'];
				} elseif ( isset( $post_item['id'] ) && is_numeric( $post_item['id'] ) ) {
					$selected_ids[] = (int) $post_item['id'];
				}
			} elseif ( is_numeric( $post_item ) ) {
				$selected_ids[] = (int) $post_item;
			}
		}
	} elseif ( $selected instanceof WP_Post ) {
		$selected_ids[] = (int) $selected->ID;
	} elseif ( is_numeric( $selected ) ) {
		$selected_ids[] = (int) $selected;
	}

	$selected_ids = array_values( array_unique( array_filter( $selected_ids ) ) );

	foreach ( $selected_ids as $post_id ) {
		$post_obj = get_post( $post_id );
		if ( $post_obj instanceof WP_Post ) {
			$highlight_posts[] = $post_obj;
		}
	}
} elseif ( 'automatic' === $highlights_mode ) {
	$args = array(
		'post_type'           => 'any',
		'post_type__not_in'   => array( 'attachment' ),
		'post_status'         => 'publish',
		'posts_per_page'      => $highlight_count,
		'ignore_sticky_posts' => true,
	);

	$front_page_id = (int) get_option( 'page_on_front' );
	if ( $front_page_id ) {
		$args['post__not_in'] = array( $front_page_id );
	}

	$highlights_category = get_sub_field( 'highlights_category' );
	$highlights_cat_id     = $news_resolve_cat_id( $highlights_category );
	if ( $highlights_cat_id ) {
		$args['cat'] = $highlights_cat_id;
	}

	if ( $exclude_featured && $featured_post instanceof WP_Post ) {
		$args['post__not_in'] = array( (int) $featured_post->ID );
	}

	$highlight_posts = get_posts( $args );
}

if ( 'dark' === $section_style ) :
	?>
	<div class="section dark">
		<div class="container">
			<?php if ( $featured_title ) : ?>
				<h2 class="font-body fw-medium"><?php echo esc_html( $featured_title ); ?></h2>
			<?php endif; ?>

			<div class="row border-between">
				<div class="col-lg-7 mb-5 mb-lg-0">
					<?php if ( $featured_post instanceof WP_Post ) : ?>
						<article class="entry border-bottom-0 mb-0">
							<div class="entry-image">
								<a href="<?php echo esc_url( get_permalink( $featured_post ) ); ?>">
									<?php echo get_the_post_thumbnail( $featured_post, 'large' ); ?>
								</a>
							</div>
							<div class="entry-title">
								<?php echo $news_primary_category_markup( $featured_post ); ?>
								<h3>
									<a href="<?php echo esc_url( get_permalink( $featured_post ) ); ?>" class="stretched-link color-underline">
										<span><?php echo esc_html( get_the_title( $featured_post ) ); ?></span>
									</a>
								</h3>
							</div>
							<div class="entry-meta">
								<ul>
									<li><a href="#"><?php echo esc_html( get_the_date( 'M j, Y', $featured_post ) ); ?></a></li>
								</ul>
							</div>
							<div class="entry-content">
								<p><?php echo esc_html( get_the_excerpt( $featured_post ) ); ?></p>
							</div>
						</article>
					<?php endif; ?>
				</div>

				<div class="col-lg-5">
					<h3 class="font-body fw-medium mb-4 h4"><?php echo esc_html( $highlights_title ?: 'Highlights' ); ?></h3>
					<div class="row posts-md col-mb-30">
						<?php foreach ( $highlight_posts as $hp ) : ?>
							<article class="entry col-12">
								<div class="grid-inner row gutter-20">
									<div class="col-md-4">
										<a class="entry-image" href="<?php echo esc_url( get_permalink( $hp ) ); ?>">
											<?php echo get_the_post_thumbnail( $hp, 'thumbnail' ); ?>
										</a>
									</div>
									<div class="col-md-8">
										<div class="entry-title title-xs">
											<?php echo $news_primary_category_markup( $hp ); ?>
											<h3>
												<a href="<?php echo esc_url( get_permalink( $hp ) ); ?>" class="stretched-link color-underline">
													<?php echo esc_html( get_the_title( $hp ) ); ?>
												</a>
											</h3>
										</div>
										<div class="entry-meta">
											<ul>
												<li><a href="#"><?php echo esc_html( get_the_date( 'M j, Y', $hp ) ); ?></a></li>
											</ul>
										</div>
									</div>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

		</div>
	</div>
	<?php
else :
	if ( $featured_title ) :
		?>
		<h2 class="font-body fw-medium"><?php echo esc_html( $featured_title ); ?></h2>
		<?php
	endif;
	?>
	<div class="row border-between">
		<div class="col-lg-7 mb-5 mb-lg-0">
			<?php if ( $featured_post instanceof WP_Post ) : ?>
				<article class="entry border-bottom-0 mb-0">
					<div class="entry-image">
						<a href="<?php echo esc_url( get_permalink( $featured_post ) ); ?>">
							<?php echo get_the_post_thumbnail( $featured_post, 'large' ); ?>
						</a>
					</div>
					<div class="entry-title">
						<?php echo $news_primary_category_markup( $featured_post ); ?>
						<h3>
							<a href="<?php echo esc_url( get_permalink( $featured_post ) ); ?>" class="stretched-link color-underline">
								<span><?php echo esc_html( get_the_title( $featured_post ) ); ?></span>
							</a>
						</h3>
					</div>
					<div class="entry-meta">
						<ul>
							<li><a href="#"><?php echo esc_html( get_the_date( 'M j, Y', $featured_post ) ); ?></a></li>
						</ul>
					</div>
					<div class="entry-content">
						<p><?php echo esc_html( get_the_excerpt( $featured_post ) ); ?></p>
					</div>
				</article>
			<?php endif; ?>
		</div>

		<div class="col-lg-5">
			<h3 class="font-body fw-medium mb-4 h4"><?php echo esc_html( $highlights_title ?: 'Highlights' ); ?></h3>
			<div class="row posts-md col-mb-30">
				<?php foreach ( $highlight_posts as $hp ) : ?>
					<article class="entry col-12">
						<div class="grid-inner row gutter-20">
							<div class="col-md-4">
								<a class="entry-image" href="<?php echo esc_url( get_permalink( $hp ) ); ?>">
									<?php echo get_the_post_thumbnail( $hp, 'thumbnail' ); ?>
								</a>
							</div>
							<div class="col-md-8">
								<div class="entry-title title-xs">
									<?php echo $news_primary_category_markup( $hp ); ?>
									<h3>
										<a href="<?php echo esc_url( get_permalink( $hp ) ); ?>" class="stretched-link color-underline">
											<?php echo esc_html( get_the_title( $hp ) ); ?>
										</a>
									</h3>
								</div>
								<div class="entry-meta">
									<ul>
										<li><a href="#"><?php echo esc_html( get_the_date( 'M j, Y', $hp ) ); ?></a></li>
									</ul>
								</div>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
endif;
