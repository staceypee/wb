<?php
@ini_set( 'upload_max_size' , '128M' );
define( 'CRB_THEME_DIR', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );

# Enqueue JS and CSS assets on the front-end
add_action( 'wp_enqueue_scripts', 'crb_wp_enqueue_scripts' );
function crb_wp_enqueue_scripts() {
	$template_dir = get_template_directory_uri();

	# Enqueue jQuery
	wp_enqueue_script( 'jquery' );


	# Enqueue Custom JS files
	# @crb_enqueue_script attributes -- id, location, dependencies, in_footer = false
	crb_enqueue_script( 'theme-functions', $template_dir . '/js/functions.js', array( 'jquery' ) );
  crb_enqueue_script( 'lity', $template_dir . '/js/lity.js' );

	# Enqueue Custom CSS files
	# @crb_enqueue_style attributes -- id, location, dependencies, media = all
	crb_enqueue_style( 'crb_google_fonts_roboto','//fonts.googleapis.com/css?family=Roboto:400,500' );
	crb_enqueue_style( 'theme-custom-styles', $template_dir . '/assets/bundle.css' );
	crb_enqueue_style( 'theme-font-awesome', $template_dir . '/assets/font-awesome.css' );
	crb_enqueue_style( 'theme-styles', $template_dir . '/style.css' );
  crb_enqueue_style( 'theme-lity', $template_dir . '/assets/lity.css' );

	# Enqueue Comments JS file
	if ( is_singular() ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

# Enqueue JS and CSS assets on admin pages
add_action( 'admin_enqueue_scripts', 'crb_admin_enqueue_scripts' );
function crb_admin_enqueue_scripts() {
	$template_dir = get_template_directory_uri();

	# Enqueue Scripts
	# @crb_enqueue_script attributes -- id, location, dependencies, in_footer = false
	# crb_enqueue_script( 'theme-admin-functions', $template_dir . '/js/admin-functions.js', array( 'jquery' ) );

	# Enqueue Styles
	# @crb_enqueue_style attributes -- id, location, dependencies, media = all
	# crb_enqueue_style( 'theme-admin-styles', $template_dir . '/css/admin-style.css' );
}

# Attach Custom Post Types and Custom Taxonomies
add_action( 'init', 'crb_attach_post_types_and_taxonomies', 0 );
function crb_attach_post_types_and_taxonomies() {
	# Attach Custom Post Types
	include_once( CRB_THEME_DIR . 'options/post-types.php' );

	# Attach Custom Taxonomies
	include_once( CRB_THEME_DIR . 'options/taxonomies.php' );
}

add_action( 'after_setup_theme', 'crb_setup_theme' );

# To override theme setup process in a child theme, add your own crb_setup_theme() to your child theme's
# functions.php file.
if ( ! function_exists( 'crb_setup_theme' ) ) {
	function crb_setup_theme() {
		# Make this theme available for translation.
		load_theme_textdomain( 'crb', get_template_directory() . '/languages' );

		# Autoload dependencies
		$autoload_dir = CRB_THEME_DIR . 'vendor/autoload.php';
		if ( ! is_readable( $autoload_dir ) ) {
			wp_die( __( 'Please, run <code>composer install</code> to download and install the theme dependencies.', 'crb' ) );
		}
		include_once( $autoload_dir );

		# Additional libraries and includes
		include_once( CRB_THEME_DIR . 'includes/comments.php' );
		include_once( CRB_THEME_DIR . 'includes/title.php' );
		include_once( CRB_THEME_DIR . 'includes/gravity-forms.php' );
		include_once( CRB_THEME_DIR . 'includes/images-sizes.php' );
		include_once( CRB_THEME_DIR . 'includes/woocommerce.php' );

		# Theme supports
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'gallery' ) );

		# Manually select Post Formats to be supported - http://codex.wordpress.org/Post_Formats
		// add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

		# Register Theme Menu Locations

		register_nav_menus(array(
			'header_menu' => __( 'Header Menu', 'crb' ),
			'second_header_menu' => __( 'Second Header Menu', 'crb' ),
			'footer_menu' => __( 'Footer Menu', 'crb' ),
		));


		# Attach custom widgets
		include_once( CRB_THEME_DIR . 'options/widgets.php' );

		# Attach custom shortcodes
		include_once( CRB_THEME_DIR . 'options/shortcodes.php' );

		# Add Actions
		add_action( 'widgets_init', 'crb_widgets_init' );
		add_action( 'carbon_register_fields', 'crb_attach_theme_options' );

		# Add Filters
		add_filter( 'excerpt_more', 'crb_excerpt_more' );
		add_filter( 'excerpt_length', 'crb_excerpt_length', 999 );
	}
}

