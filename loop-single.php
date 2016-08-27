<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="content content-fullwidth">
			<article class="article article-single">
				<header class="article-head">
					<h2 class="article-title">
						<?php the_title(); ?>
					</h2><!-- /.article-title -->

					<?php get_template_part( 'fragments/post-meta' ); ?>
				</header><!-- /.article-head -->

				<div class="article-body">
					<div class="article-entry">
						<?php the_content(); ?>
					</div><!-- /.article-entry -->
				</div><!-- /.article-body -->
			</article><!-- /.article -->

			<?php
			// comments_template();
			// carbon_pagination( 'post', array(
			// 	'prev_html' => '<a href="{URL}" class="paging-prev">' . esc_html__( '« Previous ', 'crb' ) . '</a>',
			// 	'next_html' => '<a href="{URL}" class="paging-next">' . esc_html__( 'Next »', 'crb' ) . '</a>'
			// ) );
			?>
		</div><!-- /.content -->

		<?php get_sidebar(); ?>

	<?php endwhile; ?>
<?php endif; ?>
