<?php if ( have_posts() ) : ?>
	<div class="content content-fullwidth">
		<section class="section-articles">
			<ol class="articles">
				<?php while ( have_posts() ) : the_post(); ?>
					<li class="article">
						<?php the_post_thumbnail( 'blog-size' ); ?>
						
						<?php if ( get_the_title() ) : ?>
							<h2 class="article-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2><!-- /.article-title -->
						<?php endif; ?>
						
						<?php if ( get_the_content() ) : ?>
							<div class="article-entry">
								<?php the_excerpt(); ?>
							</div><!-- /.article-entry -->
						<?php endif; ?>
					</li><!-- /.article -->
				<?php endwhile; ?>
			</ol><!-- /.articles -->
		</section><!-- /.section-articles -->
		
		<?php
		 carbon_pagination( 'posts', array(
			'prev_html' => '<a href="{URL}" class="paging-prev">' . esc_html__( '« Previous ', 'crb' ) . '</a>',
			'next_html' => '<a href="{URL}" class="paging-next">' . esc_html__( 'Next »', 'crb' ) . '</a>'
		) ); 
		?>
	</div><!-- /.content content-fullwidth -->

<?php else : ?>
	<div class="content content-fullwidth">
		<section class="section-articles">
			<ol class="articles">
				<li class="article post error404 not-found">
					<div class="article-body">
						<div class="article-entry">
							<p>
								<?php
								if ( is_category() ) { // If this is a category archive
									printf( __( "Sorry, but there aren't any posts in the %s category yet.", 'crb' ), single_cat_title( '', false ) );
								} else if ( is_date() ) { // If this is a date archive
									_e( "Sorry, but there aren't any posts with this date.", 'crb' );
								} else if ( is_author() ) { // If this is a category archive
									$userdata = get_user_by( 'id', get_queried_object_id() );
									printf( __( "Sorry, but there aren't any posts by %s yet.", 'crb' ), $userdata->display_name );
								} else if ( is_search() ) { // If this is a search
									_e( 'No posts found. Try a different search?', 'crb' );
								} else {
									_e( 'No posts found.', 'crb' );
								}
								?>
							</p>

							<?php get_search_form(); ?>
						</div><!-- /.article-entry -->
					</div><!-- /.article-body -->
				</li><!-- /.article -->
			</ol><!-- /.articles -->
		</section><!-- /.section-articles -->
	</div><!-- /.content content-fullwidth -->
<?php endif; ?>