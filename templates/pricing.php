<?php
/*
Template Name: Pricing
*/

get_header();

?>

<div class="section section-teritary section-pricing">
	<div class="section-head">
		<?php the_title( '<h1>', '</h1>' ); ?>
	</div><!-- /.section-head -->
		<?php get_template_part( 'fragments/packages' ); ?>
</div><!-- /.section section-teritary section-pricing -->

<?php get_footer(); ?>