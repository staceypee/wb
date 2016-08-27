<?php get_header(); ?>

<div class="container">
	<div class="main">
		<?php
		if ( is_single() ) {
			get_template_part( 'loop', 'single' );
		} else {
			crb_the_title( '<div class="article article-head"><h1 class="pagetitle">', '</h1></div>' );
			get_template_part( 'loop' );
		}
		?>
		<!-- get_sidebar(); -->
	</div><!-- /.main -->
</div><!-- /.container -->
<?php get_footer(); ?>