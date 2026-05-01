<?php

function news_enqueue_assets() {
	$assets_uri = get_template_directory_uri() . '/assets';

	wp_enqueue_style(
		'news-font-imports',
		'https://fonts.googleapis.com/css2?family=Domine:wght@400;500;700&family=Roboto:wght@400;500&family=Literata:opsz,wght@7..72,700&display=swap',
		array(),
		null
	);

	// CSS
	wp_enqueue_style( 'news-core-style', $assets_uri . '/style.css', array( 'news-font-imports' ), null );
	wp_enqueue_style( 'news-font-icons', $assets_uri . '/css/font-icons.css', array( 'news-core-style' ), null );
	wp_enqueue_style( 'news-bootstrap-icons', $assets_uri . '/css/icons/bootstrap-icons.css', array( 'news-font-icons' ), null );
	wp_enqueue_style( 'news-font-awesome', $assets_uri . '/css/icons/font-awesome.css', array( 'news-bootstrap-icons' ), null );
	wp_enqueue_style( 'news-unicons', $assets_uri . '/css/icons/unicons.css', array( 'news-font-awesome' ), null );
	wp_enqueue_style( 'news-blog-demo', $assets_uri . '/demos/blog/blog.css', array( 'news-unicons' ), null );
	wp_enqueue_style( 'news-custom-style', $assets_uri . '/css/custom.css', array( 'news-blog-demo' ), null );

	// JS
	wp_enqueue_script( 'news-plugins', $assets_uri . '/js/plugins.min.js', array(), null, true );
	wp_enqueue_script( 'news-functions', $assets_uri . '/js/functions.bundle.js', array( 'news-plugins' ), null, true );
}
add_action( 'wp_enqueue_scripts', 'news_enqueue_assets' );