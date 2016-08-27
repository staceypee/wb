<?php
$packages = carbon_get_the_post_meta( 'crb_pr_plans', 'complex' );

if ( !$packages ) {
	return;
}

?>
<div class="section-body">
	<ul class="packages">

		<?php foreach ( $packages as $package ) : ?>
			<li class="package">
				<div class="package-head">
					<?php if ( $package['option'] ) : ?>
						<p><?php echo esc_html( $package['option'] ); ?></p>
					<?php endif; ?>
	
					<h2><?php echo esc_html( $package['type'] ); ?></h2>
				</div><!-- /.package-head -->
				
				<?php if ( $package['crb_benefits']) : ?>
					<div class="package-body">
						<?php echo $package['crb_benefits']; ?>
					</div><!-- /.package-body -->
				<?php endif; ?>
				
				<div class="package-foot">
					<?php if ( $package['btn_text'] && $package['btn_link'] ) : ?>
						<a href="<?php echo esc_url( $package['btn_link'] ); ?>" class="btn"><?php echo esc_html( $package['btn_text'] ); ?></a>
					<?php endif; ?>
				</div><!-- /.package-foot -->
			</li><!-- /.package -->
		<?php endforeach; ?>
	</ul>
</div><!-- /.section-body -->