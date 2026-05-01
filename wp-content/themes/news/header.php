<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="x-ua-compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'stretched search-overlay' ); ?>>

<div id="wrapper">

	<header id="header" class="header-size-custom" data-sticky-shrink="false">
		<div id="header-wrap">
			<div class="container">
				<div class="header-row justify-content-lg-between">

					<div id="logo" class="mx-lg-auto col-auto flex-column order-lg-2 px-0">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
							<img class="logo-default" srcset="<?php echo esc_url( get_template_directory_uri() . '/assets/demos/blog/images/logo.png' ); ?>, <?php echo esc_url( get_template_directory_uri() . '/assets/demos/blog/images/logo@2x.png' ); ?> 2x" src="<?php echo esc_url( get_template_directory_uri() . '/assets/demos/blog/images/logo@2x.png' ); ?>" alt="Logo">
							<img class="logo-dark" srcset="<?php echo esc_url( get_template_directory_uri() . '/assets/demos/blog/images/logo-dark.png' ); ?>, <?php echo esc_url( get_template_directory_uri() . '/assets/demos/blog/images/logo-dark@2x.png' ); ?> 2x" src="<?php echo esc_url( get_template_directory_uri() . '/assets/demos/blog/images/logo-dark@2x.png' ); ?>" alt="Logo">
							<img class="logo-mobile" srcset="<?php echo esc_url( get_template_directory_uri() . '/assets/demos/blog/images/mobile-logo.png' ); ?>, <?php echo esc_url( get_template_directory_uri() . '/assets/demos/blog/images/mobile-logo@2x.png' ); ?> 2x" src="<?php echo esc_url( get_template_directory_uri() . '/assets/demos/blog/images/mobile-logo@2x.png' ); ?>" alt="Logo">
						</a>
						<span class="divider divider-center date-today"><span class="divider-text"><?php echo esc_html( wp_date( 'D, M j' ) ); ?></span></span>
					</div>

					<div class="col-auto col-lg-3 order-lg-1 d-none d-md-flex px-0">
						<div class="social-icons">
							<!-- Keep static for now; make dynamic later -->
							<a href="#" class="social-icon rounded-circle bg-dark si-mini h-bg-facebook" target="_blank" rel="noopener">
								<i class="fa-brands fa-facebook-f"></i><i class="fa-brands fa-facebook-f"></i>
							</a>
							<a href="#" class="social-icon rounded-circle bg-dark si-mini h-bg-x-twitter" target="_blank" rel="noopener">
								<i class="fa-brands fa-x-twitter"></i><i class="fa-brands fa-x-twitter"></i>
							</a>
							<a href="#" class="social-icon rounded-circle bg-dark si-mini h-bg-instagram" target="_blank" rel="noopener">
								<i class="bi-instagram"></i><i class="bi-instagram"></i>
							</a>
						</div>
					</div>

					<div class="header-misc col-auto col-lg-3 justify-content-lg-end ms-0 ms-sm-3 px-0">
						<div id="top-search" class="header-misc-icon">
							<a href="#" id="top-search-trigger"><i class="uil uil-search"></i><i class="bi-x-lg"></i></a>
						</div>

						<div class="dark-mode header-misc-icon d-none d-md-block">
							<a href="#" class="body-scheme-toggle" data-bodyclass-toggle="dark" data-add-html="<i class='bi-sun'></i>" data-remove-html="<i class='bi-moon-stars'></i>"><i class="bi-moon-stars"></i></a>
						</div>
					</div>

					<div class="primary-menu-trigger">
						<button class="cnvs-hamburger" type="button" title="Open Mobile Menu">
							<span class="cnvs-hamburger-box"><span class="cnvs-hamburger-inner"></span></span>
						</button>
					</div>

				</div>
			</div>

			<div class="container">
				<div class="header-row justify-content-lg-center header-border">

					<nav class="primary-menu with-arrows">
						<ul class="menu-container justify-content-between">
							<?php
							$locations = get_nav_menu_locations();
							$menu_id   = isset( $locations['primary_menu'] ) ? $locations['primary_menu'] : 0;
							$items     = $menu_id ? wp_get_nav_menu_items( $menu_id ) : array();

							if ( ! empty( $items ) ) :
								global $wp;
								$current_request = isset( $wp->request ) ? $wp->request : '';
								$current_path    = untrailingslashit( wp_parse_url( home_url( $current_request ), PHP_URL_PATH ) );

								foreach ( $items as $item ) :
									$item_path    = untrailingslashit( wp_parse_url( $item->url, PHP_URL_PATH ) );
									$is_current   = ( $item_path === $current_path ) || in_array( 'current-menu-item', (array) $item->classes, true );
									$item_classes = 'menu-item' . ( $is_current ? ' current' : '' );
									?>
									<li class="<?php echo esc_attr( $item_classes ); ?>">
										<a class="menu-link" href="<?php echo esc_url( $item->url ); ?>">
											<div><?php echo esc_html( $item->title ); ?></div>
										</a>
									</li>
									<?php
								endforeach;
							else :
								?>
								<li class="menu-item current"><a class="menu-link" href="<?php echo esc_url( home_url( '/' ) ); ?>"><div>Home</div></a></li>
								<?php
							endif;
							?>
						</ul>
					</nav>

					<form role="search" method="get" class="top-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" name="s" class="form-control" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Type & Hit Enter.." autocomplete="off">
					</form>

				</div>
			</div>
		</div>

		<div class="header-wrap-clone"></div>
	</header>