# Register Sidebars
# Note: In a child theme with custom crb_setup_theme() this function is not hooked to widgets_init
function crb_widgets_init() {
	$sidebar_options = array_merge(crb_get_default_sidebar_options(), array(
		'name' => 'Default Sidebar',
		'id'   => 'default-sidebar',
	));
	register_sidebar($sidebar_options);
}

# Sidebar Options
function crb_get_default_sidebar_options() {
	return array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'  => '</li>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '</h2>',
	);
}

function crb_attach_theme_options() {
	# Attach fields
	include_once( CRB_THEME_DIR . 'options/theme-options.php' );
	include_once( CRB_THEME_DIR . 'options/post-meta.php' );
}

function crb_excerpt_more() {
	return '...';
}

function crb_excerpt_length() {
	return 55;
}


/*

  Some custom functions


*/

add_filter( 'woocommerce_billing_fields', 'wc_npr_filter_phone', 10, 1 );

function wc_npr_filter_phone( $address_fields ) {
	$address_fields['billing_phone']['required'] = false;
	return $address_fields;
}


add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {
    global $woocommerce;

    foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
       $_product = $values['data'];
       if ( $_product->variation_id == '151' ) {
    		unset($fields['billing']['billing_company']);
    		unset($fields['billing']['billing_address_1']);
    		unset($fields['billing']['billing_address_2']);
    		unset($fields['billing']['billing_city']);
    		unset($fields['billing']['billing_postcode']);
    		unset($fields['billing']['billing_country']);
    		unset($fields['billing']['billing_state']);
    		unset($fields['billing']['billing_phone']);
    		unset($fields['order']['order_comments']);
       }
    }

    return $fields;
}


function pricing_table_shortcode( $atts, $content = "" ) {
  return "content = $content";
}
add_shortcode( 'pricing_table', 'pricing_table_shortcode' );



function pricing_table_column_shortcode( $atts, $content = "" ) {
  return "content = $content";
}
add_shortcode( 'pricing_column', 'pricing_table_column_shortcode' );

/**
 * Return an array of custom subscription statuses
 *
 */
function custom_wcs_get_subscription_variation_status() { 

	$subscription_statuses = array(
		'wc-variation-trial'   => _x( 'Trial', 'Subscription status', 'woocommerce-subscriptions' ),
		'wc-variation-monthly' => _x( 'Monthly', 'Subscription status', 'woocommerce-subscriptions' ),
		'wc-variation-annual'  => _x( 'Annual', 'Subscription status', 'woocommerce-subscriptions' ),
	);

	return $subscription_statuses;
}

add_action( 'automatewoo_triggers_loaded', 'custom_trigger_suspended' );
function custom_trigger_suspended()
{	
	include_once( CRB_THEME_DIR . 'includes/automatewoo-subscription-variation-status.php' );
	include_once( CRB_THEME_DIR . 'includes/automatewoo-subscription-variation-change.php' );
	include_once( CRB_THEME_DIR . 'includes/automatewoo-trigger-suspended.php' );
	new AW_Trigger_Subscription_Status_Suspended();
	new AW_Trigger_Subscription_Variation_Changed();
}

/**
 * Return an array of custom subscription statuses
 *
 */
function custom_wcs_get_subscription_statuses( $subscription_statuses ) {

	$subscription_statuses = array(
		'wc-pending'        => _x( 'Pending', 'Subscription status', 'woocommerce-subscriptions' ),
		'wc-active'         => _x( 'Active', 'Subscription status', 'woocommerce-subscriptions' ),
		'wc-on-hold'        => _x( 'On hold', 'Subscription status', 'woocommerce-subscriptions' ),
		'wc-cancelled'      => _x( 'Cancelled', 'Subscription status', 'woocommerce-subscriptions' ),
		'wc-suspended'      => _x( 'Suspended', 'Subscription status', 'woocommerce-subscriptions' ),
		'wc-switched'       => _x( 'Switched', 'Subscription status', 'woocommerce-subscriptions' ),
		'wc-expired'        => _x( 'Expired', 'Subscription status', 'woocommerce-subscriptions' ),
		'wc-pending-cancel' => _x( 'Pending Cancellation', 'Subscription status', 'woocommerce-subscriptions' ),
	);

	return $subscription_statuses;
}

add_filter( 'wcs_subscription_statuses', 'custom_wcs_get_subscription_statuses', 11 );

include 'woocommerce/myaccount/my-connections.php';

?>
