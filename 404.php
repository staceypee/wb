<?php get_header(); ?>
	<div class="container">
		<div class="main">
			<div class="content content-fullwidth">
				<div class="section-head">
					<?php crb_the_title( '<h2 class="pagetitle">', '</h2>' ); ?>
				</div>
				<div class="section-body">
					<?php printf( __( '<p>Please check the URL for proper spelling and capitalization.<br />If you\'re having trouble locating a destination, try visiting the <a href="%1$s">home page</a>.</p>', 'crb' ), home_url( '/' ) ); ?>
					<p>&nbsp;</p>
				</div>
			</div><!-- /.content content-fullwidth -->
		</div><!-- /.main -->
	</div>


<?php get_footer(); ?>