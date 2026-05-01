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
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php
							if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
								the_custom_logo();
							} else {
								echo '<span class="site-title">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
							}
							?>
						</a>
						<span class="divider divider-center date-today"><span class="divider-text"></span></span>
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
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'primary_menu',
								'container'      => false,
								'menu_class'     => 'menu-container justify-content-between',
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>

					<form role="search" method="get" class="top-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" name="s" class="form-control" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Type & Hit Enter.." autocomplete="off">
					</form>

				</div>
			</div>
		</div>

		<div class="header-wrap-clone"></div>
	</header>