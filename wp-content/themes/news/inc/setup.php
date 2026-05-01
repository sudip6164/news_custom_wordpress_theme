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