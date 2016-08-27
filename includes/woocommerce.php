<?php 

/**
 * Sidebar.
 *
 * @see woocommerce_get_sidebar()
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_action( 'woocommerce_after_main_content', 'woocommerce_get_sidebar', 20 );

/**
 * Breadcrumbs.
 *
 * @see woocommerce_breadcrumb()
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );


/**
 * Content Wrappers.
 *
 * @see woocommerce_output_content_wrapper()
 * @see woocommerce_output_content_wrapper_end()
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'crb_woocommerce_before_main_content', 10 );
add_action( 'woocommerce_after_main_content', 'crb_woocommerce_after_main_content_end_content', 15 );
add_action( 'woocommerce_after_main_content', 'crb_woocommerce_after_main_content_end_main', 25 );

function crb_woocommerce_before_main_content() {
	?><div class="main"><div class="content"><?php
}

function crb_woocommerce_after_main_content_end_content() {
	?></div><?php
}

function crb_woocommerce_after_main_content_end_main() {
	?></div><?php
}

