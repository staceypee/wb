<?php $copyright = carbon_get_theme_option( 'crb_copyright' ); ?>
			</div><!-- /.container -->
			<footer class="footer">
				<a href="<?php echo home_url('/'); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' ) ); ?>" class="logo"><?php echo esc_attr( get_bloginfo( 'name' ) ); ?></a>

				<ul class="widgets footer-widgets">
					<li class="widget">
						<h3 class="widget-title"><?php _e( 'get woobot.io updates', 'crb' ); ?></h3><!-- /.widget-title -->

						<div class="form-subscribe">
							<form id="form-subscribe" action="?" method="post">
								<label for="mail" class="hidden"><?php _e( 'Email', 'crb' ); ?></label>
								<div class="input-append" style="width:100%">
									<input type="email" id="mail" name="mail" value="" placeholder="Email address" class="span2 subscribe-field">
									<a class="add-on" onclick="document.getElementById('form-subscribe').submit()" value="GO">
										<i class="fa fa-paper-plane" aria-hidden="false"></i>
									</a>
<!--								<input type="submit" value="GO" class="subscribe-btn" >-->
								</div>
							</form>
						</div><!-- /.form-subscribe -->
					</li><!-- /.widget -->

					<li class="widget">
						<h3 class="widget-title"><?php _e( 'learn more', 'crb' ); ?></h3><!-- /.widget-title -->

						<?php
						if ( has_nav_menu( 'footer_menu' ) ) {
							wp_nav_menu( array(
								'theme_location'  => 'footer_menu',
								'container'	      => 'ul',
							) );
						}
						?>
						<?php if ( $copyright ) : ?>
							<p class="copyright"><?php echo do_shortcode( $copyright ); ?></p>
						<?php endif; ?>
					</li><!-- /.widget -->
				</ul><!-- /.widgets footer-widgets -->
			</footer><!-- /.footer -->
		</div><!-- /.shell -->
	</div><!-- /.wrapper -->
	<?php wp_footer(); ?>
</body>
</html>
