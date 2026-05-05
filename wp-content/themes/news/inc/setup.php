<?php

function news_theme_setup() {
	// Core theme features.
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'menus' );

	register_nav_menus(
		array(
			'primary_menu' => 'Primary Navigation',
		)
	);
}
add_action( 'after_setup_theme', 'news_theme_setup' );

/**
 * Resolve sort request into query args.
 *
 * @param string $sort Sort key.
 * @return array{orderby:string,order:string}
 */
function news_get_sort_query_args( $sort ) {
	$sort = is_string( $sort ) ? strtolower( trim( $sort ) ) : '';

	switch ( $sort ) {
		case 'oldest':
			return array(
				'orderby' => 'date',
				'order'   => 'ASC',
			);
		case 'comments':
			return array(
				'orderby' => 'comment_count',
				'order'   => 'DESC',
			);
		case 'latest':
		default:
			return array(
				'orderby' => 'date',
				'order'   => 'DESC',
			);
	}
}

/**
 * Apply sort dropdown order to main archive/search queries.
 *
 * @param WP_Query $query Query instance.
 * @return void
 */
function news_apply_frontend_sorting( $query ) {
	if ( is_admin() || ! ( $query instanceof WP_Query ) || ! $query->is_main_query() ) {
		return;
	}

	if ( ! ( $query->is_search() || $query->is_archive() ) ) {
		return;
	}

	$sort = isset( $_GET['sort'] ) ? sanitize_key( wp_unslash( $_GET['sort'] ) ) : 'latest';
	$args = news_get_sort_query_args( $sort );

	$query->set( 'orderby', $args['orderby'] );
	$query->set( 'order', $args['order'] );
}
add_action( 'pre_get_posts', 'news_apply_frontend_sorting' );