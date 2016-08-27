<?php
/*
Template Name: Plain minimal header/footer
*/

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

	<?php wp_head(); ?>
	<style>
		body {
			color: #49494e;

		}
		@media (max-width: 767px) {
			.woocommerce ul.woocommerce-error {
				padding-right: 0px !important;

			}
		}
		@media (max-width: 1200px) {
			.header .logo {
				padding-left: 0px;
			}
			.wrapper {
				padding-top: 0px;
			}
			.footer .widget {
				margin-bottom: 15px;
			}
			.footer .widget ul {
				margin-bottom: 15px;
			}
		}
		@media (max-width: 1023px) {
			.header .logo {
				padding:16px 0px 16px;
				font-size: 30px;
				position-bottom:0px;
			}
		}

		.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover {
			background-color:inherit;
		}
		.header .nav ul li a:hover, .footer a:hover {
	    opacity: 1;
		}
		.comments .comment-reply a:hover,
		 .paging a:hover,  a:hover {
			 text-decoration:underline;

		}

		.header .nav ul li.link-primary a {
			letter-spacing: 1px;
		}
		/*  login page */
		  .woocommerce form.login, .woocommerce form.lost_reset_password {
				padding-left: 0px;
				padding-right: 0px;
				margin-right: 20px;
				margin-top:0px;
				margin-bottom:0px;
				font: Roboto;
				font-size: 16px;
				padding-top:0px;
				border:0px
			}
		  .woocommerce form .form-row {
				padding-top: 0px;
			}
			.woocommerce-page .intro {
				border: 0px;
				margin-bottom:0px;
				padding: 20px 0px 20px 20px
			}
			.woocommerce-page  .intro .intro-content {
				padding-top: 0px;
				padding-bottom: 0px;
				padding-right: 0px;
			}
			.woocommerce-page  .intro .intro-content h1 {
				margin-bottom: 0px;
				font-size: 30px;
			}
			.woocommerce-page .article-head h1.pageTitle {
				font-size: 30px;
			}
			input.woocommerce-Button.button {
				text-transform:uppercase;
				margin-bottom: 0px;
				width: auto;
				padding-left: 10px;
				padding-right: 10px;
				font-size: 14px;
				line-height: 21px;
				color: #49494e;
				min-width:148px;
				border: 1px solid #49494e;
			}
			.woocommerce input.button:disabled,
			.woocommerce input.button:disabled[disabled] {
				padding-left: 10px;
				padding-right: 10px;
				padding-top: 0px;
				padding-bottom: 0px;
			}
			.woocommerce form .form-row input.input-text,
			.woocommerce form .form-row textarea {
				border: 1px solid #49494e;
			}

			.widget-title {
				border-bottom: 2px solid #49494e;
			}
			.input-append {
				display: inline-block;
		    margin-bottom: 10px;
		    vertical-align: middle;
		    white-space: nowrap;
			}
			.footer .form-subscribe .add-on {
				font-size:20px;
				background-color: #49494e;
				color: #f8d12c;
				padding: 6px 5px 11px 5px;
				border-top: 1px solid #49494e;
				border-top-right-radius: 4px;
				border-bottom-right-radius: 4px;
				margin-left:-5px;
			}
			.footer .form-subscribe .subscribe-field {
				display: inline-block;
				border-top-right-radius: 0px;
				border-bottom-right-radius: 0px;
				width: auto;
			}
			.intro .intro-content p {
				color: #49494e;
			}
	</style>
</head>
<body <?php body_class(); ?>>
	<div class="wrapper">
		<div class="shell">
			<header class="header">
				<a href="<?php echo home_url('/'); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' ) ); ?>" class="logo"><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></a>
				<div class="header-inner">
				</div><!-- /.header-inner -->

			</header><!-- /.header -->
			<div class="container">
            <div class="woocommerce">
              <div class="intro" style="border:0px; margin-bottom:0px">
              <div class="intro-content">
<?php
if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        the_content();
	endwhile;
endif;

?>
			</div><!-- /.intro-content -->
			</div><!-- /.intro -->
			</div><!-- /.woocommerce -->
			</div><!-- /.container -->
			<footer class="footer">
			</footer><!-- /.footer -->
		</div><!-- /.shell -->
	</div><!-- /.wrapper -->
	<?php wp_footer(); ?>
</body>
</html>

<?php
?>
