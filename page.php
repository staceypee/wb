<?php get_header(); ?>
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="container">
				<div class="main">
					<div class="content content-fullwidth">
						<div class="article article-head">
							<?php crb_the_title( '<h1 class="pagetitle">', '</h1>' ); ?>
						</div>
						<div class="article article-body">
							<?php the_content(); ?>
						</div>
					</div><!-- /.content content-fullwidth -->
				</div><!-- /.main -->
			</div><!-- /.container -->
		<?php endwhile; ?>
	<?php endif; ?>
<?php get_footer(); ?>