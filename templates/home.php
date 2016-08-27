<?php
/*
Template Name: Home
*/
get_header(); 
the_post();
$sections = carbon_get_the_post_meta( 'crb_sections', 'complex' );
?>
<?php if ( $sections ) : ?>
	<?php foreach ( $sections as $section ) : ?>
		<?php if ( $section['_type'] === "_intro" ) : ?>
			<div class="intro">
				<div class="intro-content">
					<h1><?php echo apply_filters( 'the_title', $section['crb_intro_title'] ); ?></h1>
		
					<?php if ( $section['crb_intro_content'] ) : ?>
						<p><?php echo esc_html( $section['crb_intro_content'] ); ?></p>
					<?php endif; ?>
					
					<p>
						<?php if ( $section['crb_intro_slogan'] ) : ?>
							<?php echo esc_html( $section['crb_intro_slogan'] ); ?>
						<?php endif; ?>

						<?php if ( $section['crb_intro_buttons'] ) : ?>
							<?php foreach ( $section['crb_intro_buttons'] as $button ) : ?>
								<a href="<?php echo esc_url( $button['btn_link'] ); ?>" class="btn"><?php echo esc_html( $button['btn_label'] ); ?></a>
							<?php endforeach; ?>
						<?php endif; ?>
					</p>
				</div><!-- /.intro-content -->
				
				<div class="intro-media">
					<?php echo wp_get_attachment_image( $section['crb_intro_image'], 'intro-size' ); ?>
				</div><!-- /.intro-media -->
			</div><!-- /.intro -->
		<?php elseif ( $section['_type'] === "_features" ) : ?>
			<div class="section section-features">
				<?php if ( $section['crb_ft_title'] ) : ?>
					<div class="section-head">
						<h4 class="section-title"><?php echo esc_html( $section['crb_ft_title'] ); ?></h4><!-- /.section-title -->
					</div><!-- /.section-head -->
				<?php endif; ?>
				
				<?php if ( $section['crb_features'] ) : ?>
					<div class="section-body">
						<ul class="list-features">
							<?php foreach ( $section['crb_features'] as $feature ) : ?>
								<li>
									<?php echo wp_get_attachment_image( $feature['icon'], 'feature-size' ); ?>
									
									<?php if ( $feature['content'] ) : ?>
										<p><?php echo esc_html( $feature['content'] ); ?></p>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul><!-- /.list-features -->
					</div><!-- /.section-body -->
				<?php endif; ?>
			</div>
		<?php elseif ( $section['_type'] === "_primary" ) : ?>
			<div class="section section-gray">
				<?php if ( $section['crb_pr_title'] || $section['crb_pr_sub_title'] ) : ?>
					<div class="section-head">
						<?php if ( $section['crb_pr_title'] ) : ?>
							<h4 class="section-title"><?php echo apply_filters( 'the_title', $section['crb_pr_title'] ) ?></h4><!-- /.section-title -->
						<?php endif; ?>
						
						<?php if ( $section['crb_pr_sub_title'] ) : ?>
							<p><?php echo esc_html( $section['crb_pr_sub_title'] ); ?></p>
						<?php endif; ?>
					</div><!-- /.section-head -->
				<?php endif; ?>

				<div class="section-body">
					<?php if ( $section['crb_pr_image'] ) : ?>
						<div class="section-media col-size2 alignleft phone">
							<?php echo wp_get_attachment_image( $section['crb_pr_image'], 'primary-size' ); ?>
						</div><!-- /.section-media col-size2 -->
					<?php endif; ?>

					<?php if ( $section['crb_options'] || ( $section['crb_pr_link']  && $section['crb_pr_label'] ) ) : ?>
						<div class="section-content col-size1 alignright">
							<?php if ( $section['crb_options'] ) : ?>
								<ol>
									<?php foreach ( $section['crb_options'] as $option ) : ?>
										<li>
											<?php echo apply_filters( 'the_content', $option['content'] ); ?>
										</li>
									<?php endforeach; ?>
								</ol>
							<?php endif; ?>

							<?php if ( $section['crb_pr_link']  && $section['crb_pr_label'] ) : ?>
								<p><a href="<?php echo esc_url( $section['crb_pr_link'] ); ?>" class="btn"><?php echo esc_html( $section['crb_pr_label'] ); ?></a></p>
							<?php endif; ?>
						</div><!-- /.section-content col-size1 -->
					<?php endif; ?>
				</div><!-- /.section-body -->
			</div>
		<?php elseif ( $section['_type'] === "_media" ) : ?>
			<div class="section section-secondary">
				<div class="section-body">
					<div class="section-content col-1of2">
						<?php echo apply_filters( 'the_content', $section['crb_md_content'] ); ?>
						
						<?php if ( $section['crb_md_link'] && $section['crb_md_label'] ) : ?>
							<p><a href="<?php echo esc_url( $section['crb_md_link'] ); ?>" class="btn btn-secondary"><?php echo esc_html( $section['crb_md_label'] ); ?></a></p>
						<?php endif; ?>
					</div><!-- /.section-content col-1of2 -->
					
					<?php if ( $section['crb_md_image'] ) : ?>
					<div class="section-media col-1of2 desktop">
						<?php echo wp_get_attachment_image( $section['crb_md_image'], 'media-size' ); ?>
					</div><!-- /.section-media col-1of2 -->
					<?php endif; ?>
				</div><!-- /.section-body -->
			</div>

		<?php elseif ( $section['_type'] === "_team" ) : ?>
			<div class="section section-team">
				<?php if ( $section['crb_team_title'] ) : ?>
					<div class="section-head">
						<h4 class="section-title"><?php echo apply_filters( 'the_title', $section['crb_team_title'] ); ?></h4><!-- /.section-title -->
					</div><!-- /.section-head -->
				<?php endif; ?>
				
				<?php if ( $section['crb_employees'] ) : ?>
					<div class="section-body">
						<ul class="team-members">
							<?php foreach ( $section['crb_employees'] as $employee ) : ?>
								<li class="team-member">
									<div class="team-member-avatar">
										<?php echo wp_get_attachment_image( $employee['image'], 'avatar-size' ); ?>
									</div><!-- /.team-member-avatar -->
									
									<p>
										<strong><?php echo esc_html( $employee['name'] ); ?></strong> <br>
										<?php echo esc_html( $employee['position'] ); ?>
									</p>

									<?php if ( $employee['information'] ) : ?>
										<p><?php echo esc_textarea( $employee['information'] ); ?></p>
									<?php endif; ?>
								</li><!-- /.team-member -->
							<?php endforeach; ?>
						</ul><!-- /.team-members -->
					</div><!-- /.section-body -->
				<?php endif; ?>
			</div><!-- /.section section-team -->
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
<?php get_footer(); ?>

