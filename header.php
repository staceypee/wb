<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />



	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
	<div class="wrapper">
		<div class="shell">
			<header class="header">
				<a href="<?php echo home_url('/'); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' ) ); ?>" class="logo"><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></a>
				<?php
				$total_items = 0;
				$cart_url = '#';
				if ( function_exists( 'WC' ) ) {
					$cart_url = wc_get_cart_url();
					$cart_items = WC()->cart->get_cart_item_quantities();
					foreach ($cart_items as $product_id => $quantity) {
						$total_items += $quantity;
					}
				}
				?>

				<div class="header-inner">
					<div class="shopping-cart visible-md-inline-block">
						<a href="<?php echo $cart_url; ?>">
							<span><?php echo $total_items; ?></span>
						</a>
					</div>
					<?php
					if ( has_nav_menu( 'header_menu' ) ) {
						wp_nav_menu( array(
							'theme_location'  => 'header_menu',
							'container'	      => 'nav',
							'container_class' => 'nav visible-lg-inline-block visible-md-block',
						) );
					}
					if ( has_nav_menu( 'second_header_menu' ) ) {
						wp_nav_menu( array(
							'theme_location'  => 'second_header_menu',
							'container'	      => 'nav',
							'container_class' => 'nav visible-md-block',
						) );
					}
					?>
				</div><!-- /.header-inner -->

				<div class="shopping-cart">
					<a href="<?php echo $cart_url; ?>">
						<span><?php echo $total_items; ?></span>
					</a>
				</div>

				<a href="<?php echo $cart_url; ?>" class="nav-trigger visible-md-inline-block"></a>
			</header><!-- /.header -->

			<div class="container">